<table class="table table-bordered myCustomTable">
    <thead>
        <tr>
            <th width="10%">TRX ID</th>
            <th width="35%">TRANSACTION NAME</th>
            <th width="12%">PAID AMOUNT</th>
            <th width="10%">SOURCE</th>
            <th width="10%">DATE</th>
            <th>PAID NOTE</th>
        </tr>
    </thead>
    @php
        $totalPaid = 0;
    @endphp
    <tbody>
        @if (isset($transactions) && count($transactions) > 0)
            @foreach ($transactions as $transaction)
                @php $totalPaid += $transaction->transaction_amount; @endphp
                <tr>
                    <td>{{ isset($transaction->id) ? leadingZero($transaction->id, 6) : '---' }}</td>
                    <td>{{ isset($transaction->transaction_name) ? $transaction->transaction_name : '---' }}</td>
                    <td>{!! isset($transaction->transaction_amount)
                        ? convertBadgesPrice($transaction->transaction_amount, 'success')
                        : '---' !!}
                    </td>
                    <td>{{ isset($transaction->transaction_paid_source) ? $transaction->transaction_paid_source : '---' }}
                    </td>
                    <td>{{ isset($transaction->transaction_paid_date) ? $transaction->transaction_paid_date : '---' }}
                    </td>
                    <td>{{ isset($transaction->transaction_paid_source_note) ? $transaction->transaction_paid_source_note : '---' }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">No Record Found.</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        @if (isset($transactions) && count($transactions) > 0)
            <tr>
                <th colspan="2">GRAND TOTAL</th>
                <th colspan="4">{!! convertBadgesPrice($totalPaid, 'warning') !!}</th>
            </tr>
        @endif
    </tfoot>

</table>
