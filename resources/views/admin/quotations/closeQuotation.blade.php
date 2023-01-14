<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label>More Details</label>
            <textarea type='text' class="form-control my-colorpicker1 colorpicker-element" placeholder="More Details"name="close_note"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                        CLOSE QUOTATION
                </button>
            </div>
        </div>
    </div>
</form>
