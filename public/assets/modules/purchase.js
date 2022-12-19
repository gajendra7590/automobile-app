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
                    data: "purchase_id",
                    name: "purchase_id",
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
                    data: "brand.name",
                    name: "brand.name",
                },
                {
                    data: "model.model_name",
                    name: "model.model_name",
                },
                {
                    data: "model_color.color_name",
                    name: "model_color.color_name",
                },
                {
                    data: "dc_number",
                    name: "dc_number",
                },
                {
                    data: "dc_date",
                    name: "dc_date",
                },
                {
                    data: "purchase_invoice_number",
                    name: "purchase_invoice_number",
                },
                {
                    data: "purchase_invoice_date",
                    name: "purchase_invoice_date",
                },
                {
                    data: "purchase_invoice_amount",
                    name: "purchase_invoice_amount",
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
