@extends('admin.layouts.auth-layout')
@section('container')
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>ASP</b> VDMS</a>
        </div>
        <!-- /.login-logo -->
        @php
            $pageName = $_GET['p'] ?? 'login';
            $loginSessionId =isset($_GET['sid']) ? base64_decode($_GET['sid']) : '';
            $verifyTokenEmail = isset($_GET['e']) ? base64_decode($_GET['e']) : '';
        @endphp
        @if ($pageName == 'verifyOtp')
            <div class="login-box-body" id="verifyOtpScreen">
                <p class="login-box-msg">Verify OTP </p>
                <form class="ajaxFormSubmit" method="POST" action="{{ route('verifyOtp') }}" enctype="multipart/form-data" data-redirect="{{ route('dashboardIndex') }}">
                    <div class="form-group has-feedback">
                        <input type="hidden" class="form-control" name='loginSessionId' id="loginSessionId" value="{{ $loginSessionId }}" />
                        <input type="hidden" class="form-control" name='verifyTokenEmail' id="verifyTokenEmail" value="{{ $verifyTokenEmail }}" />
                        <input type="text" class="form-control" placeholder="Enter OTP" name='verifyToken' value="" />
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                            VERIFY OTP
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p style="margin-top: 11px;text-align: right;">
                    <a href="{{ route('loginGet') }}" class="text-center">Back to login</a>
                </p>
            </div>
        @else
            <div class="login-box-body" id="loginScreen">
                <p class="login-box-msg">Login Your Account</p>
                <form class="loginAjaxForm" method="POST" action="{{ route('loginPost') }}" enctype="multipart/form-data">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" placeholder="Email" id="loginEmail" name='email' />
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Password" name='password' />
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                            LOGIN IN
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
        @endif

    </div>
@endsection
