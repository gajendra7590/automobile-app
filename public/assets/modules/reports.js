$(document).ready(function () {
    let URL = $("#current_active").attr("href");

    function loadReportModule(URL) {
        CRUD.AJAXDATA(URL, "GET").then(function (res) {
            if (typeof res.status != "undefined" && res.status == true) {
                $("#reportContainer").html(res.data);
            }
        });
    }

    $(".loadeReport").click(function (e) {
        e.preventDefault();

        let URL = $(this).attr("href");
        $(".loadeReport").parent("li").removeClass("active");
        $(this).parent("li").addClass("active");
        loadReportModule(URL);
    });

    loadReportModule(URL);
});
