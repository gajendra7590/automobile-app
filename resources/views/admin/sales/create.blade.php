@extends('admin.layouts.admin-layout')
@section('container')
    <!-- Content Wrapper. Contains page content -->

    @php
        $isEdit = isset($method) && $method == 'PUT' ? true : false;
        $isDisabled = isset($data['status']) && $data['status'] == 'close' ? 'disabled' : '';
    @endphp
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Sale <small>{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</small>
            </h1>
        </section>

        <section class="content">
            <form class="salesFormAjaxSubmit ajaxForm" role="form" method="POST"
                action="{{ isset($action) ? $action : '' }}" enctype="multipart/form-data" data-redirect="">
                @csrf
                @if (isset($method) && $method == 'PUT')
                    @method('PUT')
                @endif

                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="{{ route('purchases.index') }}">Sale</a></li>
                    <li class="active">{{ isset($method) && $method == 'PUT' ? 'Update' : 'Create' }}</li>
                </ol>
                <div class="box box-default">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @if (isset($method) && $method == 'PUT')
                                    UPDATE SALES DETAIL
                                @else
                                    CREATE NEW SALE
                                @endif
                            </h3>

                            <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit"
                                {{ $isDisabled }}>
                                @if (isset($method) && $method == 'PUT')
                                    UPDATE
                                @else
                                    CREATE
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        @if ($isEdit == false)
                            <input type="hidden" value="{{ isset($quotation_id) ? $quotation_id : null }}"
                                name="quotation_id">
                        @endif
                        {{-- Purchase --}}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>SELECT STOCK INVENTORY</label>
                                <select id="purchase" name="purchase_id" class="form-control"
                                    data-ajax_load="{{ route('ajaxLoadeView') }}"
                                    {{ isset($data['status']) && $data['status'] == 'close' ? 'disabled' : '' }}>
                                    @if ($isEdit == false)
                                        <option value="">---Select Purachse---</option>
                                    @endif
                                    @if (isset($purchases))
                                        @foreach ($purchases as $key => $purchase)
                                            <option
                                                {{ isset($data['purchase_id']) && $data['purchase_id'] == $purchase->id ? 'selected="selected"' : '' }}
                                                value="{{ $purchase->id }}">

                                                @isset($purchase->vin_number)
                                                    {{ $purchase->vin_number }} |
                                                @endisset

                                                @isset($purchase->engine_number)
                                                    {{ $purchase->engine_number }} |
                                                @endisset
                                                @isset($purchase->model->model_name)
                                                    {{ $purchase->model->model_name }}
                                                @endisset

                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>SALESMAN NAME</label>
                                <select name="salesman_id" class="form-control"
                                    {{ isset($data['status']) && $data['status'] == 'close' ? 'disabled' : '' }}>
                                    <option value="">---Select Salesman---</option>
                                    @if (isset($salesmans))
                                        @foreach ($salesmans as $key => $salesman)
                                            <option
                                                {{ isset($data['salesman_id']) && $data['salesman_id'] == $salesman->id ? 'selected' : '' }}
                                                value="{{ $salesman->id }}">{{ $salesman->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="{{ isset($htmlData) && $htmlData != '' ? '' : 'hideElement' }}" id="ajaxLoadContainer">
                            {!! isset($htmlData) ? $htmlData : '' !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="box-footer">
                        <a href="{{ route('sales.index') }}" class="btn btn-danger">BACK</a>
                        <button type="submit" class="btn btn-primary pull-right" id="ajaxFormSubmit" {{ $isDisabled }}>
                            @if (isset($method) && $method == 'PUT')
                                UPDATE
                            @else
                                CREATE
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/modules/sales.js') }}"></script>
@endpush
