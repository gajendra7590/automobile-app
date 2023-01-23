@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ isset($title) && $title ? $title : '' }}
                <small>Sale List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('sales.index') }}"> {{ isset($title) && $title ? $title : '' }} </a></li>
                <li class="active">Sale List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> List Of All Sales</h3>
                            <div class="pull-right">
                                <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> CREATE
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover myCustomTable"
                                data-url="{{ route('sales.index') }}" style="width: auto !important;">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="12%">BRANCH NAME</th>
                                        <th width="12%">DEALER NAME</th>
                                        <th width="12%">CUSTOMER NAME</th>
                                        <th width="25%">BIKE DETAIL</th>
                                        <th width="12%">TOTAL AMOUNT</th>
                                        <th width="10%">SALE DATE</th>
                                        <th width="5%">STATUS</th>
                                        <th width="5%">ACTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


@push('after-script')
    <script src="{{ asset('assets/modules/sales.js') }}"></script>
@endpush
