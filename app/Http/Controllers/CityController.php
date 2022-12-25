<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.cities.index');
        } else {

            $data = City::with([
                'district' => function ($model) {
                    $model->select('id', 'district_name', 'state_id')
                        ->with([
                            'state' => function ($cmodel) {
                                $cmodel->select('id', 'state_name');
                            }
                        ]);
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
        $districts = District::where(['active_status' => '1'])->select('id', 'district_name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.cities.ajaxModal', ['action' => route('cities.store'), 'districts' => $districts])->render()
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
        $postData = $request->only('district_id', 'cities');
        $validator = Validator::make($postData, [
            'district_id' => 'required',
            'cities.*.city_name' => "required",
            'cities.*.city_code' => 'nullable',
            'cities.*.active_status' => 'required|in:0,1'
        ], [
            'cities.*.city_name.required' => 'The City Name field is required.',
            'cities.*.active_status.required' => 'The City status field is required.'
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

        //Bulk insert
        if (count($postData['cities']) > 0) {
            foreach ($postData['cities'] as $k => $cityObj) {
                $cityObj['district_id'] = $postData['district_id'];
                City::create($cityObj);
            }
        }
        //Create New Role
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Created Successfully."
        ]);
    }

    public function createBulk()
    {
        $districts = District::where(['active_status' => '1'])->select('id', 'district_name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.cities.ajaxModal', ['action' => route('cities.store'), 'districts' => $districts, 'redirect' => 'closeModal', 'modalId' => 'ajaxModalCommon2'])->render()
        ]);
    }

    public function storeBulk(Request $request)
    {
        $postData = $request->only('district_id', 'cities');
        $validator = Validator::make($postData, [
            'district_id' => 'required',
            'cities.*.city_name' => "required",
            'cities.*.city_code' => 'nullable',
            'cities.*.active_status' => 'required|in:0,1'
        ], [
            'cities.*.city_name.required' => 'The City Name field is required.',
            'cities.*.active_status.required' => 'The City status field is required.'
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

        //Bulk insert
        if (count($postData['cities']) > 0) {
            foreach ($postData['cities'] as $k => $cityObj) {
                $cityObj['district_id'] = $postData['district_id'];
                City::create($cityObj);
            }
        }
        //Create New Role
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Created Successfully."
        ]);
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
        $districts = District::select('id', 'district_name')->get();
        $cityModel = City::find($id);
        if (!$cityModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.cities.ajaxModal', [
                'action' => route(
                    'cities.update',
                    ['city' => $id]
                ),
                'data' => $cityModel,
                'method' => 'PUT',
                'districts' => $districts

            ])->render()
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
        $postData = $request->only('district_id', 'city_name', 'city_code', 'active_status');
        $cityModel = City::find($id);
        if (!$cityModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }
        $validator = Validator::make($postData, [
            'district_id' => "required|exists:u_districts,id",
            'city_name' => "required|unique:u_cities,city_name," . $id . ",id",
            'city_code' => "nullable",
            'active_status'      => 'required|in:0,1'
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
        $cityModel->update($postData);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Updated Successfully."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cityModel = City::find($id);
        if (!$cityModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        //Delete
        $cityModel->delete();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Deleted Successfully."
        ]);
    }

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('cities.edit', ['city' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update City"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('cities.destroy', ['city' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete City"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
