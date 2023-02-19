<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='col-md-12'>
            <div class="form-group col-md-2">
                <label>BROKER</label>
                <select name="broker_id" class="form-control">
                    <option value="">---Select Broker----</option>
                    @isset($brokers)
                        @foreach ($brokers as $key => $broker)
                            <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-2">
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
            <div class="form-group col-md-4">
                <label>CUSTOMER LEDGERS DUE(OUTSTANDING)</label>
                <select name="customer_due" class="form-control">
                    <option value="">---Customer Ledgers Due----</option>
                    <option value="0">Due</option>
                    <option value="1">Close</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label>DOWN PAYMENTS </label>
                <select name="down_payment" class="form-control">
                    <option value="">---down payments----</option>
                    <option value="0">Due</option>
                    <option value="1">Close</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label>PAYMENT TYPE</label>
                <select name="payment_type" class="form-control">
                    <option value="">---payment type----</option>
                    <option value="0">Cash</option>
                    <option value="1">Credit</option>
                </select>
            </div>

            <div class="form-group col-md-2">
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
