<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.districts.index');
        } else {

            $data = District::with([
                'state' => function ($model) {
                    $model->select('id', 'state_name');
                }
            ])->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='district' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='district' class='active_status'><span class='slider round'></span></label>";
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
        $states = State::where(['active_status' => '1'])->select('id', 'state_name')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.districts.ajaxModal', ['action' => route('districts.store'), 'states' => $states])->render()
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
            $postData = $request->only('state_id', 'district_name', 'district_code', 'active_status');
            $validator = Validator::make($postData, [
                'state_id' => "required|exists:u_states,id",
                'district_name' => "required|unique:u_districts,district_name",
                'district_code' => "nullable",
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
            District::create($postData);
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
        $states = State::where(['active_status' => '1'])->select('id', 'state_name')->get();
        $districtModel = District::find($id);
        if (!$districtModel) {
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
            'data'       => view('admin.districts.ajaxModal', [
                'action' => route(
                    'districts.update',
                    ['district' => $id]
                ),
                'data' => $districtModel,
                'method' => 'PUT',
                'states' => $states

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
            $postData = $request->only('state_id', 'district_name', 'district_code', 'active_status');
            $districtModel = District::find($id);
            if (!$districtModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }
            $validator = Validator::make($postData, [
                'state_id' => "required|exists:u_states,id",
                'district_name' => "required|unique:u_districts,district_name," . $id . ",id",
                'district_code' => "nullable",
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
            District::where(['id' => $id])->update($postData);
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
            $districtModel = District::find($id);
            if (!$districtModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            if (City::where(['district_id' => $id])->count()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! You can`t delete district, before delete all cities first."
                ]);
            }

            //Delete
            $districtModel->delete();
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
        $action .= '<a href="' . route('districts.edit', ['district' => $row->id]) . '" class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="Update District"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        //$action .= '<a href="' . route('districts.destroy', ['district' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete District"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
