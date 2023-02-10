<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            @if ($method == 'POST')
                <div class="form-group col-md-12">
                    <label>PURCHASE MODEL</label>
                    <select name="purchase_id" class="form-control select2">
                        @if (isset($purchases))
                            @foreach ($purchases as $key => $purchase)
                                <option value="{{ $purchase->id }}">
                                    {{ $purchase->vin_number }} | {{ $purchase->engine_number }} |
                                    {{ isset($purchase->brand->name) ? $purchase->brand->name : '' }}
                                    - {{ isset($purchase->model->model_name) ? $purchase->model->model_name : '' }}
                                    - {{ isset($purchase->color->color_name) ? $purchase->color->color_name : '' }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                <input type="hidden" name="purchase_id" value="{{ isset($data['id']) ? $data['id'] : '' }}">
            @endif
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>PURCHASE INVOICE NUMBER</label>
                <input type="text" name="purchase_invoice_number" class="form-control"
                    value="{{ isset($data['purchase_invoice_number']) ? $data['purchase_invoice_number'] : '' }}"
                    placeholder="XXXXXXXXXXXXXX">
            </div>
            <div class="form-group col-md-6">
                <label>PURCHASE INVOICE DATE</label>
                <input type="date" name="purchase_invoice_date" placeholder="yyyy-mm-dd" class="form-control"
                    value="{{ isset($data['purchase_invoice_date']) ? $data['purchase_invoice_date'] : date('Y-m-d') }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>GST RATE(TAX RATE)</label>
                <select name="gst_rate_id" id="gst_rate" class="form-control">
                    @if (isset($gst_rates))
                        @foreach ($gst_rates as $key => $gst_rate)
                            <option
                                {{ isset($data['gst_rate_id']) && $data['gst_rate_id'] == $gst_rate->gst_rate ? 'selected' : '' }}
                                value="{{ $gst_rate->id }}" data-rate="{{ $gst_rate->gst_rate }}">
                                {{ $gst_rate->gst_rate }}%</option>
                        @endforeach
                    @endif
                </select>
                <input type="hidden" name="gst_rate_percent" id="gst_rate_percent"
                    value="{{ isset($data['gst_rate_percent']) ? $data['gst_rate_percent'] : 0 }}">
            </div>
            <div class="form-group col-md-4">
                <label>INVOICE ACTUAL PRICE</label>
                <input type="text" name="pre_gst_amount" class="form-control totalAmountCalInv totalAmountCal2Inv"
                    placeholder="₹0.00" value="{{ isset($data['pre_gst_amount']) ? $data['pre_gst_amount'] : '' }}" />
            </div>
            <div class="form-group col-md-5">
                <label>INVOICE DISCOUNT PRICE</label>
                <input type="text" name="discount_price" class="form-control totalAmountCalInv" placeholder="₹0.00"
                    value="{{ isset($data['discount_price']) ? $data['discount_price'] : '' }}" />
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label>INVOICE GST AMOUNT</label>
                <input type="text" name="gst_amount" class="form-control totalAmountCalInv totalAmountCal2Inv"
                    placeholder="₹0.00" value="{{ isset($data['gst_amount']) ? $data['gst_amount'] : '' }}" readonly />
            </div>
            <div class="form-group col-md-4">
                <label>INVOICE EX-SHOWROOM PRICE</label>
                <input type="text" name="ex_showroom_price" class="form-control" placeholder="₹0.00"
                    value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}" readonly />
            </div>
            <div class="form-group col-md-4">
                <label>INVOICE GRAND TOTAL</label>
                <input type="text" name="grand_total" class="form-control" placeholder="₹0.00"
                    value="{{ isset($data['grand_total']) ? $data['grand_total'] : '' }}" readonly />
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
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
            purchase_id: {
                required: true
            },
            purchase_invoice_number: {
                required: true
            },
            purchase_invoice_date: {
                required: true,
                date: true
            },
            pre_gst_amount: {
                required: true,
                number: true
            },
            discount_price: {
                required: false,
                number: true
            }
        },
        messages: {
            purchase_id: {
                required: "The purchase field is required."
            },
            purchase_invoice_number: {
                required: "The purchase invoice number field is required."
            },
            purchase_invoice_date: {
                required: "The purchase invoice date field is required.",
                date: "The purchase invoice date should valid date."
            },
            pre_gst_amount: {
                required: "The invoice actual price field is required.",
                number: "The invoice actual price should valid number."
            },
            discount_price: {
                required: "The discount price field is required.",
                number: "The discount price should valid number."
            }
        }
    });
</script>
