     <div class="box">
         <div class="box-body">
             <table class="table table-bordered">
                 <tr>
                     <th>ACCOUNT ID</th>
                     <td> {{ isset($data['account']['account_uuid']) ? $data['account']['account_uuid'] : '--' }}</td>
                 </tr>
                 <tr>
                     <th>TITLE</th>
                     <td>{{ isset($data['emi_title']) ? $data['emi_title'] : '--' }}</td>
                     <th>DUE PAYMENT ID</th>
                     <td>{{ $data['installment_uuid'] }}</td>
                 </tr>
                 <tr>
                     <th>TOTAL INSTALLMENT AMOUNT</th>
                     <td>
                         <span class="badge bg-red" style="padding:6px !important;">
                             {{ isset($data['emi_due_amount']) ? priceFormate($data['emi_due_amount']) : '0.00' }}
                         </span>
                     </td>
                     <th>DUE DATE</th>
                     <td>{{ isset($data['emi_due_date']) & !empty($data['emi_due_date']) ? date('Y-m-d', strtotime($data['emi_due_date'])) : '--' }}
                     </td>
                 </tr>
                 @if (isset($data['account']['due_payment_source']) && $data['account']['due_payment_source'] == '3')
                     <tr>
                         <th>PRINCIPAL AMOUNT</th>
                         <td> {{ priceFormate($data['emi_due_principal']) }} </td>
                         <th>INTREST AMOUNT</th>
                         <td> {{ priceFormate($data['emi_due_intrest']) }} </td>
                     </tr>
                 @endif
                 <tr>
                     <th>PAID AMOUNT</th>
                     <td>
                         <span class="badge bg-green" style="padding:6px !important;">
                             {{ isset($data['amount_paid']) && !empty($data['amount_paid']) ? priceFormate($data['amount_paid']) : '0.00' }}
                         </span>
                     </td>
                     <th>PAID DATE</th>
                     <td>{{ isset($data['amount_paid_date']) && !empty($data['amount_paid_date']) ? date('Y-m-d', strtotime($data['amount_paid_date'])) : '--' }}
                     </td>
                 </tr>
                 <tr>
                     <th>PAID SOURCE</th>
                     <td>{{ isset($data['amount_paid_source']) ? $data['amount_paid_source'] : '--' }}</td>
                     <th>PAID NOTE</th>
                     <td>{{ isset($data['amount_paid_note']) ? $data['amount_paid_note'] : '--' }}</td>
                 </tr>
                 <tr>
                     <th>STATUS</th>
                     <td>
                         @isset($data['status'])
                             @if ($data['status'] == '0')
                                 <span class="badge bg-red">Open</span>
                             @else
                                 <span class="badge bg-green">Close</span>
                             @endif
                         @endisset
                     </td>
                     <th>PAID DUE AMOUNT(+/-)</th>
                     <td>{{ isset($data['pay_due']) ? priceFormate($data['pay_due']) : '0.00' }}</td>
                 </tr>
                 <tr>
                     <td colspan="4">Note : If you will pay more then installment amount it will adjust in next
                         installment.</td>
                 </tr>
             </table>
         </div>
     </div>
