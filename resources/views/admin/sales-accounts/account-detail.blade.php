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
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">CUSTOMER INFORMATION</h3>
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
                <div class="col-md-7">
                    <!-- About Me Box -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">DUE/EMI HISTORY</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>Due Date</th>
                                    <th>Due Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Paid Date</th>
                                    <th>Action</th>
                                </tr>
                                @isset($data['installments'])
                                    @foreach ($data['installments'] as $k => $installment)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ date('Y-m-d', strtotime($installment->emi_due_date)) }}</td>
                                            <td>Test</td>
                                            <td>Test</td>
                                            <td>Test</td>
                                            <td>--</td>
                                        </tr>
                                    @endforeach
                                @endisset

                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-md-6">
                    <!-- About Me Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">PURCHASE BIKE DETAIL</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Dealer Name</th>
                                    <td>
                                        {{ isset($data['sale']['dealer']['company_name']) ? $data['sale']['dealer']['company_name'] : '--' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="40%">Bike (Brand | Model | Color)</th>
                                    <td>
                                        {{ isset($data['sale']['brand']['name']) ? $data['sale']['brand']['name'] . ' | ' : '' }}
                                        {{ isset($data['sale']['model']['model_name']) ? $data['sale']['model']['model_name'] . ' | ' : '' }}
                                        {{ isset($data['sale']['model_color']['color_name']) ? $data['sale']['model_color']['color_name'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="40%">Bike(Type | Fuel | Break | Wheel)</th>
                                    <td>
                                        {{ isset($data['sale']['bike_type']) ? $data['sale']['bike_type'] : '' }} |
                                        {{ isset($data['sale']['bike_fuel_type']) ? $data['sale']['bike_fuel_type'] : '' }}
                                        |
                                        {{ isset($data['sale']['break_type']) ? $data['sale']['break_type'] : '' }} |
                                        {{ isset($data['sale']['wheel_type']) ? $data['sale']['wheel_type'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="40%">SKU</th>
                                    <td>{{ isset($data['sale']['sku']) ? $data['sale']['sku'] : '--' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-md-6">
                    <!-- About Me Box -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">PAYMENT TRANSACTIONS</h3>
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
