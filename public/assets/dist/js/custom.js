function loaderHide() {
    $(".ajax_loader").hide();
}

function loaderShow() {
    $(".ajax_loader").show();
}

$(window).on("load", function () {
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
        console.log(redirect);
        var ajaxModalCommon = $(this).data("modal-id");
        var data = new FormData($(this)[0]);
        CRUD.AJAXSUBMIT(url, method, data).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                if (redirect == "closeModal") {
                    $(`#${ajaxModalCommon}`).modal("hide");
                    $("#ajaxModalDialog").modal("hide");
                    if (result.data.html && result.data.type) {
                        $(`[name='${result.data.type}']`).append(
                            result.data.html
                        );
                    }
                } else if (redirect == "ajaxModalCommon") {
                    $("#ajaxModalCommon").modal("hide");
                    $("#ajaxModalDialog").modal("hide");
                    window.location.href = "";
                } else if (redirect == "nothing") {
                    //
                } else if (redirect != undefined) {
                    if (result.data?.id) {
                        redirect = redirect.replace("{id}", result.data.id);
                    }
                    window.location.href = redirect;
                } else {
                    // window.location.href = "";
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
            var dep_dd4_name = $(this).data("dep_dd4_name");
            //Call Callback Function
            getAndFillDropDown(
                url,
                id,
                dep_dd_name,
                dep_dd2_name,
                dep_dd3_name,
                dep_dd4_name
            );
        }
    });

    function getAndFillDropDown(
        url,
        id,
        dep_dd_name,
        dep_dd2_name,
        dep_dd3_name,
        dep_dd4_name = ""
    ) {
        var formdata = new FormData();
        formdata.append("dep_dd_name", dep_dd_name);
        formdata.append("dep_dd2_name", dep_dd2_name);
        formdata.append("dep_dd3_name", dep_dd3_name);
        formdata.append("dep_dd4_name", dep_dd4_name);
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

                if (
                    typeof payload.dep_dd4_name != "undefined" &&
                    payload.dep_dd4_html != ""
                ) {
                    $('select[name="' + payload.dep_dd4_name + '"]').html(
                        payload.dep_dd4_html
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
        let type = _this.data("type");
        let URL = `/status/${id}?type=${type}`;
        CRUD.AJAXDATA(URL, "POST").then(function (res) {});
    });

    $(document).on("click", ".plusAction", function (e) {
        e.preventDefault();
        var ddchange = $(this).data("ddchange");
        var modalId = $(this).data("modalid");
        var ddchange = ddchange ? ddchange : "customer_district";
        var district_id = $(`[name=${ddchange}]`).val();
        var type = $(this).data("type");
        var ddname = $(this).data("ddname");
        var url =
            $(this).attr("href") +
            `?type=${type}&district_id=${district_id}&ddname=${ddname}&modalId=${modalId}`;
        var modal_title = $(this).data("modal_title");
        var modal_size = $(this).data("modal_size");
        if (!district_id) {
            toastr.error("Please select district first");
        } else {
            var modal_type =
                $(this).data("modal_type") != undefined
                    ? $(this).data("modal_type")
                    : "";
            $(`#ajaxModalSize${modal_type}`).addClass(modal_size);
            $(`.ajaxModalTitle${modal_type}`).html(modal_title);
            $(`.ajaxModalCommon${modal_type}`).attr("tabindex", 1200);
            $(`#ajaxModalCommon${modal_type}`).modal("show");
            CRUD.AJAXMODAL(url, "GET").then(function (result) {
                if (
                    typeof result.status != "undefined" &&
                    result.status == true
                ) {
                    $(`.ajaxModalBody${modal_type}`).html(result.data);
                } else {
                    toastr.error("Something went wrong");
                }
            });
        }
    });

    //ajaxChangeCDropDown
});
