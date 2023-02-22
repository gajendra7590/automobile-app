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
                    data: "company_name",
                    name: "company_name",
                },
                {
                    data: "company_email",
                    name: "company_email",
                },
                {
                    data: "branch_name",
                    name: "branch_name",
                },
                {
                    data: "purchase_sum_grand_total",
                    name: "purchase_sum_grand_total",
                },
                {
                    data: "dealer_payment_sum_debit_amount",
                    name: "dealer_payment_sum_debit_amount",
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
                    targets: [0, 1, 2, 3, 4, 5, 6],
                },
            ],
            order: [],
        });
    }
    mainDataTable();
});
