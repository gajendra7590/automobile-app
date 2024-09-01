<form role="form" method="POST" class="ajaxFormSubmit" action="{{ route('salesPersonalFinanace.store') }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-3">
                <label>Total Outstanding Amount</label>
                <input name="total_outstanding" type="number" class="form-control"
                    value="{{ isset($totalDue) ? $totalDue : 0.0 }}" placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-3">
                <label>Finance Amount</label>
                <input name="total_finance_amount" type="number" class="form-control calcTotalEmi" value=""
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-2">
                <label>Processing Fees</label>
                <input name="processing_fees" type="text" class="form-control calcTotalEmi" value=""
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Finance Amount Grand Total</label>
                <input name="grand_finance_amount" type="text" class="form-control" value=""
                    placeholder="₹ 0.00" readonly>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Bank Financer Name</label>
                <select class="form-control" name="financier_id">
                    @isset($financers)
                        @foreach ($financers as $financer)
                            <option value="{{ $financer->id }}">
                                {{ $financer->bank_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Installment Due Date(Every Month)</label>
                <input name="finance_due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
                <small class="text-muted text-danger">First installment due date will be next month of same selected
                    date.</small>
            </div>
        </div>
        <!-- EMI SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>EMI Term</label>
                <select class="form-control calcTotalEmi" name="finance_terms">
                    @isset($emiTerms)
                        @foreach ($emiTerms as $k => $emiTerm)
                            <option value="{{ $k }}">
                                {{ $emiTerm }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>NO OF EMI</label>
                <input name="no_of_emis" type="number" class="form-control calcTotalEmi" value=""
                    placeholder="Ex : how many emis">
            </div>
            <div class="form-group col-md-4">
                <label>RATE OF INTEREST(%)</label>
                <input name="rate_of_interest" type="text" class="form-control calcTotalEmi" value=""
                    placeholder="Any +ve Number">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>TOTAL FINANCE WITH INTEREST(approax)</label>
            <input name="show_calculated_total" type="number" class="form-control" value="" readonly>
        </div>
        <div class="form-group col-md-4">
            <label>EVERY EMI AMOUNT(approax)</label>
            <input name="show_calculated_emi" type="number" class="form-control" value="" readonly>
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
    $(document).ready(function() {

        $(".calcTotalEmi").on("keyup change", function(e) {

            var total_finance_amount = parseFloat($('input[name="total_finance_amount"]').val());
            total_finance_amount = !isNaN(total_finance_amount) ? total_finance_amount : 0.0;

            var processing_fees = parseFloat($('input[name="processing_fees"]').val());
            processing_fees = !isNaN(processing_fees) ? processing_fees : 0.0;

            var no_of_emis = $('input[name="no_of_emis"]').val();
            no_of_emis = !isNaN(no_of_emis) ? no_of_emis : 0;

            var rate_of_interest = parseFloat($('input[name="rate_of_interest"]').val());
            rate_of_interest = !isNaN(rate_of_interest) ? rate_of_interest : 0.0;

            var finance_terms = parseFloat($('select[name="finance_terms"]').val());
            var totalFinanceAmount = parseFloat(total_finance_amount + processing_fees);

            var total_months = no_of_emis;
            var term = 0;
            switch (finance_terms) {
                case 1:
                    total_months = total_months;
                    term = 1;
                    break;
                case 2:
                    total_months = total_months * 3;
                    term = 3;
                    break;
                case 3:
                    total_months = total_months * 6;
                    term = 6;
                    break;
                case 4:
                    total_months = total_months * 12;
                    term = 12;
                    break;
                case 5:
                    total_months = total_months * 2;
                    term = 2;
                    break;
                case 6:
                    total_months = total_months * 4;
                    term = 4;
                    break;
                default:
                    break;
            }
            var total_interest = parseFloat((totalFinanceAmount * rate_of_interest * (total_months /
                12)) / 100);
            var new_grand_total = parseFloat(totalFinanceAmount + total_interest);
            if (no_of_emis > 0 && rate_of_interest > 0) {
                $('input[name="show_calculated_total"]').val(new_grand_total.toFixed(2));
                let show_calculated_emi = parseFloat(new_grand_total / no_of_emis);
                $('input[name="show_calculated_emi"]').val(show_calculated_emi.toFixed(2));
            }
        });



    });


    $(".ajaxFormSubmit").validate({
        rules: {
            total_outstanding: {
                required: true,
                number: true,
                min: 1
            },
            total_finance_amount: {
                required: true,
                number: true,
                min: 1
            },
            processing_fees: {
                required: false,
                number: true,
                min: 1
            },
            finance_due_date: {
                required: true,
                date: true
            },
            financier_id: {
                required: true
            },
            no_of_emis: {
                required: true,
                digits: true,
                min: 1
            },
            rate_of_interest: {
                required: true,
                number: true,
                min: 1
            }
        },
        messages: {
            total_outstanding: {
                required: "The total outstanding field is required.",
                number: "The total outstanding should valid price",
                min: "The total outstanding should valid price",
            },
            total_finance_amount: {
                required: "The finance amount field is required.",
                number: "The finance amount should valid price",
                min: "The finance amount should valid price",
            },
            finance_due_date: {
                required: "The finance due date field is required.",
                date: "The finance due date should valid date."
            },
            financier_id: {
                required: "The financer field is required.",
            },
            no_of_emis: {
                required: "The No of EMIs field is required.",
            },
            rate_of_interest: {
                required: "The Rate of intrest field is required.",
            }
        },
    });
</script>
