<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>Select Model Name</label>
            <select class="form-control" name="model_id">
                @isset($models)
                    @foreach ($models as $model)
                        <option
                            {{ isset($data['model_id']) && $data['model_id'] == $model->id ? 'selected="selected"' : '' }}
                            value="{{ $model->id }}">{{ $model->model_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="form-group">
            <label>Variant Code</label>
            <input name="variant_name" type="text" class="form-control autoCapitalized"
                value="{{ isset($data['variant_name']) ? $data['variant_name'] : '' }}"
                placeholder="Please enter variant code..">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="active_status">
                <option value="1"
                    {{ isset($data['active_status']) && $data['active_status'] == '1' ? 'selected="selected"' : '' }}>
                    Active
                </option>
                <option value="0"
                    {{ isset($data['active_status']) && $data['active_status'] == '0' ? 'selected="selected"' : '' }}>
                    In Active
                </option>
            </select>
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
