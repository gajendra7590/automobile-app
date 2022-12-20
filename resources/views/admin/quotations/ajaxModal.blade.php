<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="box-body">
            <div class="form-group col-md-4">
                <label>Customer First Name</label>
                <input name="customer_first_name" type="text" class="form-control"
                    value="{{ isset($data['customer_first_name']) ? $data['customer_first_name'] : '' }}"
                    placeholder="Customer First Name..">
            </div>
            <div class="form-group col-md-4">
                <label>Customer Middle Name</label>
                <input name="customer_middle_name" type="text" class="form-control"
                    value="{{ isset($data['customer_middle_name']) ? $data['customer_middle_name'] : '' }}"
                    placeholder="Customer Middle Name..">
            </div>
            <div class="form-group col-md-4">
                <label>Customer Last Name</label>
                <input name="customer_last_name" type="text" class="form-control"
                    value="{{ isset($data['customer_last_name']) ? $data['customer_last_name'] : '' }}"
                    placeholder="Customer Last Name..">
            </div>

            <div class="form-group col-md-12">
                <label>Customer Address Line</label>
                <input name="customer_address_line" type="text" class="form-control"
                    value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
                    placeholder="Customer Address Line..">
            </div>
            <div class="form-group col-md-3">
                <label>Customer State</label>
                <select name="customer_state" class="form-control">
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
                <select name="customer_district" class="form-control">
                    <option value="">---Select District---</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Customer City/Village <span class="addMoreIcon"><a title="Add New" href=""><i
                                class="fa fa-plus-circle" aria-hidden="true"></i></a></span> </label>
                <select name="customer_city" class="form-control">
                    <option value="">---Select City/Village----</option>
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
                    <option value="Both"
                        {{ isset($data['payment_type']) && $data['payment_type'] == 'Both' ? 'selected="selected"' : '' }}>
                        Both(Cash & Finance)
                    </option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Is Exchange</label>
                <select class="form-control" name="payment_type">
                    <option value="No"
                        {{ isset($data['is_exchange_avaliable']) && $data['is_exchange_avaliable'] == 'No' ? 'selected="selected"' : '' }}>
                        No
                    </option>
                    <option value="Finance"
                        {{ isset($data['is_exchange_avaliable']) && $data['is_exchange_avaliable'] == 'Yes' ? 'selected="selected"' : '' }}>
                        Yes
                    </option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Hypothecation Financer</label>
                <select name="customer_city" class="form-control">
                    <option value="">---Select Hypothecation Financer----</option>
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
                <select name="bike_brand" class="form-control">
                    <option value="">---Select Brand----</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Bike Model</label>
                <select name="customer_city" class="form-control">
                    <option value="">---Select Model----</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Bike Color</label>
                <select name="customer_city" class="form-control">
                    <option value="">---Select Color----</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Ex Showroom Price</label>
                <input name="ex_showroom_price" type="text" class="form-control"
                    value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}"
                    placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Registration Amount</label>
                <input name="registration_amount" type="text" class="form-control"
                    value="{{ isset($data['registration_amount']) ? $data['registration_amount'] : '' }}"
                    placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Insurance Amount</label>
                <input name="insurance_amount" type="text" class="form-control"
                    value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}"
                    placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Insurance Amount</label>
                <input name="insurance_amount" type="text" class="form-control"
                    value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}"
                    placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Hypothecation Amount</label>
                <input name="hypothecation_amount" type="text" class="form-control"
                    value="{{ isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : '' }}"
                    placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Accessories Amount</label>
                <input name="accessories_amount" type="text" class="form-control"
                    value="{{ isset($data['accessories_amount']) ? $data['accessories_amount'] : '' }}"
                    placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Other Amount</label>
                <input name="other_charges" type="text" class="form-control"
                    value="{{ isset($data['other_charges']) ? $data['other_charges'] : '' }}" placeholder="₹ XXXX">
            </div>
            <div class="form-group col-md-3">
                <label>Total Amount</label>
                <input name="total_amount" type="text" class="form-control"
                    value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}" placeholder="₹ XXXX">
            </div>

            <div class="form-group col-md-6">
                <label>Purchase Visit Date</label>
                <input name="purchase_visit_date" type="date" class="form-control"
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
    <!-- /.box-body -->
    <div class="row">
        <div class="box-footer">
            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    @if (isset($method) && $method == 'PUT')
                        UPDATE
                    @else
                        CREATE
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>
