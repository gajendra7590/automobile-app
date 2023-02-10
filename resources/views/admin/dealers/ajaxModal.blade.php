<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-12">
            <label>Branch Name : </label>
            <select class="form-control" name="branch_id">
                @if (isset($branches))
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ isset($data) && $data->branch_id == $branch->id ? "selected='selected'" : '' }}>
                            {{ $branch->branch_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Dealer Code</label>
            <input type="text" class="form-control autoCapitalized" placeholder="Dealer Code" name="dealer_code"
                value='{{ isset($data) && $data->dealer_code ? $data->dealer_code : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Company Name</label>
            <input type="text" class="form-control autoCapitalized" placeholder="Company Name" name="company_name"
                value='{{ isset($data) && $data->company_name ? $data->company_name : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Company Email</label>
            <input type='email' class="form-control" placeholder="Company Email" name="company_email"
                value="{{ isset($data) && $data->company_email ? $data->company_email : '' }}" />
        </div>
        <div class="form-group col-md-6">
            <label>Company Office Phone</label>
            <input type='text' class="form-control" placeholder="Company Office Phone" name="company_office_phone"
                value="{{ isset($data) && $data->company_office_phone ? $data->company_office_phone : '' }}" />
        </div>
        <div class="form-group col-md-8">
            <label>Company Address</label>
            <input type='text' class="form-control" placeholder="Company Address" name="company_address"
                value="{{ isset($data) && $data->company_address ? $data->company_address : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>GST Number</label>
            <input type='text' class="form-control" placeholder="GST Number" name="company_gst_no"
                value="{{ isset($data) && $data->company_gst_no ? $data->company_gst_no : '' }}" />
        </div>

        <div class="form-group col-md-4">
            <label>Contact Person Name</label>
            <input type='text' class="form-control autoCapitalized" placeholder="Contact Person Name"
                name="contact_person"
                value="{{ isset($data) && $data->contact_person ? $data->contact_person : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Contact Person Email</label>
            <input type='text' class="form-control" placeholder="Contact Person Email" name="contact_person_email"
                value="{{ isset($data) && $data->contact_person_email ? $data->contact_person_email : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Contact Person Phone</label>
            <input type='text' class="form-control" placeholder="Contact Person Phone" name="contact_person_phone"
                value="{{ isset($data) && $data->contact_person_phone ? $data->contact_person_phone : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Contact Person Phone Alternate</label>
            <input type='text' class="form-control" placeholder="Contact Person Phone Alternate"
                name="contact_person_phone2"
                value="{{ isset($data) && $data->contact_person_phone2 ? $data->contact_person_phone2 : '' }}" />
        </div>
        <div class="form-group col-md-8">
            <label>Contact Person Address</label>
            <input type='text' class="form-control" placeholder="Contact Person Address"
                name="contact_person_address"
                value="{{ isset($data) && $data->contact_person_address ? $data->contact_person_address : '' }}" />
        </div>
        <div class="form-group col-md-12">
            <label>Contact Person Document file</label>
            <input type='file' class="form-control" placeholder="Contact Person Document file"
                name="contact_person_document_file"
                value="{{ isset($data) && $data->contact_person_document_file ? $data->contact_person_document_file : '' }}" />
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
    <div class="row">
        <div class="form-group col-12">
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
