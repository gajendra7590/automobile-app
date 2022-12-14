@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Quotations
                <small>List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Quotations list</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Quotations List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('quotations.create') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Create
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('quotations.index') }}"
                                class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="12%">CUSTOMER NAME</th>
                                        <th width="13%">CUSTOMER PHONE</th>
                                        <th width="13%">BIKE</th>
                                        <th width="12%">PAY TYPE</th>
                                        <th>TOTAL AMOUNT</th>
                                        <th>VISIT DATE</th>
                                        <th>PURCHASE DATE</th>
                                        <th>STATUS</th>
                                        <th width="10%">ACTION</th>
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
    <script src="{{ asset('assets/modules/quotations.js') }}"></script>
@endpush
