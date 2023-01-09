function loaderHide() {
    $('.ajax_loader').hide();
}

function loaderShow() {
    $('.ajax_loader').show();
}

$(window).on('load', function() {
    loaderHide();
});

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
    Operations.AJAXDATA = function (URL, METHOD, DATA = {}) {
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
                return res;
            },
            error: function (res) {
                loaderHide();
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
        var ajaxModalCommon = $(this).data("modal-id");
        var data = new FormData($(this)[0]);
        CRUD.AJAXSUBMIT(url, method, data).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                if (redirect == "closeModal") {
                    $(`#${ajaxModalCommon}`).modal("hide");
                    $("#ajaxModalDialog").modal("hide");
                } else if (redirect == "ajaxModalCommon") {
                    $("#ajaxModalCommon").modal("hide");
                    $("#ajaxModalDialog").modal("hide");
                    window.location.href = "";
                } else if (redirect != "undefined") {
                    if (result.data?.id) {
                        redirect = redirect.replace("{id}", result.data.id);
                    }
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
        var modal_size = $(this).data("modal_size");
        $("#ajaxModalSize").addClass(modal_size);
        $(".ajaxModalTitle").html(modal_title);
        $(".ajaxModalBody").attr("tabindex", 1);

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

    $(document).on("click", ".ajaxModalPopupOnPopup", function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        var modal_title = $(this).data("modal_title");
        var modal_size = $(this).data("modal_size");
        $("#ajaxModalSize2").addClass(modal_size);
        $(".ajaxModalTitle2").html(modal_title);
        // $(".ajaxModalCommon2").attr("tabindex",1)

        $(".ajaxModalBody2").html(
            `<div style="text-align: center;min-height: 174px;padding: 57px;"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true" style="color: #ea6d09;"></i></div>`
        );
        $("#ajaxModalCommon2").modal("show");
        CRUD.AJAXMODAL(url, "GET", null).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                $(".ajaxModalBody2").html(result.data);
            } else {
                toastr.error("Something went wrong");
                // to do
            }
        });
    });

    $(document).on("click", ".ajaxModalDelete", function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        $("#ajaxModalDialog").modal("show");
        $("#ajaxModalDialogForm").attr("action", url);
    });

    $(document).on("change", ".ajaxChangeCDropDown", function (e) {
        e.preventDefault();
        var id = $(this).val();
        if (id == "") {
            return false;
        } else {
            var url = $(this).data("url");
            var dep_dd_name = $(this).data("dep_dd_name");
            var dep_dd2_name = $(this).data("dep_dd2_name");
            var dep_dd3_name = $(this).data("dep_dd3_name");
            //Call Callback Function
            getAndFillDropDown(
                url,
                id,
                dep_dd_name,
                dep_dd2_name,
                dep_dd3_name
            );
        }
    });

    function getAndFillDropDown(
        url,
        id,
        dep_dd_name,
        dep_dd2_name,
        dep_dd3_name
    ) {
        var formdata = new FormData();
        formdata.append("dep_dd_name", dep_dd_name);
        formdata.append("dep_dd2_name", dep_dd2_name);
        formdata.append("dep_dd3_name", dep_dd3_name);
        formdata.append("id", id);
        CRUD.AJAXDATA(url, "POST", formdata).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                let payload = result.data;
                $('select[name="' + payload.dep_dd_name + '"]').html(
                    payload.dep_dd_html
                );

                if (
                    typeof payload.dep_dd2_name != "undefined" &&
                    payload.dep_dd2_html != ""
                ) {
                    $('select[name="' + payload.dep_dd2_name + '"]').html(
                        payload.dep_dd2_html
                    );
                }

                if (
                    typeof payload.dep_dd3_name != "undefined" &&
                    payload.dep_dd3_html != ""
                ) {
                    $('select[name="' + payload.dep_dd3_name + '"]').html(
                        payload.dep_dd3_html
                    );
                }
                return true;
            } else {
                return true;
            }
        });
    }

    $(document).on("change", ".active_status", function (e) {
        e.preventDefault();
        let _this = $(this);
        let id = _this.val();
        let type = _this.data('type');
        let URL = `/status/${id}?type=${type}`;
        CRUD.AJAXDATA(URL, "POST").then(function (res) {
        });
    });


    //ajaxChangeCDropDown
});
