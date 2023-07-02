@extends('admin.layouts.admin-layout')
@section('container')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Report Management
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Report Management</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="{{ route('loadReportSection') }}?type=vehicle_purchase_register"
                                    id="current_active" class="loadeReport">
                                    VEHICLE PURCHASES REGISTER
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=pending_purchase_invoice"
                                    class="loadeReport">
                                    PENDING PURCHASES INVOICE
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=vehicle_stock_inventory" class="loadeReport">
                                    VEHICLE STOKE INVENTORY
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=quotation_list" class="loadeReport">
                                    QUOTATION LIST
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=sales_register" class="loadeReport">
                                    SALES REGISTER
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=brokers_agents" class="loadeReport">
                                    BROKERS / AGENTS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=financers" class="loadeReport">
                                    FINANCERS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=rto" class="loadeReport">
                                    RTO
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=accounts" class="loadeReport">
                                    PAYMENT ACCOUNTS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=customer_wise_payment" class="loadeReport">
                                    CUSTOMER WISE PAYMENT
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=financer_wise_payment" class="loadeReport">
                                    FINANCER WISE PAYMENT
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=rto_agent_payment" class="loadeReport">
                                    RTO AGENTS PAYMENTS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=cust_trans_report" class="loadeReport">
                                    CUSTOMER TRANSACTION REPORT
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('loadReportSection') }}?type=receipt_voucher" class="loadeReport">
                                    RECEIPT VOUCHER
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=payment_voucher" class="loadeReport">
                                    PAYMENT VOUCHER
                                </a>
                            </li> --}}
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="reportContainer">
                                PURCHASE REPORT
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/reports.js') }}"></script>
@endpush
