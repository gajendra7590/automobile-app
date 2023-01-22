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
                    <label>Purchase Model</label>
                    <select name="purchase_id" class="form-control">
                        @if (isset($purchases))
                            @foreach ($purchases as $key => $purchase)
                                <option value="{{ $purchase->id }}">
                                    SKU - {{ $purchase->sku }} | VAR - {{ $purchase->variant }} | DCN
                                    -{{ $purchase->dc_number }} |
                                    VIN - {{ $purchase->vin_number }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                <input type="hidden" name="purchase_id" value="{{ isset($data['id']) ? $data['id'] : '' }}">
            @endif
            <div class="form-group col-md-6">
                <label>Purchase Invoice Number</label>
                <input type="text" name="purchase_invoice_number" class="form-control"
                    value="{{ isset($data['purchase_invoice_number']) ? $data['purchase_invoice_number'] : '' }}"
                    placeholder="XXXXXXXXXXXXXX">
            </div>
            <div class="form-group col-md-6">
                <label>Purchase Invoice Date</label>
                <input type="date" name="purchase_invoice_date" placeholder="yyyy-mm-dd" class="form-control"
                    value="{{ isset($data['purchase_invoice_date']) ? $data['purchase_invoice_date'] : '' }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2">
                <label>GST Rate</label>
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
            <div class="form-group col-md-5">
                <label>Invoice Actual Price</label>
                <input type="text" name="pre_gst_amount" class="form-control totalAmountCalInv totalAmountCal2Inv"
                    placeholder="₹0.00" value="{{ isset($data['pre_gst_amount']) ? $data['pre_gst_amount'] : '' }}" />
            </div>
            <div class="form-group col-md-5">
                <label>Invoice Discount Amount</label>
                <input type="text" name="discount_price" class="form-control totalAmountCalInv" placeholder="₹0.00"
                    value="{{ isset($data['discount_price']) ? $data['discount_price'] : '' }}" />
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label>Invoice GST Amount</label>
                <input type="text" name="gst_amount" class="form-control totalAmountCalInv totalAmountCal2Inv"
                    placeholder="₹0.00" value="{{ isset($data['gst_amount']) ? $data['gst_amount'] : '' }}" readonly />
            </div>
            <div class="form-group col-md-4">
                <label>Invoice Ex Showroom Price</label>
                <input type="text" name="ex_showroom_price" class="form-control" placeholder="₹0.00"
                    value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}" readonly />
            </div>
            <div class="form-group col-md-4">
                <label>Invoice Grand Total</label>
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
                        SAVE
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {

    });
</script>
