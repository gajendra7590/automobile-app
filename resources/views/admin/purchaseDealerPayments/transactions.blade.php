 <table class="table table-bordered" id="dealerTransTable">
     <caption>DEALER NAME : <b>{{ isset($dealer_name) ? strtoupper($dealer_name) : '' }}</b></caption>
     <thead>
         <tr>
             <th>#</th>
             <th>TRANS TYPE</th>
             <th>PAID FROM ACCOUNT</th>
             <th>AMOUNT</th>
             <th>PAY MODE</th>
             <th>DATE</th>
             <th>PAYMENT NOTE</th>
         </tr>
     </thead>
     <tbody>
         @if (isset($transactions) && count($transactions) > 0)
             @foreach ($transactions as $transaction)
                 <tr>
                     <td>
                         {{ isset($transaction->id) ? $transaction->id : '---' }}
                     </td>
                     <td>
                         {{ isset($transaction->transaction_type) && $transaction->transaction_type == '1' ? 'DEBIT' : 'CREDIT' }}
                     </td>
                     <td>
                         {{ isset($transaction->bankAccount->bank_account_holder_name) ? $transaction->bankAccount->bank_account_holder_name . ' - ' . $transaction->bankAccount->bank_account_number : '---' }}
                     </td>
                     <td>
                         @if (isset($transaction->transaction_type) && $transaction->transaction_type == '1')
                             {!! isset($transaction->debit_amount) ? convertBadgesPrice($transaction->debit_amount, 'danger') : '--' !!}
                         @else
                             {!! isset($transaction->credit_amount) ? convertBadgesPrice($transaction->credit_amount, 'success') : '--' !!}
                         @endif
                     </td>
                     <td>{{ isset($transaction->payment_mode) ? $transaction->payment_mode : '--' }}</td>
                     <td>{{ isset($transaction->payment_date) ? date('Y-m-d', strtotime($transaction->payment_date)) : '--' }}
                     </td>
                     <td>{{ isset($transaction->payment_note) ? $transaction->payment_note : '--' }}</td>
                 </tr>
             @endforeach
         @else
             <tr>
                 <td colspan="6"><span class="text-danget">NO TRANSACTIONS FOUND FOR DEALER.</span></td>
             </tr>
         @endif
     </tbody>
 </table>

 <script>
     $(document).ready(function() {
         $('#dealerTransTable').DataTable({
             paging: true,
             searching: true,
             columnDefs: [{
                 orderable: false,
                 targets: [1, 2, 3, 4, 6],
             }]
         })
     });
 </script>
