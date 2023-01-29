<form role="form" method="POST" class="ajaxFormSubmit" action="{{ route('salesBankFinanace.store') }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>Total Outstanding Amount</label>
                <input name="total_outstanding" type="number" class="form-control"
                    value="{{ isset($totalDue) ? $totalDue : 0.0 }}" placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-4">
                <label>Total Finance Amount</label>
                <input name="total_finance_amount" type="number" class="form-control" value=""
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Finance Due Date</label>
                <input name="finance_due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Bank Financier</label>
                <select class="form-control" name="financier_id">
                    @isset($financers)
                        @foreach ($financers as $financer)
                            <option value="{{ $financer->id }}"
                                {{ isset($data['hyp_financer']) && $data['hyp_financer'] == $financer->id ? 'selected="selected"' : '' }}>
                                {{ $financer->bank_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-8">
                <label>Bank Financier Note</label>
                <input name="financier_note" type="text" class="form-control" value=""
                    placeholder="Bank Financier Note...">
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
