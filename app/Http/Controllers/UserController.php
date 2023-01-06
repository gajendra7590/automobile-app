<?php

namespace App\Http\Controllers;

use App\Models\Branch;
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

            $data = User::with([
                'roles',
                'branch' => function ($b) {
                    $b->select('id', 'branch_name');
                }
            ])->select('id', 'name', 'email', 'profile_image', 'active_status', 'is_default', 'branch_id');
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
                ->addColumn('profile_image', function ($row) {
                    return "<img src=" . $row->profile_image . " style='height: 60px;width: 60px;border-radius: 50%;'>";
                })
                ->addColumn('branch.branch_name', function ($row) {
                    return (isset($row->branch->branch_name) > 0) ? ucfirst($row->branch->branch_name) . ' Branch' : 'All Branches';
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['branch.branch_name', 'active_status', 'action', 'profile_image'])
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
        $branches = Branch::where('active_status', '1')->select('id', 'branch_name')->get();
        $data = array(
            'action'    => route('users.store'),
            'roles'     => $roles,
            'branches' => $branches,
            'method' => 'POST',
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.users.ajaxModal', $data)->render()
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
        $postData = $request->only('name', 'email', 'password', 'branch_id', 'active_status');
        $validator = Validator::make($postData, [
            'name'     => "required",
            'email'    => "required|unique:users,email",
            'password' => "required|min:6",
            'branch_id'     => "required|exists:branches,id",
            'active_status'   => "required|in:0,1",
            'profile_image'   => "nullable|mime:jpg,png,img,jpeg"
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
        $postData['password'] = Hash::make($postData['password']);
        $userModel = User::create($postData);
        //Assign Role
        if ($userModel) {
            $userModel->assignRole(2);
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.create_success')
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
        $userModel = User::find($id);
        $roles = Role::where('id', '!=', '1')->get();
        $branches = Branch::where('active_status', '1')->select('id', 'branch_name')->get();
        $data = array(
            'action'    => route('users.update', ['user' => $id]),
            'roles'     => $roles,
            'branches'  => $branches,
            'data' => $userModel,
            'method' => 'PUT',
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.users.ajaxModal', $data)->render()
        ]);
    }

    public function changePasswordPost(Request $request, $id)
    {
        $postData = $request->only('password', 'password_confirmation');
        $validator = Validator::make($postData, [
            'password' => "required|min:6|confirmed",
        ]);

        //If Validation failed
        if ($validator->fails()) {
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()]);
        }

        $user = User::find($id);
        if (!$user) {
            response()->json(['status' => true, 'statusCode' => 419, 'message' => trans('messages.on_user_associate'), 'data' => []]);
        }
        $user->update(['password' => Hash::make(request('password'))]);

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.password_generated'),
            'data' => []
        ], 200);
    }

    public function changePassword($id)
    {
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.users.changePassword', ['id' => $id, 'action' => route('user.changePassword.post', ['user' => $id])])->render()
        ], 200);
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
                'message'    => trans('messages.user_not_exist')
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
            'message'    => trans('messages.update_success')
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
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.user_not_exist')]);
        }
        $user->delete();
        return response()->json(['status' => true, 'statusCode' => 200, 'message' => trans('messages.delete_success'),], 200);
    }

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        if ($row->id != '1' && $row->is_default == '0') {
            $action .= '<a href="' . route('users.edit', ['user' => $row->id]) . '" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Update User"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        }
        $action .= '<a href="' . route('user.changePassword', ['user' => $row->id]) . '" class="btn btn-sm btn-success ajaxModalPopup" data-modal_title="Generate Password" data-modal_size="model-sm"><i class="fa fa-key" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }
}
