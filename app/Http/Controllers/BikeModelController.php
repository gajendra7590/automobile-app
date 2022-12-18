<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\BikeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class BikeModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.models.index');
        } else {

            $data = BikeModel::with([
                'bike_brand' => function ($model) {
                    $model->select('id', 'name');
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
        $brands = BikeBrand::select('id', 'name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.models.ajaxModal', ['action' => route('models.store'), 'brands' => $brands])->render()
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
            'brand_id' => "required|exists:bike_brands,id",
            'model_name' => "required",
            'model_code' => "nullable"
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
        BikeModel::create([
            'brand_id' => $postData['brand_id'],
            'model_name' => $postData['model_name'],
            'model_code' => $postData['model_code']
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
        $brands = BikeBrand::select('id', 'name')->get();
        $modelModel = BikeModel::find($id);
        if (!$modelModel) {
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
            'data'       => view('admin.models.ajaxModal', [
                'action' => route(
                    'models.update',
                    ['model' => $id]
                ),
                'data' => $modelModel,
                'method' => 'PUT',
                'brands' => $brands

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
        $modelModel = BikeModel::find($id);
        if (!$modelModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }
        $validator = Validator::make($postData, [
            'brand_id' => "required|exists:bike_brands,id",
            'model_name' => "required",
            'model_code' => "nullable"
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
        BikeModel::where(['id' => $id])->update([
            'brand_id' => $postData['brand_id'],
            'model_name' => $postData['model_name'],
            'model_code' => $postData['model_code']
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
        $modelModel = BikeModel::find($id);
        if (!$modelModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        //Delete
        $modelModel->delete();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Deleted Successfully."
        ]);
    }

    public function getActions($row)
    {
        return '<div class="action-btn-container">
                <a href="' . route('models.edit', ['model' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Model"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                <a href="' . route('models.destroy', ['model' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Model"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>';
        // return '<div class="action-btn-container">
        //         <a href="" class="btn btn-sm btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        //        </div>';
    }
}