<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>Color Name</label>
            <input name="color_name" type="text" class="form-control"
                value="{{ isset($data['color_name']) ? $data['color_name'] : '' }}"
                placeholder="Please enter color name..">
        </div>
        <div class="form-group">
            <label>Color Code</label>
            <input name="color_code" type="text" class="form-control"
                value="{{ isset($data['color_code']) ? $data['color_code'] : '' }}"
                placeholder="Please enter model code..">
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
            @if (isset($method) && $method == 'PUT')
                UPDATE
            @else
                SAVE
            @endif
        </button>
    </div>
</form>
