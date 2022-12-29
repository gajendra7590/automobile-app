<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-3">
            <label>Sale</label>
            <select name="sale_id" class="form-control ">
                <option value="">---Select Sale---</option>
                @if (isset($sales))
                    @foreach ($sales as $key => $sale)
                        <option {{ isset($data->sale_id) && $data->sale_id == $sale->id ? 'selected' : '' }}
                            value="{{ $sale->id }}">{{ $sale->customer_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>RTO Agent </label>
            <select name="rto_agent_id" class="form-control ">
                <option value="">---Select RTO Agent---</option>
                @if (isset($rto_agents))
                    @foreach ($rto_agents as $key => $rto_agent)
                        <option
                            {{ isset($data->rto_agent_id) && $data->rto_agent_id == $rto_agent->id ? 'selected' : '' }}
                            value="{{ $rto_agent->id }}">{{ $rto_agent->agent_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>Contact Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Contact Name"
                required name="contact_name" value='{{ isset($data) && $data ? $data->contact_name : '' }}' />
        </div>
        <div class="form-group col-md-3">
            <label>Contact mobile Number</label>
            <input type='contact_mobile_number' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact mobile Number" required name="contact_mobile_number"
                value="{{ isset($data) && $data ? $data->contact_mobile_number : '' }}" />
        </div>
        <div class="form-group col-md-12">
            <label>Contact Address Line</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact Address Line" required name="contact_address_line"
                value="{{ isset($data) && $data ? $data->contact_address_line : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Contact State</label>
            <select name="contact_state_id" data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
                data-dep_dd_name="district" data-dep_dd2_name="city" class="form-control ajaxChangeCDropDown">
                <option value="">---Select State---</option>
                @if (isset($states))
                    @foreach ($states as $key => $state)
                        <option
                            {{ isset($data->contact_state_id) && $data->contact_state_id == $state->id ? 'selected' : '' }}
                            value="{{ $state->id }}">{{ $state->state_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Contact District</label>
            <select name="contact_district_id" data-dep_dd_name="city"
                data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" class="form-control ajaxChangeCDropDown">
                <option value="">---Select District---</option>
                @if (isset($districts))
                    @foreach ($districts as $key => $district)
                        <option
                            {{ isset($data->contact_district_id) && $data->contact_district_id == $district->id ? 'selected' : '' }}
                            value="{{ $district->id }}">{{ $district->district_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Contact City</label>
            <select name="contact_district_id" class="form-control">
                <option value="">---Select City---</option>
                @if (isset($cities))
                    @foreach ($cities as $key => $city)
                        <option
                            {{ isset($data->contact_city_id) && $data->contact_city_id == $city->id ? 'selected="selected"' : '' }}
                            value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Contact Zipcode</label>
            <input type='number' class="form-control my-colorpicker1 colorpicker-element" placeholder="Zipcode"
                name="contact_zipcode"
                value="{{ isset($data) && $data->contact_zipcode ? $data->contact_zipcode : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>SKU</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="SKU"
                name="sku" value="{{ isset($data) && $data ? $data->sku : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Financer Name</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Financer Name"
                name="financer_name" value="{{ isset($data) && $data ? $data->financer_name : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>GST Rate</label>
            <select name="gst_rto_rate_id" class="form-control">
                <option value="">---Select GST Rate---</option>
                @if (isset($gst_rto_rates))
                    @foreach ($gst_rto_rates as $key => $gst_rto_rate)
                        <option
                            {{ isset($data->gst_rto_rates_id) && $data->gst_rto_rate_id == $gst_rto_rate->id ? 'selected="selected"' : '' }}
                            value="{{ $gst_rto_rate->id }}">{{ $gst_rto_rate->gst_rate }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>GST Rate Percentage</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="GST Rate Percentage" name="gst_rto_rate_percentage"
                value="{{ isset($data) && $data ? $data->gst_rto_rate_percentage : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Ex Showroom Amount</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Ex Showroom Amount" name="ex_showroom_amount"
                value="{{ isset($data) && $data ? $data->ex_showroom_amount : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>tax Amount</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="tax Amount"
                name="tax_amount" value="{{ isset($data) && $data ? $data->tax_amount : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>HYP Amount</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="HYP Amount"
                name="hyp_amount" value="{{ isset($data) && $data ? $data->hyp_amount : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>TR Amount</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="TR Amount"
                name="tr_amount" value="{{ isset($data) && $data ? $data->tr_amount : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Fees</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Fees"
                name="fees" value="{{ isset($data) && $data ? $data->fees : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Total Amount</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Total Amount"
                name="total_amount" value="{{ isset($data) && $data ? $data->total_amounttotal_amount : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Payment Amout</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Payment Amout" name="payment_amout"
                value="{{ isset($data) && $data ? $data->payment_amout : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Outstanding</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Outstanding"
                name="outstanding" value="{{ isset($data) && $data ? $data->outstanding : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>RC Number</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="RC Number"
                name="rc_number" value="{{ isset($data) && $data ? $data->rc_number : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>RC Status</label>
            <select name="gst_rto_rate_id" class="form-control">
                <option value="">---Select RC Status---</option>
                <option {{ isset($data->rc_status) && $data->rc_status == 1 ? 'selected' : '' }} value="1">Yes
                </option>
                <option {{ isset($data->rc_status) && $data->rc_status == 0 ? 'selected' : '' }} value="0">No
                </option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Bike Number</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Bike Number"
                name="bike_number" value="{{ isset($data) && $data ? $data->bike_number : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Active Status</label>
            <select name="gst_rto_rate_id" class="form-control">
                <option value="">---Select Active Status---</option>
                <option {{ isset($data->active_status) && $data->active_status == 1 ? 'selected' : '' }}
                    value="1">Active</option>
                <option {{ isset($data->active_status) && $data->active_status == 0 ? 'selected' : '' }}
                    value="0">In Active</option>
            </select>
        </div>
        <div class="form-group col-md-12">
            <label>Remark</label>
            <input type='textarea' class="form-control my-colorpicker1 colorpicker-element" placeholder="Remark"
                name="remark" value="{{ isset($data) && $data ? $data->remark : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12">
            <div class="box-footer">
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
