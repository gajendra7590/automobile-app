 <div class="box box-danger">
     <div class="box-header with-border">
         <h3 class="box-title pull-left">CASH PAYMENT HISTORY</h3>
         <div class="pull-right">
             <a href="" class="btn btn-sm btn-primary">CREATE BANK FINANCE</a>
             <a href="" class="btn btn-sm btn-primary">CREATE PERSONAL FINANCE</a>
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
                     <th>CHANGE BALANCE</th>
                     <th>DUE DATE</th>
                     <th>PAID SOURCE</th>
                     <th width="10%">PAID DATE</th>
                     <th>TYPE</th>
                     <th>STATUS</th>
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
                                 <td>{{ priceFormate($cashPayment['change_balance']) }} </td>
                                 <td>{{ isset($cashPayment['due_date']) && !empty($cashPayment['due_date']) ? date('d/m/Y', strtotime($cashPayment['due_date'])) : '--' }}
                                 </td>
                                 <td>{{ isset($cashPayment['paid_source']) ? $cashPayment['paid_source'] : '--' }}</td>
                                 <td>{{ isset($cashPayment['paid_date']) && !empty($cashPayment['paid_date']) ? date('d/m/Y', strtotime($cashPayment['paid_date'])) : '--' }}
                                 </td>
                                 <td>
                                     @if ($cashPayment['trans_type'] == '1')
                                         <span title="Payment Not Done" class="label label-success"
                                             style="padding: 5px 8px;">
                                             CREDIT
                                         </span>
                                     @else
                                         <span title="Payment Done" class="label label-danger" style="padding: 5px 8px;">
                                             DEBIT
                                         </span>
                                     @endif
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
             </tbody>
         </table>
     </div>
 </div>
