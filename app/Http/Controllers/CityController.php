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
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['action'])
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
        $districts = District::select('id', 'district_name')->get();
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
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'district_id' => "required|exists:u_districts,id",
            'city_name' => "required|unique:u_cities,city_name",
            'city_code' => "nullable"
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
        City::create([
            'district_id' => $postData['district_id'],
            'city_name' => $postData['city_name'],
            'city_code' => $postData['city_code']
        ]);
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
        $postData = $request->all();
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
            'city_code' => "nullable"
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
        City::where(['id' => $id])->update([
            'district_id' => $postData['district_id'],
            'city_name' => $postData['city_name'],
            'city_code' => $postData['city_code']
        ]);
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
        return '<div class="action-btn-container">
                <a href="' . route('cities.edit', ['city' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update City"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                <a href="' . route('cities.destroy', ['city' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete City"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>';
        // return '<div class="action-btn-container">
        //         <a href="" class="btn btn-sm btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        //        </div>';
    }
}
