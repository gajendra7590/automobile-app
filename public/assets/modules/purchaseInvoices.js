$(document).ready(function () {
    $(document).on("keyup keypress", ".totalAmountCalInv", function () {
        calculateInv();
    });

    $(document).on("keyup keypress", ".totalAmountCal2Inv", function () {
        calculateInv();
    });

    $(document).on("change", "#gst_rate", function () {
        calculateInv();
    });

    function calculateInv() {
        $val = $('input[name="pre_gst_amount"]').val();
        $rate = $("#gst_rate option:selected").data("rate");
        console.log($val);
        console.log($rate);
        $("#gst_rate_percent").val($rate);
        $('input[name="gst_amount"]').val((($val * $rate) / 100).toFixed(2));

        var total = 0.0;
        $(".totalAmountCal2Inv").each(function (i, ele) {
            let input_value = parseFloat($(this).val());
            if (isNaN(input_value)) {
                total += 0.0;
            } else {
                total += input_value;
            }
        });
        $('input[name="ex_showroom_price"]').val(total);

        total = 0.0;

        $(".totalAmountCalInv").each(function (i, ele) {
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
                    data: "branch_name",
                    name: "branch_name",
                },
                {
                    data: "purchase_sku",
                    name: "purchase_sku",
                },
                {
                    data: "purchase_variant",
                    name: "purchase_variant",
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
                    data: "grand_total",
                    name: "grand_total",
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
            order: [[0, "asc"]],
        });
    }
    mainDataTable();
});
