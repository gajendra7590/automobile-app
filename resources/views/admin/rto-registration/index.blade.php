@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                RTO
                <small>Registrations</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">RTO Registrations</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> All Registrations</h3>
                            <div class="pull-right">
                                <a href="{{ route('rtoRegistration.create') }}"
                                    class="btn btn-sm btn-success ajaxModalPopup" data-modal_title="Create New Registration"
                                    data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Create Registration
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('rtoRegistration.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BRANCH NAME</th>
                                        <th>CONTACT NAME</th>
                                        <th>TOTAL AMOUNT</th>
                                        <th>RC NUMBER</th>
                                        <th>SUBMIT DATE</th>
                                        <th>RECIEVED DATE</th>
                                        <th width="6%">ACTION</th>
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
    <script src="{{ asset('assets/modules/rto-registration.js') }}"></script>
@endpush
