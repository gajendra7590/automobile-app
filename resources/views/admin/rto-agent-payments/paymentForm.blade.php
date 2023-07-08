<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>AGENT NAME</label>
                <input type="text" class="form-control"
                    value="{{ isset($data['agent_name']) ? $data['agent_name'] : '' }}" disabled>
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
            <input type="hidden" name="rto_agent_id" value="{{ isset($data['id']) ? $data['id'] : '' }}">
            <div class="form-group col-md-4">
                <label>PAYMENT AMOUNT</label>
                <input name="payment_amount" type="text" class="form-control"
                    value="{{ isset($editDetail->payment_amount) ? $editDetail->payment_amount : '' }}"
                    placeholder="₹0.00">
            </div>
            <div class="form-group col-md-4">
                <label>PAYMENT DATE</label>
                <input name="payment_date" type="date" class="form-control"
                    value="{{ isset($editDetail->payment_date) ? $editDetail->payment_date : date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-4">
                <label>PAYMENT MODE</label>
                <select class="form-control" name="payment_mode">
                    @if (isset($paymentSources))
                        @foreach ($paymentSources as $paymentSource)
                            <option value="{{ $paymentSource }}"
                                {{ isset($editDetail->payment_mode) && $editDetail->payment_mode == $paymentSource ? 'selected="selected"' : '' }}>
                                {{ $paymentSource }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>PAYMENT NOTE(IF ANY :)</label>
                <textarea name="payment_note" class="form-control" rows="5" value="" placeholder="Payment Note(If Any)">{{ isset($editDetail->payment_note) ? $editDetail->payment_note : '' }}</textarea>
            </div>
        </div>
    </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    @if (isset($editDetail->id))
                        UPDATE PAYMENT DETAIL
                    @else
                        CREATE PAYMENT
                    @endif

                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            payment_amount: {
                required: true,
                number: true,
                min: 1,
            },
            payment_date: {
                required: false,
                date: true,
            },
            payment_note: {
                required: true
            },
        },
        messages: {
            payment_amount: {
                required: "The payment amount field is required.",
                number: "The payment amount invalid price.",
                min: "The payment amount invalid price.",
            },
            payment_date: {
                required: "The payment date field is required.",
                date: "The payment date should valid date",
            },
            payment_note: {
                required: "The payment note field is required."
            },
        }
    });
</script>
