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
                <label>BIKE BRAND NAME</label>
                <select name="brand_id" data-dep_dd_name="model_id"
                    data-url="{{ url('getAjaxDropdown') . '?req=models' }}" class="form-control ajaxChangeCDropDown">
                    <option value="">---Select Brand----</option>
                    @isset($brands)
                        @foreach ($brands as $key => $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>BIKE MODEL NAME</label>
                <select name="model_id" class="form-control">
                    <option value="">---Select Model----</option>
                    @isset($models)
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>STOCK STATUS</label>
                <select name="status" class="form-control">
                    <option value="">---Select Status----</option>
                    <option value="sold">Sold </option>
                    <option value="unsold">Unsold </option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-3">
                <label>PURCHASE VEHICLE AGE(in days)</label>
                <input type='number' name="age" class="form-control" placeholder="Enter Age" min="0" />
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
            <div class="col-md-6 pull-right dateshow" hidden>
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
