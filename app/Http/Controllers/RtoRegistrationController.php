<?php

namespace App\Http\Controllers;

use App\Models\BikeAgent;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
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
            $data = BikeAgent::select('*');
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
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['action', 'active_status'])
                ->make(true);
        } else {
            $formDetails = [
                'title' => 'RTO Management',
            ];
            return view('admin.rto-management.index', $formDetails);
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
            'action' => route('rto.store'),
            'method' => 'POST',
        ];
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
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.rto-management.ajaxModal', $data)->render()
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
        //
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


    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('rto.edit', ['rto' => $id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Agent" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('rto.destroy', ['rto' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('agents.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
