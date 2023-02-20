<table class="table table-bordered">
    <tr>
        <td width="45%">SALESMAN NAME</td>
        <td width="45%">
            <select name="salesman_id" class="form-control">
                <option value="">---Select Salesman---</option>
                @if (isset($salesmans))
                    @foreach ($salesmans as $key => $salesman)
                        <option
                            {{ isset($data['salesman_id']) && $data['salesman_id'] == $salesman->id ? 'selected' : '' }}
                            value="{{ $salesman->id }}">{{ $salesman->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="salesman_id" data-input_type="select">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">PREFIX</td>
        <td width="45%">
            <select name="customer_gender" class="form-control">
                <option {{ isset($data['customer_gender']) && $data['customer_gender'] == '1' ? 'selected' : '' }}
                    value="1">Mr.</option>
                <option {{ isset($data['customer_gender']) && $data['customer_gender'] == '2' ? 'selected' : '' }}
                    value="2">Mrs.</option>
                <option {{ isset($data['customer_gender']) && $data['customer_gender'] == '3' ? 'selected' : '' }}
                    value="3">Miss</option>
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_gender"
                data-input_type="select">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER NAME</td>
        <td width="45%">
            <input name="customer_name" type="text" class="form-control"
                value="{{ isset($data['customer_name']) ? $data['customer_name'] : '' }}" placeholder="Customer Name..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_name" data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">RELATIONSHIP</td>
        <td width="45%">
            <select name="customer_relationship" class="form-control">
                <option
                    {{ isset($data['customer_relationship']) && $data['customer_relationship'] == '1' ? 'selected' : '' }}
                    value="1">S/o</option>
                <option
                    {{ isset($data['customer_relationship']) && $data['customer_relationship'] == '2' ? 'selected' : '' }}
                    value="2">W/o</option>
                <option
                    {{ isset($data['customer_relationship']) && $data['customer_relationship'] == '3' ? 'selected' : '' }}
                    value="3">D/o</option>
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_relationship"
                data-input_type="select">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER GUARDIAN NAME</td>
        <td width="45%">
            <input name="customer_guardian_name" type="text" class="form-control"
                value="{{ isset($data['customer_guardian_name']) ? $data['customer_guardian_name'] : '' }}"
                placeholder="Customer Guardian Name..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_guardian_name"
                data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER ADDRESS LINE</td>
        <td width="45%">
            <input name="customer_address_line" type="text" class="form-control"
                value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
                placeholder="Customer Address Line..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_address_line"
                data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER STATE</td>
        <td width="45%">
            <select name="customer_state" data-dep_dd_name="customer_district"
                data-url="{{ url('getAjaxDropdown') . '?req=districts' }}" data-dep_dd2_name="customer_city"
                class="form-control ajaxChangeCDropDown">
                <option value="">---Select Customer State---</option>
                @isset($states)
                    @foreach ($states as $state)
                        <option
                            {{ isset($data['customer_state']) && $data['customer_state'] == $state->id ? 'selected="selected"' : '' }}
                            value="{{ $state->id }}">{{ $state->state_name }}</option>
                    @endforeach
                @endisset
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_state"
                data-input_type="select">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER DISTRICT</td>
        <td width="45%">
            <select name="customer_district" class="form-control ajaxChangeCDropDown" data-dep_dd_name="customer_city"
                data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" data-dep_dd2_name="">
                <option value="">---Select District---</option>
                @isset($districts)
                    @foreach ($districts as $district)
                        <option
                            {{ isset($data['customer_district']) && $data['customer_district'] == $district->id ? 'selected="selected"' : '' }}
                            value="{{ $district->id }}">{{ $district->district_name }}</option>
                    @endforeach
                @endisset
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_district"
                data-input_type="select">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER CITY/VILLEGE</td>
        <td width="45%">
            <select name="customer_city" class="form-control commonSelect2">
                <option value="">---Select City/Village----</option>
                @isset($cities)
                    @foreach ($cities as $city)
                        <option
                            {{ isset($data['customer_city']) && $data['customer_city'] == $city->id ? 'selected="selected"' : '' }}
                            value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                @endisset
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_city"
                data-input_type="select">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">ZIPCODE</td>
        <td width="45%">
            <input name="customer_zipcode" type="text" class="form-control"
                value="{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '' }}" placeholder="XXXXXX">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_zipcode"
                data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER PHONE NUMBER</td>
        <td width="45%">
            <input name="customer_mobile_number" type="text" class="form-control"
                value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
                placeholder="Customer Phone..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_mobile_number"
                data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER ALTERNATE PHONE NUMBER</td>
        <td width="45%">
            <input name="customer_mobile_number_alt" type="text" class="form-control"
                value="{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '' }}"
                placeholder="Customer Altenate Phone..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_mobile_number_alt"
                data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <td width="45%">CUSTOMER EMAIL</td>
        <td width="45%">
            <input name="customer_email_address" type="text" class="form-control"
                value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
                placeholder="Customer Email..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_email_address"
                data-input_type="input">UPDATE</a>
        </td>
    </tr>
    <tr>
        <input type="hidden" name="url" value="{{ route('updateNonEditableDetail.store') }}">
        <input type="hidden" name="id" value="{{ isset($data['id']) ? $data['id'] : '0' }}" />
        <input type="hidden" name="type" value="quotaions" />
    </tr>
</table>
