<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">

        <div class="form-group col-md-6">
            <label>RTO Agent </label>
            <select name="rto_agent_id" class="form-control ">
                <option value="">---Select RTO Agent---</option>
                @if (isset($rto_agents))
                    @foreach ($rto_agents as $key => $rto_agent)
                        <option
                            {{ (isset($data->rto_agent_id) && $data->rto_agent_id == $rto_agent->id) || $key == 0 ? 'selected' : '' }}
                            value="{{ $rto_agent->id }}">{{ $rto_agent->agent_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-6">
            <label>Select Sale To Create RTO</label>
            <select name="sale_id" class="form-control " data-url="{{ route('ajaxChangeContent') }}"
                data-ele="rtoAjaxContainer">
                <option value="">---Select Sale To Create RTO---</option>
                @if (isset($sales))
                    @foreach ($sales as $key => $sale)
                        <option {{ isset($data->sale_id) && $data->sale_id == $sale->id ? 'selected' : '' }}
                            value="{{ $sale->id }}">{{ $sale->customer_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row hideElement" id="rtoAjaxContainer">
    </div>
    <div class="row">
        <div class="form-group col-12">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit">
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
