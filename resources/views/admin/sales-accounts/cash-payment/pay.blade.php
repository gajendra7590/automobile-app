<form role="form" method="POST" class="ajaxFormSubmit" action="{{ route('salesCash.store') }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>Total Outstanding Amount</label>
                <input name="total_outstanding" type="number" class="form-control"
                    value="{{ isset($data['cash_outstaning_balance']) ? $data['cash_outstaning_balance'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>Paid Amount</label>
                <input name="paid_amount" type="number" class="form-control" value="" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Paid Date</label>
                <input name="paid_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Payment Source</label>
                <select class="form-control" name="paid_source">
                    @isset($depositeSources)
                        @foreach ($depositeSources as $depositeSource)
                            <option value="{{ $depositeSource }}">
                                {{ $depositeSource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="1">PAID</option>
                    <option value="2">ON HOLD(IN CASE OF CHEQUE)</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Payment Collected By</label>
                <select class="form-control" name="collected_by">
                    <option value="">---Select Salesman---</option>
                    @isset($salemans)
                        @foreach ($salemans as $saleman)
                            <option value="{{ $saleman->id }}">
                                {{ $saleman->name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>Next Due Date</label>
                <input name="next_due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-9">
                <label>Payment Note(If Any)</label>
                <input name="payment_note" type="text" class="form-control" value=""
                    placeholder="Payment Note...">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
            SAVE
        </button>
    </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            total_outstanding: {
                required: true,
                number: true,
                min: 1
            },
            paid_amount: {
                required: true,
                number: true,
                min: 1
            },
            paid_date: {
                required: true,
                date: true
            },
            collected_by: {
                required: true
            },
            next_due_date: {
                required: true,
                date: true
            },
            payment_note: {
                required: true,
            }
        },
        messages: {
            total_outstanding: {
                required: "The total outstanding field is required.",
                number: "The total outstanding should valid price",
                min: "The total outstanding should valid price",
            },
            paid_amount: {
                required: "The paid amount field is required.",
                number: "The paid amount should valid price",
                min: "The paid amount should valid price",
            },
            paid_date: {
                required: "The paid date field is required.",
                date: "The paid date should valid date."
            },
            collected_by: {
                required: "The salesman field is required.",
            },
            next_due_date: {
                required: "The next due date field is required.",
                date: "The next due date should valid date."
            },
            payment_note: {
                required: "The payment note field is required.",
            }
        },
    });
</script>
