<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='row'>
            <div class="form-group col-md-3">
                <label>BRAND NAME</label>
                <select name="brand_id" data-dep_dd_name="model_id"
                    data-url="{{ url('getAjaxDropdown') . '?req=models' }}" class="form-control ajaxChangeCDropDown">
                    <option value="">ALL</option>
                    @isset($brands)
                        @foreach ($brands as $key => $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>MODEL NAME</label>
                <select name="model_id" class="form-control">
                    <option value="">ALL</option>
                    @isset($models)
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>AGENT NAME</label>
                <select name="agent_id" class="form-control">
                    <option value="">ALL</option>
                    @isset($rto_agents)
                        @foreach ($rto_agents as $key => $rto_agent)
                            <option value="{{ $rto_agent->id }}">{{ $rto_agent->agent_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>SENT TO RTO</label>
                <select name="sent_to_rto" class="form-control">
                    <option value="">ALL</option>
                    <option value="yes">YES</option>
                    <option value="no">NO</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PENDING REGISTRATION NUMBER</label>
                <select name="pending_registration_number" class="form-control">
                    <option value="">ALL</option>
                    <option value="yes">YES</option>
                    <option value="no">NO</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>RC STATUS</label>
                <select name="rc_status" class="form-control">
                    <option value="">ALL</option>
                    <option value="on_showroom">ON SHOWROOM</option>
                    <option value="del_to_customer">DELIVERED TO CUSTOMER</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT OUTSTANDING</label>
                <select name="payment_outstanding" class="form-control">
                    <option value="">ALL</option>
                    <option value="pending">PENDING</option>
                    <option value="close">CLOSE</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>REPORT DURATION</label>
                <select name="duration" class="form-control">
                    <option value="last_month">Last Month</option>
                    <option value="last_six_months">Last Six Months</option>
                    <option value="last_one_year">Last One Year</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="dateshow" hidden>
                <div class="form-group col-md-3">
                    <label>START DATE</label>
                    <input type='date' name="start_date" class="form-control" value="{{ date('Y-m-d',strtotime(' -30 day')) }}"
                        placeholder="0000-00-00" max="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group col-md-3">
                    <label>END DATE</label>
                    <input type='date' name="end_date" value="{{ date('Y-m-d') }}" class="form-control" placeholder="0000-00-00"
                        max="{{ date('Y-m-d') }}" />
                </div>
            </div>
        </div>
        <div class='row'>
            <div class="form-group">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                        DOWNLOAD
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

<script src="{{ asset('assets/modules/report.js') }}"></script>
