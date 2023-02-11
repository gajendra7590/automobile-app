$(document).ready(function () {
    $('select[name="bike_model_variant"]').change(function () {
        let var_code = $(this).find("option:selected").attr("data-variantCode");
        if (var_code != "") {
            $('input[name="variant"]').val(var_code);
        }
    });

    $('select[name="bike_model_color"]').change(function () {
        let sku_code = $(this).find("option:selected").attr("data-skuCode");
        if (sku_code != "") {
            $('input[name="sku"]').val(sku_code);
        }
    });

    $(document).on("keyup keypress", ".totalAmountCal", function () {
        calculate();
    });

    $(document).on("keyup keypress", ".totalAmountCal2", function () {
        calculate();
    });

    $(document).on("change", "#gst_rate", function () {
        calculate();
    });

    function calculate() {
        let pre_gst_amount = parseFloat(
            $('input[name="pre_gst_amount"]').val()
        );
        let discount_price = parseFloat(
            $('input[name="discount_price"]').val()
        );
        let rate = parseFloat($("#gst_rate option:selected").data("rate"));
        discount_price = !isNaN(discount_price) ? discount_price : 0.0;
        pre_gst_amount = !isNaN(pre_gst_amount) ? pre_gst_amount : 0.0;
        let pre_gst_re_total =
            parseFloat(pre_gst_amount) - parseFloat(discount_price);
        let gst_amount = parseFloat((pre_gst_re_total * rate) / 100);
        let gst_amount2 = parseFloat((pre_gst_amount * rate) / 100);
        $("#gst_rate_percent").val(rate);
        $('input[name="gst_amount"]').val(gst_amount);
        let ex_showroom_total =
            parseFloat(gst_amount2) + parseFloat(pre_gst_amount);
        let total = parseFloat(gst_amount) + parseFloat(pre_gst_re_total);
        $('input[name="ex_showroom_price"]').val(ex_showroom_total);
        $('input[name="grand_total"]').val(total);
    }

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
                    data: "branch.branch_name",
                    name: "branch.branch_name",
                },
                {
                    data: "bike_detail",
                    name: "bike_detail",
                },
                {
                    data: "sku",
                    name: "sku",
                },
                // {
                //     data: "variant",
                //     name: "variant",
                // },
                {
                    data: "dc_number",
                    name: "dc_number",
                },
                {
                    data: "dc_date",
                    name: "dc_date",
                },
                {
                    data: "grand_total",
                    name: "grand_total",
                },
                {
                    data: "transfer_status",
                    name: "transfer_status",
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
                    targets: [-1],
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

    $(".purchaseFormAjaxSubmit").validate({
        // errorElement: 'span',
        // errorClass: 'text-muted error',
        rules: {
            bike_branch: {
                required: true,
            },
            bike_dealer: {
                required: true,
            },
            bike_brand: {
                required: true,
            },
            bike_model: {
                required: true,
            },
            bike_model_variant: {
                required: true,
            },
            bike_type: {
                required: true,
            },
            bike_fuel_type: {
                required: true,
            },
            break_type: {
                required: true,
            },
            wheel_type: {
                required: true,
            },
            bike_model_color: {
                required: true,
            },
            vin_number: {
                required: true,
                minlength: 17,
            },
            vin_physical_status: {
                required: true,
            },
            hsn_number: {
                required: true,
            },
            engine_number: {
                required: true,
            },
            gst_rate: {
                required: true,
            },
            pre_gst_amount: {
                required: true,
                number: true,
                min: 1,
            },
            dc_number: {
                required: true,
            },
            dc_date: {
                required: true,
                date: true,
            },
        },
        messages: {
            bike_branch: {
                required: "The branch field is required.",
            },
            bike_dealer: {
                required: "The dealer field is required.",
            },
            bike_brand: {
                required: "The brand field is required.",
            },
            bike_model: {
                required: "The model field is required.",
            },
            bike_model_variant: {
                required: "The variant field is required.",
            },
            bike_model_color: {
                required: "The color field is required.",
            },
            bike_type: {
                required: "The bike type field is required.",
            },
            bike_fuel_type: {
                required: "The fuel type field is required.",
            },
            break_type: {
                required: "The break type field is required.",
            },
            wheel_type: {
                required: "The wheel type field is required.",
            },
            vin_number: {
                required: "The VIN number field is required.",
                minlength: "The VIN number should valid 17 digit.",
            },
            vin_physical_status: {
                required: "The vin physical status field is required.",
            },
            hsn_number: {
                required: "The HSN number field is required.",
            },
            engine_number: {
                required: "The engine number field is required.",
            },
            gst_rate: {
                required: "The GST Rate field is required.",
            },
            pre_gst_amount: {
                required: "The actual price field is required.",
                number: "Please enter valid price.",
                min: "Please enter valid price.",
            },
            dc_number: {
                required: "The dc number field is required.",
            },
            dc_date: {
                required: "The dc date field is required.",
                date: "The dc date field should valid date.",
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            let formObj = $(".purchaseFormAjaxSubmit");
            var url = formObj.attr("action");
            var method = formObj.attr("method");
            var redirect = formObj.data("redirect");
            var data = new FormData(formObj[0]);
            CRUD.AJAXSUBMIT(url, method, data).then(function (result) {
                if (
                    typeof result.status != "undefined" &&
                    result.status == true
                ) {
                    if (redirect != undefined) {
                        if (result.data?.id) {
                            redirect = redirect.replace("{id}", result.data.id);
                        }
                        window.location.href = redirect;
                    } else {
                        // window.location.href = "";
                    }
                }
            });
        },
    });
});
