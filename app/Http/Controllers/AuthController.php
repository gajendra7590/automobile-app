<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    /**
     * Index function
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect('dashboard');
        } else {
            return redirect('/login');
        }
    }


    /**
     * Function for load login view
     * @method GET
     */
    public function loginGet()
    {
        return view('admin.auth.login');
    }

    /**
     * Function for validate login & do logged in
     */
    public function loginPost(Request $request)
    {
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'email' => "required|email",
            'password' => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(['statusCode' => 419, 'status' => false,'message' => $validator->errors()->first(),'data' => (object)[] ]);
        }

        $userModel = User::where('email', $postData['email'])->first();

        if (!$userModel) {
            return response()->json(['statusCode' => 419, 'status' => false,'message' => 'User does not exists.','data' => (object)[] ]);
        }

        //return $validator;
        $credentials = array('email' => $postData['email'], 'password' => $postData['password']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['statusCode' => 200, 'status' => true,'message' => 'Login Successful','data' => (object)[] ]);
        } else {
            return response()->json(['statusCode' => 419, 'status' => false,'message' => 'You have entered wrong credetials','data' => (object)[] ]);
        }
    }

    /**
     * function for admin logout
     */
    public function logout(Request $request) {
        if(Auth::check()) {
            Auth::logout();
            return redirect()->route('loginGet');
        }  else {
            return redirect()->route('loginGet');
        }
    }

    /**
     * Function for load forgotPassword view
     * @method GET
     */
    public function forgotPasswordGet()
    {
        return view('admin.auth.forgotPassword');
    }

    /**
     * Function forgotPassword validate
     * @method POST
     */
    public function forgotPasswordPost(Request $request)
    {

    }

    public function profile(Request $request) {
        $user = auth()->user();
        return view('admin.auth.profile',['user' => $user,'actionProfileUpdate' => route('profileUpdate') ,'actionPasswordUpdate' => route('passwordUpdate') ]);
    }

    public function profileUpdate(Request $request){
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'email' => "required|email|unique:users,email,$user->id,id",
            'name' => "required|string",
            'profile_image' => "nullable|file|mime:png,jpg,jpeg,img"
        ]);

        if ($validator->fails()) {
            return response()->json(['statusCode' => 419, 'status' => false,'message' => $validator->errors()->first(),'data' => (object)[] ]);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if(isset($request->email) && $request->email){
            $user->profile_image = $request->profile_image;
        }
        $user->save();
        return response()->json(['statusCode' => 200, 'status' => true,'message' => 'Updated Successfully','data' => (object)[] ]);
    }

    public function passwordUpdate(Request $request){
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'password' => "required|string",
            'new_password' => "required|string|min:6|different:password"
        ]);

        if ($validator->fails()) {
            return response()->json(['statusCode' => 419, 'status' => false,'message' => $validator->errors()->first(),'data' => (object)[] ]);
        }
        if(!Hash::check($request->password, $user->password)){
            return response()->json(['statusCode' => 419, 'status' => false,'message' => 'Old password not match..','data' => (object)[] ]);
        }
        $user->password = Hash::make($request->new_password);
        return response()->json(['statusCode' => 200, 'status' => true,'message' => 'Updated Successfully','data' => (object)[] ]);
    }
}
