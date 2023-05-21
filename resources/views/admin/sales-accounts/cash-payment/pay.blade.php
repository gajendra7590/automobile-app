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
        <div class="row col_bank_account">
            <div class="form-group col-md-12">
                <label>RECEIVED IN BANK ACCOUNT</label>
                <select class="form-control" name="received_in_bank">
                    <option value="">SELECT BANK ACCOUNT</option>
                    @isset($bankAccounts)
                        @foreach ($bankAccounts as $bankAccount)
                            <option value="{{ $bankAccount->id }}">
                                {{ $bankAccount->bank_name . ' - ' . $bankAccount->bank_account_number }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>Next Due Date</label>
                <input name="next_due_date" type="date" class="form-control"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    placeholder="YYYY-MM-DD">
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

    $(document).ready(function() {
        $('.col_bank_account').hide();
        $('select[name="paid_source"]').change(function() {
            let val = $(this).val();
            if (val == 'Cash') {
                $('.col_bank_account').hide();
                $('select[name="received_in_bank"]').prop('selectedIndex', 0);
            } else {
                $('.col_bank_account').show();
            }
        });
    });
</script>
