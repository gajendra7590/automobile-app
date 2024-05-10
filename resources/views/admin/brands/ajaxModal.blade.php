<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Select Branch : </label>
                <select class="form-control" name="branch_id">
                    @isset($branches)
                        @foreach ($branches as $branch)
                            <option
                                {{ isset($data['branch_id']) && $data['branch_id'] == $branch->id ? 'selected="selected"' : '' }}
                                value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Brand Name</label>
                <input type="text" class="form-control autoCapitalized" placeholder="Brand Name" name="name"
                    value='{{ isset($data) && $data ? $data->name : '' }}' />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Brand Code</label>
                <input type="text" class="form-control autoCapitalized" placeholder="Code" name="code"
                    value='{{ isset($data) && $data ? $data->code : '' }}' />
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
                <textarea class="form-control" placeholder="Description" name="description"> {{ isset($data) && $data ? $data->description : '' }} </textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="box-footer">
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
            baranch_id: {
                required: true
            },
            name: {
                required: true
            }
        },
        messages: {
            name: {
                required: "The brand name field is required"
            }
        }
    });
</script>
