<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-4">
                <label>Branch Name</label>
                <select name="branch_id" class="form-control">
                    @if (isset($branches))
                        @foreach ($branches as $key => $branch)
                            <option
                                {{ isset($data['branch_id']) && $data['branch_id'] == $branch->id ? 'selected' : '' }}
                                value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Agent Name</label>
                <input name="agent_name" type="text" class="form-control autoCapitalized"
                    value="{{ isset($data['agent_name']) ? $data['agent_name'] : '' }}" placeholder="Enter agent name">
            </div>
            <div class="form-group col-md-4">
                <label>Agent Email</label>
                <input name="agent_email" type="text" class="form-control"
                    value="{{ isset($data['agent_email']) ? $data['agent_email'] : '' }}"
                    placeholder="Enter agent email address">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Agent Phone</label>
                <input name="agent_phone" type="text" class="form-control"
                    value="{{ isset($data['agent_phone']) ? $data['agent_phone'] : '' }}"
                    placeholder="Enter agent phone number">
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
        <div class="row">
            <div class="col-md-12">
                <input name="country_id" type="hidden" value="1">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    @if (isset($method) && $method == 'PUT')
                        UPDATE
                    @else
                        SAVE
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            agent_name: {
                required: true
            },
            agent_email: {
                required: false,
                email: true
            },
            agent_phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            }
        },
        messages: {
            agent_name: {
                required: "The Agent name field is required"
            },
            agent_email: {
                required: "The Agent email address field is required",
                email: "The Agent email address should valid email address"
            },
            agent_phone: {
                required: "The Agent phone number field is required",
                minlength: "The Agent phone number should valid 10 digits",
                maxlength: "The Agent phone number should valid 10 digits",
                digits: "The Agent phone number should valid 10 digits",
            }
        }
    });
</script>
