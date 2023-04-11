<form class="ajaxFormSubmitFinanaceDetail">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-2">
            <label>FINANCE AMOUNT</label>
            <input type="text" class="form-control" placeholder="₹0.00" name="finance_amount" value='' />
        </div>
        <div class="form-group col-md-2">
            <label>EMI AMOUNT</label>
            <input type="text" class="form-control" placeholder="₹0.00" name="emi_amount" value='' />
        </div>
        <div class="form-group col-md-2">
            <label>EMI TYPE</label>
            <select name="emi_type" class="form-control">
                <option value='1 MONTH'>1 MONTH</option>
                <option value='2 MONTH'>2 MONTH</option>
                <option value='3 MONTH'>3 MONTH</option>
                <option value='4 MONTH'>4 MONTH</option>
                <option value='5 MONTH'>5 MONTH</option>
                <option value='6 MONTH'>6 MONTH</option>
                <option value='YEARLY'>YEARLY</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>No OF EMI</label>
            <select name="number_of_emi" class="form-control">
                @for ($i = 1; $i <= 60; $i++)
                    <option value='{{ $i }}'>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>TENOUR</label>
            <select name="tenour" class="form-control">
                <option value='1 YEAR'>1 YEAR</option>
                <option value='1.5 YEAR'>1.5 YEAR</option>
                <option value='2 YEAR'>2 YEAR</option>
                <option value='2.5 YEAR'>2.5 YEAR</option>
                <option value='3 YEAR'>3 YEAR</option>
                <option value='3.5 YEAR'>3.5 YEAR</option>
                <option value='4 YEAR'>4 YEAR</option>
                <option value='4.5 YEAR'>4.5 YEAR</option>
                <option value='5 YEAR'>5 YEAR</option>
                <option value='5.5 YEAR'>5.5 YEAR</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>RATE OF INTEREST</label>
            <input type="text" class="form-control" placeholder="₹0.00" name="roi" value='' />
        </div>
        <div class="form-group col-md-2">
            <button type="submit" class="btn btn-primary" id="ajaxFormSubmit" style="margin-top: 24px;">
                ADD DETAIL
            </button>
        </div>
    </div>
</form>
<script>
    $('.ajaxFormSubmitFinanaceDetail').submit(function(e) {
        e.preventDefault();

        let finance_amount = parseFloat($("input[name='finance_amount']").val());
        let emi_amount = parseFloat($("input[name='emi_amount']").val());
        if (isNaN(emi_amount)) {
            toastr.error("Please enter valid amount");
            return false;
        }
        let emi_type = $("select[name='emi_type'] option:selected").val();
        let number_of_emi = $("select[name='number_of_emi'] option:selected").val();
        let tenour = $("select[name='tenour'] option:selected").val();
        let roi = $("input[name='roi']").val();
        if (roi != "") {
            roi = parseFloat(roi);
            if (isNaN(roi)) {
                toastr.error("Please enter valid ROI");
                return false;
            }
        }

        let schemeDetail = "DOWN PAYMENT SCHEME : ₹"+finance_amount+" :- ";
        schemeDetail += '( EMI AMOUNT : ₹' + emi_amount + ' | ';
        schemeDetail += "EMI TYPE : " + emi_type + ' | ';
        schemeDetail += "NUMBER OF EMI : " + number_of_emi + ' | ';
        schemeDetail += 'TENOUR : ' + tenour;
        if (roi != "") {
            schemeDetail += ' - RATE OF INTEREST : ' + roi + '%';
        }
        schemeDetail += ' )';
        $('input[name="hyp_financer_description"]').val(schemeDetail);
        // $('#ajaxModalCommon').modal('hide');
    });
</script>
