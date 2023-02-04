@php
    $rand = rand(11111, 99999);
@endphp
<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body" id="model_container">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Select Model : </label>
                <select class="form-control" name="model_id">
                    <option value="">---- Select Model ----</option>
                    @isset($models)
                        @foreach ($models as $model)
                            <option
                                {{ isset($data['model_id']) && $data['model_id'] == $model->id ? 'selected="selected"' : '' }}
                                value="{{ $model->id }}">{{ $model->model_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>Variant Code</label>
                <input name="variants[{{ $rand }}][variant_name]" type="text"
                    class="form-control autoCapitalized" value="" placeholder="Variant Code..">
            </div>
            <div class="form-group col-md-4">
                <label>Variant Status : </label>
                <select class="form-control " name="variants[{{ $rand }}][active_status]">
                    <option value="1" selected>Active</option>
                    <option value="0"> In Active </option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <a href="#" class="btn btn-md btn-success addMoreInFormGroup addAjaxElement"
                    data-container_el="#model_container"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
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
