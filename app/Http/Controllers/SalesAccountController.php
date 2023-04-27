<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentBankFinanace;
use App\Models\SalePaymentCash;
use App\Models\SalePaymentPersonalFinanace;
use App\Models\SalePaymentTransactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

//Trait
use App\Traits\CronHelper;
use App\Traits\CommonHelper;

class SalesAccountController extends Controller
{
    use CronHelper, CommonHelper;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!request()->ajax()) {
            $data = array(
                'branches' => self::getAllBranchesWithInActive()
            );
            return view('admin.sales-accounts.index', $data);
        } else {
            $postData = $request->all();
            $data = SalePaymentAccounts::branchWise()
                ->with([
                    'sale' => function ($model) {
                        $model->select('id', 'branch_id', 'purchase_id', 'customer_name', 'created_at')
                            ->with([
                                'branch' => function ($b) {
                                    $b->select('id', 'branch_name');
                                },
                                'purchases' => function ($b) {
                                    $b->select('id', 'sku');
                                }
                            ]);
                    }
                ])->select('id', 'account_uuid', 'sale_id', 'sales_total_amount', 'due_payment_source', 'status', 'created_at');


            //Filter By Branch
            if (isset($postData['columns'][1]['search']['value']) && ($postData['columns'][1]['search']['value'] != '')) {
                $data->whereHas('sale', function ($query) use ($postData) {
                    $query->where('branch_id', $postData['columns'][1]['search']['value']);
                });
            }

            //Filter By Status
            if (isset($postData['columns'][6]['search']['value']) && ($postData['columns'][6]['search']['value'] != '')) {
                $data->where('status', $postData['columns'][6]['search']['value']);
            }

            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";
            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where('id', $search_string)
                            ->orWhere('created_at', $search_string)
                            ->orWhereHas('sale.branch', function ($q) use ($search_string) {
                                $q->where('branch_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale', function ($q) use ($search_string) {
                                $q->where('customer_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale.purchase.brand', function ($q) use ($search_string) {
                                $q->where('name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale.purchase.model', function ($q) use ($search_string) {
                                $q->where('model_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale.purchase.color', function ($q) use ($search_string) {
                                $q->where('color_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale.purchase', function ($q) use ($search_string) {
                                $q->where('sku', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale.purchase', function ($q) use ($search_string) {
                                $q->where('vin_number', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('sale.purchase', function ($q) use ($search_string) {
                                $q->where('hsn_number', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orwhere('sales_total_amount', $search_string);
                    }
                })
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return isset($row->sale->branch) ? $row->sale->branch->branch_name : '';
                })
                ->addColumn('title', function ($row) {

                    $title = '';
                    if (isset($row->sale->customer_name)) {
                        $title .= ucfirst($row->sale->customer_name);
                    }

                    if (isset($row['sale']['purchases']) && (!empty($row['sale']['purchases']))) {
                        $title .= '(' . $row['sale']['purchases']['sku'] . ')';
                    }
                    return $title;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == '1') {
                        return '<span class="label label-success">Close</span>';
                    } else {
                        return '<span class="label label-warning">Open</span>';
                    }
                })
                ->addColumn('chassis_number', function ($row) {
                    if (isset($row->sale->purchase)) {
                        return $row->sale->purchase->vin_number;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['title', 'status', 'chassis_number', 'created_at', 'action'])
                ->make(true);
        }
    }

    /**
     * Function to load deposite down payment form
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $postData = $request->all();
        $id = isset($postData['id']) ? $postData['id'] : 0;
        $accountModel = SalePaymentAccounts::find($id);
        if (!$accountModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist')
            ]);
        }

        $data = array(
            'depositeSources' => depositeSources(),
            'duePaySources'   => duePaySources(),
            'emiTerms'        => emiTerms(),
            'action'          => route('saleAccounts.store'),
            'data'            => $accountModel,
            'financersList'   => [],
            'salemans'        => self::_getSalesman()
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.sales-accounts.create', $data)->render()
        ]);
    }

    /**
     * Function is created for deposite down payment
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $postData = $request->only('sales_account_id', 'sales_total_amount', 'deposite_amount', 'deposite_date', 'deposite_source', 'deposite_source_note', 'status', 'deposite_collected_by', 'due_amount', 'due_date');
            $validator = Validator::make($postData, [
                'sales_account_id'      => "required|exists:sale_payment_accounts,id",
                'sales_total_amount'    => "required|numeric",
                'deposite_amount'       => "required|numeric|min:1|lte:sales_total_amount",
                'deposite_date'         => 'required|date',
                'deposite_source'       => 'required|string',
                'deposite_source_note'  => 'nullable|string',
                'due_amount'            => 'required|numeric|gte:0',
                'due_date'              => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
                'status'                => 'required|in:0,1,2'
            ]);
            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }

            //Deposite Down Payment
            $salesAccountModel = SalePaymentAccounts::find($postData['sales_account_id']);
            $salesAccountModel->down_payment = $postData['deposite_amount'];
            if ($postData['due_amount'] == 0) {
                $salesAccountModel->status = 1;
                $salesAccountModel->status_closed_note = "Auto Closed Due To Full Payment.";
                $salesAccountModel->status_closed_by   = 0;
                $salesAccountModel->cash_status      = 1;
                $salesAccountModel->bank_finance_status = 1;
                $salesAccountModel->personal_finance_status = 1;
            }
            $salesAccountModel->payment_setup = 1;
            $salesAccountModel->save();

            //Create Cash History
            $downPaymentData = array(
                [
                    'sale_id' => $salesAccountModel->sale_id,
                    'sale_payment_account_id'  => $salesAccountModel->id,
                    'payment_name'   => "Account Initiated.",
                    'paid_source'    => 'Auto',
                    'paid_date'    => date('Y-m-d'),
                    'credit_amount'  => $postData['sales_total_amount'],
                    'debit_amount'   => 0,
                    'change_balance' => $postData['sales_total_amount'],
                    'trans_type'     => 1,
                    'status'         => 1
                ],
                [
                    'sale_id' => $salesAccountModel->sale_id,
                    'sale_payment_account_id'  => $salesAccountModel->id,
                    'payment_name'   => "Down Payment Paid By Customer.",
                    'credit_amount'  => 0,
                    'debit_amount'   => $postData['deposite_amount'],
                    'change_balance' => $postData['due_amount'],
                    'due_date'       => $postData['due_date'],
                    'paid_source'    => $postData['deposite_source'],
                    'paid_date'      => $postData['deposite_date'],
                    'paid_note'      => $postData['deposite_source_note'],
                    'collected_by'   => $postData['deposite_collected_by'],
                    'trans_type'     => 2,
                    'status'         => $postData['status'],
                ]
            );
            foreach ($downPaymentData as $cashData) {
                $cashPaymentModel = SalePaymentCash::create($cashData);
                SalePaymentTransactions::create([
                    'sale_id' => $cashData['sale_id'],
                    'sale_payment_account_id' => $cashData['sale_payment_account_id'],
                    'transaction_for' => 1,
                    'transaction_name' => $cashData['payment_name'],
                    'transaction_amount' => ($cashData['credit_amount'] > 0) ? $cashData['credit_amount'] : $cashData['debit_amount'],
                    'transaction_paid_source' => isset($cashData['paid_source']) ? $cashData['paid_source'] : null,
                    'transaction_paid_source_note' => isset($cashData['paid_note']) ? $cashData['paid_note'] : '',
                    'transaction_paid_date' => isset($cashData['paid_date']) ? $cashData['paid_date'] : null,
                    'trans_type' => isset($cashData['trans_type']) ? $cashData['trans_type'] : '',
                    'status' => $cashData['status'],
                    'reference_id' => $cashPaymentModel->id
                ]);
            }

            //Sale Update Self Pay In Sales Model
            Sale::where('id', $salesAccountModel['sale_id'])->update(['hyp_financer' => null, 'payment_type' => '1']);
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success')
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SalePaymentAccounts::find($id);
        if (!$data) {
            return redirect()->route('saleAccounts.index');
        }
        return view('admin.sales-accounts.show', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getActions($row)
    {
        $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
        $action  .= '<ul class="dropdown-menu">';
        $action .= '<li><a href="' . route('saleAccounts.edit', ['saleAccount' => $row->id]) . '" class="">VIEW DETAIL</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    /**
     * Function for get all tabs
     */
    public function getPaymentTabs(Request $request, $id)
    {

        if ($request->ajax()) {
            $salePaymentAccount = SalePaymentAccounts::find($id);
            if (!$salePaymentAccount) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! Account does not exis"
                ]);
            }
            $postData = $request->all();
            $data['data'] = [];
            $data['salesAccountId'] = $id;
            $type = isset($postData['tab']) ? $postData['tab'] : '';
            $viewName = 'account-detail';
            switch ($type) {
                case 'accountDetail':
                    $paymentAccount = SalePaymentAccounts::where('id', $id)->with([
                        'sale' => function ($sale) {
                            $sale->select('id', 'customer_name');
                        },
                        'financier' => function ($sale) {
                            $sale->select('id', 'bank_name');
                        }
                    ])->first();
                    $paymentAccount = $paymentAccount->toArray();
                    $data['data'] = $paymentAccount;
                    $data['data']['total_paid'] = floatval($paymentAccount['cash_paid_balance'] + $paymentAccount['bank_finance_paid_balance'] + $paymentAccount['personal_finance_outstaning_balance']);
                    $data['data']['total_due'] = floatval($paymentAccount['cash_outstaning_balance'] + ($paymentAccount['bank_finance_outstaning_balance'] >= 0 ? $paymentAccount['bank_finance_outstaning_balance'] : 0) + $paymentAccount['personal_finance_outstaning_balance']);
                    $data['data']['grand_total'] = floatval($data['data']['total_paid'] + $data['data']['total_due']);
                    $viewName = 'account-detail';
                    break;
                case 'cashPaymentHistory':
                    $data['data'] = SalePaymentCash::where('sale_payment_account_id', $id)->orderBy('id', 'DESC')->get()->toArray();
                    $data['credit_amount'] = SalePaymentCash::where('sale_payment_account_id', $id)->sum('credit_amount');
                    $data['debit_amount'] = SalePaymentCash::where('sale_payment_account_id', $id)->sum('debit_amount');
                    $data['paid_by_amount'] = SalePaymentCash::where('sale_payment_account_id', $id)->whereNotIn('paid_source', ['Auto', 'auto'])->sum('debit_amount');
                    $data['due_amount'] = ($data['credit_amount'] - $data['debit_amount']);
                    $viewName = 'cash-payment';
                    break;
                case 'bankFinanceHistory':
                    $data['data'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->orderBy('id', 'DESC')->get()->toArray();
                    $data['credit_amount'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->sum('credit_amount');
                    $data['debit_amount'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->sum('debit_amount');
                    $data['paid_by_amount'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->whereNotIn('paid_source', ['Auto', 'auto'])->sum('debit_amount');
                    $data['due_amount'] = ($data['credit_amount'] - $data['debit_amount']);
                    $viewName = 'bank-finance';
                    break;
                case 'personalFinanceHistory':
                    // updateDuesOrPaidBalance($id);
                    $data['data'] = SalePaymentPersonalFinanace::where('sale_payment_account_id', $id)->orderBy('id', 'ASC')->get()->toArray();
                    $data['paidCount'] = SalePaymentPersonalFinanace::where(['sale_payment_account_id' => $id, 'status' => '1'])->count();
                    //$data['credit_amount'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->sum('credit_amount');
                    //$data['debit_amount'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->sum('debit_amount');
                    //$data['paid_by_amount'] = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->whereNotIn('paid_source', ['Auto', 'auto'])->sum('debit_amount');
                    // $data['due_amount'] = ($data['credit_amount'] - $data['debit_amount']);
                    $viewName = 'personal-finance';
                    break;
                case 'transactions':
                    $data['data'] = SalePaymentTransactions::where('sale_payment_account_id', $id)->orderBy('id', 'DESC')->get()->toArray();
                    $viewName = 'transactions';
                    break;
                case 'customerDetail':
                    $data['data'] = SalePaymentAccounts::with([
                        'sale' => function ($model) {
                            $model->with([
                                'state' => function ($b) {
                                    $b->select('id', 'state_name');
                                },
                                'district' => function ($b) {
                                    $b->select('id', 'district_name');
                                },
                                'city' => function ($b) {
                                    $b->select('id', 'city_name');
                                }
                            ]);
                        }
                    ])->select('id', 'sale_id')->where('id', $id)->first();
                    $viewName = 'customer-detail';
                    break;
                case 'purchaseDetail':
                    $data['data'] = SalePaymentAccounts::with([
                        'sale' => function ($model) {
                            $model->with([
                                'purchases' => function ($p) {
                                    $p->with([
                                        'branch' => function ($model) {
                                            $model->select('id', 'branch_name');
                                        },
                                        'dealer' => function ($model) {
                                            $model->select('id', 'company_name');
                                        },
                                        'brand' => function ($model) {
                                            $model->select('id', 'name');
                                        },
                                        'model' => function ($model) {
                                            $model->select('id', 'model_name');
                                        },
                                        'variants' => function ($model) {
                                            $model->select('id', 'variant_name');
                                        },
                                        'modelColor' => function ($model) {
                                            $model->select('id', 'color_name', 'sku_code');
                                        },
                                        'tyreBrand' => function ($model) {
                                            $model->select('id', 'name');
                                        },
                                        'batteryBrand' => function ($model) {
                                            $model->select('id', 'name');
                                        },
                                    ]);
                                }
                            ]);
                        }
                    ])->select('id', 'sale_id')->where('id', $id)->first();
                    $viewName = 'purchase-detail';
                    break;
                default:
                    # code...
                    break;
            }

            // dd($data['data']);
            $data['salesAccountData'] = $salePaymentAccount->fresh();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Tab ajax data loaded",
                'html'       => view('admin.sales-accounts.tabMenu.' . $viewName, $data)->render()
            ]);
        } else {
            return redirect()->route('saleAccounts.edit', ['saleAccount' => $id]);
        }
    }
}
