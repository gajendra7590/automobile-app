<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\BikeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ])
                ->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='model' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='model' class='active_status'><span class='slider round'></span></label>";
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
        $brands = BikeBrand::select('id', 'name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
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
        try {
            DB::beginTransaction();
            $postData = $request->only('models', 'brand_id');
            $validator = Validator::make($postData, [
                'brand_id'              => 'required',
                'models.*.model_name'     => "required",
                'models.*.model_code'     => 'nullable',
                'models.*.active_status'  => 'required|in:0,1'
            ], [
                'models.*.model_name.required' => 'The Color Name field is required.',
                'models.*.active_status.required' => 'The Color status field is required.',
                'brand_id' => 'The brand is not selected.'
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
            if (count($postData['models']) > 0) {
                foreach ($postData['models'] as $k => $modelObj) {
                    $modelObj['brand_id'] = $postData['brand_id'];
                    BikeModel::create($modelObj);
                }
            }
            DB::commit();
            //Create New Role
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
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.models.ajaxEditModal', [
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
        try {
            DB::beginTransaction();
            $postData = $request->all();
            $modelModel = BikeModel::find($id);
            if (!$modelModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }
            $validator = Validator::make($postData, [
                'brand_id'      => "required|exists:bike_brands,id",
                'model_name'    => "required",
                'model_code'    => "nullable",
                'active_status' => 'required|in:0,1'
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
                'brand_id'      => $postData['brand_id'],
                'model_name'    => $postData['model_name'],
                'model_code'    => $postData['model_code'],
                'active_status' => $postData['active_status']
            ]);
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.update_success')
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $modelModel = BikeModel::find($id);
            if (!$modelModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            //Delete
            $modelModel->delete();
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Deleted Successfully."
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

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('models.edit', ['model' => $row->id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="Update Model"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('models.destroy', ['model' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Model"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
