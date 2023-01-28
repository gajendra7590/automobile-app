<?php

namespace App\Http\Controllers;

use App\Models\SalePaymentTransactions;
use Illuminate\Http\Request;

class SalePaymentTransactionController extends Controller
{

    public function show(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $data['data'] = SalePaymentTransactions::where('id', $id)->with([
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
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Tab ajax data loaded",
                'data'       => view('admin.sales-accounts.transactions.preview', $data)->render()
            ]);
        }
    }
}
