@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Quotations
                <small>Quotations list</small>
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
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
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
                                        <th>Customer Name</th>
                                        <th>Customer Email</th>
                                        <th>Bike Purchase</th>
                                        <th>Payment Type</th>
                                        <th>Exchange</th>
                                        <th>Total Amount</th>
                                        <th>Visit Date</th>
                                        <th>Est Purchase Date</th>
                                        <th width="10%">Action</th>
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
