<div class="row">
    <div class="form-group col-md-12">
        <p class="pull-left">
            Note : If document preview is not showing properly please downlaod & view.
        </p>
        <a href="{{ $data['file_name'] }}" class="btn btn-primary pull-right" download>
            Download Document
        </a>
    </div>
    <div class="form-group col-md-12">
        <p>File Description : </p>
        <p>{{ isset($data['file_description']) ? $data['file_description'] : '' }}</p>
    </div>
    <div class="form-group col-12">
        <p style="margin: 0px 14px;">File Preview : </p>
        <div class="file_container" style="margin: 0px 14px;">
            <object data="{{ $data['file_name'] }}" width="100%" height="380"></object>
        </div>
    </div>
</div>
