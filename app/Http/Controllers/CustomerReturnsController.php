<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\CustomerReturnRtoRegistration;
use App\Models\CustomerReturnSale;
use App\Models\CustomerReturnSalePaymentAccounts;
use App\Models\CustomerReturnSalePaymentInstallments;
use App\Models\CustomerReturnSalePaymentTransactions;
use App\Models\CustomerReturnSaleRefund;
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
            $rtoRegistrationModel = RtoRegistration::where('sale_id', $postData['sales_id'])->first();
            ///CREATE DUPLICATE CLONE

            //SALE MODEL
            $salesModelClone = $salesModel->toArray();
            CustomerReturnSale::create($salesModelClone);

            //SALE ACCOUNT MODEL
            $saleAccountModelClone = $saleAccountModel->toArray();
            CustomerReturnSalePaymentAccounts::create($saleAccountModelClone);

            //SALE RTO REGISTRATION MODEL
            if ($rtoRegistrationModel) {
                $rtoRegistrationModelClone = $rtoRegistrationModel->toArray();
                CustomerReturnRtoRegistration::create($rtoRegistrationModelClone);
            }

            //RETSTORE PURCHASE INVENTORY
            Purchase::where('id', $salesModel->purchase_id)->update(['status' => '1']);

            //DELETE
            SalePaymentAccounts::where('sale_id', $postData['sales_id'])->delete();
            Sale::where('id', $postData['sales_id'])->delete();
            if ($rtoRegistrationModel) {
                RtoRegistration::where('sale_id', $postData['sales_id'])->delete();
            }

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
        $modals = CustomerReturnSale::where('id', $id)->with([
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

        $accountModel = CustomerReturnSalePaymentAccounts::where('sale_id', $id)->first();

        $data = array(
            'data' => $modals,
            'total_paid' => ($accountModel->cash_paid_balance + $accountModel->bank_finance_paid_balance + $accountModel->personal_finance_paid_balance),
            'total_refund' => CustomerReturnSaleRefund::where('sale_id', $id)->sum('amount_refund')
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.customer-return-sales.show', $data)->render()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTransactions($id)
    {
        $modals = SalePaymentTransactions::where(['sale_id' => $id, 'status' => '1'])->where('transaction_paid_source', '!=', 'Auto')->where('transaction_paid_source', '!=', '')->whereNotNull('transaction_paid_source')->get();
        $data = array(
            'transactions' => $modals
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.customer-return-sales.showTransactions', $data)->render()
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
        //TODO
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
        //TODO
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
        $action .= '<li><a href="' . route('customerReturns.show', ['customerReturn' => $row->id]) . '" class="ajaxModalPopup" data-title="VIEW DETAIL" data-modal_title="VIEW DETAIL" data-modal_size="modal-lg">VIEW DETAIL</a></li>';
        $action .= '<li><a href="' . route('showTransactions', ['id' => $row->id]) . '" class="ajaxModalPopup" data-title="SHOW PAID TRANSACTIONS" data-modal_title="SHOW PAID TRANSACTIONS" data-modal_size="modal-lg">SHOW PAID TRANSACTIONS</a></li>';
        $action .= '<li><a href="' . route('customerRefunds.show', ['customerRefund' => $row->id]) . '" class="ajaxModalPopup" data-title="SHOW REFUND HISTORY" data-modal_title="SHOW REFUND HISTORY" data-modal_size="modal-lg">SHOW REFUND HISTORY</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }
}
