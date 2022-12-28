$(document).ready(function () {

    $(document).on("keyup keypress", ".totalAmountCal", function () {
        calculate()
    });


    $(document).on("keyup keypress", ".totalAmountCal2", function () {
        calculate()
    });

    $(document).on("change", "#gst_rate", function () {
        calculate()
    });

    function calculate(){
        $val = $('#pre_gst_amount').val();
        $rate = $('#gst_rate').val();;
        $('input[name="gst_amount"]').val(($val*$rate/100).toFixed(2));

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
