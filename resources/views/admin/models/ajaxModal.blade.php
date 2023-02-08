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
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label>Model Name</label>
                <input name="models[{{ $rand }}][model_name]" type="text" class="form-control" value=""
                    placeholder="Model Name..">
            </div>
            <div class="form-group col-md-3">
                <label>Model Code</label>
                <input name="models[{{ $rand }}][model_code]" type="text" class="form-control" value=""
                    placeholder="Model Code..">
            </div>
            <div class="form-group col-md-3">
                <label>Status : </label>
                <select class="form-control" name="models[{{ $rand }}][active_status]">
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
