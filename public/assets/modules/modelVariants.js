$(document).ready(function () {
    function mainDataTable() {
        tableObj = $("#ajaxDataTable").DataTable({
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
                    data: "variant_name",
                    name: "variant_name",
                },
                {
                    data: "model_id",
                    name: "model_id",
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
            order: [[2, "desc"]],
        });
    }
    mainDataTable();

    $(document).on("click", ".addAjaxElement", function (e) {
        e.preventDefault();
        let container_el = $(this).data("container_el");
        let randCode = Math.floor(Math.random() * 100000) + 5;
        let html =
            `<div class="row">
               <div class="form-group col-md-6">
                <label>Variant Name</label>
                <input name="variants[` +
            randCode +
            `][variant_name]" type="text" class="form-control" value=""
                    placeholder="Variant Name..">
            </div>
            <div class="form-group col-md-4">
                <label>Variant Status : </label>
                <select class="form-control" name="variants[` +
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
