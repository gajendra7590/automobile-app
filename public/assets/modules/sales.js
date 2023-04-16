$(document).ready(function () {
    $("#purchase").change(function () {
        $this = $(this);
        let id = $(this).val();
        if (id > 0) {
            $("#ajaxLoadContainer").removeClass("hideElement");
            let q = $('input[name="quotation_id"]').val();
            let URL = $this.data("ajax_load") + "?id=" + id + "&q=" + q;
            CRUD.AJAXDATA(URL, "GET").then(function (res) {
                if (typeof res.status != "undefined" && res.status == true) {
                    $("#ajaxLoadContainer").html(res.data);
                }
            });
        } else {
            $("#ajaxLoadContainer").addClass("hideElement");
        }
    });

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
                    data: "bike_detail",
                    name: "bike_detail",
                },
                {
                    data: "purchase.vin_number",
                    name: "purchase.vin_number",
                },
                {
                    data: "total_amount",
                    name: "total_amount",
                },
                {
                    data: "sale_date",
                    name: "sale_date",
                },
                {
                    data: "status",
                    name: "status",
                },
                {
                    data: "broker_status",
                    name: "broker_status",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [-1, -2, -3, 3,  4],
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

    $(document).on("change", 'select[name="payment_type"]', function () {
        let val = $(this).val();
        if (val == "1") {
            $('select[name="hyp_financer"]').attr("disabled", "disabled");
            $('input[name="hyp_financer_description"]').val("");
            $("#financeDetailSection").hide();
        } else {
            $('select[name="hyp_financer"]').removeAttr("disabled");
            $('input[name="hyp_financer_description"]').removeAttr("disabled");
            $("#financeDetailSection").show();
        }
    });

    $(document).on("change", "select[name='sale_id']", function () {
        let price = $("select[name='sale_id'] option:selected").attr(
            "data-total_amount"
        );
        let id = $("select[name='sale_id'] option:selected").val();
        $('input[name="sales_total_amount"]').val(price);
        $('input[name="due_amount"]').val(price);

        //It means de-selected
        if (id == "0") {
            $(".common_depended").addClass("hideElement");
            $(".ajaxFormSubmit").trigger("reset");
        }
        //Any option selected
        else {
            $("#deposite_section").removeClass("hideElement");
            // $("#finance_section").removeClass("hideElement");
        }
    });

    $(document).on("change", "select[name='due_payment_source']", function () {
        let id = $("select[name='due_payment_source'] option:selected").val();
        let due_date_today = $("input[name='due_date_today']").val();
        //It means de-selected
        if (id == "1") {
            $("#finance_section").addClass("hideElement");
            $("#emi_section").addClass("hideElement");

            //Reset
            $("input[name='financier_note']").val("");
            $("input[name='due_date']").attr("readonly", false);
        } else if (id == "2") {
            $("input[name='due_date']").attr("readonly", false);
            $("#finance_section").removeClass("hideElement");
            $("#emi_section").addClass("hideElement");
        } else if (id == "3") {
            $("#finance_section").removeClass("hideElement");
            $("#emi_section").removeClass("hideElement");
            //due_date_today
            $("input[name='due_date']")
                .val(due_date_today)
                .attr("readonly", true);
        }

        //REset Fields
        $("input[name='no_of_emis']").val("");
        $("input[name='rate_of_interest']").val("");
        $("input[name='processing_fees']").val("");
        $("input[name='finance_terms']").prop("selectedIndex", 0);
    });

    $(document).on(
        "keyup keypress",
        "input[name='deposite_amount']",
        function () {
            let deposite_amount = $(this).val();
            let sales_total_amount = $('input[name="sales_total_amount"').val();
            if (deposite_amount > 0) {
                let due_maount = sales_total_amount - deposite_amount;
                if (due_maount < 0) {
                    toastr.error(
                        "Sorry! Deposite amount can't be exceeded Total Sales Amount"
                    );
                    $('input[name="due_amount"').val("");
                }

                if (due_maount > 0) {
                    $("#due_section").removeClass("hideElement");
                }
                $('input[name="due_amount"').val(due_maount);
            } else {
                $('input[name="due_amount"').val(sales_total_amount);
            }
        }
    );

    $(".salesFormAjaxSubmit").validate({
        // errorElement: 'span',
        // errorClass: 'text-muted error',
        errorPlacement: function (error, element) {
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
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
            witness_person_name: {
                required: true,
            },
            witness_person_phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
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
                min: 0,
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
            sale_date: {
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
                required: "The mobile number field is required.",
                digits: "the mobile number should valid 10 digits",
                minlength: "the mobile number should valid 10 digits",
                maxlength: "the mobile number should valid 10 digits",
            },
            customer_mobile_number_alt: {
                digits: "the alt mobile number should valid 10 digits",
                minlength: "the alt mobile number should valid 10 digits",
                maxlength: "the alt mobile number should valid 10 digits",
            },
            customer_email_address: {
                email: "Customer email should valid email.",
            },
            witness_person_name: {
                required: "The reference person name field is required.",
            },
            witness_person_phone: {
                required: "The reference mobile number field is required.",
                digits: "the reference mobile number should valid 10 digits",
                minlength: "the reference mobile number should valid 10 digits",
                maxlength: "the reference mobile number should valid 10 digits",
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
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            let formObj = $(".salesFormAjaxSubmit");
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
