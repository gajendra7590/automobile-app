<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-6">
            <label>Broker Name</label>
            <input type="text" class="form-control autoCapitalized" placeholder="Enter name.." name="name"
                value='{{ isset($data) && $data ? $data->name : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Broker Email Address</label>
            <input type='text' class="form-control" placeholder="Enter email address.." name="email"
                value="{{ isset($data) && $data ? $data->email : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Mobile Number</label>
            <input type='text' class="form-control" placeholder="Enter nmbile number.." name="mobile_number"
                value="{{ isset($data) && $data ? $data->mobile_number : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Mobile Number Alternate</label>
            <input type='text' class="form-control" placeholder="Mobile Number Alternate" name="mobile_number2"
                value="{{ isset($data) && $data ? $data->mobile_number2 : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Aadhar Card Number(12 digits)</label>
            <input type='text' class="form-control" placeholder="Aadhar Card Number" name="aadhar_card"
                value="{{ isset($data) && $data ? $data->aadhar_card : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Pan Card Number(10 digits)</label>
            <input type='text' class="form-control" placeholder="Pancard Number" name="pan_card"
                value="{{ isset($data) && $data ? $data->pan_card : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Gender</label>
            <select name="gender" class="form-control select2" style="width: 100%">
                <option value='1' {{ isset($data) && $data->gender == '1' ? 'selected' : '' }}>Male</option>
                <option value='2' {{ isset($data) && $data->gender == '2' ? 'selected' : '' }}>Female</option>
                <option value='3' {{ isset($data) && $data->gender == '3' ? 'selected' : '' }}>Other </option>
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
            <input type='text' class="form-control" placeholder="Highest Qualification" name="highest_qualification"
                value="{{ isset($data) && $data ? $data->highest_qualification : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label>Address Line</label>
            <input type='text' class="form-control" placeholder="Address Line" name="address_line"
                value="{{ isset($data) && $data->address_line ? $data->address_line : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label>State</label>
            <select name="state" data-url="{{ url('getAjaxDropdown') . '?req=districts' }}"
                data-dep_dd_name="district" data-dep_dd2_name="city" class="form-control ajaxChangeCDropDown">
                <option value="">---Select State---</option>
                @if (isset($states))
                    @foreach ($states as $key => $state)
                        <option
                            {{ (isset($data->state) && $data->state == $state->id) || (!isset($data) && $key == 0) ? 'selected' : '' }}
                            value="{{ $state->id }}">{{ $state->state_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>District</label>
            <select name="district" data-dep_dd_name="city" data-url="{{ url('getAjaxDropdown') . '?req=cities' }}"
                class="form-control ajaxChangeCDropDown">
                <option value="">---Select District---</option>
                @if (isset($districts))
                    @foreach ($districts as $key => $district)
                        <option
                            {{ (isset($data->district) && $data->district == $district->id) || (!isset($data) && $key == 0) ? 'selected' : '' }}
                            value="{{ $district->id }}">{{ $district->district_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>City</label>
            <span style="margin-left: 40px;">
                <a href="{{ route('plusAction') }}" class="plusAction" id="city" data-type="city" data-type="city"
                    data-ddname="city" data-ddchange="district" data-modalid="ajaxModalCommon2" aria-hidden="true"
                    data-modal_title="Add New City/Village/Town" data-modal-index="1200" data-modal_type="2"
                    data-modal_size="modal-md">
                    <i class="fa fa-plus-circle" title="Add New City/Village/Town"></i>
                </a>
            </span>
            <select name="city" class="form-control">
                <option value="">---Select City---</option>
                @if (isset($cities))
                    @foreach ($cities as $key => $city)
                        <option
                            {{ (isset($data->city) && $data->city == $city->id) || (!isset($data) && $key == 0) ? 'selected="selected"' : '' }}
                            value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Zipcode</label>
            <input type='number' class="form-control" placeholder="Zipcode" name="zipcode"
                value="{{ isset($data) && $data->zipcode ? $data->zipcode : '' }}" />
        </div>
    </div>
    <div class="row">
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
            <textarea type='text' class="form-control" placeholder="More Details" name="more_details">{{ isset($data) && $data->more_details ? $data->more_details : '' }} </textarea>
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
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            mobile_number2: {
                required: false,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            aadhar_card: {
                required: false,
                digits: true,
                minlength: 12,
                maxlength: 12
            },
            pan_card: {
                required: false,
                minlength: 10,
                maxlength: 10,
            },
        },
        messages: {
            name: {
                required: "The broker name field is required"
            },
            email: {
                required: "The broker email field is required",
                email: "The broker email should valid email address"
            },
            mobile_number: {
                required: "The mobile number field is required",
                digits: "The mobile number should valid 10 digits",
                minlength: "The mobile number should valid 10 digits",
                maxlength: "The mobile number should valid 10 digits"
            },
            mobile_number2: {
                digits: "The alt mobile number should valid 10 digits",
                minlength: "The alt mobile number should valid 10 digits",
                maxlength: "The alt mobile number should valid 10 digits"
            },
            aadhar_card: {
                digits: "The aadhar card should valid 12 digits",
                minlength: "The aadhar card should valid 12 digits",
                maxlength: "The aadhar card should valid 12 digits"
            },
            pan_card: {
                minlength: "The pan card should valid 10 digits",
                maxlength: "The pan card should valid 10 digits"
            },
        }
    });
</script>
