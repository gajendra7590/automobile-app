@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                AGENTS PAYMENTS
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">AGENTS PAYMENTS</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">AGENTS PAYMENTS</h3>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('rtoAgentPayments.index') }}"
                                class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>AGENT NAME</th>
                                        <th>TOTAL FILES</th>
                                        <th>TOTAL BALANCE</th>
                                        <th>TOTAL PAID</th>
                                        <th>TOTAL OUTSTANDING</th>
                                        <th>BUFFER AMOUNT(+)</th>
                                        <th width="5%">ACTION</th>
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
    <script src="{{ asset('assets/modules/rtoAgentPayments.js') }}"></script>
@endpush
