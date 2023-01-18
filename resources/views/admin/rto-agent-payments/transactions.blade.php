 <table class="table table-bordered">
     <caption>AGENT NAME : <b>{{ isset($agent_name) ? strtoupper($agent_name) : '' }}</b></caption>
     <thead>
         <tr>
             <th>#</th>
             <th width="18%">PAYMENT AMOUNT</th>
             <th>PAYMENT MODE</th>
             <th>PAYMENT DATE</th>
             <th>PAYMENT NOTE</th>
             <th>LOG DATE</th>
         </tr>
     </thead>
     <tbody>
         @if (isset($transactions))
             @foreach ($transactions as $transaction)
                 <tr>
                     <td>{{ isset($transaction->id) ? $transaction->id : '--' }}</td>
                     <td>{!! isset($transaction->payment_amount) ? convertBadgesPrice($transaction->payment_amount, 'success') : '--' !!}
                     </td>
                     <td>{{ isset($transaction->payment_mode) ? $transaction->payment_mode : '--' }}</td>
                     <td>{{ isset($transaction->payment_date) ? date('Y-m-d', strtotime($transaction->payment_date)) : '--' }}
                     </td>
                     <td>{{ isset($transaction->payment_note) ? $transaction->payment_note : '--' }}</td>
                     <td>{{ isset($transaction->created_at) ? date('Y-m-d', strtotime($transaction->created_at)) : '--' }}
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
