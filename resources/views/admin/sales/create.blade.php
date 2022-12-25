@extends('admin.layouts.admin-layout')
@section('container')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Purchase <small>{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</small>
            </h1>
        </section>

        <section class="content">
            <form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
                enctype="multipart/form-data" data-redirect="{{ isset($method) && $method == 'PUT' ? 'edit' : '{id}/edit' }}">
                @csrf
                @if (isset($method) && $method == 'PUT')
                    @method('PUT')
                @endif

                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="{{ route('purchases.index') }}">Purchase</a></li>
                    <li class="active">{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</li>
                </ol>
                <div class="box box-default">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title">Purchase Detail</h3>

                            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                                @if (isset($method) && $method == 'PUT')
                                    UPDATE PURCHASE
                                @else
                                    CREATE PURCHASE
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Purchase</label>
                                    <select id="purchase" name="purchase_id" class="form-control">
                                        <option value="">---Select Purchase---</option>
                                        @if (isset($purchases))
                                            @foreach ($purchases as $key => $purchase)
                                                <option
                                                    {{ isset($data->purchase_id) && $data->purchase_id == $purchase->id ? 'selected' : '' }}
                                                    value="{{ $purchase->id }}">{{ $purchase->vin_number }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Branch</label>
                                    <select name="bike_branch" class="form-control">
                                        <option value="">---Select Branch---</option>
                                        @if (isset($branches))
                                            @foreach ($branches as $key => $branch)
                                                <option
                                                    {{ isset($data->bike_branch) && $data->bike_branch == $branch->id ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Bike Dealer</label>
                                    <select name="bike_dealer" class="form-control">
                                        <option value="">---Select Dealer---</option>
                                        @if (isset($dealers))
                                            @foreach ($dealers as $key => $dealer)
                                                <option
                                                    {{ isset($data->bike_dealer) && $data->bike_dealer == $dealer->id ? 'selected="selected"' : '' }}
                                                    value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Brand Name</label>
                                    <select name="bike_brand" data-dep_dd_name="bike_model"
                                        data-url="{{ url('getAjaxDropdown') . '?req=models' }}"
                                        data-dep_dd2_name="bike_model_color" class="form-control ajaxChangeCDropDown">
                                        <option value="">---Select Brand---</option>
                                        @if (isset($brands))
                                            @foreach ($brands as $key => $brand)
                                                <option
                                                    {{ isset($data->bike_brand) && $data->bike_brand == $brand->id ? 'selected="selected"' : '' }}
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Model Name</label>
                                    <select name="bike_model" data-dep_dd_name="bike_model_color"
                                        data-url="{{ url('getAjaxDropdown') . '?req=colors' }}" data-dep_dd2_name=""
                                        class="form-control ajaxChangeCDropDown">
                                        @if (isset($editModelsHtml))
                                            {!! $editModelsHtml !!}
                                        @else
                                            <option value="">---Select Model---</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Model Color</label>
                                    <select name="bike_model_color" class="form-control">
                                        <option value="" disabled>---Select Model Color---</option>
                                        @if (isset($colors))
                                            @foreach ($colors as $key => $color)
                                                <option
                                                    {{ isset($data->bike_model_color) && $data->bike_model_color == $color->id ? 'selected="selected"' : '' }}
                                                    value="{{ $color->id }}">{{ $color->color_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Vehicle Type</label>
                                    <select name="bike_type" class="form-control">
                                        <option value="">---Select Vehicle Type---</option>
                                        @if (isset($bike_types))
                                            @foreach ($bike_types as $key => $name)
                                                <option
                                                    {{ isset($data->bike_type) && $data->bike_type == $key ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $key }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Fuel Type</label>
                                    <select name="bike_fuel_type" class="form-control">
                                        <option value="">---Select Fuel Type---</option>
                                        @if (isset($bike_fuel_types))
                                            @foreach ($bike_fuel_types as $key => $name)
                                                <option
                                                    {{ isset($data->bike_fuel_type) && $data->bike_fuel_type == $key ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $key }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Break Type</label>
                                    <select name="break_type" class="form-control">
                                        <option value="">---Select Break Type---</option>
                                        @if (isset($break_types))
                                            @foreach ($break_types as $key => $name)
                                                <option
                                                    {{ isset($data->break_type) && $data->break_type == $key ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $key }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Wheal Type</label>
                                    <select name="wheel_type" class="form-control">
                                        <option value="">---Select Break Type---</option>
                                        @if (isset($wheel_types))
                                            @foreach ($wheel_types as $key => $name)
                                                <option
                                                    {{ isset($data->wheel_type) && $data->wheel_type == $key ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $key }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>DC Number</label>
                                    <input type="text" class="form-control" placeholder="DC Number" name="dc_number"
                                        value="{{ isset($data->dc_number) ? $data->dc_number : '' }}" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>DC Date</label>
                                    <input type="date" class="form-control" placeholder="DC Date" name="dc_date"
                                        value="{{ isset($data->dc_date) ? $data->dc_date : '' }}" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>VIN Number(Chasis Number)</label>
                                    <input type="text" class="form-control" placeholder="DC Number" name="vin_number"
                                        value="{{ isset($data->vin_number) ? $data->vin_number : '' }}" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>VIN Physical Status</label>
                                    <select name="vin_physical_status" class="form-control">
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
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" placeholder="SKU" name="sku"
                                        value="{{ isset($data->sku) ? $data->sku : '' }}" />
                                </div>
                                <div class="form-group col-md-9">
                                    <label>SKU Description</label>
                                    <input type="text" class="form-control" placeholder="SKU Description"
                                        name="sku_description"
                                        value="{{ isset($data->sku_description) ? $data->sku_description : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label>HSN Number</label>
                                    <input type="text" class="form-control" placeholder="HSN Number"
                                        name="hsn_number"
                                        value="{{ isset($data->hsn_number) ? $data->hsn_number : '' }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Model Number</label>
                                    <input type="text" class="form-control" placeholder="Model Number"
                                        name="model_number"
                                        value="{{ isset($data->model_number) ? $data->model_number : '' }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Engine Number</label>
                                    <input type="text" class="form-control" placeholder="Engine Number"
                                        name="engine_number"
                                        value="{{ isset($data->engine_number) ? $data->engine_number : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Key Number</label>
                                    <input type="text" class="form-control" placeholder="Key Number"
                                        name="key_number"
                                        value="{{ isset($data->key_number) ? $data->key_number : '' }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Service Book Number</label>
                                    <input type="text" class="form-control" placeholder="Service Book Number"
                                        name="service_book_number"
                                        value="{{ isset($data->service_book_number) ? $data->service_book_number : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label>Tyre Brand Name</label>
                                    <input type="text" class="form-control" placeholder="Tyre Brand Name"
                                        name="tyre_brand_name"
                                        value="{{ isset($data->tyre_brand_name) ? $data->tyre_brand_name : '' }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tyre Front Number</label>
                                    <input type="text" class="form-control" placeholder="Tyre Front Number"
                                        name="tyre_front_number"
                                        value="{{ isset($data->tyre_front_number) ? $data->tyre_front_number : '' }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tyre Rear Number</label>
                                    <input type="text" class="form-control" placeholder="Tyre Rear Number"
                                        name="tyre_rear_number"
                                        value="{{ isset($data->tyre_rear_number) ? $data->tyre_rear_number : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Battery Brand Name</label>
                                    <input type="text" class="form-control" placeholder="Battery Brand Name"
                                        name="battery_brand"
                                        value="{{ isset($data->battery_brand) ? $data->battery_brand : '' }}" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Battery Number</label>
                                    <input type="text" class="form-control" placeholder="Battery Number"
                                        name="battery_number"
                                        value="{{ isset($data->battery_number) ? $data->battery_number : '' }}" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Sale Price(₹)</label>
                                    <input type="text" class="form-control" placeholder="Sale Price(₹)"
                                        name="sale_price"
                                        value="{{ isset($data->sale_price) ? $data->sale_price : '' }}" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Final Price(₹)</label>
                                    <input type="text" class="form-control" placeholder="Final Price(₹)"
                                        name="final_price"
                                        value="{{ isset($data->final_price) ? $data->final_price : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label>Purchase Invoice Amount(₹)</label>
                                    <input type="text" class="form-control" placeholder="Purchase Invoice Amount(₹)"
                                        name="purchase_invoice_amount"
                                        value="{{ isset($data->purchase_invoice_amount) ? $data->purchase_invoice_amount : '' }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchase Invoice Number</label>
                                    <input type="text" class="form-control" placeholder="Purchase Invoice Number"
                                        name="purchase_invoice_number"
                                        value="{{ isset($data->purchase_invoice_number) ? $data->purchase_invoice_number : '' }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchase Invoice Date</label>
                                    <input type="date" class="form-control" placeholder="Purchase Invoice Date"
                                        name="purchase_invoice_date"
                                        value="{{ isset($data->purchase_invoice_date) ? $data->purchase_invoice_date : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <label>Vehicle Description</label>
                                    <textarea name="bike_description" rows="5" class="form-control">{{ isset($data->bike_description) ? $data->bike_description : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-12">
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

                            {{-- quotation --}}
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Quotation</label>
                                    <select id="quotation" name="quotation_id" class="form-control">
                                        <option value="">---Select Quotation---</option>
                                        @if (isset($quotations))
                                            @foreach ($quotations as $key => $quotation)
                                                <option
                                                    {{ isset($data->quotation_id) && $data->quotation_id == $quotation->id ? 'selected' : '' }}
                                                    value="{{ $quotation->id }}">
                                                    {{ $quotation->customer_first_name . ' ' . $quotation->customer_last_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Customer First Name</label>
                                <input name="customer_first_name" type="text" class="form-control"
                                    value="{{ isset($data['customer_first_name']) ? $data['customer_first_name'] : '' }}"
                                    placeholder="Customer First Name..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Relation</label>
                                <select name="relation" data-dep_dd_name="customer_district"
                                    data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
                                    data-dep_dd2_name="customer_city" class="form-control ajaxChangeCDropDown">
                                    <option value="">---Select Customer State---</option>
                                    <option
                                        {{ isset($data['relation']) && $data['customer_state'] == 'son of' ? 'selected="selected"' : '' }}
                                        value="son of">son of</option>
                                    <option
                                        {{ isset($data['relation']) && $data['customer_state'] == 'daughter of' ? 'selected="selected"' : '' }}
                                        value="daughter of">daughter of</option>
                                    <option
                                        {{ isset($data['relation']) && $data['customer_state'] == 'wife of' ? 'selected="selected"' : '' }}
                                        value="wife of">wife of</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Relatvie Name</label>
                                <input name="raletive_name" type="text" class="form-control"
                                    value="{{ isset($data['raletive_name']) ? $data['raletive_name'] : '' }}"
                                    placeholder="Relative Name..">
                            </div>
                            <div class="form-group col-md-9">
                                <label>Customer Address Line</label>
                                <input name="customer_address_line" type="text" class="form-control"
                                    value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
                                    placeholder="Customer Address Line..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Customer State</label>
                                <select name="customer_state" data-dep_dd_name="customer_district"
                                    data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
                                    data-dep_dd2_name="customer_city" class="form-control ajaxChangeCDropDown">
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
                            </div>
                            <div class="form-group col-md-3">
                                <label>Customer City/Village
                                    <span style="margin-left: 40px;">
                                        <a href="{{ route('city.create.popup') }}" class="ajaxModalPopupOnPopup"
                                            aria-hidden="true" data-modal_title="Add New City/Village/Town"
                                            data-modal-index="1200" data-modal_size="modal-md">
                                            <i class="fa fa-plus-circle" title="Add New City/Village/Town"></i>
                                        </a>
                                    </span>
                                </label>
                                <select name="customer_city" class="form-control">
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
                                    placeholder="XXXXXX">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Customer Phone</label>
                                <input name="customer_mobile_number" type="text" class="form-control"
                                    value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
                                    placeholder="Customer Phone..">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Customer Email</label>
                                <input name="customer_email_address" type="text" class="form-control"
                                    value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
                                    placeholder="Customer Email..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Is Exchange</label>
                                <select class="form-control" name="is_exchange_avaliable">
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
                                <select class="form-control" name="payment_type">
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
                                <select name="hyp_financer" class="form-control">
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
                                    placeholder="Description...">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Bike Brand</label>
                                <select name="bike_brand" data-dep_dd_name="bike_model"
                                    data-url="{{ url('getAjaxDropdown') . '?req=models' }}"
                                    data-dep_dd2_name="bike_color" class="form-control ajaxChangeCDropDown">
                                    <option value="">---Select Brand----</option>
                                    @isset($brands)
                                        @foreach ($brands as $brand)
                                            <option
                                                {{ isset($data['bike_brand']) && $data['bike_brand'] == $brand->id ? 'selected="selected"' : '' }}
                                                value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Bike Model</label>
                                <select name="bike_model" data-dep_dd_name="bike_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=colors' }}" data-dep_dd2_name=""
                                    class="form-control ajaxChangeCDropDown">
                                    <option value="">---Select Model----</option>
                                    @isset($models)
                                        @foreach ($models as $model)
                                            <option
                                                {{ isset($data['bike_model']) && $data['bike_model'] == $model->id ? 'selected="selected"' : '' }}
                                                value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Bike Color</label>
                                <select name="bike_color" class="form-control">
                                    <option value="">---Select Color----</option>
                                    @isset($colors)
                                        @foreach ($colors as $color)
                                            <option
                                                {{ isset($data['bike_color']) && $data['bike_color'] == $color->id ? 'selected="selected"' : '' }}
                                                value="{{ $color->id }}">{{ $color->color_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ex Showroom Price</label>
                                <input name="ex_showroom_price" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Registration Amount</label>
                                <input name="registration_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['registration_amount']) ? $data['registration_amount'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Insurance Amount</label>
                                <input name="insurance_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Hypothecation Amount</label>
                                <input name="hypothecation_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Accessories Amount</label>
                                <input name="accessories_amount" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['accessories_amount']) ? $data['accessories_amount'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Other Amount</label>
                                <input name="other_charges" type="text" class="form-control totalAmountCal"
                                    value="{{ isset($data['other_charges']) ? $data['other_charges'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Total Amount</label>
                                <input name="total_amount" type="text" class="form-control" readonly
                                    value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}"
                                    placeholder="₹ XXXX">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Purchase Visit Date</label>
                                <input name="purchase_visit_date" type="date" class="form-control"
                                    {{ isset($is_readonly) ? $is_readonly : '' }}
                                    value="{{ isset($data['purchase_visit_date']) ? $data['purchase_visit_date'] : '' }}"
                                    placeholder="0000-00-00">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Purchase Estimated Date</label>
                                <input name="purchase_est_date" type="date" class="form-control"
                                    value="{{ isset($data['purchase_est_date']) ? $data['purchase_est_date'] : '' }}"
                                    placeholder="0000-00-00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                            @if (isset($method) && $method == 'PUT')
                                UPDATE PURCHASE
                            @else
                                CREATE PURCHASE
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('after-script')
    <script>
        $('#purchase').change(function(e) {
            e.preventDefault();
            CRUD.AJAXMODAL(url, "GET", null).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                $columns = ['purchase_id','bike_branch','bike_dealer','bike_brand','bike_model_color','bike_type','bike_fuel_type','break_type','wheel_type','dc_number','dc_date','vin_number','vin_physical_status','sku','sku_description','hsn_number','model_number','engine_number','key_number','service_book_number','tyre_brand_name','tyre_front_number','tyre_rear_number','battery_brand','battery_number','sale_price','final_price','purchase_invoice_amount','purchase_invoice_number','purchase_invoice_date','bike_description'];
                array.forEach(element => {
                    $(`[name=${element}]`).val(result.data[`${element}`]);
                });
            } else {
                toastr.error("Something went wrong");
                // to do
            }
        });
        })
        $('#quotation').change(function(e) {
            e.preventDefault();
            console.log($(this).val());
        })
    </script>
@endpush
