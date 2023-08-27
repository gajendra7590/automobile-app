<form action="{{ isset($action) ? $action : '' }}" class="ajaxFormSubmit" data-redirect="" method="POST"
    enctype="multipart/form-data">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body" data-select2-id="15">
        <div class="row">
            @if (isset($method) && $method != 'PUT')
                <div class="col-md-12">
                    <div class="form-group">
                        <label>SELECT PURCHASE</label>
                        <input type="hidden" value="{{ route('getTransferPurchasesList') }}" id="select2SearchURL">
                        <select name="purchase_id" class="form-control select2" id="select2Ele"
                            data-placeholder="Select a document section..." style="width: 100%;" data-select2-id="7"
                            tabindex="-1" aria-hidden="true">
                        </select>
                    </div>
                </div>
            @endif
            <div class="form-group col-md-12">
                <label>BROKER NAME : </label>
                <select class="form-control" name="broker_id">
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
                <label>TOTAL ON ROAD PRICE</label>
                <input name="total_price_on_road" type="number" class="form-control"
                    value="{{ isset($data['total_price_on_road']) ? $data['total_price_on_road'] : '' }}"
                    placeholder="₹0.00">
            </div>
            @if (isset($method) && $method != 'PUT')
                <div class="form-group col-md-12">
                    <label>TRANSFER DATE</label>
                    <input name="transfer_date" type="date" class="form-control"
                        value="{{ isset($data['transfer_date']) ? $data['transfer_date'] : date('Y-m-d') }}"
                        placeholder="yyyy-mm-dd">
                </div>
            @endif
            <div class="form-group col-md-12">
                <label>TRANSFER NOTE</label>
                <textarea name="transfer_note" class="form-control">{{ isset($data['transfer_note']) ? $data['transfer_note'] : '' }}</textarea>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary pull-right" type="submit">
                    @if (isset($method) && $method != 'PUT')
                        CREATE TRANSFER
                    @else
                        UPDATE TRANSFER
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            purchase_id: {
                required: false
            },
            broker_id: {
                required: true
            },
            total_price_on_road: {
                required: true,
                number: true
            },
            transfer_date: {
                required: true,
                date: true
            },
            transfer_note: {
                required: true
            }
        },
        messages: {
            purchase_id: {
                required: "The purchase field is required."
            },
            broker_id: {
                required: "The broker name field is required."
            },
            total_price_on_road: {
                required: "The total_price_on_road field is required.",
                number: "The total_price_on_road should valid number."
            },
            transfer_date: {
                required: "The transfer date date field is required.",
                date: "The transfer date date should valid date."
            },
            transfer_note: {
                required: "The transfer note field is required."
            },
        }
    });

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
