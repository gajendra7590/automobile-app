@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Purchase <small>Create</small>
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
                    <li class="active">Create</li>
                </ol>
                <div class="box box-default">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title">Purchase Detail</h3>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Branch Name</label>
                                    <select name="bike_branch" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Bike Dealer</label>
                                    <select name="bike_dealer" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Brand Name</label>
                                    <select name="bike_brand" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Model Name</label>
                                    <select name="bike_model" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Model Color</label>
                                    <select name="bike_model_color" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Vehicle Type</label>
                                    <select name="bike_type" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Fuel Type</label>
                                    <select name="bike_fuel_type" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Break Type</label>
                                    <select name="break_type" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Wheal Type</label>
                                    <select name="wheel_type" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>DC Number</label>
                                    <input type="text" class="form-control" placeholder="DC Number" name="dc_number" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>DC Date</label>
                                    <input type="text" class="form-control" placeholder="DC Date" name="dc_date" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>VIN Number(Chasis Number)</label>
                                    <input type="text" class="form-control" placeholder="DC Number" name="vin_number" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>VIN Physical Status</label>
                                    <select name="vin_physical_status" class="form-control">
                                        <option value="">Test</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" placeholder="SKU" name="sku" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SKU Description</label>
                                    <input type="text" class="form-control" placeholder="SKU Description"
                                        name="sku_description" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label>HSN Number</label>
                                    <input type="text" class="form-control" placeholder="HSN Number" name="hsn_number" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Model Number</label>
                                    <input type="text" class="form-control" placeholder="Model Number"
                                        name="model_number" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Engine Number</label>
                                    <input type="text" class="form-control" placeholder="Engine Number"
                                        name="engine_number" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Key Number</label>
                                    <input type="text" class="form-control" placeholder="Key Number"
                                        name="key_number" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Service Book Number</label>
                                    <input type="text" class="form-control" placeholder="Service Book Number"
                                        name="service_book_number" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label>Tyre Brand Name</label>
                                    <input type="text" class="form-control" placeholder="Tyre Brand Name"
                                        name="tyre_brand_name" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tyre Front Number</label>
                                    <input type="text" class="form-control" placeholder="Tyre Front Number"
                                        name="tyre_front_number" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tyre Rear Number</label>
                                    <input type="text" class="form-control" placeholder="Tyre Rear Number"
                                        name="tyre_rear_number" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-3">
                                    <label>Battery Brand Name</label>
                                    <input type="text" class="form-control" placeholder="Battery Brand Name"
                                        name="battery_brand" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Battery Number</label>
                                    <input type="text" class="form-control" placeholder="Battery Number"
                                        name="battery_number" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Sale Price(₹)</label>
                                    <input type="text" class="form-control" placeholder="Sale Price(₹)"
                                        name="sale_price" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Final Price(₹)</label>
                                    <input type="text" class="form-control" placeholder="Final Price(₹)"
                                        name="final_price" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label>Purchase Invoice Amount(₹)</label>
                                    <input type="text" class="form-control" placeholder="Purchase Invoice Amount(₹)"
                                        name="purchase_invoice_amount" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchase Invoice Number</label>
                                    <input type="text" class="form-control" placeholder="Purchase Invoice Number"
                                        name="purchase_invoice_number" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchase Invoice Date</label>
                                    <input type="text" class="form-control" placeholder="Purchase Invoice Date"
                                        name="purchase_invoice_date" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <label>Vehicle Description</label>
                                    <textarea name="bike_description" rows="5" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- submit button --}}
                <div class="form-group">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                            @if (isset($method) && $method == 'PUT')
                                UPDATE
                            @else
                                SAVE
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection