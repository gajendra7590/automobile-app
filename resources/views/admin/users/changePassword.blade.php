<form role="form" method="POST" class="ajaxFormSubmit validatedForm" action="{{ isset($action) ? $action : '' }}"
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
                <input name="password_confirmation" type="password" class="form-control" value=""
                    placeholder="Please enter confirmation password..">
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

<script>
    $(document).ready(function() {

        jQuery('.validatedForm').validate({
            rules: {
                password: {
                    minlength: 5,
                },
                password_confirmation: {
                    minlength: 5,
                    equalTo: "#password"
                }
            }
        });
    });
</script>
