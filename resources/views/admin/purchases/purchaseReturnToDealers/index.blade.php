@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small>Purchase Return To Dealers</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Purchase Return To Dealers</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List Of All Purchase Return To Dealers</h3>
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
                                            <select class="form-control ajaxDtFilter" name="status" data-col_index="8">
                                                <option value="">---STOCK STATUS---</option>
                                                <option value="2">SOLD OUT</option>
                                                <option value="1">IN STOCK</option>
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
                            <table id="ajaxDataTable" class="table table-bordered table-hover myCustomTable"
                                data-url="{{ route('purchaseReturnToDealers.index') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BRANCH</th>
                                        <th width="18%">BIKE INFO</th>
                                        <th>SKU</th>
                                        {{-- <th>VARIANT CODE</th> --}}
                                        <th>DC NUMBER</th>
                                        <th>DC DATE</th>
                                        <th>GRAND TOTAL</th>
                                        <th>TRANSFER</th>
                                        <th>STATUS</th>
                                        <th>RETURN DATE</th>
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
    <script src="{{ asset('assets/modules/purchase-returns-to-dealers.js') }}"></script>
@endpush
