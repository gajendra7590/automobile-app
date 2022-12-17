@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Roles
                <small>user roles</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Roles</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">User Roles List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('roles.create') }}" class="btn btn-sm btn-success ajaxModalPopup"
                                    data-modal_title="Add New Role">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
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
    <script>
        $(document).ready(function() {
            const tableObj = $('#ajaxDataTable').DataTable({
                processing: false,
                serverSide: true,
                cache: true,
                type: 'GET',
                ajax: {
                    url: '{!! route('roles.index') !!}',
                    method: 'GET',
                    beforeSend: function() {
                        loaderShow();
                    },
                    complete: function() {
                        loaderHide();
                    }
                },
                searchDelay: 350,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                columnDefs: [{
                        "orderable": false,
                        "targets": []
                    },
                    {
                        "searchable": false,
                        "targets": []
                    }
                ],
                order: [
                    [0, "asc"]
                ]
            });
        })
    </script>
@endpush
