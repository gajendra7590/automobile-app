@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Account
                <small>Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Account Detail</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <!-- LEFT SIDE BASIC DETIAL -->
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle"
                                src="{{ asset('assets/dist/img/avatar.png') }}" alt="User profile picture">
                            <h3 class="profile-username text-center">
                                {{ isset($data['sale']['customer_name']) ? ucwords(strtolower($data['sale']['customer_name'])) : '' }}
                            </h3>
                            <p class="text-muted text-center">
                                @isset($data['status'])
                                    @if ($data['status'] == '0')
                                        <span class="badge bg-red">Status - Due</span>
                                    @else
                                        <span class="badge bg-green">Status - Paid</span>
                                    @endif
                                @endisset
                            </p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>SALES PRICE</b>
                                    <span class="label label-success pull-right" style="padding: 7px !important;">
                                        {{ isset($data['sales_total_amount']) ? priceFormate($data['sales_total_amount']) : '--' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>DOWN PAYMENT</b>
                                    <span class="label label-warning pull-right" style="padding: 7px !important;">
                                        {{ isset($data['deposite_amount']) ? priceFormate($data['deposite_amount']) : '--' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>DUE AMOUNT</b>
                                    <span class="label label-danger pull-right" style="padding: 7px !important;">
                                        {{ isset($data['due_amount']) ? priceFormate($data['due_amount']) : '--' }}
                                    </span>
                                </li>

                                <li class="list-group-item">
                                    <b>DUE PAYMENT METHOD</b>
                                    <span class="label label-default pull-right" style="padding: 7px !important;">
                                        {{ isset($data['due_payment_source']) ? duePaySources($data['due_payment_source']) : '--' }}
                                    </span>
                                </li>

                                @if ($data['due_payment_source'] == '3')
                                    <li class="list-group-item">
                                        <b>FINANCIER BANK</b>
                                        <span class="label label-default pull-right" style="padding: 7px !important;">
                                            {{ isset($data['financier']['bank_name']) ? ucwords(strtolower($data['financier']['bank_name'])) : '--' }}
                                        </span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>PROCESSING FEES</b>
                                        <span class="label label-info pull-right" style="padding: 7px !important;">
                                            {{ isset($data['processing_fees']) ? priceFormate($data['processing_fees']) : '--' }}
                                        </span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>TOTAL NUMBER OF EMI</b>
                                        <span class="badge bg-light-blue pull-right" style="padding: 7px 16px !important;">
                                            {{ isset($data['no_of_emis']) ? $data['no_of_emis'] : '--' }}
                                        </span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>RATE OF INTREST</b>
                                        <span class="label label-primary pull-right" style="padding: 7px !important;">
                                            {{ isset($data['rate_of_interest']) ? $data['rate_of_interest'] . '%' : '--' }}
                                        </span>
                                    </li>
                                @endif

                                <li class="list-group-item due_grand_total"
                                    style="background: #dd4b39;padding-left: 5px;color:#fff;">
                                    <div class="due_grand_total">
                                        <b>DUE GRAND TOTAL</b>
                                        <span class="label label-danger pull-right"
                                            style="padding: 3px 8px !important;font-size: 15px;">
                                            {{ isset($data['total_pay_with_intrest']) ? priceFormate($data['total_pay_with_intrest']) : '--' }}
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- RIGHT SIDE TABS -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <!-- TAB Options -->
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#duehistory" data-toggle="tab" aria-expanded="true">DUE HISTORY</a>
                            </li>
                            <li class="">
                                <a href="#transactions" data-toggle="tab" aria-expanded="false">TRANSACTIONS</a>
                            </li>
                            <li class="">
                                <a href="#customerdetail" data-toggle="tab" aria-expanded="false">CUSTOMER DETAIL</a>
                            </li>
                            <li class="">
                                <a href="#purchasedetail" data-toggle="tab" aria-expanded="false">PURCHASE BIKE DETAIL</a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <!-- DUE HISTORY - TAB -->
                            <div class="tab-pane active" id="duehistory">
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title pull-left">DUE/EMI HISTORY</h3>
                                        <div class="pull-right">
                                            <span class="label label-default">
                                                Payment Option :-
                                                {{ isset($data['due_payment_source']) ? duePaySources($data['due_payment_source']) : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table">
                                            <tr>
                                                <th>#</th>
                                                <th width="20%">TITLE</th>
                                                <th>INSTALLMENT AMOUNT</th>
                                                <th>INSTALLMENT DATE</th>
                                                <th>PAYABLE AMOUNT</th>
                                                <th>PAID AMOUNT</th>
                                                <th width="10%">PAID DATE</th>
                                                <th>+/-</th>
                                                <th>STATUS</th>
                                                <th width="10%">ACTION</th>
                                            </tr>
                                            @isset($data['installments'])
                                                @php
                                                    $pending_emi = 0;
                                                @endphp
                                                @foreach ($data['installments'] as $k => $installment)
                                                    @if ($installment->status == '0')
                                                        @php
                                                            $pending_emi++;
                                                        @endphp
                                                    @endif
                                                    <tr>
                                                        <td>{{ $installment->id }}</td>
                                                        <td>{{ $installment->emi_title }}</td>
                                                        <td>
                                                            <span class="label label-primary" style="padding: 5px;">
                                                                {{ priceFormate($installment->emi_due_amount) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ date('d/m/Y', strtotime($installment->emi_due_date)) }}</td>
                                                        <td>
                                                            <span class="label label-danger" style="padding: 5px;">
                                                                {{ priceFormate($installment->emi_due_revised_amount) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if (!empty($installment->amount_paid))
                                                                <span class="label label-success" style="padding: 5px;">
                                                                    {{ priceFormate($installment->amount_paid) }}
                                                                </span>
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                        </td>
                                                        <td>{{ !empty($installment->amount_paid) && $installment->amount_paid > 0 ? date('d/m/Y', strtotime($installment->amount_paid_date)) : '--' }}
                                                        </td>
                                                        <td>{{ !empty($installment->pay_due) ? priceFormate($installment->pay_due) : '--' }}
                                                        </td>
                                                        <td>
                                                            @if ($installment->status == '0')
                                                                <span title="Payment Not Done" class="label label-danger"
                                                                    style="padding: 5px 8px;">
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </span>
                                                            @else
                                                                <span title="Payment Done" class="label label-success"
                                                                    style="padding: 5px 8px;">
                                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($installment->status == '0' && $pending_emi == 1)
                                                                <a href="{{ route('salesDetailModal') }}?type=due-pay-form&id={{ $installment->id }}"
                                                                    class="btn btn-success btn-sm ajaxModalPopup"
                                                                    data-modal_title="Make Due Payment"
                                                                    data-modal_size="modal-lg">
                                                                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                            <a href="{{ route('salesDetailModal') }}?type=due-detail&id={{ $installment->id }}"
                                                                class="btn btn-warning btn-sm ajaxModalPopup"
                                                                data-modal_title="Due Payment Detail"
                                                                data-modal_size="modal-lg">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="10">
                                                        <b>Important Note : </b> Your next installment payment option will
                                                        visible if latest one will be mark as paid.
                                                    </td>
                                                </tr>
                                            @endisset

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- TRANSACTIONS - TAB -->
                            <div class="tab-pane" id="transactions">
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">TRANSACTIONS</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table">
                                            <tr>
                                                <th>#</th>
                                                <th width="25%">TITLE</th>
                                                <th>PAID AMOUNT</th>
                                                <th>PAID DATE</th>
                                                <th>PAID Source</th>
                                                <th width="25%">PAID NOTE</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                            @isset($data['transactions'])
                                                @foreach ($data['transactions'] as $k => $transaction)
                                                    <tr>
                                                        <td>{{ $k + 1 }}</td>
                                                        <td>{{ isset($transaction->transaction_title) ? $transaction->transaction_title : '' }}
                                                        </td>
                                                        <td>
                                                            @if (!empty($transaction->amount_paid))
                                                                <span class="label label-success" style="padding: 5px;">
                                                                    {{ priceFormate($transaction->amount_paid) }}
                                                                </span>
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                        </td>
                                                        <td>{{ !empty($transaction->amount_paid) ? date('d-m-Y', strtotime($transaction->amount_paid_date)) : '--' }}
                                                        </td>
                                                        <td>{{ isset($transaction->amount_paid_source) ? $transaction->amount_paid_source : '' }}
                                                        </td>
                                                        <td>{{ isset($transaction->amount_paid_source_note) ? $transaction->amount_paid_source_note : '' }}
                                                        </td>
                                                        <td>
                                                            @if ($transaction->status == '0')
                                                                <span title="Payment Not Done" class="label label-danger"
                                                                    style="padding: 5px 8px;">
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </span>
                                                            @else
                                                                <span title="Payment Done" class="label label-success"
                                                                    style="padding: 5px 8px;">
                                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('salesDetailModal') }}?type=transaction-detail&id={{ $transaction->id }}"
                                                                class="btn btn-success btn-sm ajaxModalPopup"
                                                                data-modal_title="Transaction Detail"
                                                                data-modal_size="modal-lg">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset

                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>

                            <!-- CUSTOMER DETAIL - TAB -->
                            <div class="tab-pane" id="customerdetail">
                                <div class="box box-success">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">CUSTOMER INFORMATION</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">Customer Name</th>
                                                <td>
                                                    {{ custFullName(isset($data['sale']) ? $data['sale'] : []) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="30%">Customer Address</th>
                                                <td>{{ custFullAddress(isset($data['sale']) ? $data['sale'] : []) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="30%">Customer Phone</th>
                                                <td>{{ isset($data['sale']['customer_mobile_number']) ? $data['sale']['customer_mobile_number'] : '--' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="30%">Customer Email</th>
                                                <td>{{ isset($data['sale']['customer_email_address']) ? $data['sale']['customer_email_address'] : '--' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>

                            <!-- PURCHASE DETAIL - TAB -->
                            <div class="tab-pane" id="purchasedetail">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">PURCHASE BIKE DETAIL</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="20%">BRANCH NAME</th>
                                                <td>
                                                    {{ isset($data['sale']['purchase']['branch']['branch_name']) ? $data['sale']['purchase']['branch']['branch_name'] : '--' }}
                                                </td>
                                                <th width="20%">DEALER NAME</th>
                                                <td>
                                                    {{ isset($data['sale']['purchase']['dealer']['company_name']) ? $data['sale']['purchase']['dealer']['company_name'] : '--' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="20%">BRAND NAME</th>
                                                <td>
                                                    {{ isset($data['sale']['purchase']['brand']['name']) ? $data['sale']['purchase']['brand']['name'] : '--' }}
                                                </td>
                                                <th width="20%">BRAND MODEL NAME</th>
                                                <td>
                                                    {{ isset($data['sale']['purchase']['model']['model_name']) ? $data['sale']['purchase']['model']['model_name'] : '--' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="20%">MODEL COLOR NAME</th>
                                                <td>
                                                    {{ isset($data['sale']['purchase']['modelColor']['color_name']) ? $data['sale']['purchase']['modelColor']['color_name'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">VEHICLE TYPE</th>
                                                <td>
                                                    {{ isset($data['sale']['purchases']['bike_type']) ? $data['sale']['purchases']['bike_type'] : '--' }}
                                                </td>
                                                <th width="20%">FUEL TYPE</th>
                                                <td>
                                                    {{ isset($data['sale']['purchases']['bike_fuel_type']) ? $data['sale']['purchases']['bike_fuel_type'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">BRAKE TYPE</th>
                                                <td>
                                                    {{ isset($data['sale']['purchases']['break_type']) ? $data['sale']['purchases']['break_type'] : '--' }}
                                                </td>
                                                <th width="20%">WHEEL TYPE</th>
                                                <td>
                                                    {{ isset($data['sale']['purchases']['wheel_type']) ? $data['sale']['purchases']['wheel_type'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">VIN NUMBER</th>
                                                <td>
                                                    {{ isset($data['sale']['purchases']['vin_number']) ? $data['sale']['purchases']['vin_number'] : '--' }}
                                                </td>
                                                <th width="20%">VIN PHYSICAL STATUS</th>
                                                <td>
                                                    {{ isset($data['sale']['purchases']['vin_physical_status']) ? $data['sale']['purchases']['vin_physical_status'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">SKU</th>
                                                <td>{{ isset($data['sale']['purchases']['sku']) ? $data['sale']['purchases']['sku'] : '--' }}
                                                </td>

                                                <th width="20%">SKU DESCRIPTION</th>
                                                <td>{{ isset($data['sale']['purchases']['sku_description']) ? $data['sale']['purchases']['sku_description'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">HSN NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['hsn_number']) ? $data['sale']['purchases']['hsn_number'] : '--' }}
                                                </td>

                                                <th width="20%">ENGINE NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['engine_number']) ? $data['sale']['purchases']['engine_number'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">KEY NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['key_number']) ? $data['sale']['purchases']['key_number'] : '--' }}
                                                </td>

                                                <th width="20%">SERVICE BOOK NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['service_book_number']) ? $data['sale']['purchases']['service_book_number'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">BATTERY BRAND</th>
                                                <td>{{ isset($data['sale']['purchases']['batteryBrand']['name']) ? $data['sale']['purchases']['batteryBrand']['name'] : '--' }}
                                                </td>

                                                <th width="20%">BATTERY NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['battery_number']) ? $data['sale']['purchases']['battery_number'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">TYRE BRAND</th>
                                                <td>{{ isset($data['sale']['purchases']['tyreBrand']['name']) ? $data['sale']['purchases']['tyreBrand']['name'] : '--' }}
                                                </td>

                                                <th width="20%">TYRE FRONT NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['tyre_front_number']) ? $data['sale']['purchases']['tyre_front_number'] : '--' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="20%">TYRE REAR NUMBER</th>
                                                <td>{{ isset($data['sale']['purchases']['tyre_rear_number']) ? $data['sale']['purchases']['tyre_rear_number'] : '--' }}
                                                </td>

                                                <th width="20%">BIKE DESCRIPTION</th>
                                                <td>{{ isset($data['sale']['purchases']['bike_description']) ? $data['sale']['purchases']['bike_description'] : '--' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
