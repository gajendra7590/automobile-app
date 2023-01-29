<form role="form" method="POST" class="ajaxFormSubmit"
    action="{{ route('bankFinanacePayStore', ['id' => isset($data['id']) ? $data['id'] : 0]) }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>Total Outstanding Amount</label>
                <input name="total_outstanding" type="number" class="form-control"
                    value="{{ isset($data['bank_finance_outstaning_balance']) ? $data['bank_finance_outstaning_balance'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>Paid Amount</label>
                <input name="paid_amount" type="number" class="form-control" value="" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Paid Date</label>
                <input name="paid_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Payment Source</label>
                <select class="form-control" name="paid_source">
                    @isset($depositeSources)
                        @foreach ($depositeSources as $depositeSource)
                            <option value="{{ $depositeSource }}">
                                {{ $depositeSource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="1">PAID</option>
                    <option value="2">ON HOLD(IN CASE OF CHEQUE)</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Payment Collected By</label>
                <select class="form-control" name="collected_by">
                    <option value="">---Select Salesman---</option>
                    @isset($salemans)
                        @foreach ($salemans as $saleman)
                            <option value="{{ $saleman->id }}">
                                {{ $saleman->name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>Next Due Date</label>
                <input name="next_due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
            <div class="form-group col-md-9">
                <label>Payment Note(If Any)</label>
                <input name="payment_note" type="text" class="form-control" value=""
                    placeholder="Payment Note...">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
            SAVE
        </button>
    </div>
</form>
