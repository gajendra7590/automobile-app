<?php

namespace App\Http\Controllers;

use App\Models\GstRates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GstRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.gst-rates.index');
        } else {
            $data = GstRates::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='gstRate' class='active_status' checked><span class='slider round'></span></label>";
                    } else {
                        return "<label class='switch'><input type='checkbox' value='$row->id' data-type='gstRate' class='active_status'><span class='slider round'></span></label>";
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
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.gst-rates.ajaxModal', ['action' => route('gst-rates.store')])->render()
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
        try{
            DB::beginTransaction();
        $postData = $request->only('gst_rate', 'cgst_rate', 'sgst_rate', 'igst_rate', 'active_status');
        $validator = Validator::make($postData, [
            'gst_rate'           => "required|numeric",
            'cgst_rate'          => "required|numeric",
            'sgst_rate'          => "required|numeric",
            'igst_rate'          => "nullable|numeric",
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
        GstRates::create($postData);
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
        $gstRatesModel = GstRates::find($id);
        if (!$gstRatesModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist',['id' => $id])
            ]);
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.gst-rates.ajaxModal', [
                'action' => route(
                    'gst-rates.update',
                    ['gst_rate' => $id]
                ),
                'data' => $gstRatesModel,
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
        try{
            DB::beginTransaction();
        $postData = $request->only('gst_rate', 'cgst_rate', 'sgst_rate', 'igst_rate', 'active_status');
        $validator = Validator::make($postData, [
            'gst_rate'           => "required|numeric",
            'cgst_rate'          => "required|numeric",
            'sgst_rate'          => "required|numeric",
            'igst_rate'          => "nullable|numeric",
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

        $gstRatesModel = GstRates::find($id);
        if (!$gstRatesModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist',['id' => $id])
            ]);
        }

        //Create New Role
        $gstRatesModel->update($postData);
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
        //TODO Later
    }

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a href="' . route('gst-rates.edit', ['gst_rate' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update GST Rate"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('gst-rates.destroy', ['gst-rate' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete State"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
