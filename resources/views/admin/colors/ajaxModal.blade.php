@php
    $rand = rand(11111, 99999);
@endphp
<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body" id="color_container">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Select Model Name: </label>
                <select class="form-control ajaxChangeCDropDown" name="bike_model" data-dep_dd_name="model_variant_id"
                    data-url="{{ url('getAjaxDropdown') . '?req=variants' }}">
                    <option value="">---- Select Model Name----</option>
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
                <label>Select Variant Name: </label>
                <select class="form-control" name="model_variant_id">
                    <option value="">---- Select Variant Name----</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-3">
                <label>Color Name</label>
                <input name="colors[{{ $rand }}][color_name]" type="text"
                    class="form-control autoCapitalized" value="" placeholder="COLOR NAME..">
            </div>
            <div class="form-group col-md-3">
                <label>Color Code</label>
                <input name="colors[{{ $rand }}][color_code]" type="text"
                    class="form-control autoCapitalized" value="" placeholder="COLOR CODE..">
            </div>
            <div class="form-group col-md-3">
                <label>SKU Code</label>
                <input name="colors[{{ $rand }}][sku_code]" type="text" class="form-control autoCapitalized"
                    value="" placeholder="SKU CODE..">
            </div>
            <div class="form-group col-md-2">
                <label>Status : </label>
                <select class="form-control" name="colors[{{ $rand }}][active_status]">
                    <option value="1" selected="selected">Active</option>
                    <option value="0">In Active </option>
                </select>
            </div>
            <div class="form-group col-md-1">
                <a href="#" class="btn btn-md btn-success addMoreInFormGroup addAjaxElement"
                    data-container_el="#color_container"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
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
