@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{isset($title) && $title ? $title : ''}}
                <small>List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboardIndex')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{route('agents.index')}}"> {{isset($title) && $title ? $title : ''}} </a></li>
                <li class="active">List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> {{isset($title) && $title ? $title : ''}} List</h3>
                            <div class="pull-right">
                                <a href="{{ route('dealers.create') }}" class="btn btn-sm btn-success ajaxModalPopup" data-modal_title="Add New Dealer">
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
                                        <th>Company Name</th>
                                        <th>Company Email</th>
                                        <th>Company Office Phone</th>
                                        <th>Company Address</th>
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
                    url: '{!! route('dealers.index') !!}',
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
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'company_email',
                        name: 'company_email'
                    },
                    {
                        data: 'company_office_phone',
                        name: 'company_office_phone'
                    },
                    {
                        data: 'company_address',
                        name: 'company_address'
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
                    [0, "desc"]
                ]
            });
        })
    </script>
@endpush

