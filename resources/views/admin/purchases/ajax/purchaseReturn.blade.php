<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <div class="row">
            <input type="hidden" name="transfer_id"
                value="{{ isset($data['transfers']['id']) ? $data['transfers']['id'] : '0' }}">
            <div class="form-group col-md-12">
                <label>Bike Brokers : </label>
                <select class="form-control" disabled>
                    <option value="">---- Select Broker ----</option>
                    @isset($brokers)
                        @foreach ($brokers as $broker)
                            <option
                                {{ isset($data['transfers']['broker_id']) && $data['transfers']['broker_id'] == $broker->id ? 'selected="selected"' : '' }}
                                value="{{ $broker->id }}">{{ $broker->name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>Transfer Date</label>
                <input type="date" class="form-control"
                    value="{{ isset($data['transfers']['transfer_date']) ? $data['transfers']['transfer_date'] : '' }}"
                    placeholder="yyyy-mm-dd" disabled>
            </div>
            <div class="form-group col-md-12">
                <label>Transfer Note</label>
                <textarea class="form-control" disabled>{{ isset($data['transfers']['transfer_note']) ? $data['transfers']['transfer_note'] : '' }}</textarea>
            </div>

            <div class="form-group col-md-12">
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
