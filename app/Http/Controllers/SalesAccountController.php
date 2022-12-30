<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentInstallments;
use App\Models\SalePaymentTransactions;
use App\Models\State;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SalesAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.sales-accounts.index');
        } else {
            $data = State::with([
                'country' => function ($model) {
                    $model->select('id', 'country_name');
                }
            ])->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return '<span class="label label-success">Active</span>';
                    } else {
                        return '<span class="label label-warning">In Active</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['active_status', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salesList = Sale::select('id', 'customer_name', 'customer_relationship', 'customer_guardian_name', 'sku', 'total_amount')->where('status', 'open')->get();
        $data = array(
            'depositeSources' => depositeSources(),
            'duePaySources'   => duePaySources(),
            'emiTerms'        => emiTerms(),
            'salesList'       => $salesList,
            'action'          => route('sales-accounts.store')
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.sales-accounts.ajaxModal', $data)->render()
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
            $postData = $request->only('sale_id', 'sales_total_amount', 'deposite_amount', 'deposite_date', 'deposite_source', 'deposite_source_note', 'due_amount', 'due_date', 'due_payment_source', 'due_note', 'financier_id', 'financier_note', 'finance_terms', 'no_of_emis', 'rate_of_interest', 'processing_fees');
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
            $postData['deposite_collected_by'] = Auth::user()->id;
            $postData['status'] = 0;

            //Create Account
            $accountModel = SalePaymentAccounts::create($postData);
            if ($accountModel) {

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
                            'amount_paid'             => null,
                            'amount_paid_date'        => null,
                            'amount_paid_source'      => null,
                            'amount_paid_note'        => null,
                            'pay_due'                 => null,
                            'status'                  => 0
                        ]);
                    }
                    //Case Of Personal Finance
                    else if ((in_array($postData['due_payment_source'], [3]))) {

                        $P = floatval($postData['due_amount']) + floatval($postData['processing_fees']);
                        $T = ($postData['no_of_emis']);
                        switch ($postData['finance_terms']) {
                            case 1:
                                $T *= 1;
                                break;
                            case 2:
                                $T *= 3;
                                break;
                            case 3:
                                $T *= 6;
                                break;
                            case 4:
                                $T *= 12;
                                break;
                        }
                        $R = $postData['rate_of_interest'];
                        $total_interest = (($P * $R * ($T / 12)) / 100);

                        $grand_total = round($P + $total_interest);

                        $installment_amount    = round($grand_total / $postData['no_of_emis']);
                        $installment_intr     = round($total_interest / $postData['no_of_emis']);
                        $installment_prin     = ($installment_amount - $installment_intr);

                        //Convert Emis
                        for ($i = 1; $i <= $postData['no_of_emis']; $i++) {
                            $emi_due_date = date('Y-m-d', strtotime("+ $T months"));
                            $emi_due_date = date('Y-m', strtotime($emi_due_date)) . '-' . date('d');
                            SalePaymentInstallments::create([
                                'sale_id'                 => $postData['sale_id'],
                                'sale_payment_account_id' => $accountModel->id,
                                'emi_title'               => 'Customer Pay With Email Installment - ' . $i,
                                'loan_total_amount'       => $postData['sales_total_amount'],
                                'emi_due_amount'          => $installment_amount,
                                'emi_due_principal'       => $installment_prin,
                                'emi_due_intrest'         => $installment_intr,
                                'emi_due_date'            => $emi_due_date,
                                'amount_paid'             => null,
                                'amount_paid_date'        => null,
                                'amount_paid_source'      => null,
                                'amount_paid_note'        => null,
                                'pay_due'                 => null,
                                'status'                  => 0
                            ]);
                        }
                    }
                }
                //Case If Pay Full Down Payment
                else {
                    SalePaymentAccounts::where('id', $accountModel->id)->update(['status' => 1, 'status_closed_note' => 'Auto Closed On Full Payment', 'status_closed_by' => Auth::user()->id]);
                    Sale::where('id', $postData['sale_id'])->update(['status' => 'close']);
                }
            }
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Created Successfully."
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

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('sales-accounts.edit', ['sales_account' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update State"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('states.destroy', ['state' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete State"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
