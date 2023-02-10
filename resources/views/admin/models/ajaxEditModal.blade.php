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
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            brand_id: {
                required: true
            },
            model_name: {
                required: true
            }
        },
        messages: {
            brand_id: {
                required: "The brand field is required"
            },
            model_name: {
                required: "The model name field is required"
            }
        }
    });
</script>
