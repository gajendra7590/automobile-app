 <div class="box box-danger">
     <div class="box-header with-border">
         <h3 class="box-title pull-left">PERSONAL FINANACE HISTORY</h3>
         <div class="pull-right">
             <span class="label label-default">
                 Payment Option :-
                 {{ isset($data['due_payment_source']) ? duePaySources($data['due_payment_source']) : '' }}
             </span>
         </div>
     </div>
     <!-- /.box-header -->
     <div class="box-body no-padding">
         <table class="table">
             <tr>
                 <th>#</th>
                 <th width="20%">PAYMENT NAME</th>
                 <th>INSTALLMENT AMOUNT</th>
                 <th>INSTALLMENT DATE</th>
                 <th>PAYABLE AMOUNT</th>
                 <th>PAID AMOUNT</th>
                 <th width="10%">PAID DATE</th>
                 <th>+/-</th>
                 <th>STATUS</th>
                 <th width="5%">ACTION</th>
             </tr>

             @if (isset($data) && count($data) > 0)
                 @isset($data)
                     @php
                         $pending_emi = 0;
                     @endphp
                     @foreach ($data as $k => $installment)
                         @if ($installment['status'] == '0')
                             @php
                                 $pending_emi++;
                             @endphp
                         @endif
                         <tr>
                             <td>{{ $installment['id'] }}</td>
                             <td>{{ $installment['payment_name'] }}</td>
                             <td>
                                 <span class="label label-primary" style="padding: 5px;">
                                     {{ priceFormate($installment['emi_total_amount']) }}
                                 </span>
                             </td>
                             <td>{{ date('d/m/Y', strtotime($installment['emi_due_date'])) }}</td>
                             <td>
                                 <span class="label label-danger" style="padding: 5px;">
                                     {{ priceFormate($installment['emi_due_revised_amount']) }}
                                 </span>
                             </td>
                             <td>
                                 @if (!empty($installment['amount_paid']))
                                     <span class="label label-success" style="padding: 5px;">
                                         {{ priceFormate($installment['amount_paid']) }}
                                     </span>
                                 @else
                                     --
                                 @endif
                             </td>
                             </td>
                             <td>{{ !empty($installment['amount_paid']) && $installment['amount_paid'] > 0 ? date('d/m/Y', strtotime($installment['amount_paid_date'])) : '--' }}
                             </td>
                             <td>
                                 @if ($installment['adjust_amount'] == 0)
                                     {{ priceFormate(0) }}
                                 @elseif ($installment['adjust_amount'] > 0)
                                     +{{ priceFormate($installment['adjust_amount']) }}
                                 @else
                                     -{{ priceFormate(-$installment['adjust_amount']) }}
                                 @endif
                             </td>
                             <td>
                                 @if ($installment['status'] == '0')
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
                                 <div class="dropdown pull-right customDropDownOption">
                                     <button class="btn btn-xs btn-primary dropdown-toggle" type="button"
                                         data-toggle="dropdown" style="padding: 3px 10px !important;">
                                         <span class="caret"></span>
                                     </button>
                                     <ul class="dropdown-menu">
                                         @if ($installment['status'] == '0' && $pending_emi == 1)
                                             <li>
                                                 <a href="{{ route('personalFinanacePayIndex', ['id' => $installment['id']]) }}"
                                                     class="ajaxModalPopup" data-modal_title="PAY INSTALLMENT"
                                                     data-modal_size="modal-lg">
                                                     PAY NOW
                                                 </a>
                                             </li>
                                         @endif
                                         <li>
                                             <a href="{{ route('salesPersonalFinanace.show', ['salesPersonalFinanace' => $installment['id']]) }}"
                                                 class="ajaxModalPopup"
                                                 data-modal_title="PERSONAL FINANCE VIEW PAYMENT DETAIL"
                                                 data-modal_size="modal-lg">
                                                 VIEW DETAIL
                                             </a>
                                         </li>
                                         @if ($installment['status'] == '1')
                                             <li>
                                                 <a href="{{ route('printReceiptPF', ['id' => base64_encode($installment['id'])]) }}"
                                                     target="_blank">
                                                     PRINT RECIEPT
                                                 </a>
                                             </li>
                                         @endif
                                     </ul>
                                 </div>
                             </td>
                         </tr>
                     @endforeach
                     <tr>
                         <td colspan="10">
                             <b>Important Note : </b> Your next installment payment option will
                             visible if latest one will be mark as paid.
                         </td>
                     </tr>
                 @endisset
             @else
                 <tr style="font-size: 17px; color: red;text-align: center;">
                     <td colspan="10">No data available in table.</td>
                 </tr>
             @endif

         </table>
     </div>
 </div>
