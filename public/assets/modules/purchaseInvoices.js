$(document).ready(function () {
    alert("test");
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
});
