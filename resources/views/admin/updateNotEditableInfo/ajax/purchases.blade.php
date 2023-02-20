<table class="table table-bordered">
    <tr>
        <th width="20%">BRANCH NAME</th>
        <td width="20%">
            <select name="bike_branch" class="form-control" disabled>
                @if (isset($branches))
                    @foreach ($branches as $key => $branch)
                        <option {{ isset($data->bike_branch) && $data->bike_branch == $branch->id ? 'selected' : '' }}
                            value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">DEALER NAME</th>
        <td width="20%">
            <select name="bike_dealer" class="form-control" disabled>
                @if (isset($dealers))
                    @foreach ($dealers as $key => $dealer)
                        <option
                            {{ isset($data->bike_dealer) && $data->bike_dealer == $dealer->id ? 'selected="selected"' : '' }}
                            value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                    @endforeach
                @endif
            </select>
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
        <th width="20%">VEHICLE TYPE</th>
        <td width="20%">
            <select name="bike_type" class="form-control" disabled>
                @if (isset($bike_types))
                    @foreach ($bike_types as $key => $name)
                        <option
                            {{ isset($data->bike_type) && $data->bike_type == $key ? 'selected="selected"' : (strtolower($key) == 'motorcycle' ? 'selected' : '') }}
                            value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">FUEL TYPE</th>
        <td width="20%">
            <select name="bike_fuel_type" class="form-control" disabled>
                @if (isset($bike_fuel_types))
                    @foreach ($bike_fuel_types as $key => $name)
                        <option
                            {{ isset($data->bike_fuel_type) && $data->bike_fuel_type == $key ? 'selected="selected"' : (strtolower($key) == 'petrol' ? 'selected' : '') }}
                            value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">VIN NUMBER(CHASIS NUMBER)</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="VIN Number(Chasis Number)" name="vin_number"
                value="{{ isset($data->vin_number) ? $data->vin_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="vin_number" data-input_type="input">SAVE</a>
        </td>

        <th width="20%">VIN PHYSICAL STATUS</th>
        <td width="20%">
            <select name="vin_physical_status" class="form-control">
                <option value="">---Select VIN Physical Status---</option>
                @if (isset($vin_physical_statuses))
                    @foreach ($vin_physical_statuses as $key => $name)
                        <option
                            {{ isset($data->vin_physical_status) && $data->vin_physical_status == $key ? 'selected="selected"' : (strtolower($key) == 'good' ? 'selected' : '') }}
                            value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="vin_physical_status"
                data-input_type="select">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">HSN NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="HSN Number" name="hsn_number"
                value="{{ isset($data->hsn_number) ? $data->hsn_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="hsn_number" data-input_type="input">SAVE</a>
        </td>

        <th width="20%">ENGINE NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="Engine Number" name="engine_number"
                value="{{ isset($data->engine_number) ? $data->engine_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="engine_number" data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">KEY NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="Key Number" name="key_number"
                value="{{ isset($data->key_number) ? $data->key_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="key_number" data-input_type="input">SAVE</a>
        </td>

        <th width="20%">SERVICE BOOK NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="Service Book Number" name="service_book_number"
                value="{{ isset($data->service_book_number) ? $data->service_book_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="service_book_number"
                data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">BATTERY BRAND</th>
        <td width="20%">
            <select name="battery_brand_id" id="battery_brand_id" class="form-control">
                @if (isset($battery_brands))
                    <option value="">---Select Battery Brand---</option>
                    @foreach ($battery_brands as $key => $battery_brand)
                        <option
                            {{ isset($data->battery_brand_id) && $data->battery_brand_id == $battery_brand->id ? 'selected' : '' }}
                            value="{{ $battery_brand->id }}">
                            {{ $battery_brand->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="battery_brand_id"
                data-input_type="select">SAVE</a>
        </td>

        <th width="20%">BATTERY NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="Battery Number" name="battery_number"
                value="{{ isset($data->battery_number) ? $data->battery_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="battery_number"
                data-input_type="select">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">TYRE BRAND</th>
        <td width="20%">
            <select name="tyre_brand_id" id="tyre_brand_id" class="form-control">
                @if (isset($tyre_brands))
                    <option value="">---Select Tyre Brand---</option>
                    @foreach ($tyre_brands as $key => $tyre_brand)
                        <option
                            {{ isset($data->tyre_brand_id) && $data->tyre_brand_id == $tyre_brand->id ? 'selected' : '' }}
                            value="{{ $tyre_brand->id }}">
                            {{ $tyre_brand->name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="tyre_brand_id"
                data-input_type="select">SAVE</a>
        </td>

        <th width="20%">TYRE FRONT NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="Tyre Front Number" name="tyre_front_number"
                value="{{ isset($data->tyre_front_number) ? $data->tyre_front_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="tyre_front_number"
                data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">TYRE REAR NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="Tyre Rear Number" name="tyre_rear_number"
                value="{{ isset($data->tyre_rear_number) ? $data->tyre_rear_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="tyre_rear_number"
                data-input_type="input">SAVE</a>
        </td>

        <th width="20%">DC NUMBER</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="DC Number" name="dc_number"
                value="{{ isset($data->dc_number) ? $data->dc_number : '' }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="dc_number" data-input_type="input">SAVE</a>
        </td>
    </tr>

    <tr>
        <th width="20%">DC DATE</th>
        <td width="20%">
            <input type="date" class="form-control" placeholder="DC Date" name="dc_date"
                value="{{ isset($data->dc_date) ? $data->dc_date : date('Y-m-d') }}" />
        </td>
        <td width="10%">
            <a class="btn btn-primary updateFormInfo" data-field_name="dc_date" data-input_type="input">SAVE</a>
        </td>

        <th width="20%">GST RATES</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="GST RATES" name="dc_date"
                value="{{ isset($data->gst_rate_percent) ? $data->gst_rate_percent : 0 }}%" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">ACTUAL PRICE(PRE GST)</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="ACTUAL PRICE(PRE GST)" name="pre_gst_amount"
                value="{{ isset($data->pre_gst_amount) ? $data->pre_gst_amount : 0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">DISCOUNT AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="DISCOUNT AMOUNT" name="discount_price"
                value="{{ isset($data->discount_price) ? $data->discount_price : 0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">GST AMOUNT</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="GST AMOUNT" name="gst_amount"
                value="{{ isset($data->gst_amount) ? $data->gst_amount : 0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">EX SHOWROOM PRICE</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="EX SHOWROOM PRICE" name="ex_showroom_price"
                value="{{ isset($data->ex_showroom_price) ? $data->ex_showroom_price : 0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>
    </tr>

    <tr>
        <th width="20%">GRAND TOTAL</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="GRAND TOTAL" name="grand_total"
                value="{{ isset($data->grand_total) ? $data->grand_total : 0 }}" readonly />
        </td>
        <td width="10%">
            ---
        </td>

        <th width="20%">VEHICLE DESCRIPTION</th>
        <td width="20%">
            <input type="text" class="form-control" placeholder="VEHICLE DESCRIPTION" name="bike_description"
                value="{{ isset($data->bike_description) ? $data->bike_description : '' }}" readonly />
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
