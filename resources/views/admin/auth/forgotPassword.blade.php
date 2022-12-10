@extends('admin._layouts.auth-layout');

@section('content')

<div class="p-3">
    <h2 class="mb-2">Reset Password</h2>
    <p>Enter your email address and we'll send you an email with instructions to reset your password.</p>
    <form>
       <div class="row">
          <div class="col-lg-12">
             <div class="floating-label form-group">
                <input class="floating-input form-control" type="email" placeholder=" ">
                <label>Email</label>
             </div>
          </div>
       </div>
       <div class="col-lg-12">
            <a href="{{ route('loginGet') }}" class="text-primary float-right">Back to login?</a>
        </div>
       <button type="submit" class="btn btn-primary">Reset</button>
    </form>
 </div>

 @endsection
