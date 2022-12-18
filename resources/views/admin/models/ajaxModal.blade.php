<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>Select Brand : </label>
            <select class="form-control" name="brand_id">
                <option value="">---- Select Brand ----</option>
                @isset($brands)
                    @foreach ($brands as $brand)
                        <option
                            {{ isset($data['brand_id']) && $data['brand_id'] == $brand->id ? 'selected="selected"' : '' }}
                            value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="form-group">
            <label>Model Name</label>
            <input name="model_name" type="text" class="form-control"
                value="{{ isset($data['model_name']) ? $data['model_name'] : '' }}"
                placeholder="Please enter model name..">
        </div>
        <div class="form-group">
            <label>Model Code</label>
            <input name="model_code" type="text" class="form-control"
                value="{{ isset($data['model_code']) ? $data['model_code'] : '' }}"
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
