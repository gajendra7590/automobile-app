<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\BikeModel;
use App\Models\BikeModelVariant;
use App\Models\Branch;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeModelVariantController extends Controller
{
    use CommonHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $postData = $request->all();
            $data = BikeModelVariant::with(['model'])->select('*')->orderBy('model_id');

            //Fitler By Branch
            if (isset($postData['columns'][2]['search']['value']) && (!empty($postData['columns'][2]['search']['value']))) {
                $data->where('model_id', $postData['columns'][2]['search']['value']);
            }

            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where(function ($qq) use ($search_string) {
                            $qq->where('id', $search_string)
                                ->orWhereHas('model', function ($q) use ($search_string) {
                                    $q->where('model_name', 'LIKE', '%' . $search_string . '%');
                                })
                                ->orwhere('variant_name', 'LIKE', '%' . $search_string . '%');
                        });
                    }
                })
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='model_variant' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='model_variant' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('model_id', function ($row) {
                    return (isset($row->model->model_name)) ? $row->model->model_name : '';
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['active_status', 'action'])
                ->make(true);
        } else {
            $models = self::_getModels();
            return view('admin.modelVariants.index', ['models' => $models]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $models = BikeModel::where('active_status', '1')->select('id', 'model_name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.modelVariants.ajaxModal', ['action' => route('modelVariants.store'), 'method' => 'POST', 'models' => $models])->render()
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
            $postData = $request->all();
            //Check Validation
            $validator = Validator::make($postData, [
                'model_id'                 => "required|exists:bike_models,id",
                'variants.*.variant_name'  => 'required|unique:bike_model_variants,variant_name',
                'variants.*.active_status'  => 'required|in:0,1'
            ], [
                'model_id' => 'The model field is required.',
                'variants.*.variant_name.required' => "The Variant Name field is required.",
                'variants.*.variant_name.unique'   => "The Variant Name(:input) is already exist."
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

            //CREATE VARIANTS
            if (isset($postData['variants']) && (count($postData['variants']))) {
                foreach ($postData['variants'] as $varaint) {
                    BikeModelVariant::create([
                        'model_id'      => isset($postData['model_id']) ? $postData['model_id'] : 0,
                        'variant_name'  => isset($varaint['variant_name']) ? $varaint['variant_name'] : null,
                        'active_status' => isset($varaint['active_status']) ? $varaint['active_status'] : 1,
                    ]);
                }
            }
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
        $bikeBrand = BikeBrand::find($id);
        return view('admin.brands.show', ['bikeBrand' => $bikeBrand]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bikeModelVariant = BikeModelVariant::find($id);
        $models = BikeModel::where('active_status', '1')->where('id', $bikeModelVariant->model_id)->select('id', 'model_name')->get();
        $data = array(
            'data'   => $bikeModelVariant,
            'models' => $models,
            'action' => route('modelVariants.update', ['modelVariant' => $id]),
            'method' => 'PUT'
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.modelVariants.ajaxEditModal', $data)->render()
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
            $postData = $request->only('model_id', 'variant_name', 'active_status');

            $validator = Validator::make($postData, [
                'model_id'       => "required|exists:bike_models,id",
                'variant_name'   => "required|unique:bike_model_variants,variant_name," . $id,
                'active_status'  => 'required|in:0,1'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }

            $bikeModelVariant = BikeModelVariant::find($id);
            if (!$bikeModelVariant) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }

            $bikeModelVariant->update($postData);
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
            $bikeBrand = BikeBrand::find($id);
            if (!$bikeBrand) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }
            if ($bikeBrand->bike_modals()->count()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.cant_delete_brand')]);
            }
            $bikeBrand->delete();
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
        $action .= '<a href="' . route('modelVariants.edit', ['modelVariant' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE MODEL VARIANT"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
