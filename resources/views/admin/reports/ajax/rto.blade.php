<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='col-md-12'>
            <div class="form-group col-md-4">
                <label>RTO STATUS</label>
                <select name="rto_status" class="form-control">
                    <option value="">---Select RTO status----</option>
                    <option value="0"> Pending </option>
                    <option value="1"> Close </option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>SENT TO RTO</label>
                <select name="sent_to_rto" class="form-control">
                    <option value="">---Select (sent to rto) status----</option>
                    <option value="1"> Yes </option>
                    <option value="0"> No </option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>PENDING REGISTRATION NUMBER</label>
                <select name="pending_registration_number" class="form-control">
                    <option value="">---Select pending registration number status----</option>
                    <option value="1"> Yes </option>
                    <option value="0"> No </option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>RC STATUS</label>
                <select name="rc_status" class="form-control">
                    <option value="">---Select rc status----</option>
                    <option value="0"> On Showroom </option>
                    <option value="1"> Delivered to Customer </option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>PAYMENT OUTSTANDING</label>
                <select name="payment_outstanding" class="form-control">
                    <option value="">---Select payment outstanding----</option>
                    <option value="0"> Pending </option>
                    <option value="1"> Close </option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>DURATION</label>
                <select name="duration" class="form-control">
                    <option value="last_month">Last Month</option>
                    <option value="last_six_months">Last Six Months</option>
                    <option value="last_one_year">Last One Year</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="col-md-6 pull-right dateshow" hidden>
                <div class="form-group col-md-6">
                    <label>START DATE</label>
                    <input type='date' name="start_date" class="form-control" value="{{ date('Y-m-d') }}"
                        placeholder="0000-00-00" min="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group col-md-6">
                    <label>END DATE</label>
                    <input type='date' name="end_date" class="form-control" placeholder="0000-00-00"
                        min="{{ date('Y-m-d') }}" />
                </div>
            </div>
        </div>
        <div class="form-group col-md-12 pull-left">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    DOWNLOAD
                </button>
            </div>
        </div>
    </form>
</section>
