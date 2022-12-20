$(document).ready(function () {
    $(document).on("keyup keypress", ".totalAmountCal", function () {
        var total = 0.0;
        $(".totalAmountCal").each(function (i, ele) {
            let input_value = parseFloat($(this).val());
            if (isNaN(input_value)) {
                total += 0.0;
            } else {
                total += input_value;
            }
        });
        $('input[name="total_amount"]').val(total);
    });

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
                    data: "customer_first_name",
                    name: "customer_first_name",
                },
                {
                    data: "customer_mobile_number",
                    name: "customer_mobile_number",
                },
                {
                    data: "bike_detail",
                    name: "bike_detail",
                },
                {
                    data: "payment_type",
                    name: "payment_type",
                },
                {
                    data: "is_exchange_avaliable",
                    name: "is_exchange_avaliable",
                },
                {
                    data: "total_amount",
                    name: "total_amount",
                },
                {
                    data: "purchase_visit_date",
                    name: "purchase_visit_date",
                },
                {
                    data: "purchase_est_date",
                    name: "purchase_est_date",
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
