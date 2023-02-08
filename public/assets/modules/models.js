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
                    data: "model_name",
                    name: "model_name",
                },
                {
                    data: "model_code",
                    name: "model_code",
                },
                {
                    data: "bike_brand.name",
                    name: "bike_brand.name",
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
                    targets: [-1, -2],
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

    $(document).on("click", ".addAjaxElement", function (e) {
        e.preventDefault();
        let container_el = $(this).data("container_el");
        let randCode = Math.floor(Math.random() * 100000) + 5;
        let html =
            `<div class="row">
               <div class="form-group col-md-4">
                <label>Model Name</label>
                <input name="models[` +
            randCode +
            `][model_name]" type="text" class="form-control" value=""
                    placeholder="Model Name..">
            </div>
            <div class="form-group col-md-3">
                <label>Model Code</label>
                <input name="models[` +
            randCode +
            `][model_code]" type="text" class="form-control" value=""
                    placeholder="Model Code..">
            </div>
            <div class="form-group col-md-3">
                <label>Status : </label>
                <select class="form-control" name="models[` +
            randCode +
            `][active_status]">
                    <option value="1" selected>Active</option>
                    <option value="0"> In Active </option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <a href="#" class="btn btn-md btn-danger removeMoreInFormGroup removeAjaxElement">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>
            </div>
        </div>`;
        $(container_el).append(html);
    });

    $(document).on("click", ".removeAjaxElement", function (e) {
        e.preventDefault();
        $(this).parents(".row").remove();
    });
});
