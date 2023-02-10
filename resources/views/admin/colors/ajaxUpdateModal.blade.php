<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Select Model</label>
                <select class="form-control ajaxChangeCDropDown" name="bike_model" data-dep_dd_name="model_variant_id"
                    data-url="{{ url('getAjaxDropdown') . '?req=variants' }}">
                    @if (!isset($data['bike_model']) && intval($data['bike_model']) == 0)
                        <option value="">---Select Model---</option>
                    @endif
                    @isset($models)
                        @foreach ($models as $model)
                            <option
                                {{ isset($data['bike_model']) && $data['bike_model'] == $model->id ? 'selected="selected"' : '' }}
                                value="{{ $model->id }}">{{ $model->model_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Select Variant</label>
                <select class="form-control" name="model_variant_id">
                    @if (!isset($data['model_variant_id']) || intval($data['model_variant_id']) == 0)
                        <option value="">---Select Variant---</option>
                    @endif
                    @isset($variants)
                        @foreach ($variants as $variant)
                            <option
                                {{ isset($data['model_variant_id']) && $data['model_variant_id'] == $variant->id ? 'selected="selected"' : '' }}
                                value="{{ $variant->id }}">
                                {{ $variant->variant_name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Color Name</label>
                <input name="color_name" type="text" class="form-control autoCapitalized"
                    value="{{ isset($data['color_name']) ? $data['color_name'] : '' }}"
                    placeholder="Please enter color name..">
            </div>
            <div class="form-group col-md-6">
                <label>Color Code</label>
                <input name="color_code" type="text" class="form-control autoCapitalized"
                    value="{{ isset($data['color_code']) ? $data['color_code'] : '' }}"
                    placeholder="Please enter model code..">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>SKU Code</label>
                <input name="sku_code" type="text" class="form-control autoCapitalized"
                    value="{{ isset($data['sku_code']) ? $data['sku_code'] : '' }}"
                    placeholder="Please enter sku code..">
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
            bike_model: {
                required: true
            },
            model_variant_id: {
                required: true
            },
            color_name: {
                required: true
            },
            sku_code: {
                required: true
            }
        },
        messages: {
            bike_model: {
                required: "The model field is required"
            },
            model_variant_id: {
                required: "The model variant field is required"
            },
            color_name: {
                required: "The color name field is required"
            },
            sku_code: {
                required: "The SKU Code field is required"
            }
        }
    });
</script>
