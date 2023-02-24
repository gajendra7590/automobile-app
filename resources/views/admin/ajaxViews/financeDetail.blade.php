<form class="ajaxFormSubmitFinanaceDetail">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-2">
            <label>EMI AMOUNT</label>
            <input type="text" class="form-control" placeholder="EMI AMOUNT" name="emi_amount" value='' />
        </div>
        <div class="form-group col-md-2">
            <label>EMI TYPE</label>
            <select name="emi_type" class="form-control">
                <option value='MONTHLY'>MONTHLY</option>
                <option value='QUARTERLY'>QUARTERLY</option>
                <option value='HALF YEARLY'>HALF YEARLY</option>
                <option value='YEARLY'>YEARLY</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>No OF EMI</label>
            <select name="number_of_emi" class="form-control">
                @for ($i = 1; $i <= 18; $i++)
                    <option value='{{ $i }}'>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>TENOUR</label>
            <select name="tenour" class="form-control">
                <option value='1 YEAR'>1 YEAR</option>
                <option value='2 YEAR'>2 YEAR</option>
                <option value='3 YEAR'>3 YEAR</option>
                <option value='4 YEAR'>4 YEAR</option>
                <option value='5 YEAR'>5 YEAR</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>RATE OF INTEREST</label>
            <input type="text" class="form-control" placeholder="RATE OF INTEREST" name="roi" value='' />
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


        let schemeDetail = "DOWN PAYMENT SCHEME - ( ";
        schemeDetail += 'EMI AMOUNT : ₹' + emi_amount + ' - ';
        schemeDetail += "EMI TYPE : " + emi_type + ' - ';
        schemeDetail += "NUMBER OF EMI : " + number_of_emi + ' - ';
        schemeDetail += 'TENOUR : ' + tenour;
        if (roi != "") {
            schemeDetail += ' - RATE OF INTEREST : ' + roi + '%';
        }
        schemeDetail += ' )';
        $('input[name="hyp_financer_description"]').val(schemeDetail);
        // $('#ajaxModalCommon').modal('hide');
    });
</script>
