@extends('admin.layouts.admin-layout')
@section('container')
    @php
        $isSoldOut = isset($data['status']) && $data['status'] == '2' ? 'disabled' : '';
    @endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Purchase <small>{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</small>
            </h1>
        </section>
        <section class="content">
            <form class="purchaseFormAjaxSubmit ajaxForm" role="form" method="POST"
                action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data"
                data-redirect="{{ isset($method) && $method == 'PUT' ? 'edit' : '{id}/edit' }}">
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
                            @if (!isset($data['status']))
                                <h3 class="box-title">Manage Purchase</h3>
                            @endif
                            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                                {{ $isSoldOut }}>
                                @if (isset($method) && $method == 'PUT')
                                    UPDATE PURCHASE
                                @else
                                    CREATE PURCHASE
                                @endif
                            </button>

                            @if (isset($data['status']))
                                <button type="button"
                                    class="btn {{ $data['status'] == '1' ? 'btn-success' : 'btn-danger' }} pull-left"
                                    style="margin-right: 10px;">
                                    STATUS - {{ $data['status'] == '1' ? 'IN STOCK' : 'SOLD OUT' }}
                                </button>
                            @endif
                        </div>
                        @if (isset($data['transfer_status']) && $data['transfer_status'] == '1')
                            <p>
                                <span class="text-danger" style="padding-left: 10px;font-size: 16px;">
                                    Note : This inventory is available at
                                    broker({{ isset($data['transfers']['broker']) ? $data['transfers']['broker']['name'] : '' }})
                                    showroom.
                                </span>
                            </p>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="required">BRANCH NAME</label>
                                <select name="bike_branch" data-dep_dd_name="bike_dealer" data-dep_dd2_name="bike_brand"
                                    data-dep_dd3_name="bike_model" data-dep_dd4_name="bike_model_variant"
                                    data-dep_dd5_name="bike_model_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=dealers' }}"
                                    class="form-control ajaxChangeCDropDown" {{ $isSoldOut }}>
                                    @if (isset($method) && $method == 'POST')
                                        <option value="">---Select Branch---</option>
                                    @endif
                                    @if (isset($branches))
                                        @foreach ($branches as $key => $branch)
                                            <option
                                                {{ isset($data->bike_branch) && $data->bike_branch == $branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">DEALER NAME</label>
                                <select name="bike_dealer" class="form-control" {{ $isSoldOut }} {{ $isSoldOut }}>
                                    @if (isset($method) && $method == 'POST')
                                        <option value="">---Select Dealer---</option>
                                    @endif
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
                                <label class="required">BRAND NAME</label>
                                <select name="bike_brand" data-url="{{ url('getAjaxDropdown') . '?req=models' }}"
                                    data-dep_dd_name="bike_model" data-dep_dd2_name="bike_model_variant"
                                    data-dep_dd3_name="bike_model_color" class="form-control ajaxChangeCDropDown"
                                    {{ $isSoldOut }}>
                                    @if (isset($method) && $method == 'POST')
                                        <option value="">---Select Brand---</option>
                                    @endif
                                    @if (isset($brands))
                                        @foreach ($brands as $key => $brand)
                                            <option
                                                {{ (isset($data->bike_brand) && $data->bike_brand == $brand->id) || ($method && $method == 'POST' && $key == 0) ? 'selected="selected"' : '' }}
                                                value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">MODEL NAME</label>
                                <select name="bike_model" data-dep_dd_name="bike_model_variant"
                                    data-dep_dd2_name="bike_model_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=variants' }}"
                                    class="form-control ajaxChangeCDropDown commonSelect2" {{ $isSoldOut }}>
                                    <option value="">---Select Model---</option>
                                    @if (isset($models))
                                        @foreach ($models as $key => $model)
                                            <option
                                                {{ isset($data->bike_model) && $data->bike_model == $model->id ? 'selected="selected"' : '' }}
                                                value="{{ $model->id }}">
                                                {{ $model->model_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-2">
                                <label class="required">MODEL VARIANT</label>
                                <select name="bike_model_variant" data-dep_dd_name="bike_model_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=colors' }}"
                                    class="form-control ajaxChangeCDropDown" {{ $isSoldOut }}>
                                    <option value="">Select Model Variant</option>
                                    @if (isset($variants))
                                        @foreach ($variants as $key => $variant)
                                            <option
                                                {{ isset($data->bike_model_variant) && $data->bike_model_variant == $variant->id ? 'selected="selected"' : '' }}
                                                value="{{ $variant->id }}"
                                                data-variantCode="{{ $variant->variant_name }}">
                                                {{ $variant->variant_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="required">VARIANT COLOR</label>
                                <select name="bike_model_color" class="form-control" {{ $isSoldOut }}
                                    {{ $isSoldOut }}>
                                    <option value="">Select Variant Color</option>
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
                            </div>
                            <div class="form-group col-md-2">
                                <label class="required">VEHICLE TYPE</label>
                                <select name="bike_type" class="form-control" {{ $isSoldOut }} {{ $isSoldOut }}>
                                    <option value="">Select Vehicle Type</option>
                                    @if (isset($bike_types))
                                        @foreach ($bike_types as $key => $name)
                                            <option
                                                {{ isset($data->bike_type) && $data->bike_type == $key ? 'selected="selected"' : (strtolower($key) == 'motorcycle' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="required">FUEL TYPE</label>
                                <select name="bike_fuel_type" class="form-control" {{ $isSoldOut }}>
                                    <option value="">Select Fuel Type</option>
                                    @if (isset($bike_fuel_types))
                                        @foreach ($bike_fuel_types as $key => $name)
                                            <option
                                                {{ isset($data->bike_fuel_type) && $data->bike_fuel_type == $key ? 'selected="selected"' : (strtolower($key) == 'petrol' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="required">BREAK TYPE</label>
                                <select name="break_type" class="form-control" {{ $isSoldOut }}>
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
                            <div class="form-group col-md-2">
                                <label class="required">WHEAL TYPE</label>
                                <select name="wheel_type" class="form-control" {{ $isSoldOut }}>
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
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="required">VIN NUMBER(CHASIS NUMBER) </label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="VIN Number(Chasis Number)" name="vin_number"
                                    value="{{ isset($data->vin_number) ? $data->vin_number : '' }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>VIN PHYSICAL STATUS</label>
                                <select name="vin_physical_status" class="form-control" {{ $isSoldOut }}>
                                    <option value="">---Select VIN Physical Status---</option>
                                    @if (isset($vin_physical_statuses))
                                        @foreach ($vin_physical_statuses as $key => $name)
                                            <option
                                                {{ isset($data->vin_physical_status) && $data->vin_physical_status == $key ? 'selected="selected"' : (strtolower($key) == 'good' ? 'selected' : '') }}
                                                value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">HSN NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }} placeholder="HSN Number"
                                    name="hsn_number" value="{{ isset($data->hsn_number) ? $data->hsn_number : '' }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">ENGINE NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="Engine Number" name="engine_number"
                                    value="{{ isset($data->engine_number) ? $data->engine_number : '' }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="required">VARIANT CODE</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="Variant(Code)" name="variant"
                                    value="{{ isset($data->variant) ? $data->variant : '' }}" readonly />
                            </div>
                            <div class="form-group col-md-3">
                                <label>SKU CODE</label>
                                <input type="text" class="form-control" {{ $isSoldOut }} placeholder="SKU"
                                    name="sku" value="{{ isset($data->sku) ? $data->sku : '' }}" readonly />
                            </div>
                            <div class="form-group col-md-6">
                                <label>SKU DESCRIPTION</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="SKU Description" name="sku_description"
                                    value="{{ isset($data->sku_description) ? $data->sku_description : '' }}" />
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-3">
                                <label>KEY NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }} placeholder="Key Number"
                                    name="key_number" value="{{ isset($data->key_number) ? $data->key_number : '' }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>SERVICE BOOK NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="Service Book Number" name="service_book_number"
                                    value="{{ isset($data->service_book_number) ? $data->service_book_number : '' }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>BATTERY BRAND</label>
                                <select name="battery_brand_id" id="battery_brand_id" class="form-control"
                                    {{ $isSoldOut }}>
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
                            </div>
                            <div class="form-group col-md-3">
                                <label>BATTERY NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="Battery Number" name="battery_number"
                                    value="{{ isset($data->battery_number) ? $data->battery_number : '' }}" />
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-4">
                                <label>TYRE BRAND</label>
                                <select name="tyre_brand_id" id="tyre_brand_id" class="form-control"
                                    {{ $isSoldOut }}>
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
                            </div>
                            <div class="form-group col-md-4">
                                <label>TYRE FRONT NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="Tyre Front Number" name="tyre_front_number"
                                    value="{{ isset($data->tyre_front_number) ? $data->tyre_front_number : '' }}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>TYRE REAR NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }}
                                    placeholder="Tyre Rear Number" name="tyre_rear_number"
                                    value="{{ isset($data->tyre_rear_number) ? $data->tyre_rear_number : '' }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="required">DC NUMBER</label>
                                <input type="text" class="form-control" {{ $isSoldOut }} placeholder="DC Number"
                                    name="dc_number" value="{{ isset($data->dc_number) ? $data->dc_number : '' }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">DC DATE</label>
                                <input type="date" class="form-control" {{ $isSoldOut }} placeholder="DC Date"
                                    name="dc_date"
                                    value="{{ isset($data->dc_date) ? $data->dc_date : date('Y-m-d') }}" />
                            </div>

                            <div class="form-group col-md-2">
                                <label class="required">GST RATE</label>
                                <select name="gst_rate" id="gst_rate" class="form-control" {{ $isSoldOut }}>
                                    @if (isset($gst_rates))
                                        @foreach ($gst_rates as $key => $gst_rate)
                                            <option
                                                {{ isset($data->gst_rate) && $data->gst_rate == $gst_rate->gst_rate ? 'selected' : '' }}
                                                value="{{ $gst_rate->id }}" data-rate="{{ $gst_rate->gst_rate }}">
                                                {{ $gst_rate->gst_rate }}%</option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="gst_rate_percent" id="gst_rate_percent"
                                    value="{{ isset($data->gst_rate_percent) ? $data->gst_rate_percent : 0 }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="required">ACTUAL PRICE(PRE GST)</label>
                                <input type="number" class="form-control totalAmountCal totalAmountCal2"
                                    placeholder="Pre GST Amount" name="pre_gst_amount" id="pre_gst_amount"
                                    value="{{ isset($data->pre_gst_amount) ? $data->pre_gst_amount : 0.0 }}"
                                    {{ $isSoldOut }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label>DISCOUNT AMOUNT(-)</label>
                                <input type="number" class="form-control totalAmountCal" placeholder="Discount Amount"
                                    name="discount_price"
                                    value="{{ isset($data->discount_price) ? $data->discount_price : 0.0 }}"
                                    {{ $isSoldOut }} />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">GST AMOUNT</label>
                                <input type="number" class="form-control totalAmountCal totalAmountCal2"
                                    placeholder="GST Amount" name="gst_amount" readonly
                                    value="{{ isset($data->gst_amount) ? $data->gst_amount : 0.0 }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">EX SHOWROOM PRICE(+GST)</label>
                                <input type="number" class="form-control " readonly placeholder="Ex Showroom Price"
                                    name="ex_showroom_price"
                                    value="{{ isset($data->ex_showroom_price) ? $data->ex_showroom_price : 0.0 }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="required">GRAND TOTAL</label>
                                <input type="number" class="form-control" {{ $isSoldOut }}
                                    placeholder="Grand Total" name="grand_total" readonly
                                    value="{{ isset($data->grand_total) ? $data->grand_total : 0.0 }}" />
                            </div>

                            <div class="form-group col-md-12">
                                <label>VEHICLE DESCRIPTION</label>
                                <textarea name="bike_description" rows="5" class="form-control" {{ $isSoldOut }}>{{ isset($data->bike_description) ? $data->bike_description : '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- submit button --}}
                    <div class="form-group">
                        <div class="box-footer">
                            <a href="{{ route('purchases.index') }}" class="btn btn-danger pull-left">BACK</a>
                            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                                {{ $isSoldOut }}>
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
    <script src="{{ asset('assets/modules/purchase.js') }}"></script>
@endpush
