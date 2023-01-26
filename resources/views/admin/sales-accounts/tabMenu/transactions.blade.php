<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">TRANSACTIONS</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-bordered myCustomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="20%">TRANSACTION NAME</th>
                    <th>TRANSACTION AMOUNT</th>
                    <th>PAID SOURCE</th>
                    <th>PAID DATE</th>
                    <th>TRANSACTION TYPE</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @isset($data)
                    @if (count($data))
                        @foreach ($data as $k => $transaction)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>{{ isset($transaction['transaction_name']) ? $transaction['transaction_name'] : '--' }}
                                </td>
                                <td>
                                    @if (!empty($transaction['transaction_amount']))
                                        {{ priceFormate($transaction['transaction_amount']) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>{{ isset($transaction['transaction_paid_source']) ? $transaction['transaction_paid_source'] : '--' }}
                                </td>
                                <td>{{ !empty($transaction['transaction_paid_date']) ? date('d-m-Y', strtotime($transaction['transaction_paid_date'])) : '--' }}
                                </td>
                                <td>
                                    @if ($transaction['trans_type'] == '1')
                                        <span class="label label-success" style="padding: 5px 8px;">
                                            CREDIT
                                        </span>
                                    @else
                                        <span class="label label-danger" style="padding: 5px 8px;">
                                            DEBIT
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction['status'] == '0')
                                        <span title="Payment Not Done" class="label label-danger" style="padding: 5px 8px;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </span>
                                    @else
                                        <span title="Payment Done" class="label label-success" style="padding: 5px 8px;">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('salesDetailModal') }}?type=transaction-detail&id={{ $transaction['id'] }}"
                                        class="btn btn-primary btn-xs ajaxModalPopup" data-modal_title="Transaction Detail"
                                        data-modal_size="modal-lg">
                                        VIEW DETAIL
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No Transaction Found.</td>
                        </tr>
                    @endif
                @endisset

            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
