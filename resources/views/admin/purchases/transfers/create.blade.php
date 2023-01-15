<form action="{{ isset($action) ? $action : '' }}" class="ajaxFormSubmit" data-redirect=""
    method="{{ isset($method) ? $method : '' }}" enctype="multipart/form-data">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body" data-select2-id="15">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Select Purchase</label>
                    <input type="hidden" value="{{ route('getTransferPurchasesList') }}" id="select2SearchURL">
                    <select name="purchase_id" class="form-control select2" id="select2Ele"
                        data-placeholder="Select a document section..." style="width: 100%;" data-select2-id="7"
                        tabindex="-1" aria-hidden="true">
                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label>Bike Brokers : </label>
                <select class="form-control" name="broker_id">
                    <option value="">---- Select Broker ----</option>
                    @isset($brokers)
                        @foreach ($brokers as $broker)
                            <option
                                {{ isset($data['broker_id']) && $data['broker_id'] == $broker->id ? 'selected="selected"' : '' }}
                                value="{{ $broker->id }}">{{ $broker->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>Transfer Date</label>
                <input name="transfer_date" type="date" class="form-control"
                    value="{{ isset($data['transfer_date']) ? $data['transfer_date'] : date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-12">
                <label>Transfer Note</label>
                <textarea name="transfer_note" class="form-control">{{ isset($data['transfer_note']) ? $data['transfer_note'] : '' }}</textarea>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-danger" href="{{ route('documentUploads.index') }}">BACK</a>
                <button class="btn btn-primary pull-right" type="submit">CREATE TRANSFER</button>
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
