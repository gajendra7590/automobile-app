<?php

namespace App\Http\Controllers;

use App\Models\BikeAgent;
use App\Models\City;
use App\Models\District;
use App\Models\GstRtoRates;
use App\Models\RtoAgent;
use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RtoRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RtoRegistration::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return '<span class="label label-success">Active</span>';
                    } else {
                        return '<span class="label label-warning">In Active</span>';
                    }
                })
                ->addColumn('contact_city', function ($row) {
                    return $row->contact_city ? $row->contact_city->city_name : '---';
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['contact_city', 'action', 'active_status'])
                ->make(true);
        } else {
            $formDetails = [
                'title' => 'RTO Registration',
            ];
            return view('admin.rto-registration.index', $formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data  = [
            'action' => route('rtoRegistration.store'),
            'method' => 'POST',
        ];
        $data['sales'] = Sale::select(['id', 'customer_name'])->get();
        $data['rto_agents'] = RtoAgent::select(['id', 'agent_name'])->get();
        $data['gst_rto_rates'] = GstRtoRates::select(['id', 'gst_rate'])->get();
        $data['states'] = State::select(['id', 'state_name'])->get();
        $data['states'] = State::select(['id', 'state_name'])->get();
        $data['districts'] = [];
        $data['cities'] = [];
        if (count($data['states'])) {
            $data['districts'] = District::select(['id', 'district_name'])->where('state_id', $data['states'][0]['id'])->get();
        }
        if (count($data['districts'])) {
            $data['cities'] = City::select(['id', 'city_name'])->where('district_id', $data['districts'][0]['id'])->get();
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.rto-registration.ajaxModal', $data)->render()
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
            $rtoRegistration = RtoRegistration::create($request->all());
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
                'data'       => $rtoRegistration
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
        $data = RtoRegistration::find($id);
        $data  = [
            'action' => route('rtoRegistration.update', ['rtoRegistration' => $id]),
            'method' => 'POST',
        ];
        $data['sales'] = Sale::select(['id', 'customer_name'])->get();
        $data['rto_agents'] = RtoAgent::select(['id', 'agent_name'])->get();
        $data['gst_rto_rates'] = GstRtoRates::select(['id', 'gst_rate'])->get();
        $data['states'] = State::select(['id', 'state_name'])->get();
        $data['states'] = State::select(['id', 'state_name'])->get();
        $data['districts'] = [];
        $data['cities'] = [];
        if (count($data['states'])) {
            $data['districts'] = District::select(['id', 'district_name'])->where('state_id', $data['states'][0]['id'])->get();
        }
        if (count($data['districts'])) {
            $data['cities'] = City::select(['id', 'city_name'])->where('district_id', $data['districts'][0]['id'])->get();
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.rto-registration.ajaxModal', $data)->render()
        ]);
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


    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('rtoRegistration.edit', ['rtoRegistration' => $id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Agent" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('rtoRegistration.destroy', ['rto' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('agents.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
