<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box" style="background: #f3f3f3a1;">
            <span class="info-box-icon bg-blue"><i class="fa fa-inr" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">TOTAL SALES PRICE</span>
                <span class="info-box-number">
                    {{ isset($data['sales_total_amount']) ? priceFormate($data['sales_total_amount']) : '--' }}
                </span>
            </div>
        </div>
    </div>
    <div class="clearfix visible-sm-block"></div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box" style="background: #f3f3f3a1;">
            <span class="info-box-icon bg-green"><i class="fa fa-plus" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">TOTAL PAID</span>
                <span class="info-box-number">
                    {{ isset($data['total_paid']) ? priceFormate($data['total_paid']) : '--' }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box" style="background: #f3f3f3a1;">
            <span class="info-box-icon bg-red"><i class="fa fa-minus" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">TOTAL DUE</span>
                <span class="info-box-number">
                    {{ isset($data['total_due']) ? priceFormate($data['total_due']) : '--' }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box" style="background: #f3f3f3a1;">
            <span class="info-box-icon bg-yellow"><i class="fa fa-calculator" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">GRAND TOTAL</span>
                <span class="info-box-number">
                    {{ isset($data['grand_total']) ? priceFormate($data['grand_total']) : '--' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box" style="background: #f3f3f3a1;">
            <span class="info-box-icon bg-blue"><i class="fa fa-user" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">SELF PAY / CASH PAY</span>
                <span class="info-box-number text-green">
                    +
                    {{ isset($salesAccountData['cash_paid_balance']) ? priceFormate($data['cash_paid_balance']) : '--' }}
                </span>
                <span class="info-box-number text-red">
                    -
                    {{ isset($salesAccountData['cash_outstaning_balance']) ? priceFormate($data['cash_outstaning_balance']) : '--' }}
                </span>
            </div>
        </div>
    </div>

    <div class="clearfix visible-sm-block"></div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box" style="background: #f3f3f3a1;">
            <span class="info-box-icon bg-blue"><i class="fa fa-user" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">SELF PAY / CASH PAY STATUS</span>
                @if (isset($data['cash_status']) && $data['cash_status'] == '1')
                    <span class="info-box-number text-green">CLOSE</span>
                @else
                    <span class="info-box-number text-red">DUE PENDING</span>
                @endif
            </div>
        </div>
    </div>
    <!-- CASE OF BANK FINANACE -->
    @if (isset($data['due_payment_source']) && $data['due_payment_source'] == '2')
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box" style="background: #f3f3f3a1;">
                <span class="info-box-icon bg-green"><i class="fa fa-bank" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">BANK FINANACE PAY</span>
                    <span class="info-box-number text-green">
                        +
                        {{ isset($data['bank_finance_paid_balance']) ? priceFormate($data['bank_finance_paid_balance']) : '--' }}
                    </span>

                    @if ($data['bank_finance_outstaning_balance'] >= 0)
                        <span class="info-box-number text-red">
                            -
                            {{ isset($data['bank_finance_outstaning_balance']) ? priceFormate($data['bank_finance_outstaning_balance']) : '--' }}
                        </span>
                    @else
                        <span class="info-box-number text-yellow">
                            +
                            {{ isset($data['bank_finance_outstaning_balance']) ? priceFormate(-$data['bank_finance_outstaning_balance']) : '--' }}(Buffer)
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box" style="background: #f3f3f3a1;">
                <span class="info-box-icon bg-green"><i class="fa fa-bank" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">BANK FINANACE PAY STATUS</span>
                    @if (isset($data['bank_finance_status']) && $data['bank_finance_status'] == '1')
                        <span class="info-box-number text-green">CLOSE</span>
                    @else
                        <span class="info-box-number text-red">DUE PENDING</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <!-- CASE OF PERSONAL FINANACE -->
    @if (isset($data['due_payment_source']) && $data['due_payment_source'] == '3')
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box" style="background: #f3f3f3a1;">
                <span class="info-box-icon bg-yellow"><i class="fa fa-leanpub" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PERSONAL FINANACE</span>
                    <span class="info-box-number text-green">
                        +
                        {{ isset($data['personal_finance_paid_balance']) ? priceFormate($data['personal_finance_paid_balance']) : '--' }}
                    </span>
                    <span class="info-box-number text-red">
                        -
                        {{ isset($data['personal_finance_outstaning_balance']) ? priceFormate($data['personal_finance_outstaning_balance']) : '--' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box" style="background: #f3f3f3a1;">
                <span class="info-box-icon bg-yellow"><i class="fa fa-leanpub" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PERSONAL FINANACE PAY STATUS</span>
                    @if (isset($data['personal_finance_status']) && $data['personal_finance_status'] == '1')
                        <span class="info-box-number text-green">CLOSE</span>
                    @else
                        <span class="info-box-number text-red">DUE PENDING</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">PAYMENT ACCOUNT BASIC DETAIL</h3>
                @if (isset($data['payment_setup']) && $data['payment_setup'] == '0')
                    <a href="{{ route('saleAccounts.create') . '?id=' . (isset($data['id']) ? $data['id'] : 0) }}"
                        class="btn btn-xs btn-primary pull-right ajaxModalPopup" data-modal_size="modal-lg"
                        data-modal_title="DEPOSITE DOWNPAYMENT">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> PAY DOWN PAYMENT
                    </a>
                @endif

                @if (isset($data['payment_setup']) && $data['payment_setup'] == '1' && Auth::user()->is_admin == '1')
                    <a href="{{ route('saleAccounts.edit', ['saleAccount' => isset($data['id']) ? $data['id'] : 0]) }}"
                        class="btn btn-xs btn-warning pull-right ajaxModalPopup" data-modal_size="modal-lg"
                        data-modal_title="UPDATE DOWN PAYMENT DETAIL">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> EDIT DOWN PAYMENT
                    </a>
                @endif

            </div>
            <div class="box-body no-padding">
                <table class="table table-bordered myCustomTable">
                    <tbody>
                        <tr>
                            <th>ACCOUNT NAME</th>
                            <td colspan="2">
                                {{ isset($data['sale']['customer_name']) ? strtoupper(strtolower($data['sale']['customer_name'])) : '' }}
                            </td>
                            <th>ACCOUNT PAY STATUS</th>
                            <td>
                                @isset($data['status'])
                                    @if ($data['status'] == '0')
                                        <span class="badge bg-red">DUE</span>
                                    @else
                                        <span class="badge bg-green">PAID</span>
                                    @endif
                                @endisset
                            </td>
                            <th>DUE PAYMENT SOURCE</th>
                            <td colspan="2">
                                {{ isset($data['due_payment_source']) ? strtoupper(duePaySources($data['due_payment_source'])) : '--' }}
                            </td>
                        </tr>
                        @if (isset($data['due_payment_source']) && in_array($data['due_payment_source'], [2, 3]))
                            <tr>
                                <th>FINANCER NAME</th>
                                <td colspan="2">
                                    {{ isset($data['financier']['bank_name']) ? strtoupper($data['financier']['bank_name']) : '--' }}
                                </td>
                                <th>FINANCER NOTE</th>
                                <td colspan="3">
                                    {{ isset($data['financier_note']) ? strtoupper($data['financier_note']) : '--' }}
                                </td>
                            </tr>
                        @endif
                        @if (isset($data['due_payment_source']) && in_array($data['due_payment_source'], [3]))
                            <tr>
                                <th>PAYMENT TERM</th>
                                <td>
                                    {{ isset($data['finance_terms']) ? strtoupper(emiTerms($data['finance_terms'])) : '--' }}
                                </td>
                                <th>PAYMENT NO OF EMI</th>
                                <td>
                                    {{ isset($data['no_of_emis']) ? $data['no_of_emis'] : '--' }}
                                </td>
                                <th>RATE OF INTREST</th>
                                <td>
                                    {{ isset($data['rate_of_interest']) ? $data['rate_of_interest'] : '--' }}
                                </td>
                                <th>PROCESSING FEES</th>
                                <td>
                                    {{ isset($data['processing_fees']) ? $data['processing_fees'] : '--' }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (isset($salesAccountData) && $salesAccountData->status == '1')
                    <p class="account_status_note"><b>Note :</b>
                        All the dues cleared so account mark as closed.
                    </p>
                @endif
            </div>

        </div>
    </div>
</div>
