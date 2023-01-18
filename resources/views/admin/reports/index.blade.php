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
                                <a href="{{ route('loadReportSection') }}?type=purchase" id="current_active" class="loadeReport">
                                    PURCHASE
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=sales" class="loadeReport">
                                    SALES REPORT
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=quotations" class="loadeReport">
                                    QUOTATIONS REPORT
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=dues" class="loadeReport">
                                    DUE REPORTS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loadReportSection') }}?type=rto" class="loadeReport">
                                    RTO REPORT
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="reportContainer">
                                PURCHASE REPORT
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/reports.js') }}"></script>
@endpush
