$(document).ready(function () {
    function mainDataTable() {
        const tableObj = $("#ajaxDataTable").DataTable({
            processing: false,
            serverSide: true,
            cache: true,
            type: "GET",
            ajax: {
                url: $("#ajaxDataTable").data("url"),
                method: "GET",
                beforeSend: function () {
                    loaderShow();
                },
                complete: function () {
                    loaderHide();
                },
            },
            paging: false,
            ordering: false,
            info: false,
            searchable: false,
            columns: [
                {
                    data: "id",
                    name: "id",
                },
                {
                    data: "agent_name",
                    name: "agent_name",
                },
                {
                    data: "registrations_count",
                    name: "registrations_count",
                },
                {
                    data: "registrations_sum_total_amount",
                    name: "registrations_sum_total_amount",
                },
                {
                    data: "payments_sum_payment_amount",
                    name: "payments_sum_payment_amount",
                },
                {
                    data: "total_outstanding",
                    name: "total_outstanding",
                },
                {
                    data: "buffer_amount",
                    name: "buffer_amount",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5, 6],
                },
                {
                    searchable: false,
                    targets: [],
                },
            ],
            order: [],
        });
    }
    mainDataTable();
});
