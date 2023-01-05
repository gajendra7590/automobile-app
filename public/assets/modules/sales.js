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
                    data: "branch.branch_name",
                    name: "branch.branch_name",
                },
                {
                    data: "dealer.company_name",
                    name: "dealer.company_name",
                },
                {
                    data: "customer",
                    name: "customer",
                },
                {
                    data: "bike_detail",
                    name: "bike_detail",
                },
                {
                    data: "total_amount",
                    name: "total_amount",
                },
                {
                    data: "created_at",
                    name: "created_at",
                },
                {
                    data: "status",
                    name: "status",
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
