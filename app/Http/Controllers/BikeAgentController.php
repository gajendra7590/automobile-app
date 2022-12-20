<?php

namespace App\Http\Controllers;

use App\Models\BikeAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeAgentController extends Controller
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
                'title' => 'Bike Agent',
            ];
            return view('admin.agents.index', $formDetails);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.agents.ajaxModal', ['action' => route('agents.store'), 'method' => 'POST'])->render()
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
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'name' => "required|string",
            'email' => "required|email",
            'mobile_number' => 'required|string|min:10|max:13',
            'mobile_number2' => 'string|min:10|max:13',
            'aadhar_card' => 'string|min:12|max:12',
            'pan_card' => 'string|min:10|max:10',
            'date_of_birth' => 'date_format:Y-m-d',
            'highest_qualification' => 'string',
            'gender' => 'string|in:male,female',
            'address_line' => 'string',
            'state' => 'string',
            'district' => 'string',
            'city' => 'string',
            'more_details' => 'string',
            'active_status'      => 'required|in:0,1'
        ]);

        //If Validation failed
        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $validator->errors()->first(),
                'errors'     => $validator->errors(),
            ]);
        }

        BikeAgent::create($request->only(['name', 'email', 'mobile_number', 'mobile_number2', 'aadhar_card', 'pan_card', 'date_of_birth', 'highest_qualification', 'gender', 'address_line', 'state', 'district', 'city', 'more_details', 'active_status']));

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'Created Successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bikeAgent = BikeAgent::find($id);
        return view('admin.agents.show', ['bikeBrand' => $bikeAgent]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bikeAgent = BikeAgent::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.agents.ajaxModal', ['data' => $bikeAgent, 'action' => route('agents.update', ['agent' => $id]), 'method' => 'PUT'])->render()
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
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'name' => "nullable|string",
            'email' => "nullable|email",
            'mobile_number' => 'nullable|string|min:10|max:13',
            'mobile_number2' => 'nullable|string|min:10|max:13',
            'aadhar_card' => 'nullable|string|min:12|max:12',
            'pan_card' => 'nullable|string|min:10|max:10',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'highest_qualification' => 'nullable|string',
            'gender' => 'nullable|string|in:male,female',
            'address_line' => 'nullable|string',
            'state' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'more_details' => 'nullable|string',
            'active_status'      => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
        }
        $bikeAgent = BikeAgent::find($id);
        if (!$bikeAgent) {
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => 'Brand Not Found']);
        }
        $bikeAgent->update($request->all());
        return response()->json(['status' => true, 'statusCode' => 200, 'message' => 'Updated Successfully',], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bikeAgent = BikeAgent::find($id);
        if (!$bikeAgent) {
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => 'Brand Not Found']);
        }
        $bikeAgent->delete();
        return response()->json(['status' => true, 'statusCode' => 200, 'message' => 'Deleted Successfully',], 200);
    }

    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('agents.edit', ['agent' => $id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Agent" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '<a href="' . route('agents.destroy', ['agent' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('agents.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
