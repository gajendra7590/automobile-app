<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group">
            <label>User Name : </label>
            <input name="name" type="text" class="form-control"
                value="{{ isset($data['name']) ? $data['name'] : '' }}" placeholder="Please enter user name..">
        </div>
        <div class="form-group">
            <label>User Email : </label>
            <input name="email" type="text" class="form-control"
                value="{{ isset($data['email']) ? $data['email'] : '' }}" placeholder="Please enter user email..">
        </div>
        <div class="form-group">
            <label>Password : </label>
            <input name="password" type="password" class="form-control" value=""
                placeholder="Please enter password..">
            <span class="help-block">** Password leave empty if you don`t want to update.</span>
        </div>
        <div class="form-group">
            <label>Status : </label>
            <select class="form-control" name="status">
                <option value="1"
                    {{ isset($data['status']) && $data['status'] == '1' ? 'selected="selected"' : '' }}>Active</option>
                <option value="0"
                    {{ isset($data['status']) && $data['status'] == '0' ? 'selected="selected"' : '' }}>In Active
                </option>
            </select>
        </div>
        <div class="form-group">
            <label>Roles / Access : </label>
            <select class="form-control" name="role">
                @isset($roles)
                    @foreach ($roles as $role)
                        <option
                            {{ isset($data['roles']) && count($data['roles']) > 0 && $data['roles'][0]['id'] == $role->id ? 'selected="selected"' : '' }}
                            value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                @endisset
            </select>
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
