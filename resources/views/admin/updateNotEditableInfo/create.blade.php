@extends('admin.layouts.admin-layout')
@section('container')
    @php
        $isSoldOut = isset($data['status']) && $data['status'] == '2' ? 'disabled' : '';
    @endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Update Not Editable Info
            </h1>
        </section>

        <section class="content">
            <div class="box box-default" data-select2-id="16">
                <div class="box-header with-border">
                    <h3 class="box-title">Update Not Editable Info</h3>
                </div>
                <form action="{{ route('updateNonEditableDetail.create') }}" class="ajaxFormSubmit ajaxForm" data-redirect=""
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body" data-select2-id="15">
                        <div class="row">
                            <input type="hidden" value="{{ route('getDocumentTypeAjaxSelect2Data') }}"
                                id="select2SearchURL">
                            <div class="form-group col-md-5">
                                <label>SECTION TYPE</label>
                                <select class="form-control" name="section_type">
                                    <option value="">--SELECT SECTION TYPE--</option>
                                    @if (isset($sectionTypes) && count($sectionTypes) > 0)
                                        @foreach ($sectionTypes as $k => $sectionType)
                                            <option value="{{ $k }}">{{ strtoupper($sectionType) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SECTION MODEL ITEMS</label>
                                    <select name="model_section_id" class="form-control select2" id="select2Ele"
                                        data-placeholder="Select a document section..." style="width: 100%;"
                                        data-select2-id="7" tabindex="-1" aria-hidden="true" disabled>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group custButton">
                                    <button class="btn btn-primary pull-right getDetailAjaxModal" type="button"
                                        style="margin-top: 25px;"
                                        data-url="{{ route('updateNonEditableDetail.create') }}">GET
                                        DETAIL</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-danger" href="{{ route('documentUploads.index') }}">BACK</a>
                                    {{-- <button class="btn btn-primary pull-right" type="submit">UPLOAD</button> --}}
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@push('after-script')
    <script></script>
    <script src="{{ asset('assets/modules/updateNonEditableInfo.js') }}"></script>
@endpush
