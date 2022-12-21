<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>Select Model : </label>
            <select class="form-control" name="bike_model">
                <option value="">---- Select Model ----</option>
                @isset($models)
                    @foreach ($models as $model)
                        <option
                            {{ isset($data['bike_model']) && $data['bike_model'] == $model->id ? 'selected="selected"' : '' }}
                            value="{{ $model->id }}">{{ $model->model_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
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
