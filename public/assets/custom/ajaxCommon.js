const AjaxPayload = {};

AjaxPayload.caller = function (Method = "GET", URL = "", DATA = null) {
    return $.ajax({
        url: URL,
        type: Method,
        data: DATA,
        processData: false,
        contentType: false,
        success: function (res) {
            return res;
        },
        error: function (error) {
            return error;
        },
    });
};
