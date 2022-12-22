@extends('admin.layouts.admin-layout')
@section('container')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Profile <small>Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Profile</a></li>
                <li class="active">View</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Details</h3>
                </div>
                <div class="box-body">
                    <div class="row" style="display: flex; justify-content: space-between;">
                        {{-- profile update --}}
                        <div class="col-md-6 jumbotron" style="border-radius:10%;">
                            <form class="ajaxFormSubmit" role="form" method="POST"
                                action="{{ isset($actionProfileUpdate) ? $actionProfileUpdate : '' }}" enctype="multipart/form-data"
                                data-redirect="ajaxModalCommon">
                                @csrf
                                @if (isset($method) && $method == 'PUT')
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <img id='profile-change'
                                            src="{{ isset($user->profile_image) && $user->profile_image ? $user->profile_image : asset('assets/dist/img/default-avatar.png') }}"
                                            style='border-radius:50%;' />
                                        <input id='input-profile' type='file' name='profile_image'>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Name</label>
                                        <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                            placeholder="Enter Name" name="name"
                                            value='{{ isset($user->name) && $user->name ? $user->name : '' }}' />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Email</label>
                                        <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                            placeholder="Enter Email" name="email"
                                            value='{{ isset($user->email) && $user->email ? $user->email : '' }}' />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- password update --}}
                        <div class="col-md-4 jumbotron" style="border-radius:10%;">
                            <h3>Change Password</h3>
                            <br />
                            <form class="ajaxFormSubmit" role="form" method="POST"
                                action="{{ isset($actionPasswordUpdate) ? $actionPasswordUpdate : '' }}" enctype="multipart/form-data"
                                data-redirect="ajaxModalCommon">
                                @csrf
                                @if (isset($method) && $method == 'PUT')
                                    @method('PUT')
                                @endif
                                <div class='row'>
                                    <div class="form-group col-md-12">
                                        <label>Password</label>
                                        <input type="password" class="form-control my-colorpicker1 colorpicker-element"
                                            placeholder="Enter Old Password" name="password"/>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control my-colorpicker1 colorpicker-element"
                                            placeholder="Enter New Password" name="new_password" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/profile.js') }}"></script>
@endpush
