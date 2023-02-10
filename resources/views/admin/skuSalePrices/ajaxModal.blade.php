<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">

        <div class="row">
            <div class="form-group col-md-4">
                <label>SKU Code</label>
                <select class="form-control" name="model_color_id">
                    @if (!isset($data->id))
                        <option value="">---SELECT SKU CODE---</option>
                    @endif
                    @isset($sku_codes)
                        @foreach ($sku_codes as $sku_code)
                            <option value="{{ $sku_code->id }}">{{ $sku_code->sku_code }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>EX SHOWROOM PRICE</label>
                <input type="text" class="form-control skuSalesPrice" placeholder="₹0.00" name="ex_showroom_price"
                    value='{{ isset($data->ex_showroom_price) ? $data->ex_showroom_price : '' }}' />
            </div>
            <div class="form-group col-md-4">
                <label>REGISTRATION AMOUNT</label>
                <input type="text" class="form-control skuSalesPrice" placeholder="₹0.00" name="registration_amount"
                    value='{{ isset($data->registration_amount) ? $data->registration_amount : '' }}' />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>INSURANCE AMOUNT</label>
                <input type="text" class="form-control skuSalesPrice" placeholder="₹0.00" name="insurance_amount"
                    value='{{ isset($data->insurance_amount) ? $data->insurance_amount : '' }}' />
            </div>
            <div class="form-group col-md-4">
                <label>HYPOTHECATION AMOUNT</label>
                <input type="text" class="form-control skuSalesPrice" placeholder="₹0.00" name="hypothecation_amount"
                    value='{{ isset($data->hypothecation_amount) ? $data->hypothecation_amount : '' }}' />
            </div>
            <div class="form-group col-md-4">
                <label>ACCESSORIES AMOUNT</label>
                <input type="text" class="form-control skuSalesPrice" placeholder="₹0.00" name="accessories_amount"
                    value='{{ isset($data->accessories_amount) ? $data->accessories_amount : '' }}' />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>OTHER CHARGES</label>
                <input type="text" class="form-control skuSalesPrice" placeholder="₹0.00" name="other_charges"
                    value='{{ isset($data->other_charges) ? $data->other_charges : '' }}' />
            </div>
            <div class="form-group col-md-4">
                <label>GRAND TOTAL</label>
                <input type="text" class="form-control" placeholder="₹0.00" name="total_amount"
                    value='{{ isset($data->total_amount) ? $data->total_amount : '' }}' readonly />
            </div>
            <div class="form-group col-md-4">
                <label>Status : </label>
                <select class="form-control" name="active_status">
                    <option value="1"
                        {{ isset($data['active_status']) && $data['active_status'] == '1' ? 'selected="selected"' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ isset($data['active_status']) && $data['active_status'] == '0' ? 'selected="selected"' : '' }}>
                        In
                        Active
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                        @if (isset($method) && $method == 'PUT')
                            UPDATE
                        @else
                            CREATE
                        @endif
                    </button>
                </div>
            </div>
        </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            model_color_id: {
                required: true
            },
            ex_showroom_price: {
                required: true,
                number: true
            },
            registration_amount: {
                required: true,
                number: true
            },
            insurance_amount: {
                required: true,
                number: true
            },
            hypothecation_amount: {
                required: true,
                number: true
            },
            accessories_amount: {
                required: true,
                number: true
            },
            other_charges: {
                required: false,
                number: true
            }
        },
        messages: {
            model_color_id: {
                required: "The SKU code field is required"
            },
            ex_showroom_price: {
                required: "The Ex showrom price field is required",
                number: "The Ex showrom price should valid amount"
            },
            registration_amount: {
                required: "The Registration amount field is required",
                number: "The Registration amount should valid amount"
            },
            insurance_amount: {
                required: "The Insurance amount field is required",
                number: "The Insurance amount should valid amount"
            },
            hypothecation_amount: {
                required: "The Hyp amount field is required",
                number: "The Hyp amount should valid amount"
            },
            accessories_amount: {
                required: "The Accessories amount field is required",
                number: "The Accessories amount should valid amount"
            },
            other_charges: {
                required: "The Other charges field is required",
                number: "The Other charges should valid amount"
            }
        }
    });
</script>
