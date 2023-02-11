@extends('admin.layouts.admin-layout')
@section('container')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Profile <small>Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Manage Profile & Password</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Manage Profile & Password</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <img id='profile-change' class="profile-user-img img-responsive img-circle"
                                        src="{{ isset($user->profile_image) && $user->profile_image ? $user->profile_image : asset('assets/dist/img/default-avatar.png') }}"
                                        alt="User profile picture">

                                    <h3 class="profile-username text-center">
                                        {{ isset($user->name) && $user->name ? $user->name : '' }}
                                    </h3>

                                    <p class="text-muted text-center">
                                        {{ isset($user->is_admin) && $user->is_admin == '1' ? 'Administrator' : 'User' }}
                                    </p>

                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Access Level</b> <a
                                                class="pull-right">{{ isset($user->branch->branch_name) ? $user->branch->branch_name : 'All Branches' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Active Status</b> <a
                                                class="pull-right">{{ isset($user->active_status) && $user->active_status == '1' ? 'Active' : 'Blocked' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Joining Date</b> <a class="pull-right">
                                                {{ isset($user->created_at) ? date('d-m-Y', strtotime($user->created_at)) : '--' }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <div class="col-md-5">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Update Your Profile</h3>
                                </div>
                                <form class="ajaxFormSubmitUP ajaxForm" role="form" method="POST"
                                    action="{{ isset($actionProfileUpdate) ? $actionProfileUpdate : '' }}"
                                    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
                                    @csrf
                                    @if (isset($method) && $method == 'PUT')
                                        @method('PUT')
                                    @endif
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Your Name</label>
                                            <input name="name" type="text" class="form-control"
                                                placeholder="Enter your name"
                                                value="{{ isset($user->name) && $user->name ? $user->name : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Your Email Address</label>
                                            <input type="email" name="email"
                                                class="form-control"placeholder="Enter your email address"
                                                value="{{ isset($user->email) && $user->email ? $user->email : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Your Profile Photo</label>
                                            <input id='input-profile' type="file" name="profile_image"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Change Your Password</h3>
                                </div>
                                <form class="ajaxFormSubmitCP ajaxForm" role="form" method="POST"
                                    action="{{ isset($actionPasswordUpdate) ? $actionPasswordUpdate : '' }}"
                                    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
                                    @csrf
                                    @if (isset($method) && $method == 'PUT')
                                        @method('PUT')
                                    @endif
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="oldPassword">Your Old Password</label>
                                            <input type="password" class="form-control" id="oldPassword"
                                                placeholder="********" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="newPassword">Your New Password</label>
                                            <input type="password" class="form-control" id="newPassword"
                                                placeholder="********" name="new_password">
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Chanage Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                </div>
            </div>
        </section>
    </div>
@endsection

@push('after-script')
    <script>
        $(".ajaxFormSubmitCP").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                new_password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                password: {
                    required: "The password field is required.",
                },
                new_password: {
                    required: "The new password field is required.",
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                let formObj = $(".ajaxFormSubmitCP");
                var url = formObj.attr("action");
                var method = formObj.attr("method");
                var redirect = formObj.data("redirect");
                var data = new FormData(formObj[0]);
                CRUD.AJAXSUBMIT(url, method, data).then(function(result) {
                    if (
                        typeof result.status != "undefined" &&
                        result.status == true
                    ) {
                        if (redirect != undefined) {
                            if (result.data?.id) {
                                redirect = redirect.replace("{id}", result.data.id);
                            }
                            window.location.href = "";
                        }
                    }
                });
            },
        });

        $(".ajaxFormSubmitUP").validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                name: {
                    required: "The name field is required.",
                },
                new_password: {
                    required: "The email field is required.",
                    email: "The email field should valid email address."
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                let formObj = $(".ajaxFormSubmitUP");
                var url = formObj.attr("action");
                var method = formObj.attr("method");
                var redirect = formObj.data("redirect");
                var data = new FormData(formObj[0]);
                CRUD.AJAXSUBMIT(url, method, data).then(function(result) {
                    if (
                        typeof result.status != "undefined" &&
                        result.status == true
                    ) {
                        if (redirect != undefined) {
                            if (result.data?.id) {
                                redirect = redirect.replace("{id}", result.data.id);
                            }
                            window.location.href = "";
                        }
                    }
                });
            },
        });
    </script>
    <script src="{{ asset('assets/modules/profile.js') }}"></script>
@endpush
