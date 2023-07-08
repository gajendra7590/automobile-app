<form class="ajaxFormSubmitFinanaceDetail">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-2">
            <label>FIRST DP AMT</label>
            <input type="text" class="form-control" placeholder="₹0.00" name="first_dp_amount" value='' />
        </div>
        <div class="form-group col-md-2">
            <label>FIRST DP DAYS</label>
            <input type="text" class="form-control" placeholder="" name="first_dp_days" value='0' />
        </div>

        <div class="form-group col-md-2">
            <label>SECOND DP AMT</label>
            <input type="text" class="form-control" placeholder="₹0.00" name="second_dp_amount" value='' />
        </div>
        <div class="form-group col-md-2">
            <label>SECOND DP DAYS</label>
            <input type="text" class="form-control" placeholder="" name="second_dp_days" value='0' />
        </div>

        <div class="form-group col-md-2">
            <label>THIRD DP AMT</label>
            <input type="text" class="form-control" placeholder="₹0.00" name="third_dp_amount" value='' />
        </div>
        <div class="form-group col-md-2">
            <label>THIRD DP DAYS</label>
            <input type="text" class="form-control" placeholder="" name="third_dp_days" value='0' />
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

        let first_dp_amount = parseFloat($("input[name='first_dp_amount']").val());
        let second_dp_amount = parseFloat($("input[name='second_dp_amount']").val());
        let third_dp_amount = parseFloat($("input[name='third_dp_amount']").val());

        let first_dp_days = parseFloat($("input[name='first_dp_days']").val());
        let second_dp_days = parseFloat($("input[name='second_dp_days']").val());
        let third_dp_days = parseFloat($("input[name='third_dp_days']").val());

        let schemeDetail = "1ST DP AMT ₹" + first_dp_amount + " DAY LIMIT " + first_dp_days + " | ";
        schemeDetail += '2ND DP AMT ₹' + second_dp_amount + " DAY LIMIT " + second_dp_days + " | ";
        schemeDetail += '3RD DP AMT ₹' + third_dp_amount + " DAY LIMIT " + third_dp_days;
        $('input[name="hyp_financer_description"]').val(schemeDetail);
        $('#ajaxModalCommon').modal('hide');
    });
</script>
