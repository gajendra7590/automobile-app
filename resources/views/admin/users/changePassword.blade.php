<form role="form" method="POST" class="ajaxFormSubmit validatedForm" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon" autocomplete="off">
    @csrf
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Password</label>
                <input name="password" type="password" id="password" class="form-control" value=""
                    placeholder="Please enter password..">
            </div>
            <div class="form-group col-md-12">
                <label>Confirm Password</label>
                <input name="password_confirmation" id="password_confirmation" type="password" class="form-control"
                    value="" placeholder="Please enter confirmation password..">
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">GENERATE NEW PASSWORD</button>
                </div>
            </div>
        </div>
</form>

<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "The password field is required",
                minlength: "The password should be valid 6 digits"
            },
            password_confirmation: {
                required: "The confirm password field is required",
                minlength: "The confirm password should be valid 6 digits",
                equalTo: "The confirm password should be equal to "
            }
        }
    });
</script>
