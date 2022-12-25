<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>GST Rates</label>
                <input name="gst_rate" type="text" class="form-control"
                    value="{{ isset($data['gst_rate']) ? $data['gst_rate'] : '' }}" placeholder="GST Rates %">
            </div>
            <div class="form-group col-md-6">
                <label>CGST Rates</label>
                <input name="cgst_rate" type="text" class="form-control"
                    value="{{ isset($data['cgst_rate']) ? $data['cgst_rate'] : '' }}" placeholder="CGST Rates %">
            </div>
            <div class="form-group col-md-6">
                <label>SGST Rates</label>
                <input name="sgst_rate" type="text" class="form-control"
                    value="{{ isset($data['sgst_rate']) ? $data['sgst_rate'] : '' }}" placeholder="SGST Rates %">
            </div>
            <div class="form-group col-md-6">
                <label>IGST Rates</label>
                <input name="igst_rate" type="text" class="form-control"
                    value="{{ isset($data['igst_rate']) ? $data['igst_rate'] : '' }}" placeholder="IGST Rates %">
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
