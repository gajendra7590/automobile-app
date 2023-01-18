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
                    data: "title",
                    name: "title",
                },
                {
                    data: "sales_total_amount",
                    name: "sales_total_amount",
                },
                {
                    data: "deposite_amount",
                    name: "deposite_amount",
                },
                {
                    data: "due_amount",
                    name: "due_amount",
                },
                {
                    data: "status",
                    name: "status",
                },
                {
                    data: "created_at",
                    name: "created_at",
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
            order: [[0, "asc"]],
        });
    }
    mainDataTable();

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
            $(".payLater").addClass("hideElement");
            $("input[name='pay_later_amount']").val(0);

            //Reset
            $("input[name='financier_note']").val("");
            $("input[name='due_date']").attr("readonly", false);
        } else if (id == "2") {
            $("input[name='due_date']").attr("readonly", false);
            $("#finance_section").removeClass("hideElement");
            $("#emi_section").addClass("hideElement");
            $(".payLater").removeClass("hideElement");
        } else if (id == "3") {
            $("#finance_section").removeClass("hideElement");
            $("#emi_section").removeClass("hideElement");
            $(".payLater").removeClass("hideElement");
            //due_date_today
            $("input[name='due_date']")
                .val(due_date_today)
                .attr("readonly", true);
        }
        PaymentCalculate();

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
            PaymentCalculate();
        }
    );

    $(document).on(
        "keyup keypress",
        "input[name='pay_later_amount']",
        function () {
            PaymentCalculate();
        }
    );

    function PaymentCalculate() {
        let deposite_amount = $('input[name="deposite_amount"]').val();
        let sales_total_amount = $('input[name="sales_total_amount"').val();
        let pay_type = $('select[name="due_payment_source"] :selected').val();
        let later_pay_amount = $("input[name='pay_later_amount']").val();
        if (deposite_amount > 0) {
            let due_amount =
                sales_total_amount - deposite_amount - later_pay_amount;
            if (due_amount < 0) {
                $('input[name="due_amount"').val(sales_total_amount);
                $('input[name="deposite_amount"]').val(0);
                $('input[name="pay_later_amount"]').val(0);
                toastr.error(
                    "Sorry! Deposite amount can't be exceeded Total Sales Amount"
                );
                return false;
            }

            // if (due_amount > 0) {
            //     $("#due_section").removeClass("hideElement");
            // }

            if ((pay_type == "2" || pay_type == "3") && due_amount > 0) {
                $(".payLater").removeClass("hideElement");
            } else {
                $(".payLater").addClass("hideElement");
                $('input[name="pay_later_amount"]').val(0);
            }

            $('input[name="due_amount"').val(due_amount);
        } else {
            $('input[name="due_amount"').val(sales_total_amount);
            $('input[name="pay_later_amount"]').val(0);
            $(".payLater").addClass("hideElement");
        }
    }
    //Document Ready Close Here
});
