<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.roles.index');
        } else {

            $data = Role::select('*');
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
            'data'       => view('admin.roles.ajaxModal', ['action' => route('roles.store')])->render()
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
            'name' => "required|unique:roles,name"
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
        Role::create(['name' => $postData['name']]);
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
        $roleModel = Role::findById($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.roles.ajaxModal', [
                'action' => route(
                    'roles.update',
                    ['role' => $id]
                ),
                'data' => $roleModel,
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
        $validator = Validator::make($postData, [
            'name' => "required|unique:roles,name," . $id
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
        Role::where(['id' => $id])->update(['name' => $postData['name']]);
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
        //
    }

    public function getActions($row)
    {
        return '<div class="action-btn-container">
                <a href="' . route('roles.edit', ['role' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update Role"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
               </div>';
        // return '<div class="action-btn-container">
        //         <a href="" class="btn btn-sm btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        //         <a href="" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        //        </div>';
    }
}
