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
                Upload Document
            </h1>
        </section>

        <section class="content">
            <div class="box box-default" data-select2-id="16">
                <div class="box-header with-border">
                    <h3 class="box-title">Upload Document</h3>
                </div>

                <div class="box-body" data-select2-id="15">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Document Section Type</label>
                            <select class="form-control" name="active_status">
                                <option value="">--SELECT DOCUMENT TYPE--</option>
                                @if (isset($documentTypes) && count($documentTypes) > 0)
                                    @foreach ($documentTypes as $documentType)
                                        <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Document Section</label>
                                <select class="form-control select2" data-placeholder="Select a section type"
                                    style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Document Section</label>
                                <input name="cgst_rate" type="file" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('after-script')
    <script src="{{ asset('assets/modules/documentUploads.js') }}"></script>
@endpush
