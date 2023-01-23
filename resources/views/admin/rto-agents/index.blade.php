@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                RTO Agents
                <small>list</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">RTO Agents</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">RTO Agents List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('rto-agents.create') }}" class="btn btn-sm btn-success ajaxModalPopup"
                                    data-modal_title="Add New RTO Agent">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('rto-agents.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Agent Name</th>
                                        <th>Agent Phone</th>
                                        <th>Agent Email</th>
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
    <script src="{{ asset('assets/modules/rto-agents.js') }}"></script>
@endpush
