<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            <label>Branch Manager Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Branch Manager Name" name="branch_manager_name"
                value='{{ isset($data) && $data->branch_manager_name ? $data->branch_manager_name : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Branch Manager Phone</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Branch Manager Phone" name="branch_manager_phone"
                value="{{ isset($data) && $data->branch_manager_phone ? $data->branch_manager_phone : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Branch Name</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Branch Name Phone" required name="branch_name"
                value="{{ isset($data) && $data->branch_name ? $data->branch_name : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Branch Phone</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Branch Phone"
                required name="branch_phone"
                value="{{ isset($data) && $data->branch_phone ? $data->branch_phone : '' }}" />
        </div>

        <div class="form-group col-md-8">
            <label>Branch Address Line</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Branch Address Line" name="branch_address_line"
                value="{{ isset($data) && $data->branch_address_line ? $data->branch_address_line : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Branch State</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Branch State"
                name="branch_state" value="{{ isset($data) && $data->branch_state ? $data->branch_state : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Branch District</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Branch District"
                name="branch_district"
                value="{{ isset($data) && $data->branch_district ? $data->branch_district : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Branch City</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Branch City"
                name="branch_city" value="{{ isset($data) && $data->branch_city ? $data->branch_city : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Branch Pincode</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Branch Pincode"
                name="branch_pincode"
                value="{{ isset($data) && $data->branch_pincode ? $data->branch_pincode : '' }}" />
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
            <label>Branch More Detail</label>
            <textarea type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Branch More Detail"
                name="branch_more_detail"> {{ isset($data) && $data->branch_more_detail ? $data->branch_more_detail : '' }} </textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    <?php if(isset($method) && $method == 'PUT'): ?>
                    UPDATE
                    <?php else: ?>
                    SAVE
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>


</form>
