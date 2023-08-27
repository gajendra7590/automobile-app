<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @method('PUT')
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>Total Sales Amount</label>
                <input name="sales_total_amount" type="number" class="form-control"
                    value="{{ isset($data['sales_total_amount']) ? $data['sales_total_amount'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>TOTAL CASH OUTSTANDING</label>
                <input type="number" class="form-control"
                    value="{{ isset($data['cash_outstaning_balance']) ? $data['cash_outstaning_balance'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>TOTAL CASH PAID BY CUSTOMER</label>
                <input type="number" class="form-control"
                    value="{{ isset($data['cash_paid_balance']) ? $data['cash_paid_balance'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Down Payment Amount</label>
                <input name="deposite_amount" type="number" class="form-control"
                    value="{{ isset($data['down_payment']) ? $data['down_payment'] : 0.0 }}" placeholder="₹ 0.00">
                <input type="hidden" name="old_deposite_amount"
                    value="{{ isset($data['down_payment']) ? $data['down_payment'] : 0.0 }}">
            </div>
            <div class="form-group col-md-4">
                <label>Down Payment Date</label>
                <input name="deposite_date" type="date" class="form-control"
                    value="{{ isset($data['down_payment_by_customer']['paid_date']) ? $data['down_payment_by_customer']['paid_date'] : date('Y-m-d') }}"
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Down Payment Paid Source</label>
                <select class="form-control" name="deposite_source">
                    @isset($depositeSources)
                        @foreach ($depositeSources as $depositeSource)
                            <option value="{{ $depositeSource }}"
                                {{ isset($data['down_payment_by_customer']['paid_source']) && $data['down_payment_by_customer']['paid_source'] == $depositeSource ? "selected='selected'" : '' }}>
                                {{ $depositeSource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2">
                <label>Payment Status</label>
                <select class="form-control" name="status">
                    <option value="1"
                        {{ isset($data['down_payment_by_customer']['status']) && $data['down_payment_by_customer']['status'] == 1 ? "selected='selected'" : '' }}>
                        Paid</option>
                    <option value="0"
                        {{ isset($data['down_payment_by_customer']['status']) && $data['down_payment_by_customer']['status'] == 0 ? "selected='selected'" : '' }}>
                        On Hold</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Payment Collected By</label>
                <select class="form-control" name="deposite_collected_by">
                    @isset($salemans)
                        @foreach ($salemans as $k => $saleman)
                            <option value="{{ $saleman->id }}"
                                {{ isset($data['down_payment_by_customer']['collected_by']) && $data['down_payment_by_customer']['collected_by'] == $saleman->id ? "selected='selected'" : '' }}>
                                {{ $saleman->name }} </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-7">
                <label>Down Payment Note</label>
                <input name="deposite_source_note" type="text" class="form-control"
                    value="{{ $data['down_payment_by_customer']['paid_note'] }}"
                    placeholder="Ex : Cheque No / Bank Detail | UPI Trans ID Etc..">
            </div>
        </div>
        <p> <span class="text-danger">NOTE : PLEASE UPDATE CAREFULLY, IN EXCEPTIONAL CASE ONLY.</span></p>
    </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
            UPDATE
        </button>
    </div>
</form>
