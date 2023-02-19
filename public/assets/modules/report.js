$(document).ready(function () {
    $("[name=start_date]").on("change", function (e) {
        $("[name=end_date]").attr("min", $(this).val());
    });

    $("[name=end_date]").on("change", function (e) {
        $("[name=start_date]").attr("max", $(this).val());
    });

    $("[name=duration]").on("change", function (e) {
        if ($(this).val() == "custom") {
            $(".dateshow").show();
        } else {
            $(".dateshow").hide();
        }
    });
});
