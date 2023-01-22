<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\CustomerReturnSale;
use App\Models\CustomerReturnSalePaymentAccounts;
use App\Models\CustomerReturnSalePaymentInstallments;
use App\Models\CustomerReturnSalePaymentTransactions;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentInstallments;
use App\Models\SalePaymentTransactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

//Helpr
use App\Traits\CommonHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CustomerReturnsController extends Controller
{
    use CommonHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.customer-return-sales.index');
        } else {

            $data = CustomerReturnSale::select('*')
                ->branchWise()
                ->select('id', 'branch_id', 'purchase_id', 'customer_name', 'total_amount', 'created_at', 'status', 'sp_account_id')
                ->with([
                    'purchase',
                    'salesman' => function ($model) {
                        $model->select('id', 'name');
                    },
                ]);

            // dd($data->limit(10)->get()->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('branch_name', function ($row) {
                    if (isset($row->purchase->branch)) {
                        return $row->purchase->branch->branch_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('dealer_name', function ($row) {
                    if (isset($row->purchase->dealer)) {
                        return $row->purchase->dealer->company_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('customer_name', function ($row) {
                    return ucwords($row->customer_name);
                })
                ->addColumn('bike_detail', function ($row) {
                    $str = '';
                    if (isset($row->purchase->brand)) {
                        $str .= $row->purchase->brand->name . ' | ';
                    }

                    if (isset($row->purchase->model)) {
                        $str .= $row->purchase->model->model_name . ' | ';
                    }

                    if (isset($row->purchase->modelColor)) {
                        $str .= $row->purchase->modelColor->color_name;
                    }
                    return $str;
                })
                ->addColumn('total_amount', function ($row) {
                    return '₹' . $row->total_amount;
                })
                ->addColumn('created_at', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'open') {
                        return '<span class="label label-warning">Open</span>';
                    } else {
                        return '<span class="label label-success">Close</span>';
                    }
                })
                ->rawColumns([
                    'action', 'branch_name', 'dealer_name', 'customer_name',
                    'bike_detail', 'total_amount', 'created_at', 'status'
                ])
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
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.customer-return-sales.ajaxModal', ['action' => route('customerReturns.store')])->render()
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

            DB::beginTransaction();;
            $postData = $request->only('sales_id');
            $validator = Validator::make($postData, [
                'sales_id' => 'required|exists:sales,id'
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

            //Get All Old Models
            $salesModel = Sale::where('id', $postData['sales_id'])->first();
            $saleAccountModel = SalePaymentAccounts::where('sale_id', $postData['sales_id'])->first();
            $saleInstallmentModel = SalePaymentInstallments::where('sale_id', $postData['sales_id'])->get();
            $saleTransactionModel = SalePaymentTransactions::where('sale_id', $postData['sales_id'])->get();

            ///CREATE DUPLICATE CLONE

            //SALE MODEL
            $salesModelClone = $salesModel->toArray();
            $salesModelClone['old_id'] = $salesModel->id;
            $newSaleModel = CustomerReturnSale::create($salesModelClone);

            //SALE ACCOUNT MODEL
            $saleAccountModelClone = $saleAccountModel->toArray();
            $saleAccountModelClone['sale_id'] = $newSaleModel->id;
            $saleAccountModelClone['old_id'] = $saleAccountModel->id;
            $saleAccountModelClone['old_sale_id'] = $salesModel->id;
            $newAccountModel = CustomerReturnSalePaymentAccounts::create($saleAccountModelClone);

            //SALE ACCOUNT INSTALLMENT MODEL
            $saleInstallmentModelClone = $saleInstallmentModel->toArray();
            foreach ($saleInstallmentModelClone as $installment) {
                $installment['sale_id'] = $newSaleModel->id;
                $installment['old_sale_id'] = $salesModel->id;
                $installment['old_id'] = $installment['id'];
                $installment['sale_payment_account_id'] = $newAccountModel->id;
                CustomerReturnSalePaymentInstallments::create($installment);
            }

            //SALE ACCOUNT TRANSACTIONS MODEL
            $saleTransactionModelClone = $saleTransactionModel->toArray();
            foreach ($saleTransactionModelClone as $transaction) {
                $transaction['sale_id'] = $newSaleModel->id;
                $transaction['old_sale_id'] = $salesModel->id;
                $transaction['old_id'] = $transaction['id'];
                $transaction['sale_payment_account_id'] = $newAccountModel->id;
                if (isset($transaction['sale_payment_installment_id']) && (intval($transaction['sale_payment_installment_id']) > 0)) {
                    $transaction['sale_payment_installment_id'] = CustomerReturnSalePaymentInstallments::where('old_id', $transaction['sale_payment_installment_id'])->value('id');
                }
                CustomerReturnSalePaymentTransactions::create($transaction);
            }

            //RETSTORE PURCHASE INVENTORY
            Purchase::where('id', $salesModel->purchase_id)->update(['status' => '1']);

            //DELETE
            RtoRegistration::where('sale_id', $postData['sales_id'])->delete();
            SalePaymentTransactions::where('sale_id', $postData['sales_id'])->delete();
            SalePaymentInstallments::where('sale_id', $postData['sales_id'])->delete();
            SalePaymentAccounts::where('sale_id', $postData['sales_id'])->delete();
            Sale::where('id', $postData['sales_id'])->delete();

            //Commit All the things
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
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
        $modals = Sale::where('id', $id)->with([
            'state' => function ($s) {
                $s->select('id', 'state_name');
            },
            'district' => function ($s) {
                $s->select('id', 'district_name');
            },
            'city' => function ($s) {
                $s->select('id', 'city_name');
            },
            'financer' => function ($s) {
                $s->select('id', 'bank_name');
            },
            'branch' => function ($s) {
                $s->select('id', 'branch_name');
            },
            'salesman' => function ($s) {
                $s->select('id', 'name');
            },
            'quotation' => function ($s) {
                $s->select('id', 'uuid');
            },
            'purchase'
        ])->first();
        if (!$modals) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }

        $data = array(
            'data' => $modals
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.sales.show', $data)->render()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auth = User::find(auth()->id());
        $bpModel = Sale::where(['id' => $id])->first();
        // return $bpModel;
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }

        $purhcaseModel = Purchase::with([
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
            'color' => function ($model) {
                $model->select('id', 'color_name');
            },
            'tyreBrand' => function ($model) {
                $model->select('id', 'name');
            },
            'batteryBrand' => function ($model) {
                $model->select('id', 'name');
            }
        ])->where('id', $bpModel['purchase_id'])->first();
        $data = array(
            'method' => 'PUT',
            'action' => route('sales.update', ['sale' => $bpModel->id]),
            'states' => self::_getStates(1),
            'districts' => [],
            'cities' => [],
            'gst_rto_rates' => self::_getRtoGstRates(),
            'salesmans' => self::_getSalesmanById($bpModel->salesman_id),
            'purchaseModel' => $purhcaseModel
        );
        //Quotation data for prefield
        if ($bpModel) {
            $data['data'] = $bpModel;
            if ($bpModel) {
                $data['districts'] = self::_getDistricts($bpModel->customer_state);
                $data['cities'] = self::_getCities($bpModel->customer_district);
                $data['bank_financers'] = self::_getFinaceirs($bpModel->payment_type - 1);
            }
        }

        $data['htmlData'] = (view('admin.sales.ajax.ajax-view')->with($data)->render());
        $data['purchases'] = self::_getPurchasesById($bpModel->purchase_id);
        return view('admin.sales.create', $data);
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
        try {
            $bpModel = Sale::where(['id' => $id])->first();
            if (!$bpModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            $postData = $request->only(
                'purchase_id',
                'quotation_id',
                'salesman_id',
                'customer_gender',
                'customer_name',
                'customer_relationship',
                'customer_guardian_name',
                'customer_address_line',
                'customer_state',
                'customer_district',
                'customer_city',
                'customer_zipcode',
                'customer_mobile_number',
                'customer_mobile_number_alt',
                'customer_email_address',
                'witness_person_name',
                'witness_person_phone',
                'payment_type',
                'is_exchange_avaliable',
                'hyp_financer',
                'hyp_financer_description',
                'ex_showroom_price',
                'registration_amount',
                'insurance_amount',
                'hypothecation_amount',
                'accessories_amount',
                'other_charges',
                'total_amount'
            );
            $formValidationArr = [
                'purchase_id' => 'nullable|exists:purchases,id',
                'quotation_id' => 'nullable|exists:quotations,id',
                'salesman_id' => 'nullable|exists:salesmans,id',
                'customer_gender' => 'nullable|in:1,2,3',
                'customer_name' => 'nullable|string',
                'customer_relationship' => 'nullable|in:1,2,3',
                'customer_guardian_name' => 'nullable|string',
                'customer_address_line' => 'nullable|string',
                'customer_state' => 'nullable|exists:u_states,id',
                'customer_district' => 'nullable|exists:u_districts,id',
                'customer_city' => 'nullable|exists:u_cities,id',
                'customer_zipcode' => 'nullable|numeric',
                'customer_mobile_number' => 'nullable|numeric',
                'customer_mobile_number_alt' => 'nullable|numeric|min:10',
                'customer_email_address' => 'nullable|email',
                'witness_person_name' => 'nullable',
                'witness_person_phone' => 'nullable|numeric|min:10',
                'payment_type' => 'nullable|in:1,2,3',
                'is_exchange_avaliable' => 'nullable|in:Yes,No',
                'hyp_financer' => 'nullable|exists:bank_financers,id',
                'hyp_financer_description' => 'nullable',
                'ex_showroom_price' => 'nullable|numeric',
                'registration_amount' => 'nullable|numeric',
                'insurance_amount' => 'nullable|numeric',
                'hypothecation_amount' => 'nullable|numeric',
                'accessories_amount' => 'nullable|numeric',
                'other_charges' => 'nullable|numeric',
                'total_amount' => 'nullable|numeric'
            ];
            if (isset($postData['payment_type']) && (in_array($postData['payment_type'], ['2', '3']))) {
                $formValidationArr['hyp_financer'] = 'nullable|exists:bank_financers,id';
            }
            $validator = Validator::make($postData, $formValidationArr);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }
            $postData['updated_by'] = Auth::user()->id;

            // dd($postData);
            $bpModel->update($postData);
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.update_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
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

    /**
     * Function for generate Full Delivery Challan
     * @param $id  Sales ID
     * @return Generate PDF Invoice
     */
    public function deliveryChallanFull(Request $request, $id)
    {
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }

        $saleModel = Sale::where('id', $id)
            ->where($where)
            ->with([
                'branch',
                'purchase'
            ])
            ->first();
        if (!$saleModel) {
            return view('admin.accessDenied');
        }

        // return view('admin.sales.deliveryChallanFull', ['data' => $saleModel]);
        $pdf = Pdf::loadView('admin.sales.deliveryChallanFull', ['data' => $saleModel]);
        return $pdf->stream('invoice.pdf');
    }


    /**
     * Function for generate On Road Delivery Challan
     * @param $id  Sales ID
     * @return Generate PDF Invoice
     */
    public function deliveryChallanOnRoad(Request $request, $id)
    {
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }

        $saleModel = Sale::where('id', $id)
            ->where($where)
            ->with([
                'branch',
                'purchase'
            ])
            ->first();
        if (!$saleModel) {
            return view('admin.accessDenied');
        }

        // return view('admin.sales.deliveryChallanOnRoad', ['data' => $saleModel]);
        $pdf = Pdf::loadView('admin.sales.deliveryChallanOnRoad', ['data' => $saleModel]);
        return $pdf->stream('invoice.pdf');
    }


    public function getActions($row)
    {
        $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
        $action  .= '<ul class="dropdown-menu">';
        $action .= '<li><a href="' . route('sales.show', ['sale' => $row->id]) . '" class="ajaxModalPopup" data-title="VIEW DETAIL" data-modal_title="VIEW DETAIL" data-modal_size="modal-lg">VIEW DETAIL</a></li>';
        if ($row->status == 'open') {
            $action .= '<li><a href="' . route('sales.edit', ['sale' => $row->id]) . '" class="" data-modal_title="UPDATE DETAIL">UPDATE</a></li>';
        }

        $action .= '<li><a href="' . route('deliveryChallanFull', ['id' => base64_encode($row->id)]) . '" target="_blank" class="" data-modal_title="UPDATE DETAIL">DELIVERY CHALLAN FULL</a></li>';
        $action .= '<li><a href="' . route('deliveryChallanOnRoad', ['id' => base64_encode($row->id)]) . '" target="_blank" class="" data-modal_title="UPDATE DETAIL">DELIVERY CHALLAN ON ROAD</a></li>';

        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    public function getModelsList($id)
    {
        $models = BikeModel::where('active_status', '1')->where(['brand_id' => $id])->get()->toArray();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => models_list($models)
        ]);
    }

    public function ajaxLoadeView(Request $request)
    {
        $postData = $request->all();
        $purhcaseModel = Purchase::with([
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
            'color' => function ($model) {
                $model->select('id', 'color_name');
            },
            'tyreBrand' => function ($model) {
                $model->select('id', 'name');
            },
            'batteryBrand' => function ($model) {
                $model->select('id', 'name');
            }
        ])->where('id', $postData['id'])->first();
        $data = array(
            'states' => self::_getStates(1),
            'districts' => [],
            'cities' => [],
            'gst_rto_rates' => self::_getRtoGstRates(),
            'purchaseModel' => $purhcaseModel,
            'action' => "add"
        );
        //Quotation data for prefield
        if (!empty($postData['q'])) {
            $quot = Quotation::select('*')->find(request('q'));
            $data['data'] = $quot;
            if ($quot) {
                $data['districts'] = self::_getDistricts($purhcaseModel->customer_state);
                $data['cities'] = self::_getCities($purhcaseModel->customer_district);
                $data['bank_financers'] = self::_getFinaceirs(($quot->payment_type - 1));
            }
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'Load Form',
            'data'       => (view('admin.sales.ajax.ajax-view')->with($data)->render())
        ]);
    }
}
