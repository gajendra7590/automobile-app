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
                            <h3 class="box-title"> {{ isset($title) && $title ? $title : '' }} List Of All Sales</h3>
                            <div class="pull-right">
                                <a href="{{ route('sales.create') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Sale
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover"
                                data-url="{{ route('sales.index') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Branch</th>
                                        <th>Dealer</th>
                                        <th>Brand</th>
                                        <th>Mode</th>
                                        <th>Model Color</th>
                                        <th>DC Number</th>
                                        <th>DC Date</th>
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Amount</th>
                                        <th>Action</th>
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
