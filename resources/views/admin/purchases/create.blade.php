@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
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
                                                    {{ isset($data->bike_dealer) && $data->bike_dealer == $dealer->id ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Brand Name</label>
                                    <select name="bike_brand" class="form-control">
                                        <option value="">---Select Brand---</option>
                                        @if (isset($brands))
                                            @foreach ($brands as $key => $brand)
                                                <option
                                                    {{ isset($data->bike_brand) && $data->bike_brand == $brand->id ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Model Name</label>
                                    <select name="bike_model" class="form-control">
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
                                        <option value="">---Select Model Color---</option>
                                        @if (isset($colors))
                                            @foreach ($colors as $key => $color)
                                                <option
                                                    {{ isset($data->bike_model_color) && $data->bike_model_color == $color->id ? 'selected="selected"' : ($key == 0 ? 'selected' : '') }}
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
                    </div>
                </div>

                {{-- submit button --}}
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
        $(document).ready(function() {
            $('select[name="bike_brand"]').change(function() {
                let id = $(this).val();
                let URL = "{{ url('getModelsList') }}/" + id;
                CRUD.AJAXDATA(URL, 'GET').then(function(res) {
                    $('select[name="bike_model"]').html(res.data);
                });
            });
        });
    </script>
@endpush
