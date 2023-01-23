$(document).ready(function () {
    $(document).on("keyup keypress", ".totalAmountCal", function () {
        var total = 0.0;
        $(".totalAmountCal").each(function (i, ele) {
            let input_value = parseFloat($(this).val());
            if (isNaN(input_value)) {
                total += 0.0;
            } else {
                total += input_value;
            }
        });
        $('input[name="total_amount"]').val(total);
    });

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
                    data: "branch_name",
                    name: "branch_name",
                },
                {
                    data: "customer_name",
                    name: "customer_name",
                },
                {
                    data: "customer_mobile_number",
                    name: "customer_mobile_number",
                },
                {
                    data: "bike_detail",
                    name: "bike_detail",
                },
                {
                    data: "payment_type",
                    name: "payment_type",
                },
                {
                    data: "total_amount",
                    name: "total_amount",
                },
                {
                    data: "purchase_visit_date",
                    name: "purchase_visit_date",
                },
                {
                    data: "status",
                    name: "status",
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
            '<div class="row">' +
            '    <div class="form-group col-md-4">' +
            "        <label>Color Name</label>" +
            '        <input name="cities[' +
            randCode +
            '][city_name]" type="text" class="form-control" value=""' +
            '            placeholder="Color name..">' +
            "    </div>" +
            '    <div class="form-group col-md-3">' +
            "        <label>Color Code</label>" +
            '        <input name="cities[' +
            randCode +
            '][city_code]" type="text" class="form-control" value=""' +
            '            placeholder="Color Code..">' +
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

    $(document).on("change", 'select[name="payment_type"]', function () {
        let val = $(this).val();
        if (val == "1") {
            $('select[name="hyp_financer"]').attr("disabled", "disabled");
            $('input[name="hyp_financer_description"]').attr(
                "disabled",
                "disabled"
            );
        } else {
            $('select[name="hyp_financer"]').removeAttr("disabled");
            $('input[name="hyp_financer_description"]').removeAttr("disabled");
        }
    });

    $(".QuotBrandChange").change(function () {
        let id = $(this).val();
        if (id > 0) {
            $("#quotation_more").removeClass("hideElement");
        } else {
            $("#quotation_more").addClass("hideElement");
        }
    });
});
