<?php

namespace App\Http\Controllers;

use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentCash;
use App\Models\SalePaymentTransactions;
use App\Traits\CommonHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalePaymentCashController extends Controller
{
    use CommonHelper;
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
    public function create(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $postData = $request->all();
            $salePaymentAccount = SalePaymentAccounts::find($postData['id']);
            if (!$salePaymentAccount) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! Account does not exis"
                ]);
            }

            $data = array(
                'data' => $salePaymentAccount,
                'depositeSources' => depositeSources(),
                'salemans'        => self::_getSalesman()
            );
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Tab ajax data loaded",
                'data'       => view('admin.sales-accounts.cash-payment.pay', $data)->render()
            ]);
        }
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
            $postData = $request->only('sales_account_id', 'total_outstanding', 'paid_amount', 'paid_date', 'paid_source', 'status', 'collected_by', 'next_due_date', 'payment_note');
            $validator = Validator::make($postData, [
                'sales_account_id'      => "required|exists:sale_payment_accounts,id",
                'total_outstanding'     => "required|numeric|min:1",
                'paid_amount'           => "required|numeric|min:1|lte:total_outstanding",
                'paid_date'             => 'required|date',
                'paid_source'           => 'required|string',
                'status'                => 'required|in:0,1,2,3',
                'next_due_date'         => 'required|date|after:' . now()->format('Y-m-d'),
                'payment_note'          => 'nullable|string',
                'collected_by'          => 'nullable|exists:salesmans,id'
            ]);
            //If Validation failed
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }

            $salePaymentAccount = SalePaymentAccounts::find($postData['sales_account_id']);

            $newOutStanding = floatval($postData['total_outstanding'] - $postData['paid_amount']);
            //DEBIT SALES CASH PAYMENT
            $payment_name = 'Down Payment Paid By Customer.';
            $createdCashPayment = SalePaymentCash::create([
                'sale_id' => $salePaymentAccount->sale_id,
                'sale_payment_account_id' => $salePaymentAccount->id,
                'payment_name' => $payment_name,
                'credit_amount' => 0,
                'debit_amount' => $postData['paid_amount'],
                'change_balance' => $newOutStanding,
                'due_date'    => $postData['next_due_date'],
                'paid_source' => $postData['paid_source'],
                'paid_date' => $postData['paid_date'],
                'paid_note' => $postData['payment_note'],
                'collected_by' => $postData['collected_by'],
                'trans_type' => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                'status' => $postData['status']
            ]);
            //CREATE NEW TRANSACTION
            SalePaymentTransactions::create([
                'sale_id' => $salePaymentAccount->sale_id,
                'sale_payment_account_id' => $salePaymentAccount->id,
                'transaction_for' => 1,
                'transaction_name' => $payment_name,
                'transaction_amount' => $postData['paid_amount'],
                'transaction_paid_source' => $postData['paid_source'],
                'transaction_paid_source_note' => $postData['payment_note'],
                'transaction_paid_date' => $postData['paid_date'],
                'trans_type' => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                'status' => $postData['status'],
                'reference_id' => $createdCashPayment->id
            ]);


            $outStandingBalance = getCashDueTotal($salePaymentAccount->id);
            $updateDAta = ['cash_status' => '0', 'cash_outstaning_balance' => $outStandingBalance];
            if (floatval($outStandingBalance) == 0) {
                $updateDAta['cash_status'] = 1;
            }
            $salePaymentAccount->update($updateDAta);
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
    public function show(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $data['data'] = SalePaymentCash::where('id', $id)->with([
                'account' => function ($account) {
                    $account->select('id', 'account_uuid', 'sales_total_amount', 'financier_id', 'due_payment_source', 'status');
                },
                'sale' => function ($account) {
                    $account->select('id', 'customer_name', 'status');
                },
                'salesman' => function ($salesman) {
                    $salesman->select('id', 'name');
                }
            ])->first();

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Tab ajax data loaded",
                'data'       => view('admin.sales-accounts.cash-payment.preview', $data)->render()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $salePaymentAccount = SalePaymentAccounts::find($id);
            if (!$salePaymentAccount) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! Account does not exis"
                ]);
            }

            $data = array(
                'data' => $salePaymentAccount
            );
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Add Charges Modal Loaded",
                'data'       => view('admin.sales-accounts.cash-payment.addCharges', $data)->render()
            ]);
        }
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
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            try {
                DB::beginTransaction();
                $postData = $request->only('sales_account_id', 'charge_amount', 'charge_reason', 'charge_note');
                $validator = Validator::make($postData, [
                    'sales_account_id'   => "required|exists:sale_payment_accounts,id",
                    'charge_amount'      => "required|numeric|min:1",
                    'charge_reason'      => "required",
                    'charge_note'        => 'nullable|string'
                ]);
                //If Validation failed
                if ($validator->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => $validator->errors()->first(),
                        'errors'     => $validator->errors()
                    ]);
                }

                $salePaymentAccount = SalePaymentAccounts::find($postData['sales_account_id']);
                $currentCashOutStandingBalance = $salePaymentAccount->cash_outstaning_balance;

                $lastCash = SalePaymentCash::where('sale_payment_account_id', $id)->orderBy('id', 'DESC')->first();
                //DEBIT SALES CASH PAYMENT
                $payment_name = $postData['charge_reason'] . ' Added.';
                $createdCashPayment = SalePaymentCash::create([
                    'sale_id' => $salePaymentAccount->sale_id,
                    'sale_payment_account_id' => $salePaymentAccount->id,
                    'payment_name' => $payment_name,
                    'credit_amount' => $postData['charge_amount'],
                    'debit_amount' => 0,
                    'change_balance' => floatval($currentCashOutStandingBalance + $postData['charge_amount']),
                    'due_date'    => $lastCash->due_date,
                    'paid_source' => 'Auto',
                    'paid_date' => date('Y-m-d'),
                    'paid_note' => $postData['charge_note'],
                    'collected_by' => 0,
                    'trans_type' => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                    'status' => 1
                ]);
                //CREATE NEW TRANSACTION
                SalePaymentTransactions::create([
                    'sale_id' => $salePaymentAccount->sale_id,
                    'sale_payment_account_id' => $salePaymentAccount->id,
                    'transaction_for' => 1,
                    'transaction_name' => $payment_name,
                    'transaction_amount' => $postData['charge_amount'],
                    'transaction_paid_source' => 'Auto',
                    'transaction_paid_source_note' => $postData['charge_note'],
                    'transaction_paid_date' => date('Y-m-d'),
                    'trans_type' => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                    'status' => 1,
                    'reference_id' => $createdCashPayment->id
                ]);

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
     * Function for get / print receipt
     */
    public function salesCashReceipt(Request $request, $id)
    {
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }
        $paymentInstallmentModel = SalePaymentCash::where('id', $id)
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
        // return $paymentInstallmentModel;
        if (!$paymentInstallmentModel) {
            return view('admin.accessDenied');
        }

        // return view('admin.sales-accounts.cash-payment.print', ['data' => $paymentInstallmentModel]);
        $pdf = Pdf::loadView('admin.sales-accounts.cash-payment.print', ['data' => $paymentInstallmentModel]);
        return $pdf->stream('invoice.pdf');
    }
}
