<table class="table table-bordered">
    <thead>
        <tr>
            <th width="6%">TRX ID</th>
            <th width="35%">TRANSACTION TITLE</th>
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
        @if (isset($transactions))
            @foreach ($transactions as $transaction)
                @php $totalPaid += $transaction->amount_paid; @endphp
                <tr>
                    <td>{{ isset($transaction->id) ? leadingZero($transaction->id, 6) : '---' }}</td>
                    <td>{{ isset($transaction->transaction_title) ? $transaction->transaction_title : '---' }}</td>
                    <td>{!! isset($transaction->amount_paid) ? convertBadgesPrice($transaction->amount_paid, 'success') : '---' !!}
                    </td>
                    <td>{{ isset($transaction->amount_paid_source) ? $transaction->amount_paid_source : '---' }}</td>
                    <td>{{ isset($transaction->amount_paid_date) ? $transaction->amount_paid_date : '---' }}</td>
                    <td>{{ isset($transaction->amount_paid_source_note) ? $transaction->amount_paid_source_note : '---' }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">GRAND TOTAL</th>
            <th>{!! convertBadgesPrice($totalPaid, 'warning') !!}</th>
        </tr>
    </tfoot>

</table>
