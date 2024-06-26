<form role="form" method="POST" class="ajaxFormSubmit"
    action="{{ route('personalFinanacePayStore', ['id' => isset($data['id']) ? $data['id'] : '0']) }}"
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
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>PAYMENT NOTE</label>
                <textarea class="form-control" name="pay_method_note" placeholder="Ex : Cheque No / Bank Detail | UPI Trans ID Etc.."></textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>PAYMENT OPTION</label>
                <select class="form-control" name="pay_option">
                    <option value="full">Full Payment</option>
                    <option value="partial">Partial Payment</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>PAYABLE AMOUNT</label>
                <input name="pay_amount" type="text" class="form-control"
                    value="{{ isset($data['emi_due_revised_amount']) ? $data['emi_due_revised_amount'] : '' }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>PAYMENT DATE</label>
                <input name="pay_date" type="date" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" placeholder="dd-mm-yyyy">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>PAYMENT COLLECTED BY SALESMAN</label>
                <select class="form-control" name="collected_by_salesman_id">
                    <option value="0">---SELECT SALESMAN---</option>
                    @isset($salemans)
                        @foreach ($salemans as $k => $saleman)
                            <option value="{{ $saleman->id }}">{{ $saleman->name }} </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            @if (isset($totalDueCounts) && $totalDueCounts == '1')
                <div class="form-group col-md-6 hideElement" id="due_date_ele">
                    <label>NEXT DUE DATE</label>
                    <input name="next_due_Date" type="date" class="form-control"
                        min="{{ isset($data['emi_due_date']) ? date('Y-m-d', strtotime($data['emi_due_date'])) : date('Y-m-d', strtotime('+1 day')) }}"
                        value="{{ isset($data['emi_due_date']) ? date('Y-m-d', strtotime($data['emi_due_date'])) : date('Y-m-d', strtotime('+1 day')) }}"
                        placeholder="YYYY-MM-DD" disabled>
                </div>
            @endif
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
            <div class="form-group col-md-12">
                <p class="text-danger" style="font-size: 15px;">
                    <b>Important Note :</b> If You are paying advance partial payment, then can only pay for next emi
                    only. more then 1 next emi you have to pay one by one.
                </p>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                PAY INSTALLMENT
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
        });

        $('.col_bank_account').hide();
        $('select[name="pay_method"]').change(function() {
            let val = $(this).val();
            if (val == 'Cash') {
                $('.col_bank_account').hide();
                $('select[name="received_in_bank"]').prop('selectedIndex', 0);
            } else {
                $('.col_bank_account').show();
            }
        });

    })
</script>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            pay_method_note: {
                required: true
            },
            pay_amount: {
                required: true,
                number: true,
                min: 1
            },
            collected_by_salesman_id: {
                required: true,
                min: 1
            }
        },
        messages: {
            pay_method_note: {
                required: "The payment note field is required."
            },
            pay_amount: {
                required: "The payment amount field is required.",
                number: "The payment amount should valid price",
                min: "The payment amount should valid price",
            },
            collected_by_salesman_id: {
                required: "The salesman field is required.",
                min: "The salesman field is required."
            }
        },
    });
</script>
