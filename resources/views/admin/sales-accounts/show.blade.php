@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Sales Account
                <small>History</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sales Account History</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <!-- TAB Options -->
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a class="loadAjaxAccountTab" id="primaryTab"
                                    href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=accountDetail' }}">ACCOUNT
                                    BASIC SUMMARY</a>
                            </li>
                            @if (isset($data->payment_setup) && $data->payment_setup == '1')
                                <li class="">
                                    <a class="loadAjaxAccountTab"
                                        href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=cashPaymentHistory' }}">CASH
                                        PAYMENT HISTORY</a>
                                </li>
                                @if (isset($data->due_payment_source) && $data->due_payment_source == '2')
                                    <li class="">
                                        <a class="loadAjaxAccountTab"
                                            href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=bankFinanceHistory' }}">BANK
                                            FINANCE HISTORY</a>
                                    </li>
                                @endif
                                @if (isset($data->due_payment_source) && $data->due_payment_source == '3')
                                    <li class="">
                                        <a class="loadAjaxAccountTab"
                                            href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=personalFinanceHistory' }}">PERSONAL
                                            FINANACE
                                            HISTORY</a>
                                    </li>
                                @endif
                                <li class="">
                                    <a class="loadAjaxAccountTab"
                                        href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=transactions' }}">ALL
                                        TRANSACTIONS</a>
                                </li>
                            @endif
                            <li class="">
                                <a class="loadAjaxAccountTab"
                                    href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=customerDetail' }}">CUSTOMER
                                    DETAIL</a>
                            </li>
                            <li class="">
                                <a class="loadAjaxAccountTab"
                                    href="{{ route('getPaymentTabs', ['id' => isset($data['id']) ? $data['id'] : '0']) . '?tab=purchaseDetail' }}">PURCHASE
                                    HISTORY</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- ACCOUNT - TAB -->
                            <div class="tab-pane active" id="tabContainer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/salesAccountDetail.js') }}"></script>
@endpush
