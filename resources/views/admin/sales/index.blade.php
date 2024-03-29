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
                            <div class="pull-right custom-fitler-container">
                                <div class="filter-options pull-left">
                                    <div class="row">
                                        <div class="col-xs-4 cust_col">
                                            <select class="form-control ajaxDtFilter" name="branch_id" data-col_index="1">
                                                <option value="">---BRANCH NAME---</option>
                                                @if (isset($branches))
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-xs-4 cust_col">
                                            <select class="form-control ajaxDtFilter" name="transfer_status"
                                                data-col_index="6">
                                                <option value="">---DUE STATUS---</option>
                                                <option value="open">OPEN</option>
                                                <option value="close">CLOSE</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-4 cust_col">
                                            <select class="form-control ajaxDtFilter" name="status" data-col_index="7">
                                                <option value="">---BROKER---</option>
                                                <option value="1">YES</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-left">
                                    <a href="#" class="btn btn-sm btn-primary" id="customFitterAction">
                                        <i class="fa fa-filter" aria-hidden="true"></i>
                                    </a>
                                </div>
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
                                        <th width="12%">CUSTOMER NAME</th>
                                        <th width="25%">BIKE DETAIL</th>
                                        <th>CHASIS NUMBER</th>
                                        <th>TOTAL AMOUNT</th>
                                        <th>SALE DATE</th>
                                        <th>STATUS</th>
                                        <th>BROKER</th>
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
