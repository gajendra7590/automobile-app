@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Purchase
                <small>Create</small>
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
                    <li><a href="{{route('purchases.index')}}">Purchase</a></li>
                    <li class="active">Create</li>
                </ol>

                {{-- customer details --}}
                <div class="box box-default">
                    <div data-widget="collapse">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer Details</h3>
                            <div class="box-tools pull-right">
                                <i class="fa fa-minus"></i>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <div class="col-md-6">
                                        <label class="col-md-3">Mr./Mrs./Ms.</label>
                                        <div class="col-md-9">
                                            <div class="form-check col-md-3">
                                                <input class="form-check-input" type="radio" name="addressed" value="Mr."
                                                    {{ isset($data) && $data->addressed == 'Mr.' ? 'checked' : '' }} >
                                                <label class="form-check-label">
                                                    Mr.
                                                </label>
                                            </div>
                                            <div class="form-check col-md-3">
                                                <input class="form-check-input" type="radio" name="addressed" value="Mrs."
                                                    {{ isset($data->addressed) && $data->addressed == 'Mrs.'  ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Mrs.
                                                </label>
                                            </div>
                                            <div class="form-check col-md-3">
                                                <input class="form-check-input" type="radio" name="addressed" value="Ms."
                                                    {{ isset($data->addressed) && $data->addressed == 'Ms.' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Ms.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="First Name" name="first_name"
                                        value='{{ isset($data) && $data->first_name ? $data->first_name : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>last Name</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="last Name" name="last_name"
                                        value='{{ isset($data) && $data->last_name ? $data->last_name : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Email" name="email"
                                        value='{{ isset($data) && $data->email ? $data->email : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Phone" name="phone"
                                        value='{{ isset($data) && $data->phone ? $data->phone : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Age</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Age" name="age"
                                        value='{{ isset($data) && $data->age ? $data->age : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-3">gender</label>
                                    <div class="col-md-9">
                                        <div class="form-check col-md-3">
                                            <input class="form-check-input" type="radio" name="gender" value="male"
                                                {{ isset($data) ? 'checked' : '' }} >
                                            <label class="form-check-label">
                                                Male
                                            </label>
                                        </div>
                                        <div class="form-check col-md-3">
                                            <input class="form-check-input" type="radio" name="gender" value="female"
                                                {{ isset($data->gender) && $data->gender == 'female'  ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Occupation</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Occupation" name="occupation"
                                        value='{{ isset($data) && $data->occupation ? $data->occupation : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>GST No</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="GST No" name="gst_no"
                                        value='{{ isset($data) && $data->gst_no ? $data->gst_no : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Booking Type</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Booking Type" name="booking_type"
                                        value='{{ isset($data) && $data->booking_type ? $data->booking_type : '' }}' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="box box-default">
                    <div data-widget="collapse">
                        <div class="box-header with-border">
                            <h3 class="box-title">Address</h3>
                            <div class="box-tools pull-right">
                                <i class="fa fa-minus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label>State</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="State" name="state"
                                        value='{{ isset($data) && $data->state ? $data->state : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>District</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="District" name="district"
                                        value='{{ isset($data) && $data->district ? $data->district : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>City</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="City" name="city"
                                        value='{{ isset($data) && $data->city ? $data->city : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Address Line1</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Address Line1" name="address_line1"
                                        value='{{ isset($data) && $data->address_line1 ? $data->address_line1 : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Address Line2</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Address Line2" name="address_line2"
                                        value='{{ isset($data) && $data->address_line2 ? $data->address_line2 : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pin Code</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="GST Pin Code" name="pin_code"
                                        value='{{ isset($data) && $data->pin_code ? $data->pin_code : '' }}' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Information --}}
                <div class="box box-default">
                    <div data-widget="collapse">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vehicle Information</h3>
                            <div class="box-tools pull-right">
                                <i class="fa fa-minus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label>Model In Inters</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Model In Inters" name="model_in_inters"
                                        value='{{ isset($data) && $data->model_in_inters ? $data->model_in_inters : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Variant</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Variant" name="varient"
                                        value='{{ isset($data) && $data->varient ? $data->varient : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Color Code</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Color Code" name="color_code"
                                        value='{{ isset($data) && $data->color_code ? $data->color_code : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Quantity" name="quantity"
                                        value='{{ isset($data) && $data->quantity ? $data->quantity : '' }}' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Enquiry Information --}}
                <div class="box box-default">
                    <div data-widget="collapse">
                        <div class="box-header with-border">
                            <h3 class="box-title">Enquiry Information</h3>
                            <div class="box-tools pull-right">
                                <i class="fa fa-minus"></i>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label class="col-md-4">Exising Customer</label>
                                    <div class="col-md-6">
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="existing_customer" value="0"
                                                {{ isset($data) && $data->existing_customer == '1' ? 'checked' : '' }} >
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="existing_customer" value="1"
                                                {{ !isset($data->existing_customer) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-4">Exchange Enquiry</label>
                                    <div class="col-md-6">
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="exchange_enquiry" value="0"
                                                {{ isset($data) && $data->exchange_enquiry == '1' ? 'checked' : '' }} >
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="exchange_enquiry" value="1"
                                                {{ !isset($data->exchange_enquiry) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-4">Finace Requirement</label>
                                    <div class="col-md-6">
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="finance_requirement" value="0"
                                                {{ isset($data) && $data->finance_requirement == '1' ? 'checked' : '' }} >
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="finance_requirement" value="1"
                                                {{ !isset($data->finance_requirement) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-4">Loyalty Customer</label>
                                    <div class="col-md-6">
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="loyalty_customer" value="0"
                                                {{ isset($data) && $data->loyalty_customer == '1' ? 'checked' : '' }} >
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="loyalty_customer" value="1"
                                                {{ !isset($data->loyalty_customer) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-md-4">
                                    <label>Enquiry Date</label>
                                    <input type="date" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Enquiry Date" name="enquiry_date"
                                        value='{{ isset($data) && $data->enquiry_date ? $data->enquiry_date : '' }}' />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Expected Date Of Purchase</label>
                                    <input type="date" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Expected Date Of Purchase" name="expected_date_of_purchase"
                                        value='{{ isset($data) && $data->expected_date_of_purchase ? $data->expected_date_of_purchase : '' }}' />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Next Follow Date</label>
                                    <input type="date" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Next Follow Date" name="next_follow_date"
                                        value='{{ isset($data) && $data->next_follow_date ? $data->next_follow_date : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>DSE Name</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="DSE Name" name="dse_name"
                                        value='{{ isset($data) && $data->dse_name ? $data->dse_name : '' }}' />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Order Number</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                        placeholder="Order Number" name="order_number"
                                        value='{{ isset($data) && $data->order_number ? $data->order_number : '' }}' />
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
