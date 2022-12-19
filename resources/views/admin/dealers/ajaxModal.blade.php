<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">
        <div class="form-group col-md-6">
            <label>Company Name</label>
            <input type="text" class="form-control my-colorpicker1 colorpicker-element" placeholder="Company Name"
                required name="company_name"
                value='{{ isset($data) && $data->company_name ? $data->company_name : '' }}' />
        </div>
        <div class="form-group col-md-6">
            <label>Company Email</label>
            <input type='email' class="form-control my-colorpicker1 colorpicker-element" placeholder="Company Email"
                required name="company_email"
                value="{{ isset($data) && $data->company_email ? $data->company_email : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Company Office Phone</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Company Office Phone" required name="company_office_phone"
                value="{{ isset($data) && $data->company_office_phone ? $data->company_office_phone : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Company Address</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Company Address"
                name="company_address"
                value="{{ isset($data) && $data->company_address ? $data->company_address : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>Company GST No</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Company GST No"
                name="company_gst_no"
                value="{{ isset($data) && $data->company_gst_no ? $data->company_gst_no : '' }}" />
        </div>

        <div class="form-group col-md-3">
            <label>Contact Person</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="Contact Person"
                name="contact_person"
                value="{{ isset($data) && $data->contact_person ? $data->contact_person : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Contact Person Email</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact Person Email" name="contact_person_email"
                value="{{ isset($data) && $data->contact_person_email ? $data->contact_person_email : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Contact Person Phone</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact Person Phone" name="contact_person_phone"
                value="{{ isset($data) && $data->contact_person_phone ? $data->contact_person_phone : '' }}" />
        </div>
        <div class="form-group col-md-3">
            <label>Contact Person Phone 2</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact Person Phone 2" name="contact_person_phone2"
                value="{{ isset($data) && $data->contact_person_phone2 ? $data->contact_person_phone2 : '' }}" />
        </div>
        <div class="form-group col-md-12">
            <label>Contact Person Address</label>
            <input type='text' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact Person Address" name="contact_person_address"
                value="{{ isset($data) && $data->contact_person_address ? $data->contact_person_address : '' }}" />
        </div>
        <div class="form-group col-md-12">
            <label>Contact Person Document file</label>
            <input type='file' class="form-control my-colorpicker1 colorpicker-element"
                placeholder="Contact Person Document file" name="contact_person_document_file"
                value="{{ isset($data) && $data->contact_person_document_file ? $data->contact_person_document_file : '' }}" />
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
