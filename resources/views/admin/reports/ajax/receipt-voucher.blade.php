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
                <label>BIKE BRAND</label>
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
            <div class="form-group col-md-2">
                <label>BIKE MODEL</label>
                <select name="model_id" class="form-control">
                    <option value="">---Select Model----</option>
                    @isset($models)
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="col-md-12">
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

<script src="{{ asset('assets/modules/report.js') }}"></script>
