<form role="form" method="POST" class="ajaxFormSubmit"
    action="{{ route('bankFinanacePayUpdate', ['id' => isset($data['id']) ? $data['id'] : 0]) }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @method('PUT')
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>Total Outstanding Amount</label>
                <input name="total_outstanding" type="number" class="form-control"
                    value="{{ isset($salesAccount['bank_finance_outstaning_balance']) ? $salesAccount['bank_finance_outstaning_balance'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>Paid Amount</label>
                <input name="paid_amount" type="number" class="form-control"
                    value="{{ $data['debit_amount'] ? $data['debit_amount'] : 0.0 }}" placeholder="₹ 0.00">
                <input name="old_paid_amount" type="hidden" class="form-control"
                    value="{{ $data['debit_amount'] ? $data['debit_amount'] : 0.0 }}" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Paid Date</label>
                <input name="paid_date" type="date" class="form-control"
                    value="{{ $data['paid_date'] ? $data['paid_date'] : date('Y-m-d') }}" placeholder="yyyy-mm-dd">
            </div>
        </div>
        {{-- received_in_bank --}}
        <div class="row">
            <div class="form-group col-md-6">
                <label>Payment Source</label>
                <select class="form-control" name="paid_source">
                    @isset($depositeSources)
                        @foreach ($depositeSources as $depositeSource)
                            <option value="{{ $depositeSource }}"
                                {{ isset($data['paid_source']) && $data['paid_source'] == $depositeSource ? 'selected="selected"' : '' }}>
                                {{ $depositeSource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="1"
                        {{ isset($data['status']) && $data['status'] == '1' ? 'selected="selected"' : '' }}>
                        PAID</option>
                    <option value="2"
                        {{ isset($data['status']) && $data['status'] == '2' ? 'selected="selected"' : '' }}>
                        ON HOLD(IN CASE OF CHEQUE)</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Payment Collected By</label>
                <select class="form-control" name="collected_by">
                    @isset($salemans)
                        @foreach ($salemans as $saleman)
                            <option value="{{ $saleman->id }}"
                                {{ isset($data['collected_by']) && $data['collected_by'] == $saleman->id ? 'selected="selected"' : '' }}>
                                {{ $saleman->name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Next Due Date</label>
                <input name="next_due_date" type="date" class="form-control"
                    min="{{ $data['due_date'] ? $data['due_date'] : date('Y-m-d', strtotime('+1 day')) }}"
                    value="{{ $data['due_date'] ? $data['due_date'] : date('Y-m-d', strtotime('+1 day')) }}"
                    placeholder="YYYY-MM-DD">
            </div>
        </div>
        <div class="row col_bank_account">
            <div class="form-group col-md-12">
                <label>RECEIVED IN BANK ACCOUNT</label>
                <select class="form-control" name="received_in_bank">
                    <option value="">SELECT BANK ACCOUNT</option>
                    @isset($bankAccounts)
                        @foreach ($bankAccounts as $bankAccount)
                            <option value="{{ $bankAccount->id }}"
                                {{ isset($data['received_in_bank']) && $data['received_in_bank'] == $bankAccount->id ? 'selected="selected";' : '' }}>
                                {{ $bankAccount->bank_name . ' - ' . $bankAccount->bank_account_number }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>Payment Note(If Any)</label>
                <input name="payment_note" type="text" class="form-control"
                    value="{{ $data['paid_note'] ? $data['paid_note'] : '' }}" placeholder="Payment Note...">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
            UPDATE
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
        //$('.col_bank_account').hide();
        let selected_val = $('select[name="paid_source"] :selected').val();
        if (selected_val == 'Cash') {
            $('.col_bank_account').hide();
            $('select[name="received_in_bank"]').prop('selectedIndex', 0);
        } else {
            $('.col_bank_account').show();
        }

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