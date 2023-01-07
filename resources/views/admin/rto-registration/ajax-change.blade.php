@php
    $priceDis = isset($data['gst_rto_rate_id']) && $data['gst_rto_rate_id'] > 0 ? '' : 'readonly';
    $editReadOnly = isset($action) && $action == 'edit' ? 'readonly' : '';
    $editDisable = isset($action) && $action == 'edit' ? 'disabled' : '';
@endphp
<div class="form-group col-md-3">
    <label>Contact Name</label>
    <input type="text" class="form-control" placeholder="Contact Name" name="contact_name"
        value='{{ isset($data['contact_name']) ? $data['contact_name'] : '' }}' {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>Contact Mobile Number</label>
    <input type='contact_mobile_number' class="form-control" placeholder="Contact Mobile Number"
        name="contact_mobile_number"
        value="{{ isset($data['contact_mobile_number']) ? $data['contact_mobile_number'] : '' }}" {{ $editReadOnly }} />
</div>
<div class="form-group col-md-6">
    <label>Contact Address Line</label>
    <input type='text' class="form-control" placeholder="Contact Address Line" name="contact_address_line"
        value="{{ isset($data['contact_address_line']) ? $data['contact_address_line'] : '' }}" {{ $editReadOnly }} />
</div>
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
    <select name="contact_district_id" data-dep_dd_name="city" data-url="{{ url('getAjaxDropdown') . '?req=cities' }}"
        class="form-control ajaxChangeCDropDown" {{ $editDisable }}>
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
    <select name="contact_city_id" class="form-control" {{ $editDisable }}>
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
<div class="form-group col-md-3">
    <label>Contact Zipcode</label>
    <input type='number' class="form-control" placeholder="Zipcode" name="contact_zipcode"
        value="{{ isset($data['contact_zipcode']) ? $data['contact_zipcode'] : '' }}" {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>SKU</label>
    <input type='text' class="form-control" placeholder="SKU" name="sku"
        value="{{ isset($data['sku']) ? $data['sku'] : '' }}" />
</div>
<div class="form-group col-md-4">
    <label>Financer Name</label>
    <input type='text' class="form-control" placeholder="Financer Name" name="financer_name"
        value="{{ isset($data['financer_name']) ? $data['financer_name'] : '' }}" />
</div>
<div class="form-group col-md-2">
    <label>GST Rate</label>
    <select name="gst_rto_rate_id" class="form-control onChangeSelect" {{ $editDisable }}>
        <option value="">---GST Rate---</option>
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
        value="{{ isset($data['gst_rto_rate_percentage']) ? $data['gst_rto_rate_percentage'] : '' }}"
        {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>Ex Showroom Amount</label>
    <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="ex_showroom_amount"
        value="{{ isset($data['ex_showroom_amount']) ? $data['ex_showroom_amount'] : '' }}" {{ $priceDis }}
        {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>Tax Amount</label>
    <input type='text' class="form-control" placeholder="₹0.00" name="tax_amount"
        value="{{ isset($data['tax_amount']) ? $data['tax_amount'] : '' }}" {{ $priceDis }} readonly
        {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>HYP Amount</label>
    <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="hyp_amount"
        value="{{ isset($data['hyp_amount']) ? $data['hyp_amount'] : '' }}" {{ $priceDis }}
        {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>TR Amount</label>
    <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="tr_amount"
        value="{{ isset($data['tr_amount']) ? $data['tr_amount'] : '' }}" {{ $priceDis }} {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>Fees</label>
    <input type='text' class="form-control onChangeInput" placeholder="₹0.00" name="fees"
        value="{{ isset($data['fees']) ? $data['fees'] : '' }}" {{ $priceDis }} {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>Total Amount</label>
    <input type='text' class="form-control" placeholder="₹0.00" name="total_amount"
        value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}" readonly {{ $editReadOnly }} />
</div>
<div class="form-group col-md-3">
    <label>Payment Amount</label>
    <input type='text' class="form-control" placeholder="₹0.00" name="payment_amount"
        value="{{ isset($data['payment_amount']) ? $data['payment_amount'] : '' }}" />
</div>
<div class="form-group col-md-3">
    <label>Payment Date</label>
    <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="payment_date"
        value="{{ isset($data['payment_date']) ? $data['payment_date'] : '' }}" />
</div>
<div class="form-group col-md-3">
    <label>Outstanding</label>
    <input type='text' class="form-control" placeholder="Outstanding" name="outstanding"
        value="{{ isset($data['outstanding']) ? $data['outstanding'] : '' }}" />
</div>
<div class="form-group col-md-4">
    <label>RC Number</label>
    <input type='text' class="form-control" placeholder="RC Number" name="rc_number"
        value="{{ isset($data['rc_number']) ? $data['rc_number'] : '' }}" />
</div>
<div class="form-group col-md-4">
    <label>RC Status</label>
    <select name="rc_status" class="form-control">
        <option value="">---Select RC Status---</option>
        <option {{ isset($data['rc_status']) && $data['rc_status'] == 1 ? 'selected' : '' }} value="1">Yes
        </option>
        <option {{ isset($data['rc_status']) && $data['rc_status'] == 0 ? 'selected' : '' }} value="0">No
        </option>
    </select>
</div>
<div class="form-group col-md-4">
    <label>Submit Date</label>
    <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="submit_date"
        value="{{ isset($data['submit_date']) ? $data['submit_date'] : '' }}" />
</div>
<div class="form-group col-md-4">
    <label>Bike Number</label>
    <input type='text' class="form-control" placeholder="Bike Number" name="bike_number"
        value="{{ isset($data['bike_number']) ? $data['bike_number'] : '' }}" />
</div>
<div class="form-group col-md-4">
    <label>Recieved Date</label>
    <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="recieved_date"
        value="{{ isset($data['recieved_date']) ? $data['recieved_date'] : '' }}" />
</div>
<div class="form-group col-md-4">
    <label>Customer Given Date</label>
    <input type='date' class="form-control" placeholder="yyyy-mm-dd" name="customer_given_date"
        value="{{ isset($data['customer_given_date']) ? $data['customer_given_date'] : '' }}" />
</div>
<div class="form-group col-md-12">
    <label>Remark</label>
    <input type='textarea' class="form-control" placeholder="Remark" name="remark"
        value="{{ isset($data['remark']) ? $data['remark'] : '' }}" />
</div>
