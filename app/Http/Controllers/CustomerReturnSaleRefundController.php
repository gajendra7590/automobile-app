<?php

namespace App\Http\Controllers;

use App\Models\CustomerReturnSale;
use App\Models\CustomerReturnSalePaymentAccounts;
use App\Models\CustomerReturnSalePaymentTransactions;
use App\Models\CustomerReturnSaleRefund;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerReturnSaleRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $postData = $request->all();
            DB::beginTransaction();
            $validator = Validator::make($postData, [
                'sale_id' => 'required|exists:customer_return_sales,id',
                'sale_account_id'  => 'required|exists:customer_return_sale_payment_accounts,id',
                'amount_refund'    => 'required|numeric|min:1',
                'total_refund_due' => 'required|numeric|min:1',
                'amount_refund_source' => "required",
                'amount_refund_date' => 'required|date',
                'payment_collected_by' => 'required',
                'payment_refund_note' => 'required'
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

            if (floatval($postData['amount_refund']) > floatval($postData['total_refund_due'])) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! you can not pay more than due amount."
                ]);
            }

            //Create Refund
            CustomerReturnSaleRefund::create($postData);
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $customerReturnSaleAccount =  CustomerReturnSalePaymentAccounts::select('id', 'sale_id')->where('sale_id', $id)->first();
        if (!$customerReturnSaleAccount) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['ID' => $id]),
            ]);
        }
        $modals = CustomerReturnSaleRefund::where(['sale_id' => $id])->get();
        $accountModel = CustomerReturnSalePaymentAccounts::where('sale_id', $id)->first();
        $total_paid   = ($accountModel->cash_paid_balance + $accountModel->bank_finance_paid_balance + $accountModel->personal_finance_paid_balance);
        $total_refund = CustomerReturnSaleRefund::where('sale_id', $id)->sum('amount_refund');
        $data = array(
            'transactions'        => $modals,
            'salesAccount'        => $customerReturnSaleAccount,
            'depositeSources'     => depositeSources(),
            'action'              => route('customerRefunds.store'),
            'method'              => "POST",
            'total_paid'          => $total_paid,
            'total_refund'        => $total_refund,
            'total_refundable'    => floatval($total_paid - $total_refund)
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.customer-return-sales.showRefunds', $data)->render()
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
        //
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
}
