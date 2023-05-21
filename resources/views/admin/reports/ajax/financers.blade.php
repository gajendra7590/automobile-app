<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='row'>
            <div class="form-group col-md-3">
                <label>BROKER NAME</label>
                <select name="broker_id" data-dep_dd_name="model_id"
                    data-url="{{ url('getAjaxDropdown') . '?req=models' }}" class="form-control ajaxChangeCDropDown">
                    <option value="">---Select Broker----</option>
                    @isset($brokers)
                        @foreach ($brokers as $key => $broker)
                            <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>BRANCH NAME</label>
                <select name="branch_id" class="form-control">
                    <option value="">---Select Branch----</option>
                    @isset($branches)
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>FINANCER NAME</label>
                <select name="financer_id" class="form-control">
                    <option value="">---Select Financer----</option>
                    @isset($financers)
                        @foreach ($financers as $financer)
                            <option value="{{ $financer->id }}">{{ $financer->bank_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>FINANCE TYPE</label>
                <select name="financer_type" class="form-control">
                    <option value="">---Select Financer Type----</option>
                    <option value="1">By Cash</option>
                    <option value="2">Bank Finance</option>
                    <option value="3">Personal Finance</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>BANK FINANCE STATUS</label>
                <select name="status" class="form-control">
                    <option value="">---Select Bank Finance Status----</option>
                    <option value="open">Due</option>
                    <option value="close">Close</option>
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
                    <input type='date' name="start_date" class="form-control" value="{{ date('Y-m-d') }}"
                        placeholder="0000-00-00" min="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group col-md-3">
                    <label>END DATE</label>
                    <input type='date' name="end_date" class="form-control" placeholder="0000-00-00"
                        min="{{ date('Y-m-d') }}" />
                </div>
            </div>
        </div>
        <div class="row">
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
