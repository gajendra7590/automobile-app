<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='row'>
            <div class="form-group col-md-3">
                <label>BRANCH NAME</label>
                <select name="branch_id" class="form-control">
                    <option value="">All</option>
                    @isset($branches)
                        @foreach ($branches as $branche)
                            <option value="{{ $branche->id }}">{{ $branche->branch_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT TYPE</label>
                <select name="payment_type" class="form-control">
                    <option value="">All</option>
                    <option value="1">SELF PAY</option>
                    <option value="2">BANK FINANCE</option>
                    <option value="3">PERSONAL FINANCE</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT PAID SOURCE</label>
                <select name="payment_mode" class="form-control">
                    <option value="">All</option>
                    @isset($depositeSources)
                        @foreach ($depositeSources as $k => $depositeSource)
                            <option value="{{ $k }}">{{ strtoupper($depositeSource) }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT TYPE</label>
                <select name="transaction_type" class="form-control">
                    <option value="">All</option>
                    <option value="1">CREDIT</option>
                    <option value="2">DEBIT</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT STATUS</label>
                <select name="payment_status" class="form-control">
                    <option value="">All</option>
                    <option value="1">PENDING</option>
                    <option value="2">PAID</option>
                    <option value="3">ON HOLD</option>
                    <option value="4">FAILED</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT DURATION</label>
                <select name="duration" class="form-control">
                    <option value="last_month">Last Month</option>
                    <option value="last_six_months">Last Six Months</option>
                    <option value="last_one_year">Last One Year</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="dateshow" hidden>
                <div class="form-group col-md-3">
                    <label>PAYMENT START DATE</label>
                    <input type='date' name="start_date" class="form-control" value="{{ date('Y-m-d',strtotime(' -30 day')) }}"
                        placeholder="0000-00-00" max="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group col-md-3">
                    <label>PAYMENT END DATE</label>
                    <input type='date' name="end_date" value="{{ date('Y-m-d') }}" class="form-control" placeholder="0000-00-00"
                        max="{{ date('Y-m-d') }}" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box-footer" style="padding: 10px 0px !important;">
                    <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                        DOWNLOAD
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

<script src="{{ asset('assets/modules/report.js') }}"></script>
