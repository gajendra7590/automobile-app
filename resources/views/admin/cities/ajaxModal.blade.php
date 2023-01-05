@php
    $rand = rand(11111, 99999);
@endphp
<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="{{ isset($redirect) && $redirect ? $redirect : 'ajaxModalCommon' }}"
    data-modal-id="{{ isset($modalId) && $modalId ? $modalId : '' }}">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body" id="city_container">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Select District : </label>
                <select class="form-control" name="district_id">
                    <option value="">---- Select District ----</option>
                    @isset($districts)
                        @foreach ($districts as $district)
                            <option
                                {{ isset($data['district_id']) && $data['district_id'] == $district->id ? 'selected="selected"' : '' }}
                                value="{{ $district->id }}">{{ $district->district_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>City Name</label>
                <input name="cities[{{ $rand }}][city_name]" type="text" class="form-control" value=""
                    placeholder="City name..">
            </div>
            <div class="form-group col-md-3">
                <label>City Code</label>
                <input name="cities[{{ $rand }}][city_code]" type="text" class="form-control" value=""
                    placeholder="City Code..">
            </div>
            <div class="form-group col-md-4">
                <label>Status : </label>
                <select class="form-control" name="cities[{{ $rand }}][active_status]">
                    <option value="1" selected="selected">Active</option>
                    <option value="0">In Active </option>
                </select>
            </div>
            <div class="form-group col-md-1">
                <a href="#" class="btn btn-md btn-success addMoreInFormGroup addAjaxElement"
                    data-container_el="#city_container"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
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
