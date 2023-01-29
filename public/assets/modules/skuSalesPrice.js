$(document).ready(function () {
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
                data: "sku_code",
                name: "sku_code",
            },
            {
                data: "ex_showroom_price",
                name: "ex_showroom_price",
            },
            {
                data: "registration_amount",
                name: "registration_amount",
            },
            {
                data: "insurance_amount",
                name: "insurance_amount",
            },
            // {
            //     data: "hypothecation_amount",
            //     name: "hypothecation_amount",
            // },
            // {
            //     data: "accessories_amount",
            //     name: "accessories_amount",
            // },
            // {
            //     data: "other_charges",
            //     name: "other_charges",
            // },
            {
                data: "total_amount",
                name: "total_amount",
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
                targets: [-1, -2],
            },
            {
                searchable: false,
                targets: [],
            },
        ],
        order: [[0, "desc"]],
    });

    $(document).on("keyup keypress", ".skuSalesPrice", function () {
        let total = 0.0;
        $(".skuSalesPrice").each(function (i, ele) {
            let price = $(this).val();
            price =
                price != "" && price != "NaN" && price != NaN
                    ? parseFloat(price)
                    : 0;
            total += price;
        });
        $("input[name='total_amount']").val(total);
    });
});
