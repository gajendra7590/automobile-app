 <div class="box box-danger">
     <div class="box-header with-border">
         <h3 class="box-title pull-left">CASH PAYMENT HISTORY</h3>
         <div class="pull-right">
             @if (isset($salesAccountData) &&
                     !in_array($salesAccountData->due_payment_source, [2, 3]) &&
                     $salesAccountData->status == 0)
                 <a href="{{ route('salesBankFinanace.create') }}?id={{ isset($salesAccountId) ? $salesAccountId : 0 }}"
                     class="btn btn-sm btn-info ajaxModalPopup" data-modal_size="modal-lg"
                     data-modal_title="SETUP BANK FINANCE ACCOUNT">
                     CREATE BANK FINANCE ACCOUNT
                 </a>
                 <a href="{{ route('salesPersonalFinanace.create') }}?id={{ isset($salesAccountId) ? $salesAccountId : 0 }}"
                     class="btn btn-sm btn-warning ajaxModalPopup" data-modal_size="modal-lg"
                     data-modal_title="SETUP PERSONAL FINANCE ACCOUNT">
                     CREATE PERSONAL FINANCE ACCOUNT
                 </a>
             @endif

             @if (isset($salesAccountData) && $salesAccountData->cash_status == 0)
                 <a href="{{ route('salesCash.create') }}?id={{ isset($salesAccountId) ? $salesAccountId : 0 }}"
                     class="btn btn-sm btn-primary ajaxModalPopup" data-modal_size="modal-lg"
                     data-modal_title="RECEIVE NEW PAYMENT">
                     RECEIVE NEW PAYMENT
                 </a>
             @endif

             @if (isset($salesAccountData) && $salesAccountData->status == 0)
                 <a href="{{ route('addChargesIndex', ['id' => isset($salesAccountId) ? $salesAccountId : 0]) }}"
                     class="btn btn-sm btn-success ajaxModalPopup" data-modal_size="modal-lg"
                     data-modal_title="ADD CHARGES">
                     ADD CHARGES
                 </a>
             @endif


         </div>
     </div>
     <!-- /.box-header -->
     <div class="box-body no-padding">
         <table class="table table-bordered myCustomTable">
             <thead>
                 <tr>
                     <th>#</th>
                     <th width="20%">PAYMENT NAME</th>
                     <th>CREDIT BALANCE</th>
                     <th>DEBIT BALANCE</th>
                     <th>DUE DATE</th>
                     <th>FIRST DP</th>
                     <th>PAID SOURCE</th>
                     <th width="10%">PAID DATE</th>
                     <th>STATUS</th>
                     <th width="5%">ACTION</th>
                 </tr>
             </thead>
             <tbody>
                 @if (isset($data) && count($data) > 0)
                     @isset($data)
                         @foreach ($data as $k => $cashPayment)
                             <tr>
                                 <td>{{ isset($cashPayment['id']) ? $cashPayment['id'] : '--' }}</td>
                                 <td>{{ isset($cashPayment['payment_name']) ? $cashPayment['payment_name'] : '' }}</td>
                                 <td>{{ priceFormate($cashPayment['credit_amount']) }} </td>
                                 <td>{{ priceFormate($cashPayment['debit_amount']) }} </td>
                                 <td>{{ isset($cashPayment['due_date']) && !empty($cashPayment['due_date']) ? date('d/m/Y', strtotime($cashPayment['due_date'])) : '--' }}
                                 </td>
                                 <td>{{ $cashPayment['is_dp'] == '1' ? 'YES' : 'NO' }} </td>
                                 <td>{{ isset($cashPayment['paid_source']) ? $cashPayment['paid_source'] : '--' }}</td>
                                 <td>{{ isset($cashPayment['paid_date']) && !empty($cashPayment['paid_date']) ? date('d/m/Y', strtotime($cashPayment['paid_date'])) : '--' }}
                                 </td>
                                 <td>
                                     @if ($cashPayment['status'] == '0')
                                         <span title="Payment Not Done" class="label label-danger"
                                             style="padding: 5px 8px;">
                                             <i class="fa fa-times" aria-hidden="true"></i>
                                         </span>
                                     @else
                                         <span title="Payment Done" class="label label-success" style="padding: 5px 8px;">
                                             <i class="fa fa-check" aria-hidden="true"></i>
                                         </span>
                                     @endif
                                 </td>
                                 <td>
                                     @if ($cashPayment['paid_source'] != '' && $cashPayment['paid_source'] != 'Auto')
                                         <div class="dropdown pull-right customDropDownOption">
                                             <button class="btn btn-xs btn-primary dropdown-toggle" type="button"
                                                 data-toggle="dropdown" style="padding: 3px 10px !important;">
                                                 <span class="caret"></span>
                                             </button>

                                             <ul class="dropdown-menu">
                                                 <li>
                                                     <a href="{{ route('salesCash.show', ['salesCash' => isset($cashPayment['id']) ? $cashPayment['id'] : 0]) }}"
                                                         class="ajaxModalPopup" data-modal_size="modal-lg"
                                                         data-modal_title="VIEW CASH PAYMENT DETAIL">
                                                         VIEW DETAIL
                                                     </a>
                                                 </li>
                                                 @if ($cashPayment['status'] == '1')
                                                     <li>
                                                         <a href="{{ route('salesCashReceipt', ['id' => isset($cashPayment['id']) ? base64_encode($cashPayment['id']) : 0]) }}"
                                                             class="" target="_blank">
                                                             PRINT RECIEPT
                                                         </a>
                                                     </li>
                                                 @endif

                                                 @if ($cashPayment['is_dp'] != '1' && $cashPayment['paid_source'] != 'Auto')
                                                     <li>
                                                         <a href="{{ route('salesCash.edit', ['salesCash' => $cashPayment['id']]) }}"
                                                             class="ajaxModalPopup" data-modal_title="Update Payment Detail"
                                                             data-modal_size="modal-lg">
                                                             UPDATE
                                                         </a>
                                                     </li>
                                                 @endif
                                             </ul>
                                         </div>
                                     @endif
                                 </td>
                             </tr>
                         @endforeach
                     @endisset
             <tfoot>
                 <tr>
                     <td>TOTAL CREDIT</td>
                     <td>{!! convertBadgesPrice(isset($credit_amount) ? $credit_amount : 0.0, 'primary') !!}</td>
                     <td>TOTAL DEBIT</td>
                     <td>{!! convertBadgesPrice(isset($debit_amount) ? $debit_amount : 0.0, 'success') !!}</td>
                     <td>TOTAL PAID BY CUSTOMER</td>
                     <td>{!! convertBadgesPrice(isset($paid_by_amount) ? $paid_by_amount : 0.0, 'warning') !!}</td>
                     <td>TOTAL DUE</td>
                     <td colspan="3">{!! convertBadgesPrice(isset($due_amount) ? $due_amount : 0.0, 'danger') !!}</td>
                 </tr>
             </tfoot>
         @else
             <tr style="font-size: 17px; color: red;text-align: center;">
                 <td colspan="11">No data available in table.</td>
             </tr>
             @endif
             </tbody>
         </table>

         @if (isset($salesAccountData) && $salesAccountData->cash_status == '1')
             <p class="account_status_note"><b>Note :</b> All dues paid by customer so cash account has been closed.</p>
         @endif
     </div>
 </div>
