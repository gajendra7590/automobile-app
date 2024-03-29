@php
    $priceDis = isset($data['gst_rto_rate_id']) && $data['gst_rto_rate_id'] > 0 ? '' : 'readonly';
    $editReadOnly = isset($action) && $action == 'edit' ? 'readonly' : '';
    $editDisable = isset($action) && $action == 'edit' ? 'disabled' : '';
@endphp
<div class="row">
    <div class="form-group col-md-3">
        <label>RTO Agent </label>
        <select name="rto_agent_id" class="form-control">
            @if (isset($rto_agents))
                @foreach ($rto_agents as $key => $rto_agent)
                    <option
                        {{ (isset($data->rto_agent_id) && $data->rto_agent_id == $rto_agent->id) || $key == 0 ? 'selected' : '' }}
                        value="{{ $rto_agent->id }}">{{ $rto_agent->agent_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Contact Name</label>
        <input type="text" class="form-control" placeholder="Contact Name" name="contact_name"
            value='{{ isset($data['contact_name']) ? $data['contact_name'] : '' }}' {{ $editReadOnly }} />
    </div>
    <div class="form-group col-md-3">
        <label>Contact Mobile Number</label>
        <input type='contact_mobile_number' class="form-control" placeholder="Contact Mobile Number"
            name="contact_mobile_number"
            value="{{ isset($data['contact_mobile_number']) ? $data['contact_mobile_number'] : '' }}"
            {{ $editReadOnly }} />
    </div>
    <div class="form-group col-md-3">
        <label>Contact Address Line</label>
        <input type='text' class="form-control" placeholder="Contact Address Line" name="contact_address_line"
            value="{{ isset($data['contact_address_line']) ? $data['contact_address_line'] : '' }}"
            {{ $editReadOnly }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label>Contact State</label>
        <select name="contact_state_id" data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
            data-dep_dd_name="district" data-dep_dd2_name="city" class="form-control ajaxChangeCDropDown"
            {{ $editDisable }}>
            <option value="">---Select State---</option>
            @if (isset($states))
                @foreach ($states as $key => $state)
                    <option
                        {{ isset($data['contact_state_id']) && $data['contact_state_id'] == $state->id ? 'selected' : '' }}
                        value="{{ $state->id }}">{{ $state->state_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Contact District</label>
        <select name="contact_district_id" data-dep_dd_name="city"
            data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" class="form-control ajaxChangeCDropDown"
            {{ $editDisable }}>
            <option value="">---Select District---</option>
            @if (isset($districts))
                @foreach ($districts as $key => $district)
                    <option
                        {{ isset($data['contact_district_id']) && $data['contact_district_id'] == $district->id ? 'selected' : '' }}
                        value="{{ $district->id }}">{{ $district->district_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Contact City</label>
        <div class="input-group col-sm-12">
            <select name="contact_city_id" class="form-control commonSelect2" style="width:100%" {{ $editDisable }}>
                <option value="">---Select City---</option>
                @if (isset($cities))
                    @foreach ($cities as $key => $city)
                        <option
                            {{ isset($data['contact_city_id']) && $data['contact_city_id'] == $city->id ? 'selected="selected"' : '' }}
                            value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label>Contact Zipcode</label>
        <input type='number' class="form-control" placeholder="Zipcode" name="contact_zipcode"
            value="{{ isset($data['contact_zipcode']) ? $data['contact_zipcode'] : '' }}" {{ $editReadOnly }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label>Chasis Number</label>
        <input type='text' class="form-control" placeholder="Chasis Number" name="chasis_number" readonly
            value="{{ isset($data['chasis_number']) ? $data['chasis_number'] : '' }}" />
    </div>
    <div class="form-group col-md-4">
        <label>Engine Number</label>
        <input type='text' class="form-control" placeholder="Engine Number" name="engine_number" readonly
            value="{{ isset($data['engine_number']) ? $data['engine_number'] : '' }}" />
    </div>
    <div class="form-group col-md-4">
        <label>Broker Name(If Any)</label>
        <input type="hidden" name="broker_id" value="{{ isset($data['broker_id']) ? $data['broker_id'] : 0 }}" />
        <input type='text' class="form-control" placeholder="Broker Name(If Any)" name="broker_name" readonly
            value="{{ isset($data['broker_name']) ? $data['broker_name'] : '' }}" />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label>SKU</label>
        <input type='text' class="form-control" placeholder="SKU" name="sku" readonly
            value="{{ isset($data['sku']) ? $data['sku'] : '' }}" />
    </div>
    <div class="form-group col-md-3">
        <label>Financer Name</label>
        <input type='text' class="form-control" placeholder="Financer Name" name="financer_name" readonly
            value="{{ isset($data['financer_name']) ? $data['financer_name'] : '' }}" />
    </div>
    <div class="form-group col-md-3">
        <label>GST Rate (TAX RATE)</label>
        <select name="gst_rto_rate_id" class="form-control onChangeSelect">
            <option value="">---GST Rate (TAX RATE)---</option>
            @if (isset($gst_rto_rates))
                @foreach ($gst_rto_rates as $key => $gst_rto_rate)
                    <option
                        {{ isset($data['gst_rto_rate_id']) && $data['gst_rto_rate_id'] == $gst_rto_rate->id ? 'selected="selected"' : '' }}
                        value="{{ $gst_rto_rate->id }}" data-percentage="{{ $gst_rto_rate->gst_rate }}">
                        {{ $gst_rto_rate->gst_rate }}</option>
                @endforeach
            @endif
        </select>
        <input type='hidden' name="gst_rto_rate_percentage"
            value="{{ isset($data['gst_rto_rate_percentage']) ? $data['gst_rto_rate_percentage'] : '' }}" />
    </div>
    <div class="form-group col-md-3">
        <label>Ex Showroom Amount</label>
        <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="ex_showroom_amount"
            value="{{ isset($data['ex_showroom_amount']) ? $data['ex_showroom_amount'] : '' }}"
            {{ $priceDis }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label>Tax Amount</label>
        <input type='text' class="form-control" placeholder="₹0.00" name="tax_amount"
            value="{{ isset($data['tax_amount']) ? $data['tax_amount'] : '' }}" {{ $priceDis }} readonly />
    </div>
    <div class="form-group col-md-3">
        <label>HYP Amount</label>
        <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="hyp_amount"
            value="{{ isset($data['hyp_amount']) ? $data['hyp_amount'] : '' }}" {{ $priceDis }} />
    </div>
    <div class="form-group col-md-3">
        <label>TR Amount</label>
        <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="tr_amount"
            value="{{ isset($data['tr_amount']) ? $data['tr_amount'] : '' }}" {{ $priceDis }} />
    </div>
    <div class="form-group col-md-3">
        <label>Fees</label>
        <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="fees"
            value="{{ isset($data['fees']) ? $data['fees'] : '' }}" {{ $priceDis }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label>Total Amount</label>
        <input type='text' class="form-control" placeholder="₹0.00" name="total_amount"
            value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}" readonly />
    </div>
    <div class="form-group col-md-9">
        <label>RTO Registration Remark(If Any)</label>
        <input type='textarea' class="form-control" placeholder="Remark" name="remark"
            value="{{ isset($data['remark']) ? $data['remark'] : '' }}"
            {{ isset($data['remark']) && !empty($data['remark']) ? 'disabled' : '' }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label>RC Number</label>
        <input type='text' class="form-control" placeholder="RC Number" name="rc_number"
            value="{{ isset($data['rc_number']) ? $data['rc_number'] : '' }}"
            {{ isset($data['rc_number']) && !empty($data['rc_number']) ? 'disabled' : '' }} />
    </div>
    <div class="form-group col-md-6">
        <label>RC Status</label>
        <select name="rc_status" class="form-control">
            <option value="">---Select RC Status---</option>
            <option {{ isset($data['rc_status']) && $data['rc_status'] == 1 ? 'selected' : '' }} value="1">Yes
            </option>
            <option {{ isset($data['rc_status']) && $data['rc_status'] == 0 ? 'selected' : '' }} value="0">No
            </option>
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label>Submit Date</label>
        <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="submit_date"
            value="{{ isset($data['submit_date']) ? $data['submit_date'] : '' }}"
            {{ isset($data['submit_date']) && !empty($data['submit_date']) ? 'disabled' : '' }} />
    </div>
    <div class="form-group col-md-6">
        <label>Recieved Date</label>
        <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="recieved_date"
            value="{{ isset($data['recieved_date']) ? $data['recieved_date'] : '' }}"
            {{ isset($data['recieved_date']) && !empty($data['recieved_date']) ? 'disabled' : '' }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label>Customer Given Name(Whom Given)</label>
        <input type='text' class="form-control" name="customer_given_name"
            value="{{ isset($data['customer_given_name']) ? $data['customer_given_name'] : '' }}"
            {{ isset($data['customer_given_name']) && !empty($data['customer_given_name']) ? 'disabled' : '' }}
            placeholder="Customer Given Name" />
    </div>
    <div class="form-group col-md-6">
        <label>Customer Given Date</label>
        <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="customer_given_date"
            value="{{ isset($data['customer_given_date']) ? $data['customer_given_date'] : '' }}"
            {{ isset($data['customer_given_date']) && !empty($data['customer_given_date']) ? 'disabled' : '' }} />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label>Customer Given Note(If Any):</label>
        <input type='text' class="form-control" name="customer_given_note"
            value="{{ isset($data['customer_given_note']) ? $data['customer_given_note'] : '' }}"
            {{ isset($data['customer_given_note']) && !empty($data['customer_given_note']) ? 'disabled' : '' }}
            placeholder="Customer Given Note" />
    </div>
</div>

<script>
    $(".commonSelect2").select2({
        placeholder: "Select an option",
    });
</script>
