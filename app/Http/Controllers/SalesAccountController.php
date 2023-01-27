<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentBankFinanace;
use App\Models\SalePaymentCash;
use App\Models\SalePaymentInstallments;
use App\Models\SalePaymentTransactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

//Trait
use App\Traits\CronHelper;
use App\Traits\CommonHelper;
use Barryvdh\DomPDF\Facade\Pdf;

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
                ])->select('id', 'account_uuid', 'sale_id', 'sales_total_amount', 'deposite_amount', 'due_amount', 'due_payment_source', 'status', 'created_at');


            //Filter By Branch
            if (isset($postData['columns'][1]['search']['value']) && ($postData['columns'][1]['search']['value'] != '')) {
                $data->whereHas('sale', function ($query) use ($postData) {
                    $query->where('branch_id', $postData['columns'][1]['search']['value']);
                });
            }

            //Filter By Status
            if (isset($postData['columns'][5]['search']['value']) && ($postData['columns'][5]['search']['value'] != '')) {
                $data->where('status', $postData['columns'][5]['search']['value']);
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
                ->addColumn('created_at', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['title', 'status', 'created_at', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
                'due_date'              => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
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
                    'credit_amount'  => $postData['sales_total_amount'],
                    'debit_amount'   => 0,
                    'change_balance' => $postData['sales_total_amount'],
                    'trans_type'     => 1,
                    'status'         => 1
                ],
                [
                    'sale_id' => $salesAccountModel->sale_id,
                    'sale_payment_account_id'  => $salesAccountModel->id,
                    'payment_name'   => "Down Payment.",
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

    public function storeBack(Request $request)
    {
        try {
            DB::beginTransaction();
            $postData = $request->only(
                'sale_id',
                'sales_total_amount',
                'deposite_amount',
                'deposite_date',
                'deposite_source',
                'deposite_source_note',
                'due_amount',
                'due_date',
                'due_payment_source',
                'due_note',
                'financier_id',
                'financier_note',
                'finance_terms',
                'no_of_emis',
                'rate_of_interest',
                'processing_fees',
                'pay_later_amount',
                'pay_later_date'
            );
            $validationArray = [
                'sale_id'               => "required|exists:sales,id|unique:sale_payment_accounts,sale_id",
                'sales_total_amount'    => "required|numeric|min:1",
                'deposite_amount'       => "required|numeric|min:0",
                'deposite_date'         => 'required|date',
                'deposite_source'       => 'required|string',
                'deposite_source_note'  => 'nullable|string',
                'due_amount'            => 'required|numeric|min:0',
                'due_payment_source'    => 'required|in:1,2,3',
                'due_note'              => 'nullable|string'
            ];

            dd($postData);

            //Conditional Valdiation
            if (isset($postData['due_payment_source']) && in_array($postData['due_payment_source'], [2, 3])) {
                $validationArray['financier_id'] = 'required|exists:bank_financers,id';
                $validationArray['financier_note'] = 'nullable|string';
            }
            //Conditional Valdiation
            if (isset($postData['due_payment_source']) && in_array($postData['due_payment_source'], [3])) {
                $validationArray['finance_terms']    = 'required|in:1,2,3,4';
                $validationArray['no_of_emis']       = 'required|integer|min:1|max:24';
                $validationArray['rate_of_interest'] = 'required|numeric|min:1|max:24';
                $validationArray['processing_fees']  = 'nullable|numeric|min:1';
            }

            //Conditional Value Reset
            if (isset($postData['due_payment_source']) && in_array($postData['due_payment_source'], [1, 2])) {
                $postData['finance_terms'] = null;
            }


            $validator = Validator::make($postData, $validationArray, [
                'sale_id.unique' => "Sorry! sales account already created for same."
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

            //Default Value Set
            $postData['deposite_collected_by'] = isset($postData['deposite_collected_by']) ? $postData['deposite_collected_by'] : Auth::user()->id;
            $postData['status'] = 0;

            //Create Account
            $accountModel = SalePaymentAccounts::create($postData);
            if ($accountModel) {

                //Update In Sale
                Sale::where('id', $postData['sale_id'])->update(['sp_account_id' => $accountModel->id]);

                //Create New Transactions
                SalePaymentTransactions::create([
                    'sale_id' => $postData['sale_id'],
                    'sale_payment_account_id' => $accountModel->id,
                    'transaction_title' => 'First Down Payment.',
                    'amount_paid' => $postData['deposite_amount'],
                    'amount_paid_source' => $postData['deposite_source'],
                    'amount_paid_source_note' => $postData['deposite_source_note'],
                    'amount_paid_date' => $postData['deposite_date'],
                    'status' => '1',
                    'payment_collected_by' => Auth::user()->id,
                    'sale_payment_installment_id' => 0
                ]);


                if ((floatval($postData['due_amount']) > 0)) {
                    $total_pay_with_intrest = 0.00;

                    //IF PAY_LATER SET
                    if (isset($postData['pay_later_amount']) && (floatval($postData['pay_later_amount']) > 0)) {
                        $total_pay_with_intrest += floatval($postData['pay_later_amount']);
                        SalePaymentInstallments::create([
                            'sale_id'                 => $postData['sale_id'],
                            'sale_payment_account_id' => $accountModel->id,
                            'emi_title'               => "Customer has given pay later.",
                            'loan_total_amount'       => $postData['pay_later_amount'],
                            'emi_due_amount'          => $postData['pay_later_amount'],
                            'emi_due_date'            => $postData['pay_later_date'],
                            'emi_other_charges'       => null,
                            'emi_other_charges_date'  => null,
                            'emi_other_charges_note'  => null,
                            'emi_due_revised_amount'  => $postData['pay_later_amount'],
                            'emi_due_revised_note'    => null,
                            'amount_paid'             => null,
                            'amount_paid_date'        => null,
                            'amount_paid_source'      => null,
                            'amount_paid_note'        => null,
                            'pay_due'                 => null,
                            'status'                  => 0
                        ]);
                    }

                    //EMI Table IF Pay Source Self Pay / Bank Finance
                    if ((in_array($postData['due_payment_source'], [1, 2]))) {
                        $emi_title = "Customer Self Pay.";
                        if ($postData['due_payment_source'] == '2') {
                            $emi_title = BankFinancer::where('id', $postData['financier_id'])->value('bank_name');
                            $emi_title = 'Customer Bank Finance - ' . ucwords(strtolower($emi_title));
                        }
                        //Finance Company
                        SalePaymentInstallments::create([
                            'sale_id'                 => $postData['sale_id'],
                            'sale_payment_account_id' => $accountModel->id,
                            'emi_title'               => $emi_title,
                            'loan_total_amount'       => $postData['sales_total_amount'],
                            'emi_due_amount'          => $postData['due_amount'],
                            'emi_due_date'            => $postData['due_date'],
                            'emi_other_charges'       => null,
                            'emi_other_charges_date'  => null,
                            'emi_other_charges_note'  => null,
                            'emi_due_revised_amount'  => $postData['due_amount'],
                            'emi_due_revised_note'    => null,
                            'amount_paid'             => null,
                            'amount_paid_date'        => null,
                            'amount_paid_source'      => null,
                            'amount_paid_note'        => null,
                            'pay_due'                 => null,
                            'status'                  => 0
                        ]);

                        $total_pay_with_intrest = $postData['due_amount'];
                    }
                    //Case Of Personal Finance
                    else if ((in_array($postData['due_payment_source'], [3]))) {

                        $P = floatval($postData['due_amount']) + floatval($postData['processing_fees']);
                        $T = ($postData['no_of_emis']);
                        $term_value = 0;
                        switch ($postData['finance_terms']) {
                            case 1:
                                $T *= 1;
                                $term_value = 1;
                                break;
                            case 2:
                                $T *= 3;
                                $term_value = 3;
                                break;
                            case 3:
                                $T *= 6;
                                $term_value = 6;
                                break;
                            case 4:
                                $T *= 12;
                                $term_value = 12;
                                break;
                        }
                        $R = $postData['rate_of_interest'];
                        $total_interest = (($P * $R * ($T / 12)) / 100);

                        $grand_total = round($P + $total_interest);
                        $total_pay_with_intrest = $grand_total;

                        $installment_amount    = round($grand_total / $postData['no_of_emis']);
                        $installment_intr     = round($total_interest / $postData['no_of_emis']);
                        $installment_prin     = ($installment_amount - $installment_intr);

                        $final_due_date = null;

                        //Convert Emis
                        for ($i = 1; $i <= $postData['no_of_emis']; $i++) {
                            // for ($i = $postData['no_of_emis']; $i >= 1; $i--) {
                            $next_month  = ($term_value * $i);
                            $emi_due_date = date('Y-m-d', strtotime("+ $next_month months"));
                            $emi_due_date = date('Y-m', strtotime($emi_due_date)) . '-' . date('d');
                            $final_due_date = $emi_due_date;
                            SalePaymentInstallments::create([
                                'sale_id'                 => $postData['sale_id'],
                                'sale_payment_account_id' => $accountModel->id,
                                'emi_title'               => 'Customer Pay With EMI Installment - ' . $i,
                                'loan_total_amount'       => $postData['sales_total_amount'],
                                'emi_due_amount'          => $installment_amount,
                                'emi_due_principal'       => $installment_prin,
                                'emi_due_intrest'         => $installment_intr,
                                'emi_due_date'            => $emi_due_date,
                                'emi_other_charges'       => null,
                                'emi_other_charges_date'  => null,
                                'emi_other_charges_note'  => null,
                                'emi_due_revised_amount'  => $installment_amount,
                                'emi_due_revised_note'    => null,
                                'amount_paid'             => null,
                                'amount_paid_date'        => null,
                                'amount_paid_source'      => null,
                                'amount_paid_note'        => null,
                                'pay_due'                 => null,
                                'status'                  => 0
                            ]);
                        }

                        //Due Date As Per Final Amount
                        SalePaymentAccounts::where('id', $accountModel->id)->update(['due_date' => $final_due_date]);
                    }

                    //$total_pay_with_intrest
                    SalePaymentAccounts::where('id', $accountModel->id)->update([
                        'total_pay_with_intrest' => $total_pay_with_intrest
                    ]);
                }
                //Case If Pay Full Down Payment
                else {
                    SalePaymentAccounts::where('id', $accountModel->id)->update(['status' => 1, 'status_closed_note' => 'Auto Closed On Full Payment', 'status_closed_by' => Auth::user()->id]);
                    Sale::where('id', $postData['sale_id'])->update(['status' => 'close']);
                }
            }

            //Account status check all dues & mark closed
            self::verifyAccountPendings();

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
     * Fucntion for load detail modal popup - transaction detail / due-emil detail
     */
    public function salesDetailModal(Request $request)
    {
        $data = null;
        $postData = $request->all();
        $view = '';
        if (isset($postData['type'])) {
            $view = $postData['type'];
            switch ($postData['type']) {
                case 'due-detail':
                    $data['data'] = SalePaymentInstallments::where('id', $postData['id'])->with([
                        'account' => function ($account) {
                            $account->select('id', 'account_uuid', 'sales_total_amount', 'financier_id', 'due_payment_source', 'status');
                        },
                        'sale' => function ($account) {
                            $account->select('id', 'customer_name', 'status');
                        }
                    ])->first();
                    // return ['status' => true, 'data' => $data];
                    break;
                case 'transaction-detail':
                    $data['data'] = SalePaymentTransactions::where('id', $postData['id'])->with([
                        'account' => function ($account) {
                            $account->select('id', 'account_uuid', 'sales_total_amount', 'financier_id', 'due_payment_source', 'status');
                        },
                        'sale' => function ($sale) {
                            $sale->select('id', 'customer_name', 'status');
                        },
                        'user' => function ($user) {
                            $user->select('id', 'name', 'email');
                        },
                        'installment' => function ($installment) {
                            $installment->select('id', 'installment_uuid', 'emi_title');
                        }
                    ])->first();
                    // return ['status' => true, 'data' => $data];
                    break;
                case 'due-pay-form':
                    $installModel = SalePaymentInstallments::where('id', $postData['id'])->with([
                        'account' => function ($account) {
                            $account->select('id', 'account_uuid', 'sales_total_amount', 'financier_id', 'due_payment_source', 'status');
                        },
                        'sale' => function ($account) {
                            $account->select('id', 'customer_name', 'status');
                        }
                    ])->first();

                    $data['data'] = $installModel;
                    $data['totalDueCounts'] = SalePaymentInstallments::where(['sale_payment_account_id' => $installModel->sale_payment_account_id, 'status' => '0'])->count();
                    $data['depositeSources'] = depositeSources();
                    $data['salemans'] = self::_getSalesman();
                    // return ['status' => true, 'data' => $data];
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.sales-accounts.modals.' . $view, $data)->render()
        ]);
    }

    /**
     * installmentPay
     */
    public function installmentPay(Request $request)
    {
        DB::beginTransaction();
        try {
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'id'              => 'required|exists:sale_payment_installments,id',
                'emi_due_amount'  => 'required|numeric|min:1',
                'pay_method'      => 'required',
                'pay_method_note' => 'required|string',
                'pay_option'      => 'required|in:full,partial',
                'pay_amount'      => 'required|numeric|min:1',
                'next_due_Date'   => 'nullable|date|after:today',
                'collected_by_salesman_id' => 'required|exists:salesmans,id',
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
            //Get Installment Model
            $instModel = SalePaymentInstallments::find($postData['id']);
            //Get OverAll Installment Total
            $total_overall_due = SalePaymentInstallments::where([
                'status' => '0',
                'sale_payment_account_id'  => $instModel->sale_payment_account_id
            ])->sum('emi_due_revised_amount');

            $due_amount = floatval($instModel->emi_due_revised_amount);
            $pay_amount = floatval($postData['pay_amount']);
            $total_overall_due = floatval($total_overall_due);

            //Due Amount & Pay Amount Is Same | CASE - 1
            if ($due_amount == $pay_amount) {
                //Mark As Paid
                $instModel->update([
                    'amount_paid'        => $pay_amount,
                    'amount_paid_date'   => date('Y-m-d'),
                    'amount_paid_source' => $postData['pay_method'],
                    'amount_paid_note'   => $postData['pay_method_note'],
                    'pay_due'            => 0.00,
                    'status'             => 1,
                    'collected_by_salesman_id'   => $postData['collected_by_salesman_id'],
                ]);
                //Add Transaction
                SalePaymentTransactions::create([
                    'sale_id'                       => $instModel->sale_id,
                    'sale_payment_account_id'       => $instModel->sale_payment_account_id,
                    'transaction_title'             => $instModel->emi_title,
                    'amount_paid'                   => $pay_amount,
                    'amount_paid_source'            => $postData['pay_method'],
                    'amount_paid_source_note'       => $postData['pay_method_note'],
                    'amount_paid_date'              => date('Y-m-d'),
                    'status'                        => 1,
                    'payment_collected_by'          => Auth::user()->id,
                    'sale_payment_installment_id'   => $instModel->id
                ]);
            }
            //If Customer Pay Less That Due | CASE - 2
            else if ($due_amount > $pay_amount) {
                //PAY FOR CURRENT EMI
                $p1_due = floatval(($due_amount - $pay_amount));
                $instModel->update([
                    'amount_paid'                => $pay_amount,
                    'amount_paid_date'           => date('Y-m-d'),
                    'amount_paid_source'         => $postData['pay_method'],
                    'amount_paid_note'           => $postData['pay_method_note'],
                    'pay_due'                    => - ($p1_due),
                    'status'                     => 1,
                    'collected_by_salesman_id'   => $postData['collected_by_salesman_id'],
                ]);
                SalePaymentTransactions::create([
                    'sale_id'                       => $instModel->sale_id,
                    'sale_payment_account_id'       => $instModel->sale_payment_account_id,
                    'transaction_title'             => $instModel->emi_title . "Due Part Payment.#EMI-" . $instModel->id,
                    'amount_paid'                   => $pay_amount,
                    'amount_paid_source'            => $postData['pay_method'],
                    'amount_paid_source_note'       => $postData['pay_method_note'],
                    'amount_paid_date'              => date('Y-m-d'),
                    'status'                        => 1,
                    'payment_collected_by'          => Auth::user()->id,
                    'sale_payment_installment_id'   => $instModel->id,
                    'status'                        => 1
                ]);
                //ADJUST NEXT/NEW EMI
                $whereNotIn = array($instModel->id);
                $nextEMIModel = SalePaymentInstallments::where([
                    'status' => '0', 'sale_payment_account_id'  => $instModel->sale_payment_account_id
                ])->whereNotIn('id', $whereNotIn)->first();
                //Create New EMI
                if (empty($nextEMIModel)) {

                    $emi_due_amount    = $p1_due;
                    $emi_due_principal = 0.00;
                    $emi_due_intrest   = 0.00;
                    $emi_due_date      = null;
                    $accountModel = SalePaymentAccounts::find($instModel->sale_payment_account_id);
                    if ($accountModel) {
                        switch ($accountModel->due_payment_source) {
                            case '3':
                                $emi_due_principal = $p1_due;
                                $months = emiTermsMonths($accountModel->finance_terms);
                                $roi   = $accountModel->rate_of_interest;
                                $emi_due_intrest = ($emi_due_principal * $roi * ($months / 12)) / 100;
                                $emi_due_amount = ($emi_due_principal + $emi_due_intrest);
                                $emi_due_date = date('Y-m-d', strtotime("+ $months months"));
                                break;
                            default:
                                $emi_due_date = isset($postData['next_due_Date']) ? date('Y-m-d', strtotime($postData['next_due_Date'])) : date('Y-m-d', strtotime("+ 1 months"));
                                break;
                        }
                    }

                    SalePaymentInstallments::create([
                        'sale_id'                   => $instModel->sale_id,
                        'sale_payment_account_id'   => $instModel->sale_payment_account_id,
                        'emi_title'                 => "Due Pending Last Installment.#" . $instModel->id,
                        'loan_total_amount'         => $instModel->loan_total_amount,
                        'emi_due_amount'            => $emi_due_amount,
                        'emi_due_principal'         => $emi_due_principal,
                        'emi_due_intrest'           => $emi_due_intrest,
                        'emi_due_date'              => $emi_due_date,
                        'emi_other_adjustment'      => ($pay_amount),
                        'emi_other_adjustment_date' => date('Y-m-d'),
                        'emi_other_adjustment_note' => "Due Payment From Last Emi Adjust",
                        'emi_due_revised_amount'    => $emi_due_amount,
                        'emi_due_revised_note'      => "Revised Due After (-" . $pay_amount . ") added.",
                        'status'                    => 0
                    ]);
                }
                //Adjust in existing next EMI Avaliable
                else {
                    $nextEMIModel->update([
                        'emi_other_adjustment'      => ($p1_due),
                        'emi_other_adjustment_date' => date('Y-m-d'),
                        'emi_other_adjustment_note' => "Due Payment From Last Emi Adjust",
                        'emi_due_revised_amount'    => floatval($nextEMIModel->emi_due_revised_amount + $p1_due),
                        'emi_due_revised_note'      => "Revised Due After (-" . $p1_due . ") added.",
                        'status'                    => 0
                    ]);
                }
            }
            //If Customer Pay More Than due | CASE - 3
            else if ($due_amount < $pay_amount) {
                //If Overall remaining is greater than - advance pay
                if ($total_overall_due < $pay_amount) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => "Opps! Sorry you are paying more than due amount."
                    ]);
                }

                //Get Advance Pay
                $advance_pay = floatval($pay_amount - $due_amount);
                $whereNotIn = array($instModel->id);
                $nextEMIModel = SalePaymentInstallments::where([
                    'status' => '0',
                    'sale_payment_account_id'  => $instModel->sale_payment_account_id
                ])->whereNotIn('id', $whereNotIn)->first();
                if (!$whereNotIn) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => "Opps! Sorry something went wrong."
                    ]);
                }

                $nextEMITotal = floatval($nextEMIModel->emi_due_revised_amount);
                //If Advance Payment Is Less Than / Equal To Next EMI
                if ($nextEMITotal >= $advance_pay) {
                    //MARK PAID CURRENT EMI
                    $instModel->update([
                        'amount_paid'        => $pay_amount,
                        'amount_paid_date'   => date('Y-m-d'),
                        'amount_paid_source' => $postData['pay_method'],
                        'amount_paid_note'   => $postData['pay_method_note'],
                        'pay_due'            => 0,
                        'status'             => 1,
                        'collected_by_salesman_id'   => $postData['collected_by_salesman_id'],
                    ]);
                    SalePaymentTransactions::create([
                        'sale_id'                     => $instModel->sale_id,
                        'sale_payment_account_id'     => $instModel->sale_payment_account_id,
                        'transaction_title'           => $instModel->emi_title,
                        'amount_paid'                 => $pay_amount,
                        'amount_paid_source'          => $postData['pay_method'],
                        'amount_paid_source_note'     => $postData['pay_method_note'],
                        'amount_paid_date'            => date('Y-m-d'),
                        'status'                      => 1,
                        'payment_collected_by'        => Auth::user()->id,
                        'sale_payment_installment_id' => $instModel->id
                    ]);

                    //MARK PAID NEXT EMI
                    $p1_status = ($nextEMITotal == $advance_pay) ? 1 : 0;
                    $nextEMIModel->update([
                        'amount_paid'                => $advance_pay,
                        'amount_paid_date'           => date('Y-m-d'),
                        'amount_paid_source'         => $postData['pay_method'],
                        'amount_paid_note'           => "Advance Payment Ref #Inst-" . $instModel->id,
                        'emi_other_adjustment'       => ($p1_status == 1) ? 0 : (-$advance_pay),
                        'emi_other_adjustment_date'  => date('Y-m-d'),
                        'emi_other_adjustment_note'  => "Advance Payment.#EMI-" . $instModel->id,
                        'emi_due_revised_amount'     => ($p1_status == 1) ? 0 : ($nextEMITotal - $advance_pay),
                        'emi_due_revised_note'       => ($p1_status == 1) ? "" : "After Advance Payment Adjusment Remianing Balance.",
                        'pay_due'                    => $advance_pay,
                        'status'                     => $p1_status,
                        'collected_by_salesman_id'   => $postData['collected_by_salesman_id'],
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => "Opps! Sorry Advance payment is more then next EMI."
                    ]);
                }
            }


            //Account status check all dues & mark closed
            self::verifyAccountPendings();

            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Your payment has been done successfully."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage()
            ]);
        }
    }

    public function printPayemntReciept(Request $request, $id)
    {
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }

        $paymentInstallmentModel = SalePaymentInstallments::where('id', $id)
            ->whereHas('sale', function ($sale) use ($where) {
                $sale->where($where);
            })
            ->with([
                'sale' => function ($sale) {
                    $sale->with(['branch', 'purchase']);
                },
                'account' => function ($account) {
                    $account->select('id', 'financier_id')->with([
                        'financier' => function ($financier) {
                            $financier->select('id', 'bank_name');
                        }
                    ]);
                }
            ])
            ->first();


        if (!$paymentInstallmentModel) {
            return view('admin.accessDenied');
        }

        // return view('admin.sales-accounts.printPaymentReciept', ['data' => $paymentInstallmentModel]);
        $pdf = Pdf::loadView('admin.sales-accounts.printPaymentReciept', ['data' => $paymentInstallmentModel]);
        return $pdf->stream('invoice.pdf');
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
                    $data['data']['total_due'] = floatval($paymentAccount['cash_outstaning_balance'] + $paymentAccount['bank_finance_outstaning_balance'] + $paymentAccount['personal_finance_outstaning_balance']);
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
                                        'modelColor' => function ($model) {
                                            $model->select('id', 'color_name');
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
