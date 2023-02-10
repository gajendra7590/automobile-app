<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-6">
            <label>Salesman Name</label>
            <input type="text" class="form-control autoCapitalized" placeholder="Name" name="name"
                value='{{ isset($data) && $data ? $data->name : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Salesman Email Address</label>
            <input type='text' class="form-control" placeholder="Email" name="email"
                value="{{ isset($data) && $data ? $data->email : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Salesman Mobile Number</label>
            <input type='text' class="form-control" placeholder="Mobile Number" name="mobile_number"
                value="{{ isset($data) && $data ? $data->mobile_number : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Salesman Status : </label>
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
    <div class="row">
        <div class="form-group col-12">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    @if (isset($method) && $method == 'PUT')
                        UPDATE
                    @else
                        CREATE
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
            }
        },
        messages: {
            name: {
                required: "The salesman name field is required"
            },
            email: {
                required: "The salesman email field is required",
                email: "The salesman email should valid email address"
            },
            mobile_number: {
                required: "The salesman mobile number field is required",
                digits: "The salesman mobile number field should valid 10 digit",
                minlength: "The salesman mobile number field should valid 10 digit",
                maxlength: "The salesman mobile number field should valid 10 digit",
            }
        }
    });
</script>
