@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Colors
                <small>Model colors list</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Colors</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Model colors list</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('colors.create') }}" class="btn btn-sm btn-primary ajaxModalPopup"
                                    data-modal_title="ADD NEW COLORS" data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> ADD
                                </a>
                            </div>
                            <div class="pull-right custom-fitler-container">
                                <div class="filter-options pull-left">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <select class="form-control ajaxDtFilter" name="variant_id" data-col_index="3">
                                                <option value="">---MODEL VARIANT NAME---</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-6">
                                            <select class="form-control ajaxDtFilter ajaxChangeCDropDown" name="bike_model"
                                                data-dep_dd_name="variant_id"
                                                data-url="{{ url('getAjaxDropdown') . '?req=variants' }}" name="model_id"
                                                data-col_index="4">
                                                <option value="">---BRAND MODEL NAME---</option>
                                                @if (isset($models))
                                                    @foreach ($models as $model)
                                                        <option value="{{ $model->id }}">{{ $model->model_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-left">
                                    <a href="#" class="btn btn-sm btn-primary" id="customFitterAction">
                                        <i class="fa fa-filter" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="ajaxDataTable" data-url="{{ route('colors.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Color Name</th>
                                        <th>SKU Code</th>
                                        <th>Model Name(Variant Code)</th>
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
    <script src="{{ asset('assets/modules/colors.js') }}"></script>
@endpush
