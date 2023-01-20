<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data" >
        <input type="hidden" name="type" value="{{isset($type) && !empty($type) ? $type : 'purchase' }}">
        <div class='col-md-12'>
            <div class="form-group col-md-2">
                <label>BIKE BRAND</label>
                <select name="bike_brand" data-dep_dd_name="bike_model"
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
                <select name="bike_model" class="form-control">
                    <option value="">---Select Model----</option>
                    @isset($models)
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>STATUS</label>
                <select name="status" class="form-control">
                    <option value="">---Select Status----</option>
                    <option value="open">Open</option>
                    <option value="close">Close</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Financers</label>
                <select name="financer_id" class="form-control">
                    <option value="">---Select Model----</option>
                    @isset($financers)
                        @foreach ($financers as $financer)
                            <option value="{{ $financer->id }}">{{ $financer->bank_name }}</option>
                        @endforeach
                    @endisset
                </select>
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

