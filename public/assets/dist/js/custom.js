function loaderShow() {
    $("#loading").show();
}

function loaderHide() {
    $("#loading").hide();
}

bearer_token = "";
app_url = "";
$.ajaxSetup({
    headers: {
        "Access-Control-Allow-Origin": "*",
        Authorization: bearer_token,
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

var LOADER = (function () {
    const loader = {};
    loader.open = function () {};

    loader.close = function () {};
    return loader;
})();

var CRUD = (function ($, l, m) {
    const Operations = {};
    // Common function for POST Operations
    Operations.AJAXSUBMIT = function (URL, METHOD, DATA = {}) {
        return $.ajax({
            url: URL,
            data: DATA,
            // cache: false,
            type: METHOD,
            // dataType: "JSON",
            contentType: false,
            processData: false,
            beforeSend: function () {
                loaderShow();
            },
            success: function (res) {
                loaderHide();
                if (typeof res.status != "undefined" && res.status == true) {
                    toastr.success(res.message, "Success!");
                } else if (
                    typeof res.status != "undefined" &&
                    res.status == false
                ) {
                    toastr.error(res.message, "Error!");
                } else {
                    toastr.error("Something went wrong", "Error!");
                }
                return res;
            },
            error: function (res) {
                loaderHide();
                toastr.error(res.message, "Error!");
                return res;
            },
        });
    };
    Operations.AJAXMODAL = function (URL, METHOD, DATA = {}) {
        return $.ajax({
            url: URL,
            data: DATA,
            // cache: false,
            type: METHOD,
            // dataType: "JSON",
            contentType: false,
            processData: false,
            success: function (res) {
                return res;
            },
            error: function (res) {
                return res;
            },
        });
    };
    return Operations;
})($, LOADER, null);

$(document).ready(function () {
    /*-----ADD & UPDATE DATA--------*/
    $(document).on("submit", ".ajaxFormSubmit", function (e) {
        e.preventDefault();
        var url = $(this).attr("action");
        var method = $(this).attr("method");
        var redirect = $(this).data("redirect");
        var data = new FormData($(this)[0]);
        CRUD.AJAXSUBMIT(url, method, data).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                if (redirect == "ajaxModalCommon") {
                    $("#ajaxModalCommon").modal("hide");
                    window.location.href = "";
                } else if (redirect != "undefined") {
                    window.location.href = redirect;
                } else {
                    window.location.href = "";
                }
            } else {
                // to do
            }
        });
    });

    $(document).on("click", ".ajaxModalPopup", function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        var modal_title = $(this).data("modal_title");
        $(".ajaxModalTitle").html(modal_title);
        $(".ajaxModalBody").html(
            `<div style="text-align: center;min-height: 174px;padding: 57px;"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true" style="color: #ea6d09;"></i></div>`
        );
        $("#ajaxModalCommon").modal("show");
        CRUD.AJAXMODAL(url, "GET", null).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                $(".ajaxModalBody").html(result.data);
            } else {
                toastr.error("Something went wrong");
                // to do
            }
        });
    });
});
