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
                BULK UPLOAD PURCHASES
            </h1>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        BULK UPLOAD PURCHASES
                    </h3>
                    <a href="{{ url('assets/sample_files/sample_purchase.csv') }}" download="sample_purchase.csv" class="btn btn-sm btn-info pull-right">
                        <i class="fa fa-download" aria-hidden="true"></i> DOWNLOAD SAMPLE FILE
                    </a>
                </div>
                <form action="{{ route('bulkUploadPurchases.store') }}" class="ajaxFormSubmit ajaxForm" data-redirect=""
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body" data-select2-id="15">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>BRANCH NAME</label>
                                <select class="form-control" name="branch_id">
                                    @if (isset($branches) && count($branches) > 0)
                                        @foreach ($branches as $k => $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ strtoupper($branch->branch_name) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>UPLOAD PURCHASE CSV</label>
                                    <input type="file" class="form-control" name="csv_file" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-danger">Note : Please upload valid CSV File With Valid Data, You can
                                        download sample CSV
                                        File.</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    {{-- <a class="btn btn-danger" href="{{ route('documentUploads.index') }}">BACK</a> --}}
                                    <button class="btn btn-primary pull-right" type="submit">UPLOAD</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
    </div>
    </section>
    </div>
@endsection
@push('after-script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
