<?php

namespace App\Http\Controllers;

use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentBankFinanace;
use App\Models\SalePaymentCash;
use App\Models\SalePaymentTransactions;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalePaymentBankFinanaceController extends Controller
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
        $postData = $request->all();
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $id = isset($postData['id']) ? $postData['id'] : 0;
            $checkAccount = SalePaymentAccounts::select('id')->where('id', $id)->first();
            if (!$checkAccount) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! Account does not exis"
                ]);
            }
            $totalDue = getCashDueTotal($checkAccount->id);
            $data = array(
                'totalDue' =>  $totalDue,
                'data' =>  $checkAccount,
                'financers' => self::_getFinaceirs(1)
            );
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Ajax View Loaded",
                'data'       => view('admin.sales-accounts.bank-finanace.create', $data)->render()
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
            $postData = $request->all();
            if (!$request->ajax()) {
                return redirect()->route('saleAccounts.index');
            } else {
                //Request
                DB::beginTransaction();
                $validator = Validator::make($postData, [
                    'sales_account_id'      => "required|exists:sale_payment_accounts,id",
                    'total_outstanding'     => "required|numeric",
                    'total_finance_amount'  => "required|numeric|min:1|lte:total_outstanding",
                    'finance_due_date'      => 'required|date|after:' . now()->format('Y-m-d'),
                    'financier_id'          => 'required|exists:bank_financers,id',
                    'financier_note'        => 'nullable|string'
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

                //CODE START FROM HERE
                $salesAccountModel = SalePaymentAccounts::find($postData['sales_account_id']);
                if ($postData['total_outstanding'] == $postData['total_finance_amount']) {
                    $salesAccountModel->update([
                        'due_payment_source'      => 2,
                        'financier_id'            => $postData['financier_id'],
                        'financier_note'          => $postData['financier_note'],
                        'cash_status'             => SalePaymentAccounts::STATUS_PAID,
                        'bank_finance_status'     => SalePaymentAccounts::STATUS_DUE,
                        'personal_finance_status' => SalePaymentAccounts::STATUS_PAID,
                        'status'                  => SalePaymentAccounts::ACC_STATUS_OPEN
                    ]);
                } else {
                    $salesAccountModel->update([
                        'due_payment_source'      => 2,
                        'financier_id'            => $postData['financier_id'],
                        'financier_note'          => $postData['financier_note'],
                        'cash_status'             => SalePaymentAccounts::STATUS_DUE,
                        'bank_finance_status'     => SalePaymentAccounts::STATUS_DUE,
                        'personal_finance_status' => SalePaymentAccounts::STATUS_PAID,
                        'status'                  => SalePaymentAccounts::ACC_STATUS_OPEN
                    ]);
                }
                $salesAccountModel = $salesAccountModel->fresh();
                //Create Entry In Sales Cash Balance - Debit Finance Amount
                $payment_name = "Cash Balance Conveterd To Bank Finance.";
                $paid_note    = "Customer cash due balance converted to bank finance.";
                $lastCashBalModel = SalePaymentCash::where('sale_id', $salesAccountModel->sale_id)->orderBy('id', 'DESC')->first();
                $CashModel = SalePaymentCash::create([
                    'sale_id' => $salesAccountModel->sale_id,
                    'sale_payment_account_id' => $salesAccountModel->id,
                    'payment_name'   => $payment_name,
                    'credit_amount'  => 0,
                    'debit_amount'   => $postData['total_finance_amount'],
                    'change_balance' => floatval($postData['total_outstanding'] - $postData['total_finance_amount']),
                    'due_date'       => $lastCashBalModel->due_date,
                    'paid_source'    => "Auto",
                    'paid_date'      => date('Y-m-d'),
                    'paid_note'      => $paid_note,
                    'collected_by'   => 0,
                    'trans_type'     => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                    'status'         => SalePaymentAccounts::PAY_STATUS_PAID
                ]);
                //Create Entry Into Transaction For Debit Cash Balance
                SalePaymentTransactions::create([
                    'sale_id'                      => $CashModel->sale_id,
                    'sale_payment_account_id'      => $CashModel->sale_payment_account_id,
                    'transaction_for'              => 1,
                    'transaction_name'             => $payment_name,
                    'transaction_amount'           => $CashModel->debit_amount,
                    'transaction_paid_source'      => "Auto",
                    'transaction_paid_source_note' => $paid_note,
                    'transaction_paid_date'        => date('Y-m-d'),
                    'trans_type'                   => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                    'status'                       => SalePaymentAccounts::PAY_STATUS_PAID,
                    'reference_id'                 => $CashModel->id
                ]);
                //CREATE BANK FINANCE ENTRY
                $payment_name = "Finance Account Created.";
                $paid_note    = "Finance Account Balance Credited";
                $financeModel = SalePaymentBankFinanace::create([
                    'sale_id' => $salesAccountModel->sale_id,
                    'sale_payment_account_id' => $salesAccountModel->id,
                    'payment_name'   => $payment_name,
                    'credit_amount'  => $postData['total_finance_amount'],
                    'debit_amount'   => 0,
                    'change_balance' => $postData['total_finance_amount'],
                    'due_date'       => $postData['finance_due_date'],
                    'paid_source'    => "Auto",
                    'paid_date'      => null,
                    'paid_note'      => $paid_note,
                    'collected_by'   => 0,
                    'trans_type'     => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                    'status'         => SalePaymentAccounts::PAY_STATUS_PAID
                ]);
                //Create Entry Into Transaction For Credit Finance Balance
                SalePaymentTransactions::create([
                    'sale_id'                      => $financeModel->sale_id,
                    'sale_payment_account_id'      => $financeModel->sale_payment_account_id,
                    'transaction_for'              => 2,
                    'transaction_name'             => $payment_name,
                    'transaction_amount'           => $financeModel->credit_amount,
                    'transaction_paid_source'      => "Auto",
                    'transaction_paid_source_note' => $paid_note,
                    'transaction_paid_date'        => date('Y-m-d'),
                    'trans_type'                   => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                    'status'                       => SalePaymentAccounts::PAY_STATUS_PAID,
                    'reference_id'                 => $financeModel->id
                ]);
                DB::commit();
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.create_success')
                ]);
            }
        } catch (\Exception $e) {
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
    public function edit(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $checkAccount = SalePaymentAccounts::select('*')->where('id', $id)->first();
            if (!$checkAccount) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! Account does not exis"
                ]);
            }

            //Check IF No Transaction DONE
            $checkIfAccountDebited = SalePaymentBankFinanace::where(["sale_payment_account_id" => $id, 'trans_type' => "2"])->count();
            if ($checkIfAccountDebited > 0) {
                DB::rollBack();
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! This account can not be edit."
                ]);
            }

            $bankFinanceModel = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->orderBy('id', 'ASC')->first();

            $totalDue = ($checkAccount->cash_outstaning_balance + $checkAccount->bank_finance_outstaning_balance);
            $data = array(
                'totalDue' => $totalDue,
                'data' =>  $checkAccount,
                'financers' => self::_getFinaceirs(1),
                'current_due_date' => $bankFinanceModel->due_date
            );
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Ajax View Loaded",
                'data'       => view('admin.sales-accounts.bank-finanace.edit', $data)->render()
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
        try {
            $postData = $request->all();
            // dd($postData);
            if (!$request->ajax()) {
                return redirect()->route('saleAccounts.index');
            } else {
                //Request
                DB::beginTransaction();

                $salesAccountModel = SalePaymentAccounts::find($id);
                if (!$salesAccountModel) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => trans('messages.id_not_exist', ["ID" => $id])
                    ]);
                }

                //Check IF No Transaction DONE
                $checkIfAccountDebited = SalePaymentBankFinanace::where(["sale_payment_account_id" => $id, 'trans_type' => "2"])->count();
                if ($checkIfAccountDebited > 0) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => "Sorry! This account can not be edit."
                    ]);
                }

                //Form Validation
                $validator = Validator::make($postData, [
                    'sales_account_id'      => "required|exists:sale_payment_accounts,id",
                    'total_outstanding'     => "required|numeric",
                    'total_finance_amount'  => "required|numeric|min:1|lte:total_outstanding",
                    'finance_due_date'      => 'required|date|after:' . now()->format('Y-m-d'),
                    'financier_id'          => 'required|exists:bank_financers,id',
                    'financier_note'        => 'nullable|string'
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

                $bankFinanceModel = SalePaymentBankFinanace::where('sale_payment_account_id', $id)->orderBy('id', 'ASC')->first();

                //CASE - 1 IF Amount As Same As Last Then Update Info Only
                if ($salesAccountModel->bank_finance_outstaning_balance ==  $postData['total_finance_amount']) {
                    $differenceAmount = ($salesAccountModel->bank_finance_outstaning_balance - $postData['total_finance_amount']);
                    $salesAccountModel->update([
                        'financier_id' => $postData['financier_id'],
                        'financier_note' => $postData['financier_note']
                    ]);
                    $bankFinanceModel->update([
                        'due_date' => $postData['finance_due_date']
                    ]);
                }


                //CASE - 2 IF Amount Is Less Than Last One
                if ($salesAccountModel->bank_finance_outstaning_balance > $postData['total_finance_amount']) {
                    $differenceAmount = ($salesAccountModel->bank_finance_outstaning_balance - $postData['total_finance_amount']);
                    $salesAccountModel->update([
                        'financier_id' => $postData['financier_id'],
                        'financier_note' => $postData['financier_note']
                    ]);
                    $bankFinanceModel->update([
                        'due_date' => $postData['finance_due_date'],
                        'credit_amount'   => $postData['total_finance_amount'],
                        'debit_amount'    => 0,
                        'change_balance'  => $postData['total_finance_amount']
                    ]);

                    //Create Entry In Sales Cash Balance - Credit Amount
                    $payment_name = "Bank Finance Balance Lowered Down And Converted To Cash.";
                    $lastCashBalModel = SalePaymentCash::where('sale_id', $salesAccountModel->sale_id)->orderBy('id', 'DESC')->first();
                    $CashModel = SalePaymentCash::create([
                        'sale_id' => $salesAccountModel->sale_id,
                        'sale_payment_account_id' => $salesAccountModel->id,
                        'payment_name'   => $payment_name,
                        'credit_amount'  => $differenceAmount,
                        'debit_amount'   => 0,
                        'change_balance' => floatval($salesAccountModel->cash_outstaning_balance + $differenceAmount),
                        'due_date'       => $lastCashBalModel->due_date,
                        'paid_source'    => "Auto",
                        'paid_date'      => date('Y-m-d'),
                        'paid_note'      => $payment_name,
                        'collected_by'   => 0,
                        'trans_type'     => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                        'status'         => SalePaymentAccounts::PAY_STATUS_PAID
                    ]);

                    //Create Entry Into Transaction
                    SalePaymentTransactions::create([
                        'sale_id'                      => $CashModel->sale_id,
                        'sale_payment_account_id'      => $CashModel->sale_payment_account_id,
                        'transaction_for'              => 1,
                        'transaction_name'             => $payment_name,
                        'transaction_amount'           => $CashModel->credit_amount,
                        'transaction_paid_source'      => "Auto",
                        'transaction_paid_source_note' => $payment_name,
                        'transaction_paid_date'        => date('Y-m-d'),
                        'trans_type'                   => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                        'status'                       => SalePaymentAccounts::PAY_STATUS_PAID,
                        'reference_id'                 => $CashModel->id
                    ]);
                }

                //CASE - 3 IF Amount Is Hight Than Last One
                if ($salesAccountModel->bank_finance_outstaning_balance < $postData['total_finance_amount']) {
                    $differenceAmount = ($postData['total_finance_amount'] - $salesAccountModel->bank_finance_outstaning_balance);
                    $salesAccountModel->update([
                        'financier_id' => $postData['financier_id'],
                        'financier_note' => $postData['financier_note']
                    ]);
                    $bankFinanceModel->update([
                        'due_date' => $postData['finance_due_date'],
                        'credit_amount'   => $postData['total_finance_amount'],
                        'debit_amount'    => 0,
                        'change_balance'  => $postData['total_finance_amount']
                    ]);

                    //Create Entry In Sales Cash Balance - Credit Amount
                    $payment_name = "Bank finanace amount increase & cash balance converted to finance.";
                    $lastCashBalModel = SalePaymentCash::where('sale_id', $salesAccountModel->sale_id)->orderBy('id', 'DESC')->first();
                    $CashModel = SalePaymentCash::create([
                        'sale_id' => $salesAccountModel->sale_id,
                        'sale_payment_account_id' => $salesAccountModel->id,
                        'payment_name'   => $payment_name,
                        'credit_amount'  => 0,
                        'debit_amount'   => $differenceAmount,
                        'change_balance' => floatval($salesAccountModel->cash_outstaning_balance - $differenceAmount),
                        'due_date'       => $lastCashBalModel->due_date,
                        'paid_source'    => "Auto",
                        'paid_date'      => date('Y-m-d'),
                        'paid_note'      => $payment_name,
                        'collected_by'   => 0,
                        'trans_type'     => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                        'status'         => SalePaymentAccounts::PAY_STATUS_PAID
                    ]);

                    //Create Entry Into Transaction
                    SalePaymentTransactions::create([
                        'sale_id'                      => $CashModel->sale_id,
                        'sale_payment_account_id'      => $CashModel->sale_payment_account_id,
                        'transaction_for'              => 1,
                        'transaction_name'             => $payment_name,
                        'transaction_amount'           => $CashModel->debit_amount,
                        'transaction_paid_source'      => "Auto",
                        'transaction_paid_source_note' => $payment_name,
                        'transaction_paid_date'        => date('Y-m-d'),
                        'trans_type'                   => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                        'status'                       => SalePaymentAccounts::PAY_STATUS_PAID,
                        'reference_id'                 => $CashModel->id
                    ]);
                }


                DB::commit();
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.updated_success')
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $postData = $request->all();
            if (!$request->ajax()) {
                return redirect()->route('saleAccounts.index');
            } else {
                //Request
                DB::beginTransaction();

                $salesAccountModel = SalePaymentAccounts::find($id);
                if (!$salesAccountModel) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => trans('messages.id_not_exist', ["ID" => $id])
                    ]);
                }

                //$id
                $checkIfAccountDebited = SalePaymentBankFinanace::where(["sale_payment_account_id" => $id, 'trans_type' => "2"])->count();
                if ($checkIfAccountDebited > 0) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => "Sorry! This account can not be cancelled."
                    ]);
                }

                //Create Entry In Sales Cash Balance - Credit Amount
                $payment_name = "Bank Finance Balance Conveterd To Cash Balance.";
                $paid_note = "Bank finanace file has cancelled so balance transfer into cash balance.";
                $lastCashBalModel = SalePaymentCash::where('sale_id', $salesAccountModel->sale_id)->orderBy('id', 'DESC')->first();
                $CashModel = SalePaymentCash::create([
                    'sale_id' => $salesAccountModel->sale_id,
                    'sale_payment_account_id' => $salesAccountModel->id,
                    'payment_name'   => $payment_name,
                    'credit_amount'  => $salesAccountModel->bank_finance_outstaning_balance,
                    'debit_amount'   => 0,
                    'change_balance' => floatval($salesAccountModel->cash_outstaning_balance + $salesAccountModel->bank_finance_outstaning_balance),
                    'due_date'       => $lastCashBalModel->due_date,
                    'paid_source'    => "Auto",
                    'paid_date'      => date('Y-m-d'),
                    'paid_note'      => $paid_note,
                    'collected_by'   => 0,
                    'trans_type'     => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                    'status'         => SalePaymentAccounts::PAY_STATUS_PAID
                ]);

                //Create Entry Into Transaction
                SalePaymentTransactions::create([
                    'sale_id'                      => $CashModel->sale_id,
                    'sale_payment_account_id'      => $CashModel->sale_payment_account_id,
                    'transaction_for'              => 1,
                    'transaction_name'             => $payment_name,
                    'transaction_amount'           => $CashModel->credit_amount,
                    'transaction_paid_source'      => "Auto",
                    'transaction_paid_source_note' => $paid_note,
                    'transaction_paid_date'        => date('Y-m-d'),
                    'trans_type'                   => SalePaymentAccounts::TRANS_TYPE_CREDIT,
                    'status'                       => SalePaymentAccounts::PAY_STATUS_PAID,
                    'reference_id'                 => $CashModel->id
                ]);

                //REMOVE DATA FROM BANK FINANCE
                SalePaymentBankFinanace::where(['sale_payment_account_id' => $salesAccountModel->id])->delete();

                //UPDATE DATA INTO
                $salesAccountModel->update([
                    'bank_finance_outstaning_balance' => 0,
                    'bank_finance_paid_balance' => 0,
                    'bank_finance_status' => 1,
                    'cash_status' => 0,
                    'status'     => 0,
                    'due_payment_source' => 1,
                    'financier_id' => null,
                    'financier_note' => null
                ]);

                DB::commit();
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.create_success')
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => $e->getMessage()
            ]);
        }
    }

    /**
     * Bank Finance Account Pay Model Open
     */
    public function payIndex(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $postData = $request->all();
            $salePaymentAccount = SalePaymentAccounts::find($id);
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
                'message'    => "Pay Modal Loaded",
                'data'       => view('admin.sales-accounts.bank-finanace.pay', $data)->render()
            ]);
        }
    }

    /**
     * Function for save bank finanace payment
     */
    public function payStore(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $postData = $request->only('sales_account_id', 'total_outstanding', 'paid_amount', 'paid_date', 'paid_source', 'status', 'collected_by', 'next_due_date', 'payment_note');
            $validator = Validator::make($postData, [
                'sales_account_id'      => "required|exists:sale_payment_accounts,id",
                'total_outstanding'     => "required|numeric|min:1",
                'paid_amount'           => "required|numeric|min:1",
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
            $payment_name = 'Due Paid By Financer';
            $createdCashPayment = SalePaymentBankFinanace::create([
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
     * Function for Bank Finanace Cancel - Modal Open
     */
    public function bankFinanaceCancel(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $postData = $request->all();
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
                'message'    => "Cancel Modal Loaded.",
                'data'       => view('admin.sales-accounts.bank-finanace.cancel', $data)->render()
            ]);
        }
    }
}