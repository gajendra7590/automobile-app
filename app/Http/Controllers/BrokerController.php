<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Broker::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='Broker' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='Broker' class='active_status'><span class='slider round'></span></label>";
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
                'title' => 'Brokers List',
            ];
            return view('admin.brokers.index', $formDetails);
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
            'action' => route('brokers.store'),
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
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.brokers.ajaxModal', $data)->render()
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
            DB::beginTransaction();;
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'name' => "required|string",
                'email' => "required|email",
                'mobile_number' => 'required|string|min:10|max:13',
                'mobile_number2' => 'nullable|string|min:10|max:13',
                'aadhar_card' => 'nullable|string|min:12|max:12',
                'pan_card' => 'nullable|string|min:10|max:10',
                'date_of_birth' => 'nullable|date_format:Y-m-d',
                'highest_qualification' => 'nullable|string',
                'gender' => 'required|in:1,2,3',
                'address_line' => 'nullable|string',
                'state' => 'nullable|exists:u_states,id',
                'district' => 'nullable|exists:u_districts,id',
                'city' => 'nullable|exists:u_cities,id',
                'zipcode' => 'nullable|numeric|min:6',
                'more_details' => 'nullable|string',
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

            $createData = $request->only(['name', 'email', 'mobile_number', 'mobile_number2', 'aadhar_card', 'pan_card', 'date_of_birth', 'highest_qualification', 'gender', 'address_line', 'state', 'district', 'zipcode', 'city', 'more_details', 'active_status']);

            Broker::create($createData);
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
            ], 200);
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
        $brokers = Broker::find($id);
        return view('admin.brokers.show', ['brokers' => $brokers]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brokers = Broker::find($id);
        $data = ['data' => $brokers, 'action' => route('brokers.update', ['broker' => $id]), 'method' => 'PUT'];
        $data['states'] = State::select(['id', 'state_name'])->get();
        $data['districts'] = [];
        $data['cities'] = [];
        if ($brokers->state) {
            $data['districts'] = District::select(['id', 'district_name'])->where('state_id', $brokers->state)->get();
        }
        if ($brokers->district) {
            $data['cities'] = City::select(['id', 'city_name'])->where('district_id', $brokers->district)->get();
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.brokers.ajaxModal', $data)->render()
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
        try {
            DB::beginTransaction();
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'name' => "required|string",
                'email' => "required|email",
                'mobile_number' => 'required|string|min:10|max:13',
                'mobile_number2' => 'nullable|string|min:10|max:13',
                'aadhar_card' => 'nullable|string|min:12|max:12',
                'pan_card' => 'nullable|string|min:10|max:10',
                'date_of_birth' => 'nullable|date_format:Y-m-d',
                'highest_qualification' => 'nullable|string',
                'gender' => 'required|in:1,2,3',
                'address_line' => 'nullable|string',
                'state' => 'nullable|exists:u_states,id',
                'district' => 'nullable|exists:u_districts,id',
                'city' => 'nullable|exists:u_cities,id',
                'zipcode' => 'nullable|numeric|min:6',
                'more_details' => 'nullable|string',
                'active_status'      => 'required|in:0,1'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $broker = Broker::find($id);
            if (!$broker) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }

            $updateData = $request->only(['name', 'email', 'mobile_number', 'mobile_number2', 'aadhar_card', 'pan_card', 'date_of_birth', 'highest_qualification', 'gender', 'address_line', 'state', 'district', 'zipcode', 'city', 'more_details', 'active_status']);

            $broker->update($updateData);
            DB::commit();
            return response()->json(['status' => true, 'statusCode' => 200, 'message' => trans('messages.update_success'),], 200);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $broker = Broker::find($id);
            if (!$broker) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            $broker->delete();
            DB::commit();
            return response()->json(['status' => true, 'statusCode' => 200, 'message' => trans('messages.delete_success'),], 200);
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

    public function getActions($id)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('brokers.edit', ['broker' => $id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Broker Detail" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('agents.destroy', ['agent' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-redirect="' . route('agents.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
