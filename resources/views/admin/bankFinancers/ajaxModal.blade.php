<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif


    <div class="row">
        <div class="form-group col-md-4">
            <label>Bank Name</label>
            <input type="text" class="form-control autoCapitalized" placeholder="Bank Name" name="bank_name"
                value='{{ isset($data) && $data->bank_name ? $data->bank_name : '' }}' />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Branch Code</label>
            <input type='text' class="form-control autoCapitalized" placeholder="Bank Branch Code"
                name="bank_branch_code"
                value="{{ isset($data) && $data->bank_branch_code ? $data->bank_branch_code : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Contact Number</label>
            <input type='text' class="form-control" placeholder="Bank Contact Number" name="bank_contact_number"
                value="{{ isset($data) && $data->bank_contact_number ? $data->bank_contact_number : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Bank Email Address</label>
            <input type='email' class="form-control" placeholder="Bank Email Address" name="bank_email_address"
                value="{{ isset($data) && $data->bank_email_address ? $data->bank_email_address : '' }}" />
        </div>
        <div class="form-group col-md-8">
            <label>Bank Full Address</label>
            <input type='text' class="form-control" placeholder="Bank Full Address" name="bank_full_address"
                value="{{ isset($data) && $data->bank_full_address ? $data->bank_full_address : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Bank Manager Name</label>
            <input type='text' class="form-control autoCapitalized" placeholder="Bank Manager Name"
                name="bank_manager_name"
                value="{{ isset($data) && $data->bank_manager_name ? $data->bank_manager_name : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Manager Contact</label>
            <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask=""
                placeholder="Bank Manager Contact" name="bank_manager_contact"
                value="{{ isset($data) && $data->bank_manager_contact ? $data->bank_manager_contact : '' }}">
        </div>
        <div class="form-group col-md-4">
            <label>Bank Manager Email</label>
            <input type='email' class="form-control" placeholder="Bank Manager Email" name="bank_manager_email"
                value="{{ isset($data) && $data->bank_manager_email ? $data->bank_manager_email : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Bank Financer Name</label>
            <input type='text' class="form-control" placeholder="Bank Financer Name" name="bank_financer_name"
                value="{{ isset($data) && $data->bank_financer_name ? $data->bank_financer_name : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Financer Contact</label>
            <input type='text' class="form-control" placeholder="Bank Financer Contact" name="bank_financer_contact"
                value="{{ isset($data) && $data->bank_financer_contact ? $data->bank_financer_contact : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Financer Email</label>
            <input type='email' class="form-control" placeholder="Bank Financer Email" name="bank_financer_email"
                value="{{ isset($data) && $data->bank_financer_email ? $data->bank_financer_email : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Financer Address</label>
            <input type='text' class="form-control" placeholder="Bank Financer Address" name="bank_financer_address"
                value="{{ isset($data) && $data->bank_financer_address ? $data->bank_financer_address : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Financer Aadhar Card</label>
            <input type='number' class="form-control" placeholder="Bank Financer Aadhar Card"
                name="bank_financer_aadhar_card"
                value="{{ isset($data) && $data->bank_financer_aadhar_card ? $data->bank_financer_aadhar_card : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Bank Financer Pan Card</label>
            <input type='number' class="form-control" placeholder="Bank Financer Pan Card"
                name="bank_financer_pan_card"
                value="{{ isset($data) && $data->bank_financer_pan_card ? $data->bank_financer_pan_card : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Financer Type: </label>
            <select class="form-control" name="financer_type">
                <option value="1"
                    {{ isset($data['financer_type']) && $data['financer_type'] == '1' ? 'selected="selected"' : '' }}>
                    Bank Financers
                </option>
                <option value="2"
                    {{ isset($data['financer_type']) && $data['financer_type'] == '2' ? 'selected="selected"' : '' }}>
                    Personal Financers
                </option>
            </select>
        </div>

        <div class="form-group col-md-6">
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
            bank_name: {
                required: true
            },
            financer_type: {
                required: true
            }
        },
        messages: {
            bank_name: {
                required: "The Bank name field is required"
            },
            financer_type: {
                required: "The Financer type field is required"
            }
        }
    });
</script>
