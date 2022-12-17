@extends('admin.layouts.auth-layout')
@section('container')
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>Aakash</b> Automobiles</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form class="ajaxFormSubmit" method="POST" action="{{route('loginPost')}}" enctype="multipart/form-data" data-redirect="{{route('dashboardIndex')}}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name='email' />
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
                            Sign In
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <br />
            <a href="#">Forgot Password</a><br />
        </div>
    </div>
@endsection
