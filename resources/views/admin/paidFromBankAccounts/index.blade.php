@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                PAID FROM BANK ACCOUNTS LIST
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">PAID FROM BANK ACCOUNTS LIST</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">PAID FROM BANK ACCOUNTS LIST</h3>
                            <div class="pull-right">
                                <a href="{{ route('paidFromBankAccounts.create') }}"
                                    class="btn btn-sm btn-primary ajaxModalPopup" data-modal_title="ADD NEW BANK ACCOUNT"
                                    data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> ADD ACCOUNT
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('paidFromBankAccounts.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BANK NAME</th>
                                        <th>ACCOUNT NUMBER</th>
                                        <th>ACCOUNT HOLDER</th>
                                        <th>IFSC CODE</th>
                                        <th>BRANCH NAME</th>
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
    <script src="{{ asset('assets/modules/paidFromBankAccounts.js') }}"></script>
@endpush
