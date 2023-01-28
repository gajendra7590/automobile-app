 <table class="table table-bordered">
     <tr>
         <th>ACCOUNT ID</th>
         <td> {{ isset($data['account']['account_uuid']) ? $data['account']['account_uuid'] : '--' }}</td>
     </tr>
     <tr>
         <th>TITLE</th>
         <td>{{ isset($data['transaction_name']) ? $data['transaction_name'] : '--' }}</td>
         <th>TRANSACTION ID</th>
         <td>{{ isset($data['id']) ? leadingZero($data['id'], 6) : '--' }}</td>
     </tr>
     <tr>
         <th>AMOUNT PAID</th>
         <td>
             <span class="badge bg-green" style="padding:6px !important;">
                 {{ isset($data['transaction_amount']) ? priceFormate($data['transaction_amount']) : '0.00' }}
             </span>
         </td>
         <th>PAID DATE</th>
         <td>{{ isset($data['transaction_paid_date']) & !empty($data['transaction_paid_date']) ? date('Y-m-d', strtotime($data['transaction_paid_date'])) : '--' }}
         </td>
     </tr>
     <tr>
         <th>PAID SOURCE</th>
         <td>{{ isset($data['transaction_paid_source']) ? $data['transaction_paid_source'] : '--' }}</td>
         <th>PAID NOTE</th>
         <td>{{ isset($data['transaction_paid_source_note']) ? $data['transaction_paid_source_note'] : '--' }}</td>
     </tr>
     <tr>
         <th>PAYMENT COLLECTED</th>
         <td>{{ isset($data['user']['name']) ? $data['user']['name'] : '--' }}</td>
         <th>INSTALLMENT REF ID</th>
         <td>{{ isset($data['installment']['installment_uuid']) ? $data['installment']['installment_uuid'] : '--' }}
         </td>
     </tr>
     <tr>
         <th>STATUS</th>
         <td>
             @isset($data['status'])
                 @if ($data['status'] == '0')
                     <span class="badge bg-red">Pending</span>
                 @else
                     <span class="badge bg-green">Paid</span>
                 @endif
             @endisset
         </td>
         <th>TRANSACTION FOR</th>
         <td>
             @isset($data['transaction_for'])
                 @if ($data['transaction_for'] == '1')
                     CASH PAYMENT
                 @elseif ($data['transaction_for'] == '2')
                     BANK FINANCE
                 @else
                     PERSONAL FINANACE
                 @endif
             @endisset
         </td>
     </tr>
     <tr>
         <td colspan="4"></td>
     </tr>
 </table>
