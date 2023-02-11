@php
    $editReadOnly = isset($method) && $method == 'PUT' ? 'readonly' : '';
    $editDisable = isset($method) && $method == 'PUT' ? 'disabled' : '';
@endphp
<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row">

        <div class="form-group col-md-6">
            <label>RTO Agent </label>
            <select name="rto_agent_id" class="form-control" {{ $editDisable }}>
                {{-- <option value="">---Select RTO Agent---</option> --}}
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
                data-ele="rtoAjaxContainer" {{ $editDisable }}>
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
    <div class="{{ isset($htmlData) ? '' : 'hideElement' }}" id="rtoAjaxContainer">
        {!! isset($htmlData) ? $htmlData : '' !!}
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
<script>
    $(".ajaxFormSubmit").validate({
        // errorElement: 'span',
        // errorClass: 'text-muted error',
        rules: {
            sale_id: {
                required: true,
            },
            contact_name: {
                required: true,
            },
            contact_mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            contact_address_line: {
                required: true,
            },
            contact_state_id: {
                required: true,
            },
            contact_district_id: {
                required: true,
            },
            contact_city_id: {
                required: true,
            },
            contact_zipcode: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6,
            },
            sku: {
                required: true
            },
            gst_rto_rate_id: {
                required: true,
            },
            ex_showroom_amount: {
                required: true,
                number: true,
                min: 1,
            },
            tax_amount: {
                required: true,
                number: true,
                min: 1,
            },
            hyp_amount: {
                required: true,
                number: true,
                min: 0,
            },
            tr_amount: {
                required: true,
                number: true,
                min: 0,
            },
            fees: {
                required: true,
                number: true,
                min: 0,
            },
            total_amount: {
                required: true,
                number: true,
                min: 0,
            },
            rc_status: {
                required: true
            },
            submit_date: {
                required: false,
                date: true,
            },
            recieved_date: {
                required: false,
                date: true,
            },
        },
        messages: {
            sale_id: {
                required: "The sale stock field is required.",
            },
            contact_name: {
                required: "The customer name field is required.",
            },
            contact_mobile_number: {
                required: "The customer phone field is required.",
            },
            contact_address_line: {
                required: "The customer address line is required.",
            },
            contact_state_id: {
                required: "The state field is required.",
            },
            contact_district_id: {
                required: "The district field is required.",
            },
            contact_city_id: {
                required: "The city field is required.",
            },
            contact_zipcode: {
                required: "The zipcode field is required.",
                digits: "the zipcode should valid 6 digits",
                minlength: "the zipcode should valid 6 digits",
                maxlength: "the zipcode should valid 6 digits",
            },
            sku: {
                required: "The SKU code field is required."
            },
            gst_rto_rate_id: {
                required: "The GST Rate field is required."
            },
            ex_showroom_amount: {
                required: "The exshowroom amount field is required.",
                number: "The exshowroom amount invalid price.",
                min: "The exshowroom amount invalid price.",
            },
            tax_amount: {
                required: "The Tax amount field is required.",
                number: "The Tax amount invalid price.",
                min: "The Tax amount invalid price.",
            },
            hyp_amount: {
                required: "The HYP amount field is required.",
                number: "The HYP amount invalid price.",
                min: "The HYP amount invalid price.",
            },
            tr_amount: {
                required: "The TR amount field is required.",
                number: "The TR amount invalid price.",
                min: "The TR amount invalid price.",
            },
            fees: {
                required: "The Fees amount field is required.",
                number: "The Fees amount invalid price.",
                min: "The Fees amount invalid price.",
            },
            total_amount: {
                required: "The Total amount field is required.",
                number: "The Total amount invalid price.",
                min: "The Total amount invalid price.",
            },
            rc_status: {
                required: "The RC status field is required.",
            },
            submit_date: {
                required: "The submit date field is required.",
                date: "The submit date should valid date",
            },
            recieved_date: {
                required: "The recieved date field is required.",
                date: "The recieved date should valid date",
            },
        }
    });
</script>
