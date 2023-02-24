@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Quotations
                <small>List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Quotations list</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Quotations List</h3>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('quotations.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> CREATE QUOTATION
                                </a>
                            </div>
                            <div class="pull-right custom-fitler-container">
                                <div class="filter-options pull-left">
                                    <div class="row">
                                        <div class="col-xs-4 cust_col">
                                            <select class="form-control ajaxDtFilter" name="branch_id" data-col_index="1">
                                                <option value="">---BRANCH NAME---</option>
                                                @if (isset($branches))
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-xs-4 cust_col">
                                            <select class="form-control ajaxDtFilter" name="transfer_status"
                                                data-col_index="5">
                                                <option value="">---PAY TYPE---</option>
                                                @if (isset($payTypes))
                                                    @foreach ($payTypes as $k => $payType)
                                                        <option value="{{ $k }}">{{ $payType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-xs-4 cust_col">
                                            <select class="form-control ajaxDtFilter" name="status" data-col_index="8">
                                                <option value="">---SELECT STATUS---</option>
                                                <option value="open">OPEN</option>
                                                <option value="close">CLOSED</option>
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
                            <table id="ajaxDataTable" data-url="{{ route('quotations.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="12%">BRANCH NAME</th>
                                        <th width="12%">NAME</th>
                                        <th width="13%">PHONE</th>
                                        <th width="15%">BRAND & MODEL</th>
                                        <th width="12%">PAY TYPE</th>
                                        <th width="12%">TOTAL AMOUNT</th>
                                        <th width="8%">Q DATE</th>
                                        <th width="4%">STATUS</th>
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
    <script src="{{ asset('assets/modules/quotations.js') }}"></script>
@endpush
