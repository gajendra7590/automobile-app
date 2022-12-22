<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon" autocomplete="off">
    @csrf
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Password</label>
                <input name="password" type="password" class="form-control" value=""
                    placeholder="Please enter password..">
            </div>
            <div class="form-group col-md-12">
                <label>Confirm Password</label>
                <input name="confirmation_password" type="password" class="form-control" value=""
                    placeholder="Please enter confirm password..">
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary" id="ajaxFormSubmit"> SAVE </button>
                </div>
            </div>
        </div>
</form>
