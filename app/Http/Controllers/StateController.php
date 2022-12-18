<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.states.index');
        } else {
            $data = State::with([
                'country' => function ($model) {
                    $model->select('id', 'country_name');
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
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.states.ajaxModal', ['action' => route('states.store')])->render()
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
            'country_id' => "required",
            'state_name' => "required|unique:u_states,state_name",
            'state_code' => "required"
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
        State::create([
            'country_id' => $postData['country_id'],
            'state_name' => $postData['state_name'],
            'state_code' => $postData['state_code']
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
        $stateModel = State::find($id);
        if (!$stateModel) {
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
            'data'       => view('admin.states.ajaxModal', [
                'action' => route(
                    'states.update',
                    ['state' => $id]
                ),
                'data' => $stateModel,
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
        $postData = $request->all();
        $stateModel = State::find($id);
        if (!$stateModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }
        $validator = Validator::make($postData, [
            'state_name' => "required|unique:u_states,state_name," . $id . ",id",
            'state_code' => "required"
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
        State::where(['id' => $id])->update([
            'state_name' => $postData['state_name'],
            'state_code' => $postData['state_code']
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
        $stateModel = State::find($id);
        if (!$stateModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        //Delete
        $stateModel->delete();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Deleted Successfully."
        ]);
    }


    public function getActions($row)
    {
        return '<div class="action-btn-container">
                <a href="' . route('states.edit', ['state' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update State"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                <a href="' . route('states.destroy', ['state' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete State"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>';
        // return '<div class="action-btn-container">
        //         <a href="" class="btn btn-sm btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        //        </div>';
    }
}
