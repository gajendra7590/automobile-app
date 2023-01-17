<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">

        <!-- SALE SELECTION SECTION-->
        <div class="row">
            <div class="form-group col-md-9">
                <label>Select Sales Model</label>
                <select class="form-control" name="sale_id">
                    <option value="0">---Select Sale Model---</option>
                    @isset($salesList)
                        @foreach ($salesList as $saleModel)
                            <option value="{{ $saleModel->id }}" data-total_amount="{{ $saleModel->total_amount }}"
                                {{ isset($data['id']) && $data['id'] == $saleModel->id ? 'selected="selected"' : '' }}>
                                {{ ucfirst($saleModel->customer_name) . '/' . ucfirst($saleModel->customer_guardian_name) }}
                                -
                                {{ $saleModel->purchases->sku }}
                            </option>
                        @endforeach
                    @endisset
                </select>

            </div>
            <div class="form-group col-md-3">
                <label>Total Sales Amount</label>
                <input name="sales_total_amount" type="number" class="form-control"
                    value="{{ isset($data['total_amount']) ? $data['total_amount'] : 0.0 }}" placeholder="₹ 0.00"
                    readonly>
            </div>
        </div>
        <!-- DEPOSITE SECTION-->
        <div class="row common_depended {{ isset($data['id']) ? '' : 'hideElement' }}" id="deposite_section">
            <div class="form-group col-md-4">
                <label>Deposite Amount(Down Payment)</label>
                <input name="deposite_amount" type="number" class="form-control" value="" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Deposite Date</label>
                <input name="deposite_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-4">
                <label>Deposite Source</label>
                <select class="form-control" name="deposite_source">
                    @isset($depositeSources)
                        @foreach ($depositeSources as $depositeSource)
                            <option value="{{ $depositeSource }}">
                                {{ $depositeSource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-12">
                <label>Deposite Source Description(If Any)</label>
                <textarea class="form-control" name="deposite_source_note"
                    placeholder="Ex : Cheque No / Bank Detail | UPI Trans ID Etc.."></textarea>
            </div>

            <div class="form-group col-md-6 payLater hideElement">
                <label>Pay Later Amount</label>
                <input name="pay_later_amount" type="number" class="form-control" value="0" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-6 payLater hideElement">
                <label>Pay Later Date</label>
                <input name="pay_later_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="yyyy-mm-dd">
            </div>

            <div class="form-group col-md-4">
                <label>Due Amount</label>
                <input name="due_amount" type="number" class="form-control"
                    value="{{ isset($data['total_amount']) ? $data['total_amount'] : 0.0 }}" placeholder="₹ 0.00"
                    readonly>
            </div>
            <div class="form-group col-md-4">
                <label>Due Payment Source</label>
                <select class="form-control ajaxChangeCDropDown" name="due_payment_source"
                    data-dep_dd_name="financier_id" data-url="{{ route('getAjaxDropdown') }}?req=financiers_list">
                    @isset($duePaySources)
                        @foreach ($duePaySources as $k => $duePaySource)
                            <option value="{{ $k }}"
                                {{ isset($data['payment_type']) && $data['payment_type'] == $k ? 'selected="selected"' : '' }}>
                                {{ $duePaySource }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Due Date</label>
                <input name="due_date_today" type="hidden" class="form-control" value="{{ date('Y-m-d') }}">
                <input name="due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="0000-00-00">
            </div>
            <div class="form-group col-md-12">
                <label>Due Description(If Any)</label>
                <textarea class="form-control" name="due_note" placeholder="Ex : Add description about how remaining amount is due">{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '' }}</textarea>
            </div>
        </div>

        <!-- FINANCIERS SECTION-->
        <div class="row common_depended {{ isset($data['payment_type']) && in_array($data['payment_type'], [2, 3]) ? '' : 'hideElement' }}"
            id="finance_section">
            <div class="form-group col-md-4">
                <label>Financier</label>
                <select class="form-control" name="financier_id">
                    <option value="">---Select Financier---</option>
                    @isset($financersList)
                        @foreach ($financersList as $financer)
                            <option value="{{ $financer->id }}"
                                {{ isset($data['hyp_financer']) && $data['hyp_financer'] == $financer->id ? 'selected="selected"' : '' }}>
                                {{ $financer->bank_name }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="form-group col-md-8">
                <label>Financier Description(If Any)</label>
                <input name="financier_note" type="text" class="form-control" value=""
                    placeholder="Financier Description">
            </div>
        </div>

        <!-- EMI SECTION-->
        <div class="row common_depended {{ isset($data['payment_type']) && in_array($data['payment_type'], [3]) ? '' : 'hideElement' }}"
            id="emi_section">
            <div class="form-group col-md-3">
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
            <div class="form-group col-md-3">
                <label>No Of EMI</label>
                <input name="no_of_emis" type="number" class="form-control" value=""
                    placeholder="Ex : how many emis">
            </div>
            <div class="form-group col-md-3">
                <label>Rate Of Intrest(%)</label>
                <input name="rate_of_interest" type="text" class="form-control" value=""
                    placeholder="Any +ve Number">
            </div>
            <div class="form-group col-md-3">
                <label>Processing Fees(If Any)</label>
                <input name="processing_fees" type="text" class="form-control" value=""
                    placeholder="₹ 0.00">
            </div>
            {{-- <div class="form-group col-md-3">
                <label>Total Finanace Amount</label>
                <input name="due_grand_total" type="number" class="form-control" value="" readonly
                    placeholder="₹ 0.00">
            </div> --}}
        </div>

    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
            @if (isset($method) && $method == 'PUT')
                UPDATE
            @else
                SAVE
            @endif
        </button>
    </div>
</form>
