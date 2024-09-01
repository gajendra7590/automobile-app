<form class="ajaxFormSubmit" role="form" method="POST" action="{{ route('SkuSalesPriceCsvUpload') }}" enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>SELECT CSV FILE( <span class="text-danger">* Only allowed .csv</span> )</label>
                <input type="file" name="csv_file" class="form-control" accept=".csv" />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">UPLOAD CSV</button>
                </div>
            </div>
        </div>
</form>
<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            csv_file : {
                required: true
            }
        }
    });
</script>
