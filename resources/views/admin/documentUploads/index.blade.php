@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Document
                <small>Uploads</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Document Uploads</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Documents List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('documentUploads.create') }}" class="btn btn-sm btn-success"
                                    data-modal_title="Upload New Document" data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('documentUploads.index') }}"
                                class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SECTION TYPE</th>
                                        <th>UPLOAD NOTE</th>
                                        <th>FILE EXTENTION</th>
                                        <th>FILE SIZE</th>
                                        <th>UPLOADED AT</th>
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
    <script src="{{ asset('assets/modules/documentUploads.js') }}"></script>
@endpush
