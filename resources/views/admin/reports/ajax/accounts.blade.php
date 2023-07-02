<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='row'>
            <div class="form-group col-md-3">
                <label>BROKER NAME</label>
                <select name="broker_id" class="form-control">
                    <option value="">---Select Broker----</option>
                    @isset($brokers)
                        @foreach ($brokers as $key => $broker)
                            <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>BRANCH</label>
                <select name="branch" class="form-control">
                    <option value="">---Branch----</option>
                    @isset($branches)
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>CUSTOMER LEDGERS DUE(OUTSTANDING)</label>
                <select name="customer_due" class="form-control">
                    <option value="">---Customer Ledgers Due----</option>
                    <option value="0">Due</option>
                    <option value="1">Close</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>DOWN PAYMENTS </label>
                <select name="down_payment" class="form-control">
                    <option value="">---Down Payments----</option>
                    <option value="0">Due</option>
                    <option value="1">Close</option>
                </select>
            </div>
        </div>
        <div class='row'>
            <div class="form-group col-md-3">
                <label>PAYMENT TYPE</label>
                <select name="payment_type" class="form-control">
                    <option value="">---Payment Type----</option>
                    <option value="0">Cash</option>
                    <option value="1">Credit</option>
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
