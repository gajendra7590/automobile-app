<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
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
        <div class="form-group">
            <label>City Name</label>
            <input name="city_name" type="text" class="form-control"
                value="{{ isset($data['city_name']) ? $data['city_name'] : '' }}"
                placeholder="Please enter city name..">
        </div>
        <div class="form-group">
            <label>City Code</label>
            <input name="city_code" type="text" class="form-control"
                value="{{ isset($data['city_code']) ? $data['city_code'] : '' }}"
                placeholder="Please enter city code..">
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
