<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            <label>Branch Name</label>
            <input type='text' class="form-control" placeholder="Branch Name" required name="branch_name"
                value="{{ isset($data) && $data->branch_name ? $data->branch_name : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Branch Email</label>
            <input type='text' class="form-control" placeholder="Branch Email" required name="branch_email"
                value="{{ isset($data) && $data->branch_email ? $data->branch_email : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Branch Phone</label>
            <input type='text' class="form-control" placeholder="Branch Phone" required name="branch_phone"
                value="{{ isset($data) && $data->branch_phone ? $data->branch_phone : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Branch Phone2</label>
            <input type='text' class="form-control" placeholder="Branch Phone2" required name="branch_phone2"
                value="{{ isset($data) && $data->branch_phone2 ? $data->branch_phone2 : '' }}" />
        </div>

        <div class="form-group col-md-8">
            <label>Branch Address Line</label>
            <input type='text' class="form-control" placeholder="Branch Address Line" name="branch_address_line"
                value="{{ isset($data) && $data->branch_address_line ? $data->branch_address_line : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Branch State</label>
            <select name="branch_state" data-dep_dd_name="branch_district"
                data-url="{{ url('getAjaxDropdown') . '?req=districts' }}" data-dep_dd2_name="branch_city"
                class="form-control ajaxChangeCDropDown">
                <option value="">---Select State---</option>
                @isset($states)
                    @foreach ($states as $state)
                        <option
                            {{ isset($data['branch_state']) && $data['branch_state'] == $state->id ? 'selected="selected"' : '' }}
                            value="{{ $state->id }}">{{ $state->state_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Branch District</label>
            <select name="branch_district" class="form-control ajaxChangeCDropDown" data-dep_dd_name="branch_city"
                data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" data-dep_dd2_name="">
                <option value="">---Select District---</option>
                @isset($districts)
                    @foreach ($districts as $district)
                        <option
                            {{ isset($data['branch_district']) && $data['branch_district'] == $district->id ? 'selected="selected"' : '' }}
                            value="{{ $district->id }}">{{ $district->district_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Branch City</label>
            <select name="branch_city" class="form-control">
                <option value="">---Select City---</option>
                @isset($cities)
                    @foreach ($cities as $city)
                        <option
                            {{ isset($data['branch_city']) && $data['branch_city'] == $city->id ? 'selected="selected"' : '' }}
                            value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Branch Pincode</label>
            <input type='text' class="form-control" placeholder="Branch Pincode" name="branch_pincode"
                value="{{ isset($data) && $data->branch_pincode ? $data->branch_pincode : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Branch GST Number</label>
            <input type='text' class="form-control" placeholder="Branch GST Number" name="gstin_number"
                value="{{ isset($data) && $data->gstin_number ? $data->gstin_number : '' }}" />
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
        <div class="form-group col-md-12">
            <label>Branch More Detail</label>
            <textarea type='text' class="form-control" placeholder="Branch More Detail" name="branch_more_detail"> {{ isset($data) && $data->branch_more_detail ? $data->branch_more_detail : '' }} </textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    <?php if(isset($method) && $method == 'PUT'): ?>
                    UPDATE
                    <?php else: ?>
                    SAVE
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>


</form>
