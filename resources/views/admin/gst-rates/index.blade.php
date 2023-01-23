@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                GST Rates
                <small>setting</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">GST Rates</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">GST Rates List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('gst-rates.create') }}" class="btn btn-sm btn-success ajaxModalPopup"
                                    data-modal_title="Add New RTO Rates">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('gst-rates.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>GST Rate(%)</th>
                                        <th>CGST Rate(%)</th>
                                        <th>SGST Rate(%)</th>
                                        <th>IGST Rate(%)</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
    <script src="{{ asset('assets/modules/gst-rates.js') }}"></script>
@endpush
