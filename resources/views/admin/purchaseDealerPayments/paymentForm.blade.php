<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>DEALER NAME</label>
                <input type="text" class="form-control"
                    value="{{ isset($data['company_name']) ? $data['company_name'] : '' }}" disabled>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>TOTAL BALANCE</label>
                <input type="text" class="form-control" value="{{ isset($total_balance) ? $total_balance : '' }}"
                    disabled>
            </div>
            <div class="form-group col-md-4">
                <label>TOTAL PAID</label>
                <input type="text" class="form-control" value="{{ isset($total_paid) ? $total_paid : '' }}" disabled>
            </div>
            <div class="form-group col-md-4">
                <label>TOTAL OUTSTANDING</label>
                <input type="text" class="form-control"
                    value="{{ isset($total_outstanding) ? $total_outstanding : '' }}" disabled>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>TRANSACTION TYPE</label>
                <select class="form-control" name="transaction_type">
                    <option value="1">DEBIT</option>
                    <option value="2">CREDIT</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>PAID FROM ACCOUNT</label>
                <select class="form-control" name="bank_account_id">
                    @if (isset($bank_accounts))
                        @foreach ($bank_accounts as $bank_account)
                            <option value="1">
                                {{ strtoupper($bank_account->bank_account_holder_name . ' - ' . $bank_account->bank_account_number) }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row">
            <input type="hidden" name="dealer_id" value="{{ isset($data['id']) ? $data['id'] : '' }}">
            <div class="form-group col-md-4">
                <label>AMOUNT</label>
                <input name="payment_amount" type="text" class="form-control" value="" placeholder="₹0.00">
            </div>
            <div class="form-group col-md-4">
                <label>DATE</label>
                <input name="payment_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-4">
                <label>PAYMENT MODE</label>
                <select class="form-control" name="payment_mode">
                    @if (isset($paymentSources))
                        @foreach ($paymentSources as $paymentSource)
                            <option value="{{ $paymentSource }}">{{ $paymentSource }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>PAYMENT NOTE(IF ANY :)</label>
                <textarea name="payment_note" class="form-control" rows="5" value="" placeholder="Payment Note(If Any)"></textarea>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    CREATE DEALER PAYMENT
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            payment_date: {
                required: true,
                date: true
            },
            payment_amount: {
                required: true,
                number: true
            },
            payment_mode: {
                required: true
            },
            payment_note: {
                required: true
            }
        },
        messages: {
            payment_date: {
                required: "The payment date field is required.",
                date: "The payment date should valid date."
            },
            payment_amount: {
                required: "The payment amount field is required.",
                number: "The payment amount should valid number."
            },
            payment_mode: {
                required: "The payment mode field is required."
            },
            payment_note: {
                required: "The payment note field is required."
            }
        }
    });
</script>
