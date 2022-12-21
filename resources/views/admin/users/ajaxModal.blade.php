<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon" autocomplete="off">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Full Name</label>
                <input name="name" type="text" class="form-control"
                    value="{{ isset($data['name']) ? $data['name'] : '' }}" placeholder="Please enter name..">
            </div>
            <div class="form-group col-md-6">
                <label>Email Address</label>
                <input name="email" type="text" class="form-control"
                    value="{{ isset($data['email']) ? $data['email'] : '' }}" placeholder="rock@test.com">
            </div>
            @if (isset($method) && $method != 'PUT')
                <div class="form-group col-md-6">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" value=""
                        placeholder="Please enter password..">
                </div>
            @endif
            <div class="form-group col-md-6">
                <label>Associated Branch</label>
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
            <div class="form-group col-md-12">
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
