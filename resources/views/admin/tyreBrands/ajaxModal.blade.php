<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>TYRE BRAND Name</label>
                <input type="text" class="form-control" placeholder="Name" name="name"
                    value='{{ isset($data) && $data ? $data->name : '' }}' />
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
        <div class="row">
            <div class="form-group col-md-12">
                <label>Description</label>
                <textarea class="form-control" placeholder="Description" name="description" rows="6">{{ isset($data) && $data ? $data->description : '' }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
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
            }
        },
        messages: {
            name: {
                required: "The Tyre brand name field is required"
            }
        }
    });
</script>
