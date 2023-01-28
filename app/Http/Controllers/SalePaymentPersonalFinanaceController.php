<?php

namespace App\Http\Controllers;

use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentInstallments;
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

                $salesAccountModel = SalePaymentAccounts::find($postData['sales_account_id']);

                print_r($postData);


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


                dd($install_principal);
                $final_due_date = null;
                //Convert Emis
                for ($i = 1; $i <= $postData['no_of_emis']; $i++) {
                    // for ($i = $postData['no_of_emis']; $i >= 1; $i--) {
                    $next_month  = ($term_value * $i);
                    $emi_due_date = date('Y-m-d', strtotime("+ $next_month months"));
                    $emi_due_date = date('Y-m', strtotime($emi_due_date)) . '-' . date('d');
                    $final_due_date = $emi_due_date;
                    SalePaymentInstallments::create([
                        'sale_id'                 => $salesAccountModel->sale_id,
                        'sale_payment_account_id' => $salesAccountModel->id,
                        'payment_name'            => 'Installment - ' . $i,
                        'emi_total_amount'        => $postData['sales_total_amount'],
                        'emi_due_amount'          => $install_amount,
                        'emi_principal_amount'    => $install_principal,
                        'emi_intrest_amount'      => $install_intrest,
                        'emi_due_date'            => $emi_due_date,
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

                die('Remvoe It');
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
}