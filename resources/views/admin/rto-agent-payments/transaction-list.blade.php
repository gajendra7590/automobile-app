@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                AGENTS PAYMENTS
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">AGENTS PAYMENTS</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">AGENTS PAYMENTS</h3>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <caption>
                                    <div class="col-md-9">
                                        <div class="pull-left">AGENT NAME :
                                            <b>{{ isset($agent_name) ? strtoupper($agent_name) : '' }}</b>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="pull-right">
                                            <a href="{{ route('rtoAgentPayments.index') }}" class="btn btn-sm btn-warning">
                                                BACK
                                            </a>
                                            <a href="{{ route('rtoAgentPayments.create') }}?agent_id={{ $agent_id }}"
                                                class="btn btn-sm btn-success ajaxModalPopup"
                                                data-modal_title="CREATE NEW PAYMENT" data-title="CREATE NEW PAYMENT"
                                                data-modal_size="modal-lg">
                                                CREATE NEW PAYMENT
                                            </a>
                                        </div>
                                    </div>
                                </caption>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="18%">PAYMENT AMOUNT</th>
                                        <th>PAYMENT MODE</th>
                                        <th>PAYMENT DATE</th>
                                        <th>PAYMENT NOTE</th>
                                        <th>LOG DATE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($transactions))
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ isset($transaction->id) ? $transaction->id : '--' }}</td>
                                                <td>{!! isset($transaction->payment_amount) ? convertBadgesPrice($transaction->payment_amount, 'success') : '--' !!}
                                                </td>
                                                <td>{{ isset($transaction->payment_mode) ? $transaction->payment_mode : '--' }}
                                                </td>
                                                <td>{{ isset($transaction->payment_date) ? date('Y-m-d', strtotime($transaction->payment_date)) : '--' }}
                                                </td>
                                                <td>{{ isset($transaction->payment_note) ? $transaction->payment_note : '--' }}
                                                </td>
                                                <td>{{ isset($transaction->created_at) ? date('Y-m-d', strtotime($transaction->created_at)) : '--' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('rtoAgentPayments.edit', ['rtoAgentPayment' => $transaction->id]) }}"
                                                        class="btn btn-sm btn-primary ajaxModalPopup"
                                                        data-modal_title="EDIT PAYMENT DETAIL"
                                                        data-title="EDIT PAYMENT DETAIL" data-modal_size="modal-lg">
                                                        EDIT
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6"><span class="text-danget">NO RECORD FOUND.</span></td>
                                        </tr>
                                    @endif
                                </tbody>
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
    <script src="{{ asset('assets/modules/rtoAgentPayments.js') }}"></script>
@endpush
