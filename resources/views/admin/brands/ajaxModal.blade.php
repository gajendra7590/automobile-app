<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>Brand Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Name"
                name="name" value='{{ isset($data) && $data ? $data->name : '' }}' />
        </div>
        <div class="form-group">
            <label>Brand Code</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Code"
                name="code" value='{{ isset($data) && $data ? $data->code : '' }}' />
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control my-colorpicker1 colorpicker-element" placeholder="Description" name="description"> {{ isset($data) && $data ? $data->description : '' }} </textarea>
        </div>
        <div class="form-group">
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
