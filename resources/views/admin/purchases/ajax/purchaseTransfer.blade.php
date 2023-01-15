<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <div class="row">
            <input type="hidden" name="purchase_id" value="{{ isset($purchase_id) ? $purchase_id : '' }}">
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
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    @if (isset($method) && $method == 'PUT')
                        UPDATE
                    @else
                        SAVE
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>
