@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->

    @php
        $isEdit = isset($method) && $method == 'PUT' ? true : false;
        $editDisabled = $isEdit == true ? 'disabled="disabled"' : '';
        $isDisabled = isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled="disabled"' : '';
    @endphp
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Sale <small>{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</small>
            </h1>
        </section>

        <section class="content">
            <form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
                enctype="multipart/form-data" data-redirect="">
                @csrf
                @if (isset($method) && $method == 'PUT')
                    @method('PUT')
                @endif

                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="{{ route('purchases.index') }}">Sale</a></li>
                    <li class="active">{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</li>
                </ol>
                <div class="box box-default">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title">Sale Detail</h3>

                            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                                {{ $isDisabled }}>
                                @if (isset($method) && $method == 'PUT')
                                    UPDATE SALE
                                @else
                                    CREATE SALE
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <input type="hidden" value="{{ isset($data['quotation_id']) ? $data['quotation_id'] : 0 }}" name="quotation_id">
                        {{-- Purchase --}}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>In Stock Bikes(Purchases)</label>
                                <select id="purchase" name="purchase_id" class="form-control" {{ $editDisabled }}
                                data-getpurchases="{{ url('getPurchaseDetails') }}"
                                data-getmodels="{{ url('getModelsList') }}"
                                data-getcolors="{{ url('getColorsList') }}"
                                >
                                    <option value="">---Select Purachse---</option>
                                    @if (isset($purchases))
                                        @foreach ($purchases as $key => $purchase)
                                            <option
                                                {{ isset($data['purchase_id']) && $data['purchase_id'] == $purchase->id ? 'selected="selected"' : '' }}
                                                value="{{ $purchase->id }}">
                                                @isset($purchase->branch->branch_name)
                                                    {{ $purchase->branch->branch_name }} |
                                                @endisset

                                                @isset($purchase->brand->name)
                                                    {{ $purchase->brand->name }} |
                                                @endisset

                                                @isset($purchase->model->model_name)
                                                    {{ $purchase->model->model_name }} |
                                                @endisset

                                                @isset($purchase->modelColor->color_name)
                                                    {{ $purchase->modelColor->color_name }} |
                                                @endisset

                                                @isset($purchase->sku)
                                                    {{ $purchase->sku }}
                                                @endisset

                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bike Dealer</label>
                                <select name="bike_dealer" class="form-control" {{ $editDisabled }}>
                                    <option value="">---Select Dealer---</option>
                                    @if (isset($dealers))
                                        @foreach ($dealers as $key => $dealer)
                                            <option
                                                {{ isset($data['bike_dealer']) && $data['bike_dealer'] == $dealer->id ? 'selected="selected"' : '' }}
                                                value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Branch</label>
                                <select name="bike_branch" data-dep_dd_name="bike_brand" data-dep_dd2_name="bike_model"
                                    data-dep_dd3_name="bike_model_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=brands' }}"
                                    class="form-control ajaxChangeCDropDown" {{ $editDisabled }}>
                                    <option value="">---Select Branch---</option>
                                    @if (isset($branches))
                                        @foreach ($branches as $key => $branch)
                                            <option
                                                {{ isset($data['bike_brand']) && $data['bike_brand'] == $branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Brand Name</label>
                                <select name="bike_brand" data-url="{{ url('getAjaxDropdown') . '?req=models' }}"
                                    data-dep_dd_name="bike_model" data-dep_dd2_name="bike_model_color"
                                    class="form-control ajaxChangeCDropDown" {{ $isDisabled }}>
                                    <option value="">---Select Brand---</option>
                                    @if (isset($brands))
                                        @foreach ($brands as $key => $brand)
                                            <option
                                                {{ isset($data['bike_brand']) && $data['bike_brand'] == $brand->id ? 'selected="selected"' : '' }}
                                                value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Model Name</label>
                                <select name="bike_model" data-dep_dd_name="bike_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=colors' }}"
                                    class="form-control ajaxChangeCDropDown" {{ $isDisabled }}>
                                    <option value="">---Select Model---</option>
                                    @if (isset($models))
                                        @foreach ($models as $key => $model)
                                            <option
                                                {{ isset($data['bike_model']) && $data['bike_model'] == $model->id ? 'selected="selected"' : '' }}
                                                value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Model Color</label>
                                <select name="bike_color" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select Model Color---</option>
                                    @if (isset($colors))
                                        @foreach ($colors as $key => $color)
                                            <option
                                                {{ isset($data['bike_color']) && $data['bike_color'] == $color->id ? 'selected="selected"' : '' }}
                                                value="{{ $color->id }}">{{ $color->color_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Vehicle Type</label>
                                <select name="bike_type" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select Vehicle Type---</option>
                                    @if (isset($bike_types))
                                        @foreach ($bike_types as $key => $name)
                                            <option
                                                {{ isset($data['bike_type']) && $data['bike_type'] == $key ? 'selected="selected"' : (strtolower($key) == 'bike' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fuel Type</label>
                                <select name="bike_fuel_type" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select Fuel Type---</option>
                                    @if (isset($bike_fuel_types))
                                        @foreach ($bike_fuel_types as $key => $name)
                                            <option
                                                {{ isset($data->bike_fuel_type) && $data->bike_fuel_type == $key ? 'selected="selected"' : (strtolower($key) == 'petrol' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Break Type</label>
                                <select name="break_type" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select Break Type---</option>
                                    @if (isset($break_types))
                                        @foreach ($break_types as $key => $name)
                                            <option
                                                {{ isset($data->break_type) && $data->break_type == $key ? 'selected="selected"' : (strtolower($key) == 'normal' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Wheal Type</label>
                                <select name="wheel_type" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select Wheal Type---</option>
                                    @if (isset($wheel_types))
                                        @foreach ($wheel_types as $key => $name)
                                            <option
                                                {{ isset($data->wheel_type) && $data->wheel_type == $key ? 'selected="selected"' : (strtolower($key) == 'alloy' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>


                            <div class="form-group col-md-4">
                                <label>VIN Number(Chasis Number)</label>
                                <input type="text" class="form-control" placeholder="VIN Number" name="vin_number"
                                    value="{{ isset($data->vin_number) ? $data->vin_number : '' }}" {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-4">
                                <label>VIN Physical Status</label>
                                <select name="vin_physical_status" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select VIN Physical Status---</option>
                                    @if (isset($vin_physical_statuses))
                                        @foreach ($vin_physical_statuses as $key => $name)
                                            <option
                                                {{ isset($data->vin_physical_status) && $data->vin_physical_status == $key ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>SKU</label>
                                <input type="text" class="form-control" placeholder="SKU" name="sku"
                                    value="{{ isset($data->sku) ? $data->sku : '' }}" {{ $isDisabled }} />
                            </div>

                            <div class="form-group col-md-12">
                                <label>SKU Description</label>
                                <input type="text" class="form-control" placeholder="SKU Description"
                                    name="sku_description"
                                    value="{{ isset($data->sku_description) ? $data->sku_description : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>HSN Number</label>
                                <input type="text" class="form-control" placeholder="HSN Number" name="hsn_number"
                                    value="{{ isset($data->hsn_number) ? $data->hsn_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Engine Number</label>
                                <input type="text" class="form-control" placeholder="Engine Number"
                                    name="engine_number"
                                    value="{{ isset($data->engine_number) ? $data->engine_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Key Number</label>
                                <input type="text" class="form-control" placeholder="Key Number" name="key_number"
                                    value="{{ isset($data->key_number) ? $data->key_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Service Book Number</label>
                                <input type="text" class="form-control" placeholder="Service Book Number"
                                    name="service_book_number"
                                    value="{{ isset($data->service_book_number) ? $data->service_book_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tyre Brand</label>
                                <select name="tyre_brand_id" id="tyre_brand_id" class="form-control" {{ $isDisabled }}>
                                    @if (isset($tyre_brands))
                                        <option value="">---Select Tyre Brand---</option>
                                        @foreach ($tyre_brands as $key => $tyre_brand)
                                            <option
                                                {{ isset($data->tyre_brand_id) && $data->tyre_brand_id == $tyre_brand->id ? 'selected' : '' }}
                                                value="{{ $tyre_brand->id }}"
                                                data-rate="{{ $tyre_brand->tyre_brand_id }}">
                                                {{ $tyre_brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Tyre Front Number</label>
                                <input type="text" class="form-control" placeholder="Tyre Front Number"
                                    name="tyre_front_number"
                                    value="{{ isset($data->tyre_front_number) ? $data->tyre_front_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-2">
                                <label>Tyre Rear Number</label>
                                <input type="text" class="form-control" placeholder="Tyre Rear Number"
                                    name="tyre_rear_number"
                                    value="{{ isset($data->tyre_rear_number) ? $data->tyre_rear_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>Battery Brand</label>
                                <select name="battery_brand_id" id="battery_brand_id" class="form-control" {{ $isDisabled }}>
                                    @if (isset($battery_brands))
                                        <option value="">---Select Battery Brand---</option>
                                        @foreach ($battery_brands as $key => $battery_brand)
                                            <option
                                                {{ isset($data->battery_brand_id) && $data->battery_brand_id == $battery_brand->id ? 'selected' : '' }}
                                                value="{{ $battery_brand->id }}" >
                                                {{ $battery_brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Battery Number</label>
                                <input type="text" class="form-control" placeholder="Battery Number"
                                    name="battery_number"
                                    value="{{ isset($data->battery_number) ? $data->battery_number : '' }}"
                                    {{ $isDisabled }} />
                            </div>
                            <div class="form-group col-md-12">
                                <label>Vehicle Description</label>
                                <textarea name="bike_description" rows="3" placeholder="Vehicle Description..." class="form-control"
                                    {{ $isDisabled }}>
                                    {{ isset($data->bike_description) ? $data->bike_description : '' }}
                                </textarea>
                            </div>
                        </div>


                        {{-- Quotation --}}
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Prefix</label>
                                <select name="customer_gender" class="form-control" {{ $isDisabled }}>
                                    <option
                                        {{ isset($data['customer_gender']) && $data['customer_gender'] == '1' ? 'selected' : '' }}
                                        value="1">Mr.</option>
                                    <option
                                        {{ isset($data['customer_gender']) && $data['customer_gender'] == '2' ? 'selected' : '' }}
                                        value="2">Mrs.</option>
                                    <option
                                        {{ isset($data['customer_gender']) && $data['customer_gender'] == '3' ? 'selected' : '' }}
                                        value="2">Miss</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Customer Name</label>
                                <input name="customer_name" type="text" class="form-control"
                                    value="{{ isset($data['customer_name']) ? $data['customer_name'] : '' }}"
                                    placeholder="Customer Name.." {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Relation</label>
                                <select name="customer_relationship" class="form-control" {{ $isDisabled }}>
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
                            </div>
                            <div class="form-group col-md-4">
                                <label>Customer Guardian Name</label>
                                <input name="customer_guardian_name" type="text" class="form-control"
                                    value="{{ isset($data['customer_guardian_name']) ? $data['customer_guardian_name'] : '' }}"
                                    placeholder="Customer Guardian Name.." {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Customer Address Line</label>
                                <input name="customer_address_line" type="text" class="form-control"
                                    value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
                                    placeholder="Customer Address Line.." {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Customer State</label>
                                <select name="customer_state" data-dep_dd_name="customer_district"
                                    data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
                                    data-dep_dd2_name="customer_city" class="form-control ajaxChangeCDropDown"
                                    {{ $isDisabled }}>
                                    <option value="">---Select Customer State---</option>
                                    @isset($states)
                                        @foreach ($states as $state)
                                            <option
                                                {{ isset($data['customer_state']) && $data['customer_state'] == $state->id ? 'selected="selected"' : '' }}
                                                value="{{ $state->id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Customer District</label>
                                <select name="customer_district" class="form-control ajaxChangeCDropDown"
                                    data-dep_dd_name="customer_city"
                                    data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" data-dep_dd2_name=""
                                    {{ $isDisabled }}>
                                    <option value="">---Select District---</option>
                                    @isset($districts)
                                        @foreach ($districts as $district)
                                            <option
                                                {{ isset($data['customer_district']) && $data['customer_district'] == $district->id ? 'selected="selected"' : '' }}
                                                value="{{ $district->id }}">{{ $district->district_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Customer City/Village
                                    <span style="margin-left: 40px;">
                                        <a href="{{ route('city.create.popup') }}" class="ajaxModalPopupOnPopup"
                                            aria-hidden="true" data-modal_title="Add New City/Village/Town"
                                            data-modal-index="1200" data-modal_size="modal-md" {{ $isDisabled }}>
                                            <i class="fa fa-plus-circle" title="Add New City/Village/Town"></i>
                                        </a>
                                    </span>
                                </label>
                                <select name="customer_city" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select City/Village----</option>
                                    @isset($cities)
                                        @foreach ($cities as $city)
                                            <option
                                                {{ isset($data['customer_city']) && $data['customer_city'] == $city->id ? 'selected="selected"' : '' }}
                                                value="{{ $city->id }}">{{ $city->city_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>ZipCode</label>
                                <input name="customer_zipcode" type="text" class="form-control"
                                    value="{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '' }}"
                                    placeholder="XXXXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Customer Phone</label>
                                <input name="customer_mobile_number" type="text" class="form-control"
                                    value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
                                    placeholder="Customer Phone.." {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Customer Email</label>
                                <input name="customer_email_address" type="text" class="form-control"
                                    value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
                                    placeholder="Customer Email.." {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Is Exchange</label>
                                <select class="form-control" name="is_exchange_avaliable" {{ $isDisabled }}>
                                    <option value="No"
                                        {{ isset($data['is_exchange_avaliable']) && $data['is_exchange_avaliable'] == 'No' ? 'selected="selected"' : '' }}>
                                        No
                                    </option>
                                    <option value="Yes"
                                        {{ isset($data['is_exchange_avaliable']) && $data['is_exchange_avaliable'] == 'Yes' ? 'selected="selected"' : '' }}>
                                        Yes
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Payment Type</label>
                                <select class="form-control" name="payment_type" {{ $isDisabled }}>
                                    <option value="Cash"
                                        {{ isset($data['payment_type']) && $data['payment_type'] == 'Cash' ? 'selected="selected"' : '' }}>
                                        Cash
                                    </option>
                                    <option value="Finance"
                                        {{ isset($data['payment_type']) && $data['payment_type'] == 'Finance' ? 'selected="selected"' : '' }}>
                                        Finance
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Hypothecation Financer</label>
                                <select name="hyp_financer" class="form-control" {{ $isDisabled }}>
                                    <option value="">---Select Hypothecation Financer----</option>
                                    @isset($bank_financers)
                                        @foreach ($bank_financers as $bank_financer)
                                            <option
                                                {{ isset($data['hyp_financer']) && $data['hyp_financer'] == $bank_financer->id ? 'selected="selected"' : '' }}
                                                value="{{ $bank_financer->id }}">{{ $bank_financer->bank_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Hypothecation Financer Description</label>
                                <input name="hyp_financer_description" type="text" class="form-control"
                                    value="{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '' }}"
                                    placeholder="Description..." {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ex Showroom Price</label>
                                <input name="ex_showroom_price" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Registration Amount</label>
                                <input name="registration_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['registration_amount']) ? $data['registration_amount'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Insurance Amount</label>
                                <input name="insurance_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Hypothecation Amount</label>
                                <input name="hypothecation_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Accessories Amount</label>
                                <input name="accessories_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['accessories_amount']) ? $data['accessories_amount'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Other Amount</label>
                                <input name="other_charges" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['other_charges']) ? $data['other_charges'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Total Amount</label>
                                <input name="total_amount" type="text" class="form-control" readonly
                                    value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}"
                                    placeholder="₹ XXXX" {{ $isDisabled }}>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- submit button --}}
                <div class="form-group">
                    <div class="box-footer">
                        <a href="{{ route('sales.index') }}" class="btn btn-danger">BACK</a>
                        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                            {{ $isDisabled }}>
                            @if (isset($method) && $method == 'PUT')
                                UPDATE SALE
                            @else
                                CREATE SALE
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/sales.js') }}" ></script>
@endpush
