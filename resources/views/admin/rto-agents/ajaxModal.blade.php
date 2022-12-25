<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Agent Name</label>
                <input name="agent_name" type="text" class="form-control"
                    value="{{ isset($data['agent_name']) ? $data['agent_name'] : '' }}" placeholder="Agent Name">
            </div>
            <div class="form-group col-md-12">
                <label>Agent Phone</label>
                <input name="agent_phone" type="text" class="form-control"
                    value="{{ isset($data['agent_phone']) ? $data['agent_phone'] : '' }}" placeholder="Agent Phone">
            </div>
            <div class="form-group col-md-12">
                <label>Agent Email</label>
                <input name="agent_email" type="text" class="form-control"
                    value="{{ isset($data['agent_email']) ? $data['agent_email'] : '' }}" placeholder="Agent Email">
            </div>
            <div class="form-group col-md-12">
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
