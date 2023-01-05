@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small>Purchases List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('purchases.index') }}"> {{ isset($title) && $title ? $title : '' }} </a></li>
                <li class="active">Purchase List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> {{ isset($title) && $title ? $title : '' }} List Of All Purchases</h3>
                            <div class="pull-right">
                                <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Purchase
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover"
                                data-url="{{ route('purchases.index') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BRANCH</th>
                                        <th width="18%">BIKE INFO</th>
                                        <th>INVOICE NUMBER</th>
                                        <th>INVOICE DATE</th>
                                        <th>INVOICE AMOUNT</th>
                                        <th>GRAND TOTAL</th>
                                        <th>STATUS</th>
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
    <script src="{{ asset('assets/modules/purchase.js') }}"></script>
@endpush
