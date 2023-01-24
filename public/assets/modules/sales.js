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
                    data: "total_amount",
                    name: "total_amount",
                },
                {
                    data: "created_at",
                    name: "created_at",
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
                    targets: [-1, -2, -3],
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
            $('input[name="hyp_financer_description"]').attr(
                "disabled",
                "disabled"
            );
        } else {
            $('select[name="hyp_financer"]').removeAttr("disabled");
            $('input[name="hyp_financer_description"]').removeAttr("disabled");
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
});
