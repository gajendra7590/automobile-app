<form role="form" method="POST" class="ajaxFormSubmit" action="{{ route('salesPersonalFinanace.store') }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-3">
                <label>Total Outstanding Amount</label>
                <input name="total_outstanding" type="number" class="form-control"
                    value="{{ isset($totalDue) ? $totalDue : 0.0 }}" placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-3">
                <label>Finance Amount</label>
                <input name="total_finance_amount" type="number" class="form-control" value=""
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-2">
                <label>Processing Fees</label>
                <input name="processing_fees" type="text" class="form-control" value="" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Finance Amount Grand Total</label>
                <input name="grand_finance_amount" type="text" class="form-control" value=""
                    placeholder="₹ 0.00" readonly>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Bank Financer Name</label>
                <select class="form-control" name="financier_id">
                    @isset($financers)
                        @foreach ($financers as $financer)
                            <option value="{{ $financer->id }}">
                                {{ $financer->bank_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Installment Due Date(Every Month)</label>
                <input name="finance_due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
                <small class="text-muted text-danger">First installment due date will be next month of same selected
                    date.</small>
            </div>
        </div>
        <!-- EMI SECTION-->
        <div class="row">
            <div class="form-group col-md-4">
                <label>EMI Term</label>
                <select class="form-control" name="finance_terms">
                    @isset($emiTerms)
                        @foreach ($emiTerms as $k => $emiTerm)
                            <option value="{{ $k }}">
                                {{ $emiTerm }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>No Of EMI</label>
                <input name="no_of_emis" type="number" class="form-control" value=""
                    placeholder="Ex : how many emis">
            </div>
            <div class="form-group col-md-4">
                <label>Rate Of Intrest(%)</label>
                <input name="rate_of_interest" type="text" class="form-control" value=""
                    placeholder="Any +ve Number">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
            CREATE PERSONAL FINANACE
        </button>
    </div>
</form>
