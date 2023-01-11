<form role="form" method="POST" class="ajaxFormSubmit" action="{{ route('installmentPay') }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <div class="row">
            <input name="id" value="{{ isset($data['id']) ? $data['id'] : '0' }}" type="hidden">
            <div class="form-group col-md-4">
                <label>TOTAL PAY AMOUNT</label>
                <input name="emi_due_amount" class="form-control" readonly
                    value="{{ isset($data['emi_due_revised_amount']) ? $data['emi_due_revised_amount'] : '' }}"
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>PAYMENT DUE DATE</label>
                <input type="text" class="form-control" readonly
                    value="{{ isset($data['emi_due_date']) ? date('Y-m-d', strtotime($data['emi_due_date'])) : '' }}">
            </div>
            <div class="form-group col-md-4">
                <label>PAYMENT METHOD</label>
                <select class="form-control" name="pay_method">
                    @isset($depositeSources)
                        @foreach ($depositeSources as $depositeSource)
                            <option value="{{ $depositeSource }}">
                                {{ $depositeSource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>PAYMENT NOTE</label>
                <textarea class="form-control" name="pay_method_note" placeholder="Ex : Cheque No / Bank Detail | UPI Trans ID Etc.."></textarea>
            </div>
            <div class="form-group col-md-6">
                <label>PAYMENT OPTION</label>
                <select class="form-control" name="pay_option">
                    <option value="full">Full Payment</option>
                    <option value="partial">Partial Payment</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>PAYABLE AMOUNT</label>
                <input name="pay_amount" type="number" class="form-control"
                    value="{{ isset($data['emi_due_revised_amount']) ? $data['emi_due_revised_amount'] : '' }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            @if (isset($totalDueCounts) && $totalDueCounts == '1')
                <div class="form-group col-md-6 hideElement" id="due_date_ele">
                    <label>NEXT DUE DATE</label>
                    <input name="next_due_Date" type="date" class="form-control"
                        value="{{ isset($data['emi_due_date']) ? date('Y-m-d', strtotime($data['emi_due_date'])) : date('Y-m-d') }}"
                        placeholder="YYYY-MM-DD" disabled>
                </div>
            @endif

            <div class="form-group col-md-12">
                <p class="text-danger" style="font-size: 15px;">
                    <b>Important Note :</b> If You are paying advance partial payment, then can only pay for next emi
                    only. more then 1 next emi you have to pay one by one.
                </p>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-success pull-right" id="ajaxFormSubmit">
                MAKE PAYMENT
            </button>
        </div>
</form>

<script>
    $(document).ready(function() {

        $('select[name="pay_option"]').change(function() {
            let pay_option = $(this).val();
            if (pay_option == 'partial') {
                $('input[name="pay_amount"]').removeAttr('readonly');
            } else {
                let total_pay = $('input[name="emi_due_amount"]').val();
                $('input[name="pay_amount"]').attr('readonly', true).val(total_pay);
            }
        });

        $('input[name="pay_amount"]').keyup(function() {
            let payable_amount = $(this).val();
            let emi_due_amount = $('input[name="emi_due_amount"]').val();
            if (parseFloat(emi_due_amount) == parseFloat(payable_amount)) {
                $('#due_date_ele').addClass('hideElement');
                $('input[name="next_due_Date"]').attr('disabled', 'disabled');
            } else {
                $('#due_date_ele').removeClass('hideElement');
                $('input[name="next_due_Date"]').removeAttr('disabled');
            }
        })

    })
</script>
