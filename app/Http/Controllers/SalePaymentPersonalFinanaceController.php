<?php

namespace App\Http\Controllers;

use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentCash;
use App\Models\SalePaymentPersonalFinanace;
use App\Models\SalePaymentTransactions;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalePaymentPersonalFinanaceController extends Controller
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
                'financers' => self::_getFinaceirs(2),
                'emiTerms'  => emiTerms()
            );
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Ajax View Loaded",
                'data'       => view('admin.sales-accounts.personal-finanace.create', $data)->render()
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
                    'total_outstanding'     => "required|numeric|min:1",
                    'total_finance_amount'  => "required|numeric|min:1|lte:total_outstanding",
                    'grand_finance_amount'  => "required|numeric|min:1",
                    'processing_fees'       => "nullable|numeric|min:0",
                    'financier_id'          => 'required|exists:bank_financers,id',
                    'finance_due_date'      => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
                    'finance_terms'         => 'required|numeric|in:1,2,3,4',
                    'no_of_emis'            => 'required|numeric|integer',
                    'rate_of_interest'      => 'required|numeric'
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

                //CODE START FROM HERE
                $P = floatval($postData['grand_finance_amount']);
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

                $total_interest = round((($P * $R * ($T / 12)) / 100), 2);
                $grand_total = round(($P + $total_interest), 2);

                $install_amount    = round(($grand_total / $postData['no_of_emis']), 2);
                $install_intrest     = round(($total_interest / $postData['no_of_emis']), 2);
                $install_principal     = ($install_amount - $install_intrest);

                $final_due_date = null;
                //CREATE EMI HISTORY
                for ($i = 1; $i <= $postData['no_of_emis']; $i++) {
                    $next_month  = ($term_value * $i);
                    $emi_due_date = date('Y-m-d', strtotime("+ $next_month months"));
                    $emi_due_date = date('Y-m', strtotime($emi_due_date)) . '-' . date('d');
                    $final_due_date = $emi_due_date;
                    SalePaymentPersonalFinanace::create([
                        'sale_id'                 => $salePaymentAccount->sale_id,
                        'sale_payment_account_id' => $salePaymentAccount->id,
                        'payment_name'            => 'Installment - ' . $i,
                        'emi_total_amount'        => $install_amount,
                        'emi_principal_amount'    => $install_principal,
                        'emi_intrest_amount'      => $install_intrest,
                        'emi_due_date'            => $final_due_date,
                        'adjust_amount'           => null,
                        'adjust_date'             => null,
                        'adjust_note'             => null,
                        'emi_due_revised_amount'  => $install_amount,
                        'emi_due_revised_note'    => null,
                        'amount_paid'             => null,
                        'amount_paid_date'        => null,
                        'amount_paid_source'      => null,
                        'amount_paid_note'        => null,
                        'collected_by'            => null,
                        'status'                  => 0
                    ]);
                }

                //DEBIT SALES CASH PAYMENT
                $currentCashOutStandingBalance = $salePaymentAccount->cash_outstaning_balance;
                $lastCash = SalePaymentCash::where('sale_payment_account_id', $salePaymentAccount->id)->orderBy('id', 'DESC')->first();
                $payment_name = "Cash Balance Conveterd To Personal Finance.";
                $createdCashPayment = SalePaymentCash::create([
                    'sale_id' => $salePaymentAccount->sale_id,
                    'sale_payment_account_id' => $salePaymentAccount->id,
                    'payment_name' => $payment_name,
                    'credit_amount' => 0,
                    'debit_amount' => $postData['total_finance_amount'],
                    'change_balance' => floatval($currentCashOutStandingBalance - $postData['total_finance_amount']),
                    'due_date'    => $lastCash->due_date,
                    'paid_source' => 'Auto',
                    'paid_date' => date('Y-m-d'),
                    'paid_note' => $payment_name,
                    'collected_by' => 0,
                    'trans_type' => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                    'status' => 1
                ]);
                //CREATE NEW TRANSACTION
                SalePaymentTransactions::create([
                    'sale_id' => $salePaymentAccount->sale_id,
                    'sale_payment_account_id' => $salePaymentAccount->id,
                    'transaction_for' => 1,
                    'transaction_name' => $payment_name,
                    'transaction_amount' => $postData['total_finance_amount'],
                    'transaction_paid_source' => 'Auto',
                    'transaction_paid_source_note' => $payment_name,
                    'transaction_paid_date' => date('Y-m-d'),
                    'trans_type' => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                    'status' => 1,
                    'reference_id' => $createdCashPayment->id
                ]);

                //UPDATE ACCOUNT DETAIL
                $salePaymentAccount->update([
                    'personal_finance_outstaning_balance' => $postData['grand_finance_amount'],
                    'personal_finance_paid_balance' => 0,
                    'personal_finance_status' => 0,
                    'personal_finance_amount'  => $postData['total_finance_amount'],
                    'due_payment_source' => 3,
                    'financier_id' => $postData['financier_id'],
                    'finance_terms' => $postData['finance_terms'],
                    'no_of_emis' => $postData['no_of_emis'],
                    'rate_of_interest' => $postData['rate_of_interest'],
                    'processing_fees' => $postData['processing_fees']
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

    /**
     * Function for load pay modal
     */
    public function payIndex(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            $installModel = SalePaymentPersonalFinanace::where('id', $id)->with([
                'account' => function ($account) {
                    $account->select('id', 'account_uuid', 'sales_total_amount', 'financier_id', 'due_payment_source', 'status');
                },
                'sale' => function ($account) {
                    $account->select('id', 'customer_name', 'status');
                }
            ])->first();

            $data['data'] = $installModel;
            $data['totalDueCounts'] = SalePaymentPersonalFinanace::where(['sale_payment_account_id' => $installModel->sale_payment_account_id, 'status' => '0'])->count();
            $data['depositeSources'] = depositeSources();
            $data['salemans'] = self::_getSalesman();

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.ajax_model_loaded'),
                'data'       => view('admin.sales-accounts.personal-finanace.pay', $data)->render()
            ]);
        }
    }

    /**
     * Function for pay saved
     */
    public function payStore(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('saleAccounts.index');
        } else {
            DB::beginTransaction();
            try {
                $postData = $request->all();
                $validator = Validator::make($postData, [
                    'id'              => 'required|exists:sale_payment_personal_finanace,id',
                    'emi_due_amount'  => 'required|numeric|min:1',
                    'pay_method'      => 'required',
                    'pay_method_note' => 'required|string',
                    'pay_option'      => 'required|in:full,partial',
                    'pay_amount'      => 'required|numeric|min:1',
                    'next_due_Date'   => 'nullable|date|after:today',
                    'collected_by_salesman_id' => 'nullable|exists:salesmans,id',
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
                $instModel = SalePaymentPersonalFinanace::find($id);
                //Get OverAll Installment Total
                $total_overall_due = SalePaymentPersonalFinanace::where([
                    'status' => '0',
                    'sale_payment_account_id'  => $instModel->sale_payment_account_id
                ])->limit(2)->sum('emi_due_revised_amount');

                $due_amount = floatval($instModel->emi_due_revised_amount);
                $pay_amount = floatval($postData['pay_amount']);
                $total_overall_due = floatval($total_overall_due);

                //Due Amount & Pay Amount Is Same | CASE - 1
                if ($due_amount == $pay_amount) {
                    //Mark As Paid
                    $instModel->update([
                        'amount_paid'               => floatval($instModel->amount_paid + $pay_amount),
                        'amount_paid_date'          => date('Y-m-d'),
                        'amount_paid_source'        => $postData['pay_method'],
                        'amount_paid_note'          => $postData['pay_method_note'],
                        'pay_due'                   => 0.00,
                        'status'                    => SalePaymentAccounts::STATUS_PAID,
                        'collected_by_salesman_id'  => $postData['collected_by_salesman_id'],
                    ]);
                    //Add Transaction
                    SalePaymentTransactions::create([
                        'sale_id'                       => $instModel->sale_id,
                        'sale_payment_account_id'       => $instModel->sale_payment_account_id,
                        'transaction_for'               => SalePaymentAccounts::TRANSACTION_TYPE_PF,
                        'transaction_name'              => ucwords(strtolower($instModel->payment_name . " Paid By Customer.")),
                        'transaction_amount'            => $pay_amount,
                        'transaction_paid_source'       => $postData['pay_method'],
                        'transaction_paid_source_note'  => $postData['pay_method_note'],
                        'transaction_paid_date'         => date('Y-m-d'),
                        'trans_type'                    => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                        'status'                        => SalePaymentAccounts::STATUS_PAID,
                        'reference_id'                   => $instModel->id
                    ]);
                }
                //If Customer Pay Less That Due | CASE - 2
                else if ($due_amount > $pay_amount) {
                    //PAY FOR CURRENT EMI
                    $p1_due = floatval(($due_amount - $pay_amount));
                    $instModel->update([
                        'amount_paid'                => floatval($instModel->amount_paid + $pay_amount),
                        'amount_paid_date'           => date('Y-m-d'),
                        'amount_paid_source'         => $postData['pay_method'],
                        'amount_paid_note'           => $postData['pay_method_note'],
                        'status'                     => SalePaymentAccounts::STATUS_PAID,
                        'collected_by_salesman_id'   => $postData['collected_by_salesman_id'],
                    ]);
                    SalePaymentTransactions::create([
                        'sale_id'                       => $instModel->sale_id,
                        'sale_payment_account_id'       => $instModel->sale_payment_account_id,
                        'transaction_for'               => SalePaymentAccounts::TRANSACTION_TYPE_PF,
                        'transaction_name'              => ucwords(strtolower($instModel->payment_name . " Paid By Customer.")),
                        'transaction_amount'            => $pay_amount,
                        'transaction_paid_source'       => $postData['pay_method'],
                        'transaction_paid_source_note'  => $postData['pay_method_note'],
                        'transaction_paid_date'         => date('Y-m-d'),
                        'trans_type'                    => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                        'status'                        => SalePaymentAccounts::STATUS_PAID,
                        'reference_id'                  => $instModel->id
                    ]);

                    //ADJUST NEXT/NEW EMI
                    $whereNotIn = array($instModel->id);
                    $nextEMIModel = SalePaymentPersonalFinanace::where([
                        'status' => '0', 'sale_payment_account_id'  => $instModel->sale_payment_account_id
                    ])->whereNotIn('id', $whereNotIn)->first();
                    //Create New EMI
                    if (empty($nextEMIModel)) {

                        $emi_due_amount    = $p1_due;
                        $emi_due_principal = 0.00;
                        $emi_due_intrest   = 0.00;
                        $emi_due_date      = null;
                        $accountModel = SalePaymentAccounts::find($instModel->sale_payment_account_id);

                        $emi_due_principal = $p1_due;
                        $months = emiTermsMonths($accountModel->finance_terms);
                        $roi   = $accountModel->rate_of_interest;
                        $emi_due_intrest = ($emi_due_principal * $roi * ($months / 12)) / 100;
                        $emi_due_amount = ($emi_due_principal + $emi_due_intrest);
                        $emi_due_date = date('Y-m-d', strtotime("+ $months months"));
                        SalePaymentPersonalFinanace::create([
                            'sale_id'                   => $instModel->sale_id,
                            'sale_payment_account_id'   => $instModel->sale_payment_account_id,
                            'payment_name'              => "#" . $id . " Installment Outstanding Balance EMI Created.",
                            'emi_total_amount'          => $emi_due_amount,
                            'emi_principal_amount'      => $emi_due_principal,
                            'emi_intrest_amount'        => $emi_due_intrest,
                            'emi_due_date'              => $emi_due_date,
                            'adjust_amount'             => - ($pay_amount),
                            'adjust_date'               => date('Y-m-d'),
                            'adjust_note'               => "Adjust last EMI pending balance.",
                            'emi_due_revised_amount'    => $emi_due_amount,
                            'emi_due_revised_note'      => "Revised Due After (-" . $pay_amount . ") added.",
                            'status'                    => SalePaymentAccounts::PAY_STATUS_PENDING
                        ]);
                    }
                    //Adjust in existing next EMI Avaliable
                    else {
                        $nextEMIModel->update([
                            'adjust_amount'             => - ($p1_due),
                            'adjust_date'               => date('Y-m-d'),
                            'adjust_note'               => "#" . $id . "Last Emi Due Adjusted.",
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
                    $nextEMIModel = SalePaymentPersonalFinanace::where([
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
                            'amount_paid'        => floatval($instModel->amount_paid + $pay_amount),
                            'amount_paid_date'   => date('Y-m-d'),
                            'amount_paid_source' => $postData['pay_method'],
                            'amount_paid_note'   => $postData['pay_method_note'],
                            'status'             => SalePaymentAccounts::PAY_STATUS_PAID,
                            'collected_by'       => $postData['collected_by_salesman_id'],
                        ]);
                        //MARK PAID CURRENT EMI - TRANSACTION CREATE
                        SalePaymentTransactions::create([
                            'sale_id'                       => $instModel->sale_id,
                            'sale_payment_account_id'       => $instModel->sale_payment_account_id,
                            'transaction_for'               => SalePaymentAccounts::TRANSACTION_TYPE_PF,
                            'transaction_name'              => ucwords(strtolower($instModel->payment_name . " Paid By Customer.")),
                            'transaction_amount'            => $pay_amount,
                            'transaction_paid_source'       => $postData['pay_method'],
                            'transaction_paid_source_note'  => $postData['pay_method_note'],
                            'transaction_paid_date'         => date('Y-m-d'),
                            'trans_type'                    => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                            'status'                        => SalePaymentAccounts::STATUS_PAID,
                            'reference_id'                  => $instModel->id
                        ]);

                        //MARK PAID NEXT EMI
                        $p1_status = ($nextEMITotal == $advance_pay) ? SalePaymentAccounts::STATUS_PAID : SalePaymentAccounts::STATUS_DUE;
                        $nextEMIModel->update([
                            'amount_paid'                => floatval($nextEMIModel->amount_paid + $advance_pay),
                            'amount_paid_date'           => date('Y-m-d'),
                            'amount_paid_source'         => $postData['pay_method'],
                            'amount_paid_note'           => "Advance Payment Ref #Inst-" . $instModel->id,
                            'adjust_amount'              => ($p1_status == 1) ? 0 : ($advance_pay),
                            'adjust_date'                => date('Y-m-d'),
                            'adjust_note'                => "Advance Payment.#EMI-" . $instModel->id,
                            'emi_due_revised_amount'     => ($p1_status == 1) ? 0 : ($nextEMITotal - $advance_pay),
                            'emi_due_revised_note'       => ($p1_status == 1) ? "" : "After Advance Payment Adjusment Remianing Balance.",
                            'pay_due'                    => $advance_pay,
                            'status'                     => $p1_status,
                            'collected_by'               => $postData['collected_by_salesman_id'],
                        ]);
                        //MARK PAID NEXT EMI - TRANSACTION CREATE
                        SalePaymentTransactions::create([
                            'sale_id'                       => $nextEMIModel->sale_id,
                            'sale_payment_account_id'       => $nextEMIModel->sale_payment_account_id,
                            'transaction_for'               => SalePaymentAccounts::TRANSACTION_TYPE_PF,
                            'transaction_name'              => ucwords(strtolower($nextEMIModel->payment_name . " Paid By Customer.")),
                            'transaction_amount'            => $advance_pay,
                            'transaction_paid_source'       => $postData['pay_method'],
                            'transaction_paid_source_note'  => $postData['pay_method_note'],
                            'transaction_paid_date'         => date('Y-m-d'),
                            'trans_type'                    => SalePaymentAccounts::TRANS_TYPE_DEBIT,
                            'status'                        => SalePaymentAccounts::STATUS_PAID,
                            'reference_id'                  => $instModel->id
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
    }
}
