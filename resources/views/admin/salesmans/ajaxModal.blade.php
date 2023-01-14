<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-4">
            <label>Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Name" required
                name="name" value='{{ isset($data) && $data ? $data->name : '' }}' />
        </div>
        <div class="form-group col-md-4">
            <label>Email</label>
            <input type='email' class="form-control my-colorpicker1 colorpicker-element" placeholder="Email" required
                name="email" value="{{ isset($data) && $data ? $data->email : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Mobile Number</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Mobile Number"
                required name="mobile_number" value="{{ isset($data) && $data ? $data->mobile_number : '' }}" />
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
