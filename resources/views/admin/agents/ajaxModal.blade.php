<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-6">
            <label>Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Name" required
                name="name" value='{{ isset($data) && $data ? $data->name : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Email</label>
            <input type='email' class="form-control my-colorpicker1 colorpicker-element" placeholder="Email" required
                name="email" value="{{ isset($data) && $data ? $data->email : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Mobile Number</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Mobile Number"
                required name="mobile_number" value="{{ isset($data) && $data ? $data->mobile_number : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Mobile Number Alternate</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Mobile Number Alternate" name="mobile_number2"
                value="{{ isset($data) && $data ? $data->mobile_number2 : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Aadhar Card Number(12 digits)</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Aadhar Card Number" name="aadhar_card"
                value="{{ isset($data) && $data ? $data->aadhar_card : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Pancard Number(10 digits)</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Pancard Number"
                name="pan_card" value="{{ isset($data) && $data ? $data->pan_card : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Gender</label>
            <select class="form-control select2" style="width: 100%">
                <option value='male' {{ isset($data) && $data->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value='female' {{ isset($data) && $data->gender == 'female' ? 'selected' : '' }}>Female
                </option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Date Of Birth</label>
            <input type="date" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask=""
                placeholder="Date Of Birth" name="date_of_birth"
                value="{{ isset($data) && $data ? $data->date_of_birth : '' }}">
        </div>
        <div class="form-group col-md-4">
            <label>Highest Qualification</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Highest Qualification" name="highest_qualification"
                value="{{ isset($data) && $data ? $data->highest_qualification : '' }}" />
        </div>
        <div class="form-group col-md-12">
            <label>Address Line</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Address Line"
                name="address_line" value="{{ isset($data) && $data->address_line ? $data->address_line : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>State</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="State"
                name="state" value="{{ isset($data) && $data->state ? $data->state : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>District</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="District"
                name="district" value="{{ isset($data) && $data->district ? $data->district : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>City</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="City"
                name="city" value="{{ isset($data) && $data->city ? $data->city : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Zipcode</label>
            <input type='number' class="form-control my-colorpicker1 colorpicker-element" placeholder="Zipcode"
                name="zipcode" value="{{ isset($data) && $data->zipcode ? $data->zipcode : '' }}" />
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
        <div class="form-group col-md-12">
            <label>More Details</label>
            <textarea type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="More Details"
                name="more_details">{{ isset($data) && $data->more_details ? $data->more_details : '' }} </textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12">
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
    </div>
</form>
