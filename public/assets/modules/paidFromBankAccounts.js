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
            searchDelay: 350,
            columns: [
                {
                    data: "id",
                    name: "id",
                },
                {
                    data: "branch_name",
                    name: "branch_name",
                },
                {
                    data: "bank_name",
                    name: "bank_name",
                },
                {
                    data: "bank_account_number",
                    name: "bank_account_number",
                },
                {
                    data: "bank_account_holder_name",
                    name: "bank_account_holder_name",
                },
                {
                    data: "bank_ifsc_code",
                    name: "bank_ifsc_code",
                },
                {
                    data: "bank_branch_name",
                    name: "bank_branch_name",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [1, -1, -2],
                },
                {
                    searchable: false,
                    targets: [],
                },
            ],
            order: [[0, "desc"]],
        });
    }
    mainDataTable();
});
