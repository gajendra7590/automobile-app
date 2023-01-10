@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Sale
                <small>Accounts List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sales Accounts List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Sales Accounts List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('saleAccounts.create') }}" class="btn btn-sm btn-success ajaxModalPopup"
                                    data-modal_title="Create New Sales Account" data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Create Account
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('saleAccounts.index') }}"
                                class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>TITLE</th>
                                        <th>SALES PRICE</th>
                                        <th>DOWN PAYMENT</th>
                                        <th>TOTAL DUE</th>
                                        <th>STATUS</th>
                                        <th>CREATED DATE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
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
        <!-- /.content -->
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/sales-accounts.js') }}"></script>
@endpush
