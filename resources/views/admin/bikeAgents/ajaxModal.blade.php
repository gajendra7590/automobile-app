<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Name"
                name="name" value='{{ isset($data) && $data ? $data->name : '' }}' />
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type='email' class="form-control my-colorpicker1 colorpicker-element" placeholder="Email"
                name="email" value="{{ isset($data) && $data ? $data->email : '' }}" />
        </div>
        <div class="form-group">
            <label>Mobile Number</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Mobile Number"
                name="mobile_number" value="{{ isset($data) && $data ? $data->mobile_number : '' }}" />
        </div>
        <div class="form-group">
            <label>Mobile Number 2</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Mobile Number 2"
                name="mobile_number2" value="{{ isset($data) && $data ? $data->mobile_number2 : '' }}" />
        </div>
        <div class="form-group">
            <label>Aadhar Card</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Aadhar Card"
                name="aadhar_card" value="{{ isset($data) && $data ? $data->aadhar_card : '' }}" />
        </div>
        <div class="form-group">
            <label>Pan Card</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Pan Card"
                name="pan_card" value="{{ isset($data) && $data ? $data->pan_card : '' }}" />
        </div>
        <div class="form-group">
            <label>Date Of Birth</label>
            <input type="date" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask=""
                placeholder="Date Of Birth" name="date_of_birth"
                value="{{ isset($data) && $data ? $data->date_of_birth : '' }}">
        </div>
        <div class="form-group">
            <label>Highest Qualification</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Highest Qualification" name="highest_qualification"
                value="{{ isset($data) && $data ? $data->highest_qualification : '' }}" />
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select class="form-control select2" style="width: 100%">
                <option value='male' {{ isset($data) && $data->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value='female' {{ isset($data) && $data->gender == 'female' ? 'selected' : '' }}>Female
                </option>
            </select>
        </div>
        <div class="form-group">
            <label>Address Line</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Highest Qualification" name="address_line"
                value="{{ isset($data) && $data->address_line ? $data->address_line : '' }}" />
        </div>
        <div class="form-group">
            <label>State</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="State"
                name="state" value="{{ isset($data) && $data->state ? $data->state : '' }}" />
        </div>
        <div class="form-group">
            <label>District</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="District"
                name="district" value="{{ isset($data) && $data->district ? $data->district : '' }}" />
        </div>
        <div class="form-group">
            <label>City</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="City"
                name="city" value="{{ isset($data) && $data->city ? $data->city : '' }}" />
        </div>
        <div class="form-group">
            <label>Zipcode</label>
            <input type='number' class="form-control my-colorpicker1 colorpicker-element" placeholder="Zipcode"
                name="zipcode" value="{{ isset($data) && $data->zipcode ? $data->zipcode : '' }}" />
        </div>
        <div class="form-group">
            <label>More Details</label>
            <textarea type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="More Details"
                name="more_details">{{ isset($data) && $data->more_details ? $data->more_details : '' }} </textarea>
        </div>
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
