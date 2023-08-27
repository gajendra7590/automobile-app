<?php

namespace App\Http\Controllers;

use App\Models\BankAccounts;
use App\Models\BikeDealer;
use App\Models\Purchase;
use App\Models\PurchaseDealerPaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PurchaseDealerPaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.purchaseDealerPayments.index');
        } else {
            $data = BikeDealer::select('id', 'branch_id', 'company_name', 'company_email', 'company_gst_no', 'created_at')
                ->branchWise()
                ->with([
                    'branch' => function ($branch) {
                        $branch->select('id', 'branch_name');
                    }
                ])
                ->withSum('purchase', 'grand_total')
                ->withSum('dealer_payment', 'credit_amount')
                ->withSum('dealer_payment', 'debit_amount');

            // dd($data->get()->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('branch_name', function ($row) {
                    return isset($row->branch->branch_name) ? $row->branch->branch_name : '--';
                })
                ->addColumn('purchase_sum_grand_total', function ($row) {
                    $balance = (floatval($row->purchase_sum_grand_total) > 0) ? floatval($row->purchase_sum_grand_total) : 0.00;
                    $credit_total =  (floatval($row->dealer_payment_sum_credit_amount) > 0) ? floatval($row->dealer_payment_sum_credit_amount) : 0.00;
                    $balance += $credit_total;
                    return convertBadgesPrice($balance, 'info');
                })
                ->addColumn('dealer_payment_sum_debit_amount', function ($row) {
                    $balance = (floatval($row->dealer_payment_sum_debit_amount) > 0) ? floatval($row->dealer_payment_sum_debit_amount) : 0.00;
                    return convertBadgesPrice($balance, 'success');
                })
                ->addColumn('total_outstanding', function ($row) {
                    $balance = (floatval($row->purchase_sum_grand_total) > 0) ? floatval($row->purchase_sum_grand_total) : 0.00;
                    $credit_total =  (floatval($row->dealer_payment_sum_credit_amount) > 0) ? floatval($row->dealer_payment_sum_credit_amount) : 0.00;
                    $balance += $credit_total;
                    $balance2 = (floatval($row->dealer_payment_sum_debit_amount) > 0) ? floatval($row->dealer_payment_sum_debit_amount) : 0.00;
                    $total_outs = floatval($balance - $balance2);
                    return convertBadgesPrice((($total_outs > 0) ? $total_outs : 0), 'danger');
                })
                ->addColumn('buffer_amount', function ($row) {
                    $balance = (floatval($row->purchase_sum_grand_total) > 0) ? floatval($row->purchase_sum_grand_total) : 0.00;
                    $credit_total =  (floatval($row->dealer_payment_sum_credit_amount) > 0) ? floatval($row->dealer_payment_sum_credit_amount) : 0.00;
                    $balance += $credit_total;
                    $balance2 = (floatval($row->dealer_payment_sum_debit_amount) > 0) ? floatval($row->dealer_payment_sum_debit_amount) : 0.00;
                    $total_outs = floatval($balance - $balance2);
                    return convertBadgesPrice((($total_outs < 0) ? (-$total_outs) : 0), 'primary');
                })
                ->rawColumns([
                    'branch_name',
                    'purchase_sum_grand_total',
                    'dealer_payment_sum_debit_amount',
                    'total_outstanding',
                    'buffer_amount',
                    'action'
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
        $postData = $request->all();
        if (!isset($postData['dealer_id'])) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['ID', $postData['dealer_id']])
            ]);
        }

        $model = BikeDealer::find($postData['dealer_id']);
        $credit_amount = floatval(PurchaseDealerPaymentHistory::where('dealer_id', $postData['dealer_id'])->sum('credit_amount'));

        $debit_amount = floatval(PurchaseDealerPaymentHistory::where('dealer_id', $postData['dealer_id'])->sum('debit_amount'));

        $total_balance = floatval(Purchase::where('bike_dealer', $postData['dealer_id'])->sum('grand_total'));

        $total_balance += $credit_amount;

        $total_outstanding = (($total_balance - $debit_amount) > 0) ? ($total_balance - $debit_amount) : 0;

        $bank_accounts = BankAccounts::get();

        $data = array(
            'action' => route('purchaseDealerPayments.store'),
            'method' => 'POST',
            'data'   => $model,
            'paymentSources' => depositeSources(),
            'total_balance'  => $total_balance,
            'total_paid'     => $debit_amount,
            'total_outstanding'  => $total_outstanding,
            'bank_accounts'    => $bank_accounts
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.purchaseDealerPayments.paymentForm', $data)->render()
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
            $postData = $request->all();
            $validator = Validator::make($request->all(), [
                'dealer_id'        => 'required|exists:bike_dealers,id',
                'transaction_type' => 'required|in:1,2',
                'bank_account_id'  => 'required|exists:bank_accounts,id',
                'payment_amount'   => "required|numeric|min:1",
                'payment_date'     => "required|date",
                'payment_mode'     => "required",
                'payment_note'     => "required"
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
            PurchaseDealerPaymentHistory::create([
                'dealer_id'         =>  isset($postData['dealer_id']) ? $postData['dealer_id'] : null,
                'uuid'              => strtolower('pay_' . (Str::random(25))),
                'transaction_type'  =>  isset($postData['transaction_type']) ? $postData['transaction_type'] : null,
                'bank_account_id'   =>  isset($postData['bank_account_id']) ? $postData['bank_account_id'] : null,
                'credit_amount'     => (isset($postData['transaction_type']) && ($postData['transaction_type']) == '2') ? floatval($postData['payment_amount']) : 0.00,
                'debit_amount'      => (isset($postData['transaction_type']) && ($postData['transaction_type']) == '1') ? floatval($postData['payment_amount']) : 0.00,
                'payment_mode'      =>  isset($postData['payment_mode']) ? $postData['payment_mode'] : null,
                'payment_date'      =>  isset($postData['payment_date']) ? date('Y-m-d', strtotime($postData['payment_date'])) : null,
                'payment_note'      =>  isset($postData['payment_note']) ? $postData['payment_note'] : null
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transactions = PurchaseDealerPaymentHistory::where('dealer_id', $id)
            ->with([
                'dealer' => function ($dealer) {
                    $dealer->select('id', 'branch_id', 'company_name', 'company_email');
                },
                'bankAccount' => function ($dealer) {
                    $dealer->select('id', 'bank_account_holder_name', 'bank_account_number');
                }
            ])->get();
        $data = array(
            'transactions'   => $transactions,
            'dealer_name'     => BikeDealer::where('id', $id)->value('company_name')
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.purchaseDealerPayments.transactions', $data)->render()
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

    public function getActions($data)
    {
        $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
        $action  .= '<ul class="dropdown-menu">';
        $action .= '<li><a href="' . route('purchaseDealerPayments.show', ['purchaseDealerPayment' => $data->id]) . '" class="ajaxModalPopup" data="VIEW TRANSACTIONS" data-modal_title="VIEW TRANSACTIONS" data-modal_size="modal-lg" data-title="View">VIEW TRANSACTIONS</a></li>';
        $action .= '<li><a href="' . route('purchaseDealerPaymentLedger', ['id' => $data->id]) . '">PAYMENT LEDGER DOWNLOAD</a></li>';
        $action .= '<li><a href="' . route('purchaseDealerPayments.create') . '?dealer_id=' . $data->id . '" class="ajaxModalPopup" data-modal_title="CREATE NEW PAYMENT" data-title="CREATE NEW PAYMENT" data-modal_size="modal-lg">CREATE NEW PAYMENT</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    public function downloadLedger($id)
    {
        $final_array[] = ['Date', 'Txn Type', 'Particulars', 'Vch Type', 'Vch No', 'Debit', 'Credit'];
        //ALL PRUCHASES
        $purchases = Purchase::select('id', 'uuid', 'vin_number', 'sku_description', 'grand_total', 'dc_date')
            ->selectRaw('dc_date as transaction_date')
            ->selectRaw('1 as is_purchase')
            ->where(['bike_dealer' => $id])
            ->orderBy('dc_date', 'ASC')
            // ->whereDate('dc_date', '2023-08-16')
            ->get();
        $allPurchases = [];
        if ($purchases) {
            $allPurchases = $purchases->toArray();
        }

        //ALL DEALER TRANSACTIONS
        $dealer_payment = PurchaseDealerPaymentHistory::select('id', 'uuid', 'transaction_type', 'credit_amount', 'debit_amount', 'payment_mode', 'payment_date')
            ->selectRaw('payment_date as transaction_date')
            ->selectRaw('0 as is_purchase')
            ->where('dealer_id', $id)
            ->orderBy('payment_date', 'ASC')
            ->get();
        $allTransactions = [];
        if ($dealer_payment) {
            $allTransactions = $dealer_payment->toArray();
            foreach ($allTransactions as $allTransaction) {
                $allPurchases[] = $allTransaction;
            }
        }

        //SORTING
        $collection = collect($allPurchases);
        $final_sorted_result = $collection->sortBy([
            ['transaction_date', 'asc'],
            ['is_purchase', 'desc']
        ]);
        if ($final_sorted_result) {
            $total_debit_amount = 0.00;
            $total_credit_amount = 0.00;
            foreach ($final_sorted_result as $key => $trans) {

                $txn_type      = ($trans['is_purchase'] == '1') ? 'Cr' : (($trans['transaction_type'] == '2') ? 'Cr' : 'Dr');

                $debit_amount  = 0.00;
                $credit_amount  = 0.00;
                if ($trans['is_purchase'] == '1') {
                    $debit_amount  = 0.00;
                    $credit_amount  = floatval($trans['grand_total']);
                } else {
                    if ($trans['transaction_type'] == '1') {
                        $credit_amount  = 0.00;
                        $debit_amount = floatval($trans['debit_amount']);
                    } else if ($trans['transaction_type'] == '1') {
                        $debit_amount = 0.00;
                        $credit_amount  = floatval($trans['credit_amount']);
                    }
                }

                $particulars = "";
                if ($trans['is_purchase'] == '0') {
                    $particulars = (($trans['transaction_type'] == '1') ? "TXN - BANK TRANSFER SEND TO DEALER" : "TXN - ADD CREDIT BALANCE");
                } else {
                    $particulars = 'RECEIVED STOCK - ' . $trans['vin_number'];
                }

                $vcr_type = 'RECEIVED STOCK';
                if ($trans['is_purchase'] == '0') {
                    $vcr_type = "TRANSACTION";
                }

                $final_array[] = [
                    $trans['transaction_date'],
                    $txn_type,
                    $particulars,
                    $vcr_type,
                    $trans['uuid'],
                    number_format($debit_amount, 2),
                    number_format($credit_amount, 2)
                ];

                $total_debit_amount += $debit_amount;
                $total_credit_amount += $credit_amount;
            }

            $final_array[] = [
                'GRAND TOTAL',
                '',
                '',
                '',
                '',
                number_format($total_debit_amount, 2),
                number_format($total_credit_amount, 2)
            ];

            $filename = date('YmdHis') . '_' . rand(111111, 999999) . '_'  . "_report.csv";
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            $f = fopen('php://output', 'w');
            foreach ($final_array as $line) {
                fputcsv($f, $line);
            }
            fclose($f);
            exit();
        } else {
            return redirect()->route('purchaseDealerPayments.index');
        }
    }
}
