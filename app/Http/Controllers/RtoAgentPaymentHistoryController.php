<?php

namespace App\Http\Controllers;

use App\Models\RtoAgent;
use App\Models\RtoAgentPaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RtoAgentPaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.rto-agent-payments.index');
        } else {
            $data = RtoAgent::select('id', 'agent_name', 'active_status')
                ->withSum('payments', 'payment_amount')
                ->withSum('registrations', 'total_amount')
                ->withCount('registrations');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('registrations_count', function ($row) {
                    return $row->registrations_count;
                })
                ->addColumn('registrations_sum_total_amount', function ($row) {
                    return convertBadgesPrice($row->registrations_sum_total_amount, 'info');
                })
                ->addColumn('payments_sum_payment_amount', function ($row) {
                    return convertBadgesPrice($row->payments_sum_payment_amount, 'success');
                })
                ->addColumn('total_outstanding', function ($row) {
                    $total_outs = ($row->registrations_sum_total_amount - $row->payments_sum_payment_amount);
                    return convertBadgesPrice((($total_outs > 0) ? $total_outs : 0), 'danger');
                })
                ->addColumn('buffer_amount', function ($row) {
                    $total_outs = ($row->registrations_sum_total_amount - $row->payments_sum_payment_amount);
                    return convertBadgesPrice((($total_outs < 0) ? (-$total_outs) : 0), 'primary');
                })
                ->rawColumns([
                    'registrations_count', 'registrations_sum_total_amount', 'payments_sum_payment_amount', 'total_outstanding', 'buffer_amount', 'active_status', 'action'
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
        if (!isset($postData['agent_id'])) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['ID', $postData['agent_id']])
            ]);
        }

        $model = RtoAgent::find($postData['agent_id']);
        $data = array(
            'action' => route('rtoAgentPayments.store'),
            'method' => 'POST',
            'data'   => $model,
            'paymentSources' => depositeSources()
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.rto-agent-payments.paymentForm', $data)->render()
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
            $postData = $request->only('rto_agent_id', 'payment_amount', 'payment_date', 'payment_mode', 'payment_note');
            $validator = Validator::make($postData, [
                'rto_agent_id'    => 'required|exists:rto_agents,id',
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

            //Create New Role
            RtoAgentPaymentHistory::create($postData);
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
        $transactions = RtoAgentPaymentHistory::where('rto_agent_id', $id)->with(['agent'])->get();
        $data = array(
            'transactions'   => $transactions,
            'agent_name'     => RtoAgent::where('id', $id)->value('agent_name')
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.rto-agent-payments.transactions', $data)->render()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
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
        $action .= '<li><a href="' . route('rtoAgentPayments.show', ['rtoAgentPayment' => $data->id]) . '" class="ajaxModalPopup" data="VIEW PAYMENT HISTORY" data-modal_title="VIEW PAYMENT HISTORY" data-modal_size="modal-lg" data-title="View">VIEW PAYMENT HISTORY</a></li>';
        $action .= '<li><a href="' . route('rtoAgentPayments.create') . '?agent_id=' . $data->id . '" class="ajaxModalPopup" data-modal_title="CREATE NEW PAYMENT" data-title="CREATE NEW PAYMENT" data-modal_size="modal-lg">CREATE NEW PAYMENT</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }
}
