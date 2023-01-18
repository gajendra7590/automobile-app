<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-6">
                <label>AGENT NAME</label>
                <input type="text" class="form-control"
                    value="{{ isset($data['agent_name']) ? $data['agent_name'] : '' }}" disabled>
            </div>
            <input type="hidden" name="rto_agent_id" value="{{ isset($data['id']) ? $data['id'] : '' }}">
            <div class="form-group col-md-6">
                <label>PAYMENT AMOUNT</label>
                <input name="payment_amount" type="text" class="form-control" value="" placeholder="₹0.00">
            </div>
            <div class="form-group col-md-6">
                <label>PAYMENT DATE</label>
                <input name="payment_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-6">
                <label>PAYMENT MODE</label>
                <select class="form-control" name="payment_mode">
                    @if (isset($paymentSources))
                        @foreach ($paymentSources as $paymentSource)
                            <option value="{{ $paymentSource }}">{{ $paymentSource }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>PAYMENT NOTE(IF ANY :)</label>
                <textarea name="payment_note" class="form-control" rows="5" value="" placeholder="Payment Note(If Any)"></textarea>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    CREATE PAYMENT
                </button>
            </div>
        </div>
    </div>
</form>
