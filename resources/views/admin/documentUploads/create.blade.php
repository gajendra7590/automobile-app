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

                <form action="{{ route('documentUploads.store') }}" class="ajaxFormSubmit" data-redirect="" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="box-body" data-select2-id="15">
                        <div class="row">
                            <input type="hidden" value="{{ route('getSectionTypeDropdown') }}" id="select2SearchURL">
                            <div class="form-group col-md-6">
                                <label>Document Type</label>
                                <select class="form-control" name="document_section_type">
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
                                    <select name="document_section_id" class="form-control select2" id="select2Ele"
                                        data-placeholder="Select a document section..." style="width: 100%;"
                                        data-select2-id="7" tabindex="-1" aria-hidden="true" disabled>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row hideElement" id="nextContainer">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Document Description(If Any :)</label>
                                    <textarea name="document_section_title" id="" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload Document</label>
                                    <input name="document_file" type="file" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-danger" href="{{ route('documentUploads.index') }}">BACK</a>
                                <button class="btn btn-primary pull-right" type="submit">UPLOAD</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@push('after-script')
    <script src="{{ asset('assets/modules/documentUploads.js') }}"></script>
@endpush
