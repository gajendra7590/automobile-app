$(document).ready(function () {
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
                    data: "district.state.state_name",
                    name: "district.state.state_name",
                },
                {
                    data: "district.district_name",
                    name: "district.district_name",
                },
                {
                    data: "city_name",
                    name: "city_name",
                },
                {
                    data: "city_code",
                    name: "city_code",
                },
                {
                    data: "active_status",
                    name: "active_status",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [1, -1, -2],
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

$(document).on("click", ".addAjaxElement", function (e) {
    e.preventDefault();
    let container_el = $(this).data("container_el");
    let randCode = Math.floor(Math.random() * 100000) + 5;
    let html =
        '<div class="row">' +
        '    <div class="form-group col-md-4">' +
        "        <label>CITY NAME</label>" +
        '        <input name="cities[' +
        randCode +
        '][city_name]" type="text" class="form-control" value="" required data-msg-required="The city name field is required"' +
        '            placeholder="CITY NAME..">' +
        "    </div>" +
        '    <div class="form-group col-md-3">' +
        "        <label>CITY CODE</label>" +
        '        <input name="cities[' +
        randCode +
        '][city_code]" type="text" class="form-control" value=""' +
        '            placeholder="CITY CODE..">' +
        "    </div>" +
        '    <div class="form-group col-md-4">' +
        "        <label>Status : </label>" +
        '        <select class="form-control" name="cities[' +
        randCode +
        '][active_status]">' +
        '            <option value="1" selected="selected">Active</option>' +
        '            <option value="0">In Active </option>' +
        "        </select>" +
        "    </div>" +
        '    <div class="form-group col-md-1">' +
        '        <a href="#" class="btn btn-md btn-danger removeMoreInFormGroup removeAjaxElement"' +
        '            data-container_el="#city_container"><i class="fa fa-trash-o" aria-hidden="true"></i></a>' +
        "    </div>" +
        "</div>";
    $(container_el).append(html);
});

$(document).on("click", ".removeAjaxElement", function (e) {
    e.preventDefault();
    $(this).parents(".row").remove();
});
