<section class="content">
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('purchases.index') }}">Purchase</a></li>
        <li class="active">Report</li>
    </ol>
    <form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
        enctype="multipart/form-data">
        @csrf
        <div class="form-group col-md-4">
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
        <div class="form-group col-md-4">
            <label>Bike Model</label>
            <select name="bike_model" class="form-control ajaxChangeCDropDown">
                <option value="">---Select Model----</option>
                @isset($models)
                    @foreach ($models as $model)
                        <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        {{-- submit button --}}
        <div class="form-group">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
                    Download
                </button>
            </div>
        </div>
    </form>
</section>
