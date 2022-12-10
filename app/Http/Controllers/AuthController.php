<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
