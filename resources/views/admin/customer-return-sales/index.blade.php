@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ isset($title) && $title ? $title : '' }}
                <small>Customer Returns List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Customer Returns List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Customer Returns</h3>
                            <div class="pull-right">
                                <a href="{{ route('customerReturns.create') }}" data-modal_title="Create New Return"
                                    data-modal_size="modal-lg" class="btn btn-sm btn-success ajaxModalPopup">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Create New Return
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover myCustomTable"
                                data-url="{{ route('customerReturns.index') }}" style="width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="12%">BRANCH NAME</th>
                                        <th width="12%">DEALER NAME</th>
                                        <th width="12%">CUSTOMER NAME</th>
                                        <th width="15%">BIKE DETAIL</th>
                                        <th width="12%">TOTAL AMOUNT</th>
                                        <th width="10%">SALE DATE</th>
                                        <th width="5%">STATUS</th>
                                        <th width="6%">ACTION</th>
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
    <script src="{{ asset('assets/modules/customer_return_sales.js') }}"></script>
@endpush
