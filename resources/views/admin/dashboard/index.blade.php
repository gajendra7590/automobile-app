@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ isset($data['totalPurchases']) ? $data['totalPurchases'] : '0' }}</h3>
                            <p>TOTAL PURCHASES</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-bicycle"></i>
                        </div>
                        <a href="{{ route('purchases.index') }}" target="_blank" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ isset($data['totalQuotations']) ? $data['totalQuotations'] : '0' }}</h3>
                            <p>TOTAL QUOTATIONS</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-book"></i>
                        </div>
                        <a href="{{ route('quotations.index') }}" target="_blank" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ isset($data['totalSales']) ? $data['totalSales'] : '0' }}</h3>
                            <p>TOTAL SALE</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-bicycle"></i>
                        </div>
                        <a href="{{ route('sales.index') }}" target="_blank" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ isset($data['totalRtoRegistrations']) ? $data['totalRtoRegistrations'] : '0' }}</h3>
                            <p>TOTAL RTO REGISTRATION</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-body"></i>
                        </div>
                        <a href="{{ route('rto-agents.index') }}" target="_blank" class="small-box-footer">More info <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua" style="height: 101px"><i class="fa fa-motorcycle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">IN STOCK</span>
                            <span class="info-box-number">
                                {{ isset($data['totalPurchasesInStock']) ? $data['totalPurchasesInStock'] : '0' }}
                            </span>

                            <span class="info-box-text">SOLD OUT</span>
                            <span class="info-box-number">
                                {{ isset($data['totalPurchasesSoldOut']) ? $data['totalPurchasesSoldOut'] : '0' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green" style="height: 101px"><i class="fa fa-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">OPEN</span>
                            <span class="info-box-number">
                                {{ isset($data['totalSalesOpen']) ? $data['totalSalesOpen'] : '0' }}
                            </span>

                            <span class="info-box-text">CLOSED</span>
                            <span class="info-box-number">
                                {{ isset($data['totalSalesClose']) ? $data['totalSalesClose'] : '0' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow" style="height: 101px"><i class="fa fa-motorcycle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">OPEN</span>
                            <span class="info-box-number">
                                {{ isset($data['totalQuotationOpen']) ? $data['totalQuotationOpen'] : '0' }}
                            </span>

                            <span class="info-box-text">CLOSED</span>
                            <span class="info-box-number">
                                {{ isset($data['totalQuotationClose']) ? $data['totalQuotationClose'] : '0' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red" style="height: 101px"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">FILED</span>
                            <span class="info-box-number">
                                {{ isset($data['totalRegistionDone']) ? $data['totalRegistionDone'] : '0' }}
                            </span>

                            <span class="info-box-text">PENDING FILED</span>
                            <span class="info-box-number">
                                {{ isset($data['totalRegistionPending']) ? $data['totalRegistionPending'] : '0' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
