<form role="form" method="POST" class="ajaxFormSubmit"
    action="{{ route('salesPersonalFinanace.destroy', ['salesPersonalFinanace' => isset($data['id']) ? $data['id'] : 0]) }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @method('DELETE')
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-12">
                <h4>Are you Sure want to cancel the personal finanace account?</h4>
                <p>
                    <b>Note:</b> As you close the account your personal finance account outstanding balance will
                    conveted into cash balance.
                </p>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-danger pull-right" id="ajaxFormSubmit">
            SUBMIT REQUEST
        </button>
    </div>
</form>
