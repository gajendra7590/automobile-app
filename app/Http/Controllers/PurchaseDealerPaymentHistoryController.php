<?php

namespace App\Http\Controllers;

use App\Models\BikeDealer;
use App\Models\Purchase;
use App\Models\PurchaseDealerPaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

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
                ->withSum('dealer_payment', 'payment_amount');
            // ->withCount('registrations');


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
                    return convertBadgesPrice($balance, 'info');
                })
                ->addColumn('dealer_payment_sum_payment_amount', function ($row) {
                    $balance = (floatval($row->dealer_payment_sum_payment_amount) > 0) ? floatval($row->dealer_payment_sum_payment_amount) : 0.00;
                    return convertBadgesPrice($balance, 'success');
                })
                ->addColumn('total_outstanding', function ($row) {

                    $balance = (floatval($row->purchase_sum_grand_total) > 0) ? floatval($row->purchase_sum_grand_total) : 0.00;
                    $balance2 = (floatval($row->dealer_payment_sum_payment_amount) > 0) ? floatval($row->dealer_payment_sum_payment_amount) : 0.00;


                    $total_outs = floatval($balance - $balance2);
                    return convertBadgesPrice((($total_outs > 0) ? $total_outs : 0), 'danger');
                })
                ->addColumn('buffer_amount', function ($row) {
                    $balance = (floatval($row->purchase_sum_grand_total) > 0) ? floatval($row->purchase_sum_grand_total) : 0.00;
                    $balance2 = (floatval($row->dealer_payment_sum_payment_amount) > 0) ? floatval($row->dealer_payment_sum_payment_amount) : 0.00;
                    $total_outs = floatval($balance - $balance2);
                    return convertBadgesPrice((($total_outs < 0) ? (-$total_outs) : 0), 'primary');
                })
                ->rawColumns([
                    'branch_name',
                    'purchase_sum_grand_total',
                    'dealer_payment_sum_payment_amount',
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
        $total_paid = PurchaseDealerPaymentHistory::where('dealer_id', $postData['dealer_id'])->sum('payment_amount');
        $total_balance = Purchase::where('bike_dealer', $postData['dealer_id'])->sum('grand_total');
        $total_outstanding = (($total_balance - $total_paid) > 0) ? ($total_balance - $total_paid) : 0;
        $data = array(
            'action' => route('purchaseDealerPayments.store'),
            'method' => 'POST',
            'data'   => $model,
            'paymentSources' => depositeSources(),
            'total_balance'  => $total_balance,
            'total_paid'     => $total_paid,
            'total_outstanding'  => $total_outstanding,
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
            $postData = $request->only('dealer_id', 'payment_amount', 'payment_date', 'payment_mode', 'payment_note');
            $validator = Validator::make($postData, [
                'dealer_id'    => 'required|exists:bike_dealers,id',
                'payment_amount'  => "required|numeric|min:1",
                'payment_date'    => "required|date",
                'payment_mode'    => "required|string",
                'payment_note'    => "required|string"
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

            PurchaseDealerPaymentHistory::create($postData);
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
            ->with(['dealer'])->get();
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
        $action .= '<li><a href="' . route('purchaseDealerPayments.create') . '?dealer_id=' . $data->id . '" class="ajaxModalPopup" data-modal_title="CREATE NEW PAYMENT" data-title="CREATE NEW PAYMENT" data-modal_size="modal-lg">CREATE NEW PAYMENT</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }
}
