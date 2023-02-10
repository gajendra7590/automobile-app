<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Select State : </label>
                <select class="form-control" name="state_id">
                    <option value="">---- Select State ----</option>
                    @isset($states)
                        @foreach ($states as $state)
                            <option
                                {{ isset($data['state_id']) && $data['state_id'] == $state->id ? 'selected="selected"' : '' }}
                                value="{{ $state->id }}">{{ $state->state_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>District Name</label>
                <input name="district_name" type="text" class="form-control"
                    value="{{ isset($data['district_name']) ? $data['district_name'] : '' }}"
                    placeholder="Please enter district name..">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>District Code</label>
                <input name="district_code" type="text" class="form-control"
                    value="{{ isset($data['district_code']) ? $data['district_code'] : '' }}"
                    placeholder="Please enter district code..">
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
        <input name="country_id" type="hidden" value="1">
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
            state_id: {
                required: true
            },
            district_name: {
                required: true
            }
        },
        messages: {
            state_id: {
                required: "The State name field is required"
            },
            district_name: {
                required: "The District name field is required"
            }
        }
    });
</script>
