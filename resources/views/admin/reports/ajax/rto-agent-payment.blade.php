<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='row'>
            <div class="form-group col-md-3">
                <label>AGENT NAME</label>
                <select name="rto_agent_id" class="form-control">
                    <option value="">All</option>
                    @isset($rto_agents)
                        @foreach ($rto_agents as $rto_agent)
                            <option value="{{ $rto_agent->id }}">{{ $rto_agent->agent_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>PAYMENT SOURCE</label>
                <select name="payment_source" class="form-control">
                    <option value="">All</option>
                    @isset($depositeSources)
                        @foreach ($depositeSources as $k => $depositeSource)
                            <option value="{{ $k }}">{{ strtoupper($depositeSource) }}</option>
                        @endforeach
                    @endisset
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
