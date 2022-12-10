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
    Operations.AJAXSUBMIT = function (URL,METHOD,DATA = {}) {
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
                if((typeof(res.status) != 'undefined') && (res.status == true) ) {
                    toastr.success(res.message,'Success!')
                }else if((typeof(res.status) != 'undefined') && (res.status == false) ) {
                    toastr.error(res.message,'Error!')
                }else{
                    toastr.error('Something went wrong','Error!');
                }
                return res;
            },
            error: function (res) {
                loaderHide();
                toastr.error(res.message,'Error!')
                return res;
            },
        });
    };
    return Operations;
})($, LOADER, null);

$(document).ready(function () {
    /*-----ADD & UPDATE DATA--------*/
    $(document).on("submit", '.ajaxFormSubmit', function (e) {
        e.preventDefault()
        var url =$(this).attr("action");
        var method = $(this).attr("method");
        var redirect = $(this).data("redirect");
        var data = new FormData($(this)[0]);
        CRUD.AJAXSUBMIT(url,method,data).then(function (result){
            if(typeof(result.status) != 'undefined' && result.status == true){
                if(redirect != 'undefined') {
                    window.location.href = redirect;
                }else{
                    window.location.href = '';
                }
            }else{
                // to do
            }
        });

    });
    $table_url = $("[id=dataTable]").attr("data-url");
    $filterData = $("[id=dataTable]").attr("filter-data");
    if ($table_url) {
        draw_datatable($filterData);
    }
    $("#password, #confirm_password").on("keyup", function () {
        if ($("#password").val() == $("#confirm_password").val()) {
            $("#submit-button").prop("disabled", false);
            $("#message").html("Matching").css("color", "green");
        } else {
            $("#submit-button").prop("disabled", true);
            $("#message").html("Not Matching").css("color", "red");
        }
    });
    $(document).on("click", "#filter-table", function () {
        $request = "";
        var orderByField = $(this).attr("orderByField");
        var dataTable = $("#dataTable");
        var orderByKeyword = dataTable.attr("orderByKeyword");
        var filterData = $("[id=dataTable]").attr("filter-data");
        //creating  array of all index
        filterData = filterData.split("&");
        //removing index with orderByField and orderByKeyword values
        filterData.forEach((value, key) => {
            if (
                value.includes("orderByField") ||
                value.includes("orderByKeyword")
            ) {
                filterData.splice(key, 1);
            }
        });
        if (!orderByKeyword) {
            dataTable.attr("orderByKeyword", "asc");
            orderByKeyword = "asc";
        } else {
            if (orderByKeyword == "asc") {
                dataTable.attr("orderByKeyword", "dsc");
                orderByKeyword = "dsc";
            } else {
                dataTable.attr("orderByKeyword", "asc");
                orderByKeyword = "asc";
            }
        }
        filterData.push("orderByField=" + orderByField);
        filterData.push("orderByKeyword=" + orderByKeyword);
        filterData.forEach((value, key) => {
            $request = $request.concat("&" + value);
        });
        draw_datatable($request);
        $request = "";
    });
    $(document).on("click", "thead th.sorting_asc ", function () {
        th = true;
    });
    $(document).on("click", "thead th.sorting_dsc", function () {
        th = true;
    });
});

function getFormData($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    if (Object.keys(indexed_array).length == 1) {
        indexed_array["fields"] = unindexed_array[0]["name"];
    }

    return indexed_array;
}

function draw_datatable($request, modalId = null, colunmArray = null) {
    columns_arr = colunmArray && colunmArray.length ? colunmArray : columns_arr;
    modalId = modalId ? modalId : "dataTable";
    datatable = $(`#${modalId}`);
    var searchBy = datatable.attr("searchBy");
    var searchPlaceholder = datatable.attr("searchPlaceholder");
    searchBy = searchBy ? searchBy : "name";
    searchPlaceholder = searchPlaceholder ? searchPlaceholder : "Search...";
    $(`#${modalId}`).DataTable({
        dom: "lfrtip",
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        destroy: true,
        sPaginationType: "full_numbers",
        language: {
            searchPlaceholder: searchPlaceholder,
        },
        ajax: {
            url:
                app_url +
                "/" +
                $table_url +
                "?" +
                $request +
                "&userId=" +
                userId,
            dataSrc: "data",
            beforeSend: function () {
                loaderShow();
            },
            complete: function (e) {
                if (e.status == 401) {
                    window.location.href = app_url + "/login";
                }
                loaderHide();
            },
            data: function ($request) {
                order = $request.order[0];
                var input = $(`#${modalId}`).DataTable().page.info();
                input.page = input.page + 1;
                if ($request.search.value && $request.search.value != "") {
                    input[searchBy] = $request.search.value;
                }
                if (th == true) {
                    input.orderByField = columns_arr[order.column].orderByField;
                    input.orderByKeyword = order.dir == "desc" ? "dsc" : "asc";
                }
                return input;
            },
            dataFilter: function (data) {
                var json = jQuery.parseJSON(data);
                json = json.Result;
                json.recordsTotal = json.count;
                json.recordsFiltered = json.count;
                json.data = json.rows;
                return JSON.stringify(json);
            },
        },
        columns: columns_arr,
    });
}

function dropdown(
    url,
    selected_id,
    selected_value,
    fieldname = "name",
    optionValue = "id"
) {
    var count = 0;
    $.ajax({
        url: app_url + url,
        async: false,
        method: "GET",
        dataType: "json",
        success: function (response) {
            $("#" + selected_id + "").empty("");
            var options = '<option value="">select</option>';
            if (response.statusCode == 200) {
                var option_list = response.Result
                    ? response.Result
                    : response.data;
                count = Object.keys(option_list).length;
                $.each(option_list, function (index, value) {
                    let selected =
                        parseInt(selected_value) === value.id ? "selected" : "";
                    let name = value[fieldname] ? value[fieldname] : "";
                    options +=
                        '<option value="' +
                        value[optionValue] +
                        '" ' +
                        selected +
                        ">" +
                        name +
                        "</option>";
                });
                $("#" + selected_id + "").append(options);
            }
        },
    });
    return count;
}
