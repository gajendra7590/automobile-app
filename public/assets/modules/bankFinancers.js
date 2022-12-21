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
                    data: "bank_name",
                    name: "bank_name",
                },
                {
                    data: "bank_branch_code",
                    name: "bank_branch_code",
                },

                {
                    data: "bank_manager_name",
                    name: "bank_manager_name",
                },
                {
                    data: "bank_financer_name",
                    name: "bank_financer_name",
                },
                {
                    data: "bank_financer_contact",
                    name: "bank_financer_contact",
                },
                {
                    data: "active_status",
                    name: "active_status",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [-1],
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
