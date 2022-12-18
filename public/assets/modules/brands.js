$(document).ready(function() {
    const tableObj = $('#ajaxDataTable').DataTable({
        processing: false,
        serverSide: true,
        cache: true,
        type: 'GET',
        ajax: {
            url: $("#ajaxDataTable").data("url"),
            method: 'GET',
            beforeSend: function() {
                loaderShow();
            },
            complete: function() {
                loaderHide();
            }
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
                data: 'code',
                name: 'code'
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
