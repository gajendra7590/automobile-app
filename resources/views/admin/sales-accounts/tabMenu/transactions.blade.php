<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">TRANSACTIONS</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-bordered myCustomTable">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>TRANSACTION NAME</th>
                    <th width="15%">TRANSACTION AMOUNT</th>
                    <th width="10%">PAID SOURCE</th>
                    <th width="8%">PAID DATE</th>
                    <th width="12%">TRANSACTION TYPE</th>
                    <th width="5%">STATUS</th>
                    <th width="5%">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @isset($data)
                    @if (count($data))
                        @foreach ($data as $k => $transaction)
                            <tr>
                                <td>{{ isset($transaction['id']) ? $transaction['id'] : '--' }}</td>
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
                                    @if (isset($transaction['transaction_paid_source']) && $transaction['transaction_paid_source'] != 'Auto')
                                        <a href="{{ route('transactions.show', ['id' => $transaction['id']]) }}"
                                            class="btn btn-primary btn-sm ajaxModalPopup"
                                            data-modal_title="VIEW TRANSCTION DETAIL" data-modal_size="modal-lg">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    @endif
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
