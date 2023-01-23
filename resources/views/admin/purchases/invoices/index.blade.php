@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small>Purchase Invoices List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Purchase Invoices List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> List Of All Purchase Invoices</h3>
                            @if (isset($pending_invoices) && $pending_invoices > 0)
                                <div class="pull-right">
                                    <a href="{{ route('purchaseInvoices.create') }}"
                                        class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="Create New Invoice"
                                        data-modal_size="modal-lg">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> CREATE
                                    </a>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover myCustomTable"
                                data-url="{{ route('purchaseInvoices.index') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BRANCH</th>
                                        <th>SKU</th>
                                        <th>VARIANT CODE</th>
                                        <th>INVOICE NUMBER</th>
                                        <th>INVOICE DATE</th>
                                        <th>GRAND TOTAL</th>
                                        <th>ACTION</th>
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
    <script src="{{ asset('assets/modules/purchaseInvoices.js') }}"></script>
@endpush
