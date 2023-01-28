<form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="box-body">
        <!-- DEPOSITE SECTION-->
        <div class="row">
            <div class="form-group col-md-3">
                <label>Total Sales Amount</label>
                <input name="sales_total_amount" type="number" class="form-control"
                    value="{{ isset($data['sales_total_amount']) ? $data['sales_total_amount'] : 0.0 }}"
                    placeholder="₹ 0.00" readonly>
            </div>
            <div class="form-group col-md-3">
                <label>Down Payment Amount</label>
                <input name="deposite_amount" type="number" class="form-control" value="" placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-3">
                <label>Down Payment Date</label>
                <input name="deposite_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="₹ 0.00">
            </div>
            <div class="form-group col-md-3">
                <label>Down Payment Paid Source</label>
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
        </div>
        <div class="row">
            <div class="form-group col-md-7">
                <label>Down Payment Note</label>
                <input name="deposite_source_note" type="text" class="form-control" value=""
                    placeholder="Ex : Cheque No / Bank Detail | UPI Trans ID Etc..">
            </div>
            <div class="form-group col-md-2">
                <label>Payment Status</label>
                <select class="form-control" name="status">
                    <option value="1">Paid</option>
                    <option value="0">On Hold</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Payment Collected By</label>
                <select class="form-control" name="deposite_collected_by">
                    <option value="0">---SalesMan---</option>
                    @isset($salemans)
                        @foreach ($salemans as $k => $saleman)
                            <option value="{{ $saleman->id }}">{{ $saleman->name }} </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Due Amount</label>
                <input name="due_amount" type="number" class="form-control"
                    value="{{ isset($data['total_amount']) ? $data['total_amount'] : 0.0 }}" placeholder="₹ 0.00"
                    readonly>
            </div>
            <div class="form-group col-md-6">
                <label>Next Payment Due Date</label>
                <input name="due_date_today" type="hidden" class="form-control" value="{{ date('Y-m-d') }}">
                <input name="due_date" type="date" class="form-control" value="{{ date('Y-m-d') }}"
                    placeholder="0000-00-00">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input name="sales_account_id" type="hidden" value="{{ isset($data['id']) ? $data['id'] : 0 }}">
        <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
            @if (isset($method) && $method == 'PUT')
                UPDATE
            @else
                SAVE
            @endif
        </button>
    </div>
</form>
