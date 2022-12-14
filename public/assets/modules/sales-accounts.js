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
    //Document Ready Close Here
});
