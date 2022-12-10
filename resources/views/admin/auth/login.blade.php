@extends('admin._layouts.auth-layout')
@section('content')
    <div class="p-3">
        <h2 class="mb-2">Sign In</h2>
        <p>Login to stay connected.</p>
        <form class="ajaxFormSubmit" method="POST" action="{{route('loginPost')}}" enctype="multipart/form-data" data-redirect="{{route('dashboardIndex')}}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="floating-label form-group">
                        <input class="floating-input form-control" type="email" name="email"
                            placeholder=" ">
                        <label>Email</label>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="floating-label form-group">
                        <input class="floating-input form-control" type="password" name="password"
                            placeholder=" ">
                        <label>Password</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input"
                            id="customCheck1">
                        <label class="custom-control-label control-label-1"
                            for="customCheck1">Remember Me</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('forgotPasswordGet') }}"
                        class="text-primary float-right">Forgot Password?</a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
            <p class="mt-3">
                Create an Account <a href="javascript:void(0);"
                    class="text-primary">Sign Up</a>
            </p>
        </form>
    </div>
@endsection
