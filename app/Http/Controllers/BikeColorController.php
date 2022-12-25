<?php

namespace App\Http\Controllers;

use App\Models\BikeColor;
use App\Models\BikeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.colors.index');
        } else {

            $data = BikeColor::with('model')->select('*');
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
                ->rawColumns(['model.model_name', 'active_status', 'action'])
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
        $models = BikeModel::where('active_status', '1')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.colors.ajaxModal', ['action' => route('colors.store'), 'models' => $models])->render()
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
        $postData = $request->only('color_name', 'color_code', 'active_status', 'bike_model', 'colors');
        $validator = Validator::make($postData, [
            'bike_model' => 'required',
            'colors.*.color_name' => "required",
            'colors.*.color_code' => 'nullable',
            'colors.*.active_status'      => 'required|in:0,1'
        ], [
            'colors.*.color_name.required' => 'The Color Name field is required.',
            'colors.*.active_status.required' => 'The Color status field is required.'
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
        if (count($postData['colors']) > 0) {
            foreach ($postData['colors'] as $k => $colorObj) {
                $colorObj['bike_model'] = $postData['bike_model'];
                BikeColor::create($colorObj);
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
        $colorModel = BikeColor::find($id);
        $models = BikeModel::where('active_status', '1')->get();
        if (!$colorModel) {
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
            'data'       => view('admin.colors.ajaxUpdateModal', [
                'action' => route(
                    'colors.update',
                    ['color' => $id]
                ),
                'models' => $models,
                'data' => $colorModel,
                'method' => 'PUT'
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
        $postData = $request->only('color_name', 'color_code', 'active_status', 'bike_model');
        $colorModel = BikeColor::find($id);
        if (!$colorModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }
        $validator = Validator::make($postData, [
            'bike_model'      => 'required',
            'color_name' => "required|unique:bike_colors,color_name," . $id,
            'color_code' => "nullable",
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
        BikeColor::where(['id' => $id])->update($postData);
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
        $colorModel = BikeColor::find($id);
        if (!$colorModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        //Delete
        $colorModel->delete();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Deleted Successfully."
        ]);
    }

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('colors.edit', ['color' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Model Color" data-modal_size="modal-md"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('colors.destroy', ['color' => $row->id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $row->id . '" data-redirect="' . route('colors.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}
