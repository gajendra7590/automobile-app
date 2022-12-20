<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.users.index');
        } else {

            $data = User::with('roles')->select('id', 'name', 'email', 'active_status', 'is_default', 'created_at');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    if (isset($row->roles) && count($row->roles)) {
                        return $row->roles[0]->name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return '<span class="label label-success">Active</span>';
                    } else {
                        return '<span class="label label-warning">In Active</span>';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['active_status', 'created_at', 'action'])
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
        $roles = Role::where('id', '!=', '1')->get();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.users.ajaxModal', ['action' => route('users.store'), 'roles' => $roles])->render()
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
            'name'     => "required",
            'email'    => "required|unique:users,email",
            'password' => "required|min:6",
            'active_status'   => "required|in:0,1",
            'role'     => "required|exists:roles,id"
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
        $userModel = User::create([
            'name' => $postData['name'],
            'email' => $postData['email'],
            'password' => Hash::make($postData['password']),
            'active_status' => $postData['active_status']
        ]);

        //Assign Role
        if ($userModel) {
            $userModel->assignRole($postData['role']);
        }

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
        $roles = Role::where('id', '!=', '1')->get();
        $userModel = User::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => 'AjaxModal Loaded',
            'data'       => view('admin.users.ajaxModal', [
                'action' => route(
                    'users.update',
                    ['user' => $id]
                ),
                'data' => $userModel,
                'method' => 'PUT',
                'roles' => $roles
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
            'name'     => "required",
            'email'    => "required|unique:users,email," . $id,
            'password' => "nullable|min:6",
            'active_status'   => "required|in:0,1",
            'role'     => "required|exists:roles,id"
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

        $userModel = User::find($id);
        if (!$userModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Opps! user does not exist."
            ]);
        }


        $userModel->name = $postData['name'];
        $userModel->email = $postData['email'];
        $userModel->active_status = $postData['active_status'];
        if (!empty($postData['password'])) {
            $userModel->password = Hash::make($postData['password']);
        }

        if (!empty($postData['role'])) {
            $userModel->syncRoles($postData['role']);
        }
        $userModel->save();
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
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => 'Not Found']);
        }
        $user->delete();
        return response()->json(['status' => true, 'statusCode' => 200, 'message' => 'Deleted Successfully',], 200);
    }

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        if ($row->id != '1' && $row->is_default == '0') {
            $action .= '<a href="' . route('users.edit', ['user' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update User"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
            $action .= '<a href="' . route('users.destroy', ['user' => $row->id]) . '" class="btn btn-sm btn-danger ajaxModalDelete"  data-id="' . $row->id . '" data-redirect="' . route('users.index') . '"><i class="fa fa-trash-o" aria-hidden="true"> </i></a>';
        } else {
            $action .= '--';
        }
        $action .= '</div>';
        return $action;
    }
}
