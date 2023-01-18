<section class="content">
    <form method="GET" redirect="nothing" action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data" >
        <div class="form-group col-md-2">
            <label>Bike Brand</label>
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
            <label>Bike Model</label>
            <select name="bike_model" class="form-control">
                <option value="">---Select Model----</option>
                @isset($models)
                    @foreach ($models as $model)
                        <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="form-group">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                    Download
                </button>
            </div>
        </div>
    </form>
</section>
