<form role="form" method="POST" class="ajaxFormSubmit"
    action="{{ route('salesCash.update', ['salesCash' => isset($data['id']) ? $data['id'] : 0]) }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @method('PUT')
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-3">
                <label>Charge Amount</label>
                <input name="charge_amount" type="number" class="form-control" value="" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-3">
                <label>Charge Reason</label>
                <select class="form-control" name="charge_reason">
                    <option value="Other Charge Amount">OTHER CHARGE</option>
                    <option value="Late Fees Amount">LATE FEES</option>
                    <option value="Penalty Amount">PENALTY</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Charge Note</label>
                <input name="charge_note" type="text" class="form-control" value=""
                    placeholder="Charge Note...">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
            ADD CHARGES
        </button>
    </div>
</form>
