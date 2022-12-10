<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Function login validate
     * @method POST
     */
    public function loginPost(Request $request)
    {
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
