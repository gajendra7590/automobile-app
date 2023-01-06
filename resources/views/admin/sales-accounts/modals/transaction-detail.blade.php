     <div class="box">
         <div class="box-body">
             <table class="table table-bordered">
                 <tr>
                     <th>ACCOUNT ID</th>
                     <td> {{ isset($data['account']['account_uuid']) ? $data['account']['account_uuid'] : '--' }}</td>
                 </tr>
                 <tr>
                     <th>TITLE</th>
                     <td>{{ isset($data['transaction_title']) ? $data['transaction_title'] : '--' }}</td>
                     <th>TRANSACTION ID</th>
                     <td>{{ isset($data['transaction_uuid']) ? $data['transaction_uuid'] : '--' }}</td>
                 </tr>
                 <tr>
                     <th>AMOUNT PAID</th>
                     <td>
                         <span class="badge bg-green" style="padding:6px !important;">
                             {{ isset($data['amount_paid']) ? priceFormate($data['amount_paid']) : '0.00' }}
                         </span>
                     </td>
                     <th>PAID DATE</th>
                     <td>{{ isset($data['amount_paid_date']) & !empty($data['amount_paid_date']) ? date('Y-m-d', strtotime($data['amount_paid_date'])) : '--' }}
                     </td>
                 </tr>
                 <tr>
                     <th>PAID SOURCE</th>
                     <td>{{ isset($data['amount_paid_source']) ? $data['amount_paid_source'] : '--' }}</td>
                     <th>PAID NOTE</th>
                     <td>{{ isset($data['amount_paid_source_note']) ? $data['amount_paid_source_note'] : '--' }}</td>
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
                     <th>PAID DUE AMOUNT(+/-)</th>
                     <td>{{ isset($data['pay_due']) ? priceFormate($data['pay_due']) : '0.00' }}</td>
                 </tr>
                 <tr>
                     <td colspan="4"></td>
                 </tr>
             </table>
         </div>
     </div>
