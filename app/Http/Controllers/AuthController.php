<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{


    /**
     * Function for load login view
     * @method GET
     */
    public function loginGet() {
        return view('admin.auth.login');
    }

     /**
     * Function login validate
     * @method POST
     */
    public function loginPost(Request $request) {

    }

    /**
     * Function for load forgotPassword view
     * @method GET
     */
    public function forgotPasswordGet() {
        return view('admin.auth.forgotPassword');
    }

     /**
     * Function forgotPassword validate
     * @method POST
     */
    public function forgotPasswordPost(Request $request) {

    }



}
