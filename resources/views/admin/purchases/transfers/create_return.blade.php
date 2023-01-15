<form action="{{ isset($action) ? $action : '' }}" class="ajaxFormSubmit" data-redirect="" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="box-body" data-select2-id="15">
        <div class="row">
            <div class="form-group col-md-12">
                <input type="hidden" name="purchase_id" value="{{ isset($purchase_id) ? $purchase_id : '' }}" />
                <label>Return Date</label>
                <input name="return_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-12">
                <label>Return Note</label>
                <textarea name="return_note" class="form-control"></textarea>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-danger" href="{{ route('documentUploads.index') }}">BACK</a>
                <button class="btn btn-primary pull-right" type="submit">CREATE RETURN</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(".select2").select2({
        ajax: {
            delay: 1000,
            allowClear: true,
            minimumInputLength: 1,
            dataType: "json",
            url: function() {
                return $("#select2SearchURL").val();
            },
            beforeSend: function() {
                let selVal = $(
                    'select[name="document_section_type"] option:selected'
                ).val();
                if (selVal == "") {
                    return false;
                }
            },
            data: function(params) {
                var query = {
                    search: params.term,
                    type_id: $(
                        'select[name="document_section_type"] option:selected'
                    ).val(),
                };
                return query;
            },
        },
    });
</script>
