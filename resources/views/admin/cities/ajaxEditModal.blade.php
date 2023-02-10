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
                <input name="city_name" type="text" required data-msg-required="The city name field is required"
                    class="form-control" value="{{ isset($data['city_name']) ? $data['city_name'] : '' }}"
                    placeholder="City name..">
            </div>
            <div class="form-group col-md-4">
                <label>City Code</label>
                <input name="city_code" type="text" class="form-control"
                    value="{{ isset($data['city_code']) ? $data['city_code'] : '' }}" placeholder="City Code..">
            </div>
            <div class="form-group col-md-4">
                <label>Status : </label>
                <select class="form-control" name="active_status">
                    <option value="1"
                        {{ isset($data['active_status']) && $data['active_status'] == '1' ? 'selected="selected"' : '' }}
                        selected="selected">Active</option>
                    <option value="0"
                        {{ isset($data['active_status']) && $data['active_status'] == '0' ? 'selected="selected"' : '' }}>
                        In Active </option>
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
                CREATE
            @endif
        </button>
    </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            district_id: {
                required: true
            },
            city_name: {
                required: true
            }
        },
        messages: {
            district_id: {
                required: "The District name field is required"
            },
            city_name: {
                required: "The City name field is required"
            }
        }
    });
</script>
