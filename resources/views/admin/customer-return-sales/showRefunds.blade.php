<div class="customer-refund-view">
    <div class="refund-table">
        <div class="refund-table-actions" style="margin-bottom: 15px;">
            <a class="btn btn-xs btn-primary" id="createRefundButton">
                <i class="fa fa-plus-circle"></i> CREATE
            </a>
        </div>
        <table class="table table-bordered myCustomTable">
            <thead>
                <tr>
                    <th width="10%">REFUND ID</th>
                    <th width="15%">REFUND AMOUNT</th>
                    <th width="10%">SOURCE</th>
                    <th width="10%">DATE</th>
                    <th width="20%">PERSON NAME</th>
                    <th width="30%">REFUND PAYMENT NOTE</th>
                </tr>
            </thead>
            @php
                $totalRefund = 0;
            @endphp
            <tbody>
                @if (isset($transactions) && count($transactions) > 0)
                    @foreach ($transactions as $transaction)
                        @php $totalRefund += $transaction->amount_refund; @endphp
                        <tr>
                            <td>{{ isset($transaction->id) ? leadingZero($transaction->id, 6) : '---' }}</td>
                            <td>{!! isset($transaction->amount_refund) ? convertBadgesPrice($transaction->amount_refund, 'success') : '---' !!}</td>
                            <td>{{ isset($transaction->amount_refund_source) ? $transaction->amount_refund_source : '---' }}
                            </td>
                            <td>{{ isset($transaction->amount_refund_date) ? $transaction->amount_refund_date : '---' }}
                            </td>
                            <td>{{ isset($transaction->payment_collected_by) ? $transaction->payment_collected_by : '---' }}
                            </td>
                            <td>{{ isset($transaction->payment_refund_note) ? $transaction->payment_refund_note : '---' }}
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
                        <th>REFUNDED</th>
                        <td>{!! convertBadgesPrice($total_refund, 'success') !!}</td>
                        <th>TOTAL PAID</th>
                        <td>{!! convertBadgesPrice($total_paid, 'primary') !!}</td>
                        <th>TOTAL REFUND DUE</th>
                        <td>{!! convertBadgesPrice($total_refundable, 'danger') !!}</td>
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
    <div class="refund-form" style="display: none;">
        <div class="form-container">
            <form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
                enctype="multipart/form-data" data-redirect="ajaxModalCommon">
                @csrf
                @if (isset($method) && $method == 'PUT')
                    @method('PUT')
                @endif
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>TOTAL PAID</label>
                            <input type="text" class="form-control"
                                value="{{ isset($total_paid) ? $total_paid : '0.00' }}" placeholder="₹0.00" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TOTAL REFUNDED</label>
                            <input type="text" class="form-control"
                                value="{{ isset($total_refund) ? $total_refund : '0.00' }}" placeholder="₹0.00"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TOTAL REFUND DUE</label>
                            <input type="text" name="total_refund_due" class="form-control"
                                value="{{ isset($total_refundable) ? $total_refundable : '0.00' }}" placeholder="₹0.00"
                                id="total_refund_due" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="required">REFUND AMOUNT</label>
                            <input name="amount_refund" type="text" class="form-control" value=""
                                placeholder="₹0.00">
                            <small class="text-muted text-danger">Note: Please enter the value only less than equal
                                total
                                refund due
                                amount.</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required">REFUND SOURCE NAME </label>
                            <select class="form-control" name="amount_refund_source">
                                <option value="">---- Refund Source ----</option>
                                @isset($depositeSources)
                                    @foreach ($depositeSources as $depositeSource)
                                        <option value="{{ $depositeSource }}">{{ $depositeSource }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="required">REFUND DATE</label>
                            <input name="amount_refund_date" type="date" class="form-control"
                                value="{{ date('Y-m-d') }}" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required">REFUND COLLECTED BY PERSON</label>
                            <input name="payment_collected_by" type="text" class="form-control" value=""
                                placeholder="Refund Collected By Person">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="required">REFUND NOTE(IF ANY:)</label>
                            <textarea name="payment_refund_note" type="text" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <input type="hidden" name="sale_id"
                        value="{{ isset($salesAccount->sale_id) ? $salesAccount->sale_id : '' }}" />
                    <input type="hidden" name="sale_account_id"
                        value="{{ isset($salesAccount->id) ? $salesAccount->id : '' }}" />
                    <a class="btn btn-danger pull-left" id="createRefundButtonBack">
                        BACK
                    </a>
                    <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                        CREATE REFUND
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
