@extends('admin._layouts.admin-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Bike Categories List</h4>
                        <p class="mb-0">Bike Categories List</p>
                    </div>
                    <a href="#" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Add
                        Bike Categories</a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table id="ajaxDataTable" class="data-table table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                                        <label for="checkbox1" class="mb-0"></label>
                                    </div>
                                </th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            {{-- <tr>
                                <td>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="checkbox2">
                                        <label for="checkbox2" class="mb-0"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="../assets/images/table/product/01.jpg"
                                            class="img-fluid rounded avatar-50 mr-3" alt="image">
                                        <div>
                                            Organic Cream
                                            <p class="mb-0"><small>This is test Product</small></p>
                                        </div>
                                    </div>
                                </td>
                                <td>10.0</td>
                                <td>
                                    <div class="d-flex align-items-center list-action">
                                        <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top"
                                            title="" data-original-title="View" href="#"><i
                                                class="ri-eye-line mr-0"></i></a>
                                        <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top"
                                            title="" data-original-title="Edit" href="#"><i
                                                class="ri-pencil-line mr-0"></i></a>
                                        <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top"
                                            title="" data-original-title="Delete" href="#"><i
                                                class="ri-delete-bin-line mr-0"></i></a>
                                    </div>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('afterjs')
    <script>
        $(document).ready(function() {
            const tableObj = $('#ajaxDataTable').DataTable({
                processing: false,
                serverSide: true,
                cache: true,
                type: 'GET',
                ajax: {
                    url: '{!! route('brands.index') !!}',
                    method: 'GET',
                    // beforeSend: function() {
                    //     loaderShow();
                    // },
                    // complete: function() {
                    //     loaderHide();
                    // }
                },
                searchDelay: 350,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                columnDefs: [{
                        "orderable": false,
                        "targets": []
                    },
                    {
                        "searchable": false,
                        "targets": []
                    }
                ],
                order: [
                    [0, "desc"]
                ]
            });
        })
    </script>
@endpush
