<?php

namespace App\Http\Controllers;

use App\Models\BikeColor;
use App\Models\BikeModel;
use App\Models\BikeModelVariant;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeColorController extends Controller
{
    use CommonHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!request()->ajax()) {
            $models = self::_getModels();
            return view('admin.colors.index', ['models' => $models]);
        } else {


            $postData = $request->all();
            $data = BikeColor::with('model')->with(['variant', 'model'])->select('*');

            //Fitler By Model
            if (isset($postData['columns'][4]['search']['value']) && (!empty($postData['columns'][4]['search']['value']))) {
                $data->where('bike_model', $postData['columns'][4]['search']['value']);
            }

            //Fitler By Variant
            if (isset($postData['columns'][3]['search']['value']) && (!empty($postData['columns'][3]['search']['value']))) {
                $data->where('model_variant_id', $postData['columns'][3]['search']['value']);
            }

            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where(function ($qq) use ($search_string) {
                            $qq->where('id', $search_string)
                                ->orwhere('color_name', 'LIKE', '%' . $search_string . '%')
                                ->orwhere('color_code', 'LIKE', '%' . $search_string . '%')
                                ->orWhereHas('variant', function ($q) use ($search_string) {
                                    $q->where('variant_name', 'LIKE', '%' . $search_string . '%');
                                })
                                ->orWhereHas('model', function ($q) use ($search_string) {
                                    $q->where('model_name', 'LIKE', '%' . $search_string . '%');
                                });
                        });
                    }
                })
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='color' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='color' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('model_name', function ($row) {
                    $str = (isset($row->model->model_name)) ? $row->model->model_name : '';
                    $str .= (isset($row->variant->variant_name)) ? '(' . $row->variant->variant_name . ')' : '';
                    return $str;
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['model_name', 'variant_code', 'active_status', 'action'])
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
            'message'    => trans('messages.ajax_model_loaded'),
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
        try {
            DB::beginTransaction();
            $postData = $request->only('color_name', 'bike_model', 'model_variant_id', 'colors');
            $validator = Validator::make($postData, [
                'bike_model'                => 'required|exists:bike_models,id',
                'model_variant_id'          => 'required|exists:bike_model_variants,id',
                'colors.*.color_name'       => "required",
                'colors.*.sku_code'         => 'required',
                'colors.*.color_code'       => 'nullable',
                'colors.*.active_status'    => 'required|in:0,1'
            ], [
                'bike_model.required'             => 'The model field is required.',
                'model_variant_id.required'       => 'The model varaint field is required.',
                'colors.*.color_name.required'    => 'The color name field is required.',
                'colors.*.sku_code.required'      => 'The sku code field is required.',
                'colors.*.sku_code.unique'      => 'The sku code(:input) is already exist.',
                'colors.*.active_status.required' => 'The status field is required.'
            ]);

            //If Validation Failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }

            //Bulk Insert
            if (count($postData['colors']) > 0) {
                foreach ($postData['colors'] as $k => $colorObj) {
                    $colorObj['bike_model'] = $postData['bike_model'];
                    $colorObj['model_variant_id'] = $postData['model_variant_id'];
                    $colorObj['sku_code'] = strtoupper($colorObj['sku_code']);
                    BikeColor::create($colorObj);
                }
            }
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
        $models = BikeModel::where(['id' => $colorModel->bike_model, 'active_status' => '1'])->get();
        $variants = (object) [];
        if (count($models) > 0) {
            $variants = BikeModelVariant::where(['id' => $colorModel->model_variant_id, 'active_status' => '1'])->get();
        } else {
            $models = self::_getModels();
        }
        if (!$colorModel) {
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
            'data'       => view('admin.colors.ajaxUpdateModal', [
                'action'   => route('colors.update', ['color' => $id]),
                'models'   => $models,
                'variants' => $variants,
                'data'     => $colorModel,
                'method'   => 'PUT'
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
            $colorModel = BikeColor::find($id);
            if (!$colorModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }
            $postData = $request->only('bike_model', 'model_variant_id', 'color_name', 'color_code', 'sku_code', 'active_status');
            $validator = Validator::make($postData, [
                'bike_model'        => 'nullable|exists:bike_models,id',
                'model_variant_id'  => 'nullable|exists:bike_model_variants,id',
                'color_name'        => 'required',
                'color_code'        => "nullable",
                'sku_code'          => 'required',
                'active_status'     => 'required|in:0,1'
            ], [
                'bike_model.exists'       => 'The bike model id(:input) does not exist.',
                'model_variant_id.exists' => 'The bike model variant id(:input) does not exist.',
                'color_name.required'     => 'The color name field is required.',
                'color_name.unique'       => 'The color name field already exists.',
                'sku_code.required'       => 'The sku code field is required.',
                'sku_code.unique'         => 'The sku code field already exists.',
                'active_status.required'  => 'The status field is required'
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

            //UPDATE DATA
            $postData['sku_code'] = strtoupper($postData['sku_code']);
            BikeColor::where(['id' => $id])->update($postData);
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
            $colorModel = BikeColor::find($id);
            if (!$colorModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            //Delete
            $colorModel->delete();
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
        $action .= '<a href="' . route('colors.edit', ['color' => $row->id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE MODEL COLOR" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('colors.destroy', ['color' => $row->id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $row->id . '" data-redirect="' . route('colors.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }

    public function getColorsList($id)
    {
        $colors = BikeColor::where('active_status', '1')->where(['bike_model' => $id])->get()->toArray();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => colors_list($colors)
        ]);
    }
}
