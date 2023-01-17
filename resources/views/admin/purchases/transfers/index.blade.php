@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small>All Transfers</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">All Transfers</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List Of All Transfers</h3>
                            <div class="pull-right">
                                <a href="{{ route('purchaseTransfers.create') }}"
                                    class="btn btn-sm btn-success ajaxModalPopup" data-modal_title="Create New Transfer"
                                    data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> CREATE NEW TRANSFER
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover"
                                data-url="{{ route('purchaseTransfers.index') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BRANCH</th>
                                        <th>BROKER</th>
                                        <th width="16%">BIKE INFO</th>
                                        <th>SKU</th>
                                        <th>VARIANT CODE</th>
                                        <th>DC NUMBER</th>
                                        <th>DC DATE</th>
                                        <th>GRAND TOTAL</th>
                                        <th>STATUS</th>
                                        <th width="5%">ACTION</th>
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
    <script src="{{ asset('assets/modules/purchaseTransfers.js') }}"></script>
@endpush
