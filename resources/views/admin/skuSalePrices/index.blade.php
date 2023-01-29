@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                SKU SALES PRICES LIST
                <small>List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">SKU SALES PRICES LIST</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">SKU SALES PRICES LIST</h3>
                            <div class="pull-right">
                                <a href="{{ route('skuSalesPrice.create') }}" class="btn btn-sm btn-primary ajaxModalPopup"
                                    data-modal_title="CREATE NEW SKU SALES PRICE" data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> CREATE
                                </a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" class="table table-bordered table-hover myCustomTable"
                                data-url="{{ route('skuSalesPrice.index') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SKU CODE</th>
                                        <th>EX SHOWROOM PRICE</th>
                                        <th>REGISTRATION AMOUNT</th>
                                        <th>INSURANCE AMOUNT</th>
                                        {{-- <th>HYPOTHECATION AMOUNT</th>
                                        <th>ACCESSORIES AMOUNT</th>
                                        <th>OTHER CHARGES</th> --}}
                                        <th>GRAND TOTAL</th>
                                        <th>STATUS</th>
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
    <script src="{{ asset('assets/modules/skuSalesPrice.js') }}"></script>
@endpush
