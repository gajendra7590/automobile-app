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

    $(".quotationFormAjaxSubmit").validate({
        // errorElement: 'span',
        // errorClass: 'text-muted error',
        rules: {
            branch_id: {
                required: true,
            },
            customer_name: {
                required: true,
            },
            customer_guardian_name: {
                required: true,
            },
            customer_address_line: {
                required: true,
            },
            customer_state: {
                required: true,
            },
            customer_district: {
                required: true,
            },
            customer_city: {
                required: true,
            },
            customer_zipcode: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6,
            },
            customer_mobile_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            customer_mobile_number_alt: {
                required: false,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            customer_email_address: {
                required: false,
                email: true,
            },
            bike_brand: {
                required: true,
                minlength: 17,
            },
            bike_model: {
                required: true,
            },
            bike_model_variant: {
                required: true,
            },
            bike_color: {
                required: true,
            },
            ex_showroom_price: {
                required: true,
                number: true,
                min: 1,
            },
            registration_amount: {
                required: true,
                number: true,
                min: 1,
            },
            insurance_amount: {
                required: true,
                number: true,
                min: 1,
            },
            hypothecation_amount: {
                required: true,
                number: true,
                min: 1,
            },
            accessories_amount: {
                required: true,
                number: true,
                min: 1,
            },
            other_charges: {
                required: true,
                number: true,
            },
            purchase_visit_date: {
                required: true,
                date: true,
            },
            purchase_est_date: {
                required: true,
                date: true,
            },
        },
        messages: {
            branch_id: {
                required: "The branch field is required.",
            },
            customer_name: {
                required: "The customer name field is required.",
            },
            customer_guardian_name: {
                required: "The  guardian name field is required.",
            },
            customer_address_line: {
                required: "The address line is required.",
            },
            customer_state: {
                required: "The state field is required.",
            },
            customer_district: {
                required: "The district field is required.",
            },
            customer_city: {
                required: "The city field is required.",
            },
            customer_zipcode: {
                required: "The zipcode field is required.",
                digits: "the zipcode should valid 6 digits",
                minlength: "the zipcode should valid 6 digits",
                maxlength: "the zipcode should valid 6 digits",
            },
            customer_mobile_number: {
                required: "The phone field is required.",
                digits: "the phone should valid 10 digits",
                minlength: "the phone should valid 10 digits",
                maxlength: "the phone should valid 10 digits",
            },
            customer_mobile_number_alt: {
                digits: "the alt phone should valid 10 digits",
                minlength: "the alt phone should valid 10 digits",
                maxlength: "the alt phone should valid 10 digits",
            },
            customer_email_address: {
                email: "Customer email should valid email.",
            },
            bike_brand: {
                required: "The brand field is required.",
            },
            bike_model: {
                required: "The model field is required.",
            },
            bike_model_variant: {
                required: "The modal variant field is required.",
            },
            bike_color: {
                required: "The color field is required.",
            },
            ex_showroom_price: {
                required: "The exshowroom amount field is required.",
                number: "The exshowroom amount invalid price.",
                min: "The exshowroom amount invalid price.",
            },
            registration_amount: {
                required: "The registration amount field is required.",
                number: "The registration amount invalid price.",
                min: "The registration amount invalid price.",
            },
            insurance_amount: {
                required: "The insurance amount field is required.",
                number: "The insurance amount invalid price.",
                min: "The insurance amount invalid price.",
            },
            hypothecation_amount: {
                required: "The hyp amount field is required.",
                number: "The hyp amount invalid price.",
                min: "The hyp amount invalid price.",
            },
            accessories_amount: {
                required: "The accessories amount field is required.",
                number: "The accessories amount invalid price.",
                min: "The accessories amount invalid price.",
            },
            other_charges: {
                required: "The other charges field is required.",
                number: "The other charges invalid price.",
                min: "The other charges invalid price.",
            },
            purchase_visit_date: {
                required: "The purchase visit date field is required.",
                date: "The purchase visit date should valid date",
            },
            purchase_est_date: {
                required: "The purchase Est Date field is required.",
                date: "The purchase est date should valid date",
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            let formObj = $(".quotationFormAjaxSubmit");
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
