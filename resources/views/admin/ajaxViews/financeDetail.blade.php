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
            <label>ROI %</label>
            <input type="text" class="form-control" placeholder="ROI %" name="roi" value='' />
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

        let emi_amount = $("input[name='emi_amount']").val();
        let emi_type = $("select[name='emi_type'] option:selected").val();
        let number_of_emi = $("select[name='number_of_emi'] option:selected").val();
        let tenour = $("select[name='tenour'] option:selected").val();
        let roi = $("input[name='roi']").val();

        let schemeDetail = "DOWN PAYMENT SCHEME - ( TOTAL AMOUNT ₹";
        schemeDetail += emi_amount + ' - ';
        schemeDetail += emi_type + ' INSTALLMENT - ';
        schemeDetail += number_of_emi + ' EMI - TENOUR ';
        schemeDetail += tenour + ' - ';
        schemeDetail += roi + ' % ROI)';
        $('input[name="hyp_financer_description"]').val(schemeDetail);
        $('#ajaxModalCommon').modal('hide');
    });
</script>
