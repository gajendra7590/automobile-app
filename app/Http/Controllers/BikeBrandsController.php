<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BikeBrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BikeBrand::with([
                'branch' => function ($branch) {
                    $branch->select('id', 'branch_name');
                }
            ])->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='brand' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='brand' class='active_status'><span class='slider round'></span></label>";
                    }
                })
                ->addColumn('branch_name', function ($row) {
                    return isset($row->branch->branch_name) ? $row->branch->branch_name : "";
                })
                ->addColumn('action', function ($row) {
                    $btn = $this->getActions($row['id']);
                    return $btn;
                })
                ->rawColumns(['active_status', 'branch_name', 'action'])
                ->make(true);
        } else {
            return view('admin.brands.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::where('active_status', '1')->select('id', 'branch_name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.brands.ajaxModal', ['action' => route('brands.store'), 'method' => 'POST', 'branches' => $branches])->render()
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
            $validator = Validator::make($postData, [
                'name' => "required|unique:bike_brands,name",
                'branch_id' => "required|exists:branches,id",
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

            BikeBrand::create($request->only('name', 'branch_id', 'description', 'code', 'active_status'));
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
        $branches = Branch::where('active_status', '1')->select('id', 'branch_name')->get();
        $bikeBrand = BikeBrand::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.brands.ajaxModal', ['data' => $bikeBrand, 'branches' => $branches, 'action' => route('brands.update', ['brand' => $id]), 'method' => 'PUT'])->render()
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
            $postData = $request->only('name', 'branch_id', 'code', 'description', 'active_status');
            $validator = Validator::make($postData, [
                'name' => "required|unique:bike_brands,name," . $id,
                'branch_id' => "required|exists:branches,id",
                'active_status'      => 'required|in:0,1'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
            }
            $bikeBrand = BikeBrand::find($id);
            if (!$bikeBrand) {
                return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.brand_not_found')]);
            }

            $bikeBrand->update($postData);
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
        $action .= '<a href="' . route('brands.edit', ['brand' => $id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="UPDATE BRAND" data-modal_size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('brands.destroy', ['brand' => $id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $id . '" data-modal_title="" data-redirect="' . route('brands.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        $action .= '</div>';
        return $action;
    }
}