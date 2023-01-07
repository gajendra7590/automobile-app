<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ strip_tags(config('constants.APP_NAME')) }} | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/square/blue.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/my_custom.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" />

    @stack('after-css')
    <style>
        .login-logo,
        .register-logo {
            background: #3c424f !important;
        }

        .login-logo a,
        .register-logo a {
            color: #fff !important;
        }

        .ajax_loader {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            opacity: 0.8;
            z-index: 999999;
        }

        .loader-center {
            position: absolute;
            top: 50%;
            left: 50%;
            display: block;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .action-btn-container a {
            margin-left: 2px !important;
        }
    </style>
</head>

<body class="hold-transition login-page"
    style="background-image: url('{{ asset('/assets/dist/img/auth-layout-bg.jpg') }}') !important; ">
    <div class="ajax_loader">
        <div class="loader-center">
            <div class="loader">
                <img src="{{ asset('/assets/dist/img/loading-spinner.gif') }}" />
            </div>
        </div>
    </div>
    <!-- /.login-box -->
    @yield('container')
    <!-- jQuery 3 -->
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/dist/js/custom.js') }}"></script>

    <script src="{{ asset('assets/dist/js/toastr.min.js') }}"></script>

    @stack('after-script')
</body>

</html>
