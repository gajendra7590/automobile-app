@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Sale
                <small>Accounts List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sales Accounts List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Sales Accounts List</h3>
                            </div>
                            {{-- <div class="pull-right">
                                <a href="{{ route('saleAccounts.create') }}" class="btn btn-sm btn-primary ajaxModalPopup"
                                    data-modal_title="Create New Sales Account" data-modal_size="modal-lg">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> CREATE
                                </a>
                            </div> --}}
                            <div class="pull-right custom-fitler-container">
                                <div class="filter-options pull-left">
                                    <div class="row">
                                        <div class="col-xs-6 cust_col">
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
                                        <div class="col-xs-6 cust_col">
                                            <select class="form-control ajaxDtFilter" name="transfer_status"
                                                data-col_index="5">
                                                <option value="">---ACCOUNT STATUS---</option>
                                                <option value="0">DUE/OPEN</option>
                                                <option value="1">CLOSED</option>
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
                            <table id="ajaxDataTable" data-url="{{ route('saleAccounts.index') }}"
                                class="table table-bordered table-hover myCustomTable">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>BRANCH NAME</th>
                                        <th>ACCOUNT NAME</th>
                                        <th>SALES PRICE</th>
                                        <th>CREATED DATE</th>
                                        <th width="6%">STATUS</th>
                                        <th width="5%">ACTION</th>
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
    <script src="{{ asset('assets/modules/sales-accounts.js') }}"></script>
@endpush
