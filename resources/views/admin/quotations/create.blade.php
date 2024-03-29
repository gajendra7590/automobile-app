@extends('admin.layouts.admin-layout')
@section('container')

    @php
        $action_type = isset($method) && $method == 'PUT' ? 'update' : 'create';
        $is_disabled = isset($method) && $method == 'PUT' ? 'disabled' : '';
        $is_readonly = isset($method) && $method == 'PUT' ? 'readonly' : '';
        $isClosed = isset($data['status']) && $data['status'] == 'close' ? 'disabled' : '';
    @endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Quotation <small>{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</small>
            </h1>
        </section>

        <section class="content">
            <form class="quotationFormAjaxSubmit ajaxForm" role="form" method="POST"
                action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data"
                data-redirect="{{ isset($method) && $method == 'PUT' ? 'edit' : '{id}/edit' }}">
                @csrf
                @if (isset($method) && $method == 'PUT')
                    @method('PUT')
                @endif

                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="{{ route('quotations.index') }}">Quotation</a></li>
                    <li class="active">{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</li>
                </ol>
                <div class="box box-default">
                    <div>
                        <div class="box-header with-border">
                            @if (!isset($data['status']))
                                <h3 class="box-title">Customer Quotations</h3>
                            @endif
                            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                                {{ $isClosed }}>
                                @if (isset($method) && $method == 'PUT')
                                    UPDATE QUOTATION
                                @else
                                    CREATE QUOTATION
                                @endif
                            </button>

                            @if (isset($data['status']))
                                <button type="button"
                                    class="btn {{ $data['status'] == 'open' ? 'btn-warning' : 'btn-success' }} pull-left"
                                    style="margin-right: 10px;">
                                    STATUS - {{ $data['status'] == 'open' ? 'OPEN' : 'CLOSED' }}
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>BRANCH NAME</label>
                                <select name="branch_id" class="form-control ajaxChangeCDropDown QuotBrandChange"
                                    data-dep_dd_name="bike_brand" data-dep_dd2_name="bike_model"
                                    data-dep_dd3_name="bike_model_color"
                                    data-url="{{ url('getAjaxDropdown') . '?req=brands' }}" {{ $isClosed }}
                                    {{ $is_disabled }}>
                                    <option value="">---Select Branch---</option>
                                    @if (isset($branches))
                                        @foreach ($branches as $key => $branch)
                                            <option
                                                {{ isset($data['branch_id']) && $data['branch_id'] == $branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>SALESMAN NAME</label>
                                <select name="salesman_id" class="form-control" {{ $isClosed }} {{ $is_disabled }}>
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
                            </div>
                        </div>

                        <div class="{{ isset($method) && $method == 'PUT' ? '' : 'hideElement' }}" id="quotation_more">

                            <div class="row">
                                <div class="form-group col-md-1">
                                    <label>PREFIX</label>
                                    <select name="customer_gender" class="form-control" {{ $isClosed }}>
                                        <option
                                            {{ isset($data['customer_gender']) && $data['customer_gender'] == '1' ? 'selected' : '' }}
                                            value="1">Mr.</option>
                                        <option
                                            {{ isset($data['customer_gender']) && $data['customer_gender'] == '2' ? 'selected' : '' }}
                                            value="2">Mrs.</option>
                                        <option
                                            {{ isset($data['customer_gender']) && $data['customer_gender'] == '3' ? 'selected' : '' }}
                                            value="3">Miss</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label>CUSTOMER NAME</label>
                                    <input name="customer_name" type="text" class="form-control"
                                        value="{{ isset($data['customer_name']) ? $data['customer_name'] : '' }}"
                                        placeholder="Customer Name.." {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-1">
                                    <label>RELATION</label>
                                    <select name="customer_relationship" class="form-control" {{ $isClosed }}>
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
                                <div class="form-group col-md-5">
                                    <label>CUSTOMER GUARDIAN NAME</label>
                                    <input name="customer_guardian_name" type="text" class="form-control"
                                        value="{{ isset($data['customer_guardian_name']) ? $data['customer_guardian_name'] : '' }}"
                                        placeholder="Customer Guardian Name.." {{ $isClosed }}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>CUSTOMER ADDRESS LINE</label>
                                    <input name="customer_address_line" type="text" class="form-control"
                                        value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
                                        placeholder="Customer Address Line.." {{ $isClosed }}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>CUSTOMER STATE</label>
                                    <select name="customer_state" data-dep_dd_name="customer_district"
                                        data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
                                        data-dep_dd2_name="customer_city" class="form-control ajaxChangeCDropDown"
                                        {{ $isClosed }}>
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
                                    <label>CUSTOMER DISTRICT</label>
                                    <select name="customer_district" class="form-control ajaxChangeCDropDown"
                                        data-dep_dd_name="customer_city"
                                        data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" data-dep_dd2_name=""
                                        {{ $isClosed }}>
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
                                    <label>CUSTOMER CITY/VILLEGE
                                        <span style="margin-left: 40px;">
                                            <a href="{{ route('plusAction') }}" class="plusAction" id="city"
                                                data-type="city" aria-hidden="true"
                                                data-modal_title="Add New City/Village/Town" data-modal-index="1200"
                                                data-modal_size="modal-md" {{ $isClosed }}>
                                                <i class="fa fa-plus-circle" title="Add New City/Village/Town"></i>
                                            </a>
                                        </span>
                                    </label>
                                    <div class="input-group col-sm-12">
                                        <select name="customer_city" class="form-control commonSelect2"
                                            style="width: 100%" {{ $isClosed }}>
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
                                </div>
                                <div class="form-group col-md-3">
                                    <label>ZIPCODE</label>
                                    <input name="customer_zipcode" type="text" class="form-control"
                                        value="{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '' }}"
                                        placeholder="XXXXXX" {{ $isClosed }}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>CUSTOMER PHONE NUMBER</label>
                                    <input name="customer_mobile_number" type="text" class="form-control"
                                        value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
                                        placeholder="Customer Phone.." {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>CUSTOMER ALTERNATE PHONE NUMBER</label>
                                    <input name="customer_mobile_number_alt" type="text" class="form-control"
                                        value="{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '' }}"
                                        placeholder="Customer Altenate Phone.." {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>CUSTOMER EMAIL</label>
                                    <input name="customer_email_address" type="text" class="form-control"
                                        value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
                                        placeholder="Customer Email.." {{ $isClosed }}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>IS EXCHNAGE</label>
                                    <select class="form-control" name="is_exchange_avaliable" {{ $isClosed }}>
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
                                    <label>PAYMENT TYPE</label>
                                    <select name="payment_type" class="form-control ajaxChangeCDropDown"
                                        data-dep_dd_name="hyp_financer"
                                        data-url="{{ url('getAjaxDropdown') . '?req=financiers_list' }}"
                                        data-dep_dd2_name=""
                                        {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
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
                                </div>
                                <div class="form-group col-md-6">
                                    <label>HYPOTHECATION FINANCER</label>
                                    <select name="hyp_financer" class="form-control"
                                        {{ isset($data['payment_type']) && in_array($data['payment_type'], [2, 3]) ? '' : 'disabled' }}
                                        {{ $isClosed }}>
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
                            </div>

                            <div class="row" id="financeDetailSection"
                                style="display:{{ $action_type == 'create' || (isset($data['payment_type']) && $data['payment_type'] == '1') ? 'none' : 'block' }}">
                                <div class="form-group col-md-10">
                                    <label>FINANCE SCHEME DETAIL</label>
                                    <input name="hyp_financer_description" type="text" class="form-control"
                                        value="{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '' }}"
                                        placeholder="FINANCE SCHEME DETAIL..." readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <a href="{{ route('openFinanceDetail') }}"
                                        class="btn btn-md btn-primary ajaxModalPopup financeDetail"
                                        style="margin-top: 26px;" data-modal_title="ADD FINANCE DETAIL"
                                        data-modal_size="modal-lg">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> ADD FINANCE DETAIL
                                    </a>
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-md-3">
                                    <label>BRAND NAME</label>
                                    <select name="bike_brand" data-dep_dd_name="bike_model"
                                        data-url="{{ url('getAjaxDropdown') . '?req=models' }}"
                                        data-dep_dd2_name="bike_model_variant" data-dep_dd3_name="bike_color"
                                        class="form-control ajaxChangeCDropDown" {{ $is_disabled }} {{ $isClosed }}>
                                        <option value="">---Select Brand----</option>
                                        @isset($brands)
                                            @foreach ($brands as $key => $brand)
                                                <option
                                                    {{ (isset($data['bike_brand']) && $data['bike_brand'] == $brand->id) ||
                                                    ($method && $method == 'POST' && $key == 0)
                                                        ? 'selected'
                                                        : '' }}
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>MODEL NAME</label>
                                    <br />
                                    <div class="input-group col-sm-12">
                                        <select name="bike_model" data-dep_dd_name="bike_model_variant"
                                            style="width:100%;" data-url="{{ url('getAjaxDropdown') . '?req=variants' }}"
                                            data-dep_dd2_name="bike_color"
                                            class="form-control ajaxChangeCDropDown commonSelect2" {{ $isClosed }}>
                                            <option value="">---Select Model----</option>
                                            @isset($models)
                                                @foreach ($models as $model)
                                                    <option
                                                        {{ isset($data['bike_model']) && $data['bike_model'] == $model->id ? 'selected' : '' }}
                                                        value="{{ $model->id }}">{{ $model->model_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>MODEL VARIANT NAME</label>
                                    <select name="bike_model_variant" data-dep_dd_name="bike_color"
                                        data-url="{{ url('getAjaxDropdown') . '?req=colors' }}" data-dep_dd2_name=""
                                        class="form-control ajaxChangeCDropDown" {{ $isClosed }}>
                                        <option value="">---Select Variant----</option>
                                        @isset($variants)
                                            @foreach ($variants as $variant)
                                                <option
                                                    {{ isset($data['bike_model_variant']) && $data['bike_model_variant'] == $variant->id ? 'selected="selected"' : '' }}
                                                    value="{{ $variant->id }}">{{ $variant->variant_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>VARIANT COLOR NAME</label>
                                    <select name="bike_color" class="form-control" {{ $isClosed }}>
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
                            </div>

                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label>EX-SHOWROOM PRICE</label>
                                    <input name="ex_showroom_price" type="text" class="form-control totalAmountCal"
                                        value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>REGISTRATION AMOUNT</label>
                                    <input name="registration_amount" type="text" class="form-control totalAmountCal"
                                        value="{{ isset($data['registration_amount']) ? $data['registration_amount'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>INSURANCE AMOUNT</label>
                                    <input name="insurance_amount" type="text" class="form-control totalAmountCal"
                                        value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>HYPOTHECATION AMOUNT</label>
                                    <input name="hypothecation_amount" type="text" class="form-control totalAmountCal"
                                        value="{{ isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>ACCESSORIES / HELMET AMOUNT</label>
                                    <input name="accessories_amount" type="text" class="form-control totalAmountCal"
                                        value="{{ isset($data['accessories_amount']) ? $data['accessories_amount'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>OTHER AMOUNT</label>
                                    <input name="other_charges" type="text" class="form-control totalAmountCal"
                                        value="{{ isset($data['other_charges']) ? $data['other_charges'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>GRAND TOTAL AMOUNT</label>
                                    <input name="total_amount" type="text" class="form-control" readonly
                                        value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}"
                                        placeholder="₹ XXXX" {{ $isClosed }}>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>QUOTATIONS DATE</label>
                                    <input name="purchase_visit_date" type="date" class="form-control"
                                        {{ isset($is_readonly) && $is_readonly ? $is_readonly : '' }}
                                        value="{{ isset($data['purchase_visit_date']) ? $data['purchase_visit_date'] : date('Y-m-d') }}"
                                        placeholder="0000-00-00" {{ $isClosed }}>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>DELIVERY DATE</label>
                                    <input name="purchase_est_date" type="date" class="form-control"
                                        value="{{ isset($data['purchase_est_date']) ? $data['purchase_est_date'] : date('Y-m-d', strtotime('+1 month')) }}"
                                        placeholder="0000-00-00" {{ $isClosed }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- submit button --}}
                <div class="form-group">
                    <div class="box-footer">
                        <a href="{{ route('quotations.index') }}" class="btn btn-danger pull-left">BACK</a>
                        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                            {{ $isClosed }}>
                            @if (isset($method) && $method == 'PUT')
                                UPDATE
                            @else
                                CREATE
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection


@push('after-script')
    <script src="{{ asset('assets/modules/quotations.js') }}"></script>
@endpush

@push('after-css')
    <style>

    </style>
@endpush
