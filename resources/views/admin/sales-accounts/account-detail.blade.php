@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Account
                <small>Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Account Detail</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-5">
                    <!-- About Me Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer Detail</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Customer Name</th>
                                    <td>
                                        {{ custFullName(isset($data['sale']) ? $data['sale'] : []) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%">Customer Address</th>
                                    <td>{{ custFullAddress(isset($data['sale']) ? $data['sale'] : []) }}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Customer Phone</th>
                                    <td>{{ isset($data['sale']['customer_mobile_number']) ? $data['sale']['customer_mobile_number'] : '--' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%">Customer Email</th>
                                    <td>{{ isset($data['sale']['customer_email_address']) ? $data['sale']['customer_email_address'] : '--' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-md-5">
                    <!-- About Me Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vehicle Detail</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Customer Name</th>
                                    <td>
                                        {{ custFullName(isset($data['sale']) ? $data['sale'] : []) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%">Customer Address</th>
                                    <td>{{ custFullAddress(isset($data['sale']) ? $data['sale'] : []) }}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Customer Phone</th>
                                    <td>{{ isset($data['sale']['customer_mobile_number']) ? $data['sale']['customer_mobile_number'] : '--' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%">Customer Email</th>
                                    <td>{{ isset($data['sale']['customer_email_address']) ? $data['sale']['customer_email_address'] : '--' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
    </div>
@endsection
