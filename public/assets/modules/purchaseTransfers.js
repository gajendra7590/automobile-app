$(document).ready(function () {
    $(document).on("keyup keypress", ".totalAmountCal", function () {
        calculate();
    });

    $(document).on("keyup keypress", ".totalAmountCal2", function () {
        calculate();
    });

    $(document).on("change", "#gst_rate", function () {
        calculate();
    });

    function calculate() {
        $val = $("#pre_gst_amount").val();
        $rate = $("#gst_rate option:selected").data("rate");
        $("#gst_rate_percent").val($rate);
        $('input[name="gst_amount"]').val((($val * $rate) / 100).toFixed(2));

        var total = 0.0;
        $(".totalAmountCal2").each(function (i, ele) {
            let input_value = parseFloat($(this).val());
            if (isNaN(input_value)) {
                total += 0.0;
            } else {
                total += input_value;
            }
        });
        $('input[name="ex_showroom_price"]').val(total);

        total = 0.0;

        $(".totalAmountCal").each(function (i, ele) {
            let input_value = parseFloat($(this).val());
            if (isNaN(input_value)) {
                total += 0.0;
            } else {
                total += input_value;
            }
        });
        $('input[name="grand_total"]').val(total);
    }

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
                    data: "broker_name",
                    name: "broker_name",
                },
                {
                    data: "bike_detail",
                    name: "bike_detail",
                },
                {
                    data: "sku",
                    name: "sku",
                },
                {
                    data: "transfers.total_price_on_road",
                    name: "transfers.total_price_on_road",
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
                    targets: [-1, -2, 3],
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
