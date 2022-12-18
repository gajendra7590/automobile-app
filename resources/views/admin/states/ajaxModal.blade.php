<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>State Name</label>
            <input name="state_name" type="text" class="form-control"
                value="{{ isset($data['state_name']) ? $data['state_name'] : '' }}"
                placeholder="Please enter state name..">
        </div>
        <div class="form-group">
            <label>State Code</label>
            <input name="state_code" type="text" class="form-control"
                value="{{ isset($data['state_code']) ? $data['state_code'] : '' }}"
                placeholder="Please enter state code..">
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="country_id" type="hidden" value="1">
        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
            @if (isset($method) && $method == 'PUT')
                UPDATE
            @else
                SAVE
            @endif
        </button>
    </div>
</form>
