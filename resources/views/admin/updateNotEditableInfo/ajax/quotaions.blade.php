<table class="table table-bordered">
    <tr>
        <th width="20%">BRANCH NAME</th>
        <td width="20%">
            <select name="branch_id" class="form-control" disabled>
                @if (isset($branches))
                    @foreach ($branches as $key => $branch)
                        <option {{ isset($data['branch_id']) && $data['branch_id'] == $branch->id ? 'selected' : '' }}
                            value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">SALESMAN NAME</th>
        <td width="20%">
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
            <a class="btn btn-primary updateFormInfo" data-field_name="salesman_id" data-input_type="select">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">PREFIX</th>
        <td width="20%">
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
                data-input_type="select">SAVE</a>
        </td>

        <th width="20%">CUSTOMER NAME</th>
        <td width="20%">
            <input name="customer_name" type="text" class="form-control"
                value="{{ isset($data['customer_name']) ? $data['customer_name'] : '' }}" placeholder="Customer Name..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_name" data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">RELATIONSHIP</th>
        <td width="20%">
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
                data-input_type="select">SAVE</a>
        </td>

        <th width="20%">CUSTOMER GUARDIAN NAME</th>
        <td width="20%">
            <input name="customer_guardian_name" type="text" class="form-control"
                value="{{ isset($data['customer_guardian_name']) ? $data['customer_guardian_name'] : '' }}"
                placeholder="Customer Guardian Name..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_guardian_name"
                data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">CUSTOMER ADDRESS LINE</th>
        <td width="20%">
            <input name="customer_address_line" type="text" class="form-control"
                value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
                placeholder="Customer Address Line..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_address_line"
                data-input_type="input">SAVE</a>
        </td>

        <th width="20%">CUSTOMER STATE</th>
        <td width="20%">
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
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_state" data-input_type="select">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">CUSTOMER DISTRICT</th>
        <td width="20%">
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
                data-input_type="select">SAVE</a>
        </td>

        <th width="20%">CUSTOMER CITY/VILLEGE</th>
        <td width="20%">
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
                data-input_type="select">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">ZIPCODE</th>
        <td width="20%">
            <input name="customer_zipcode" type="text" class="form-control"
                value="{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '' }}" placeholder="XXXXXX">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_zipcode"
                data-input_type="input">SAVE</a>
        </td>

        <th width="20%">CUSTOMER PHONE NUMBER</th>
        <td width="20%">
            <input name="customer_mobile_number" type="text" class="form-control"
                value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
                placeholder="Customer Phone..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_mobile_number"
                data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">CUSTOMER ALTERNATE PHONE NUMBER</th>
        <td width="20%">
            <input name="customer_mobile_number_alt" type="text" class="form-control"
                value="{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '' }}"
                placeholder="Customer Altenate Phone..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_mobile_number_alt"
                data-input_type="input">SAVE</a>
        </td>

        <th width="20%">CUSTOMER EMAIL</th>
        <td width="20%">
            <input name="customer_email_address" type="text" class="form-control"
                value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
                placeholder="Customer Email..">
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="customer_email_address"
                data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">IS EXCHNAGE</th>
        <td width="20%">
            <select class="form-control" name="is_exchange_avaliable" disabled>
                <option value="No"
                    {{ isset($data['is_exchange_avaliable']) && $data['is_exchange_avaliable'] == 'No' ? 'selected="selected"' : '' }}>
                    No
                </option>
                <option value="Yes"
                    {{ isset($data['is_exchange_avaliable']) && $data['is_exchange_avaliable'] == 'Yes' ? 'selected="selected"' : '' }}>
                    Yes
                </option>
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">PAYMENT TYPE</th>
        <td width="20%">
            <select name="payment_type" class="form-control" disabled>
                <option value="1"
                    {{ isset($data['payment_type']) && $data['payment_type'] == '1' ? 'selected="selected"' : '' }}>
                    Cash / Credit
                </option>
                <option value="2"
                    {{ isset($data['payment_type']) && $data['payment_type'] == '2' ? 'selected="selected"' : '' }}>
                    Finance
                </option>
                <option value="3"
                    {{ isset($data['payment_type']) && $data['payment_type'] == '3' ? 'selected="selected"' : '' }}>
                    Private Finance
                </option>
            </select>
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">HYPOTHECATION FINANCER</th>
        <td width="20%">
            <select name="hyp_financer" class="form-control" disabled>
                @isset($bank_financers)
                    @foreach ($bank_financers as $bank_financer)
                        <option
                            {{ isset($data['hyp_financer']) && $data['hyp_financer'] == $bank_financer->id ? 'selected="selected"' : '' }}
                            value="{{ $bank_financer->id }}">{{ $bank_financer->bank_name }}</option>
                    @endforeach
                @endisset
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">HYPOTHECATION FINANCER NOTE</th>
        <td width="20%">
            <input name="hyp_financer_description" type="text" class="form-control" disabled
                value="{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '' }}"
                placeholder="Description..." disabled>
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">BRAND NAME</th>
        <td width="20%">
            <select name="bike_brand" class="form-control" disabled>
                @if (isset($brands))
                    @foreach ($brands as $key => $brand)
                        <option
                            {{ (isset($data->bike_brand) && $data->bike_brand == $brand->id) || ($method && $method == 'POST' && $key == 0) ? 'selected="selected"' : '' }}
                            value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">MODEL NAME</th>
        <td width="20%">
            <select name="bike_model" class="form-control" disabled>
                @if (isset($models))
                    @foreach ($models as $key => $model)
                        <option
                            {{ isset($data->bike_model) && $data->bike_model == $model->id ? 'selected="selected"' : '' }}
                            value="{{ $model->id }}">
                            {{ $model->model_name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">MODEL VARIANT</th>
        <td width="20%">
            <select name="bike_model_variant" class="form-control" disabled>
                @if (isset($variants))
                    @foreach ($variants as $key => $variant)
                        <option
                            {{ isset($data->bike_model_variant) && $data->bike_model_variant == $variant->id ? 'selected="selected"' : '' }}
                            value="{{ $variant->id }}" data-variantCode="{{ $variant->variant_name }}">
                            {{ $variant->variant_name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">VARIANT COLOR</th>
        <td width="20%">
            <select name="bike_model_color" class="form-control" disabled>
                @if (isset($colors))
                    @foreach ($colors as $key => $color)
                        <option
                            {{ isset($data->bike_model_color) && $data->bike_model_color == $color->id ? 'selected="selected"' : '' }}
                            value="{{ $color->id }}"
                            data-skuCode="{{ isset($color->sku_code) ? $color->sku_code : '' }}">
                            {{ $color->color_name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">EX-SHOWROOM PRICE</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="ex_showroom_price"
                value="{{ isset($data->ex_showroom_price) ? $data->ex_showroom_price : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
        <th width="20%">REGISTRATION AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="registration_amount"
                value="{{ isset($data->registration_amount) ? $data->registration_amount : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">INSURANCE AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="insurance_amount"
                value="{{ isset($data->insurance_amount) ? $data->insurance_amount : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
        <th width="20%">HYPOTHECATION AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="hypothecation_amount"
                value="{{ isset($data->hypothecation_amount) ? $data->hypothecation_amount : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">ACCESSORIES AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="accessories_amount"
                value="{{ isset($data->accessories_amount) ? $data->accessories_amount : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
        <th width="20%">OTHER AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="other_charges"
                value="{{ isset($data->other_charges) ? $data->other_charges : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">GRAND TOTAL AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="₹0.00" name="total_amount"
                value="{{ isset($data->total_amount) ? $data->total_amount : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
        <th width="20%">QUOTATIONS DATE</th>
        <td width="20%">
            <input type="date" class="form-control" placeholder="" name="purchase_visit_date"
                value="{{ isset($data->purchase_visit_date) ? $data->purchase_visit_date : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>
    <tr>
        <th width="20%">DELIVERY DATE</th>
        <td width="20%">
            <input type="date" class="form-control" placeholder="" name="purchase_est_date"
                value="{{ isset($data->purchase_est_date) ? $data->purchase_est_date : 0.0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <input type="hidden" name="url" value="{{ route('updateNonEditableDetail.store') }}">
        <input type="hidden" name="id" value="{{ isset($data['id']) ? $data['id'] : '0' }}" />
        <input type="hidden" name="type" value="{{ isset($request_type) ? $request_type : '' }}" />
    </tr>
</table>
