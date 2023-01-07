$(document).ready(function () {

    $('#purchase').change(function() {
        $this = $(this);
        let id = $(this).val();
        let URL = $this.data('getpurchases') + '/'+ id;
        CRUD.AJAXDATA(URL, 'GET').then(function(res) {
            let bike_model = res.data.bike_model
            let bike_color = res.data.bike_model_color
            $("[name=bike_brand]").val(res.data.bike_brand)
            URL = $this.data('getmodels') + '/' + res.data.bike_brand;
            CRUD.AJAXDATA(URL, 'GET').then(function(res) {
                $('select[name="bike_model"]').html(res.data)
                $("[name=bike_model]").val(bike_model)
                URL = $this.data('getcolors') + "/" + bike_model;
                CRUD.AJAXDATA(URL, 'GET').then(function(res) {
                    $('select[name="bike_color"]').html(res.data)
                    $("[name=bike_color]").val(bike_color)
                });
            });
            $("[name=bike_branch]").val(res.data.bike_branch)
            $("[name=bike_dealer]").val(res.data.bike_dealer)
            $("[name=bike_type]").val(res.data.bike_type)
            $("[name=bike_fuel_type]").val(res.data.bike_fuel_type)
            $("[name=break_type]").val(res.data.break_type)
            $("[name=wheel_type]").val(res.data.wheel_type)
            $("[name=dc_number]").val(res.data.dc_number)
            $("[name=dc_date]").val(res.data.dc_date)
            $("[name=vin_number]").val(res.data.vin_number)
            $("[name=vin_physical_status]").val(res.data.vin_physical_status)
            $("[name=sku]").val(res.data.sku)
            $("[name=sku_description]").val(res.data.sku_description)
            $("[name=hsn_number]").val(res.data.hsn_number)
            $("[name=engine_number]").val(res.data.engine_number)
            $("[name=key_number]").val(res.data.key_number)
            $("[name=service_book_number]").val(res.data.service_book_number)
            $("[name=tyre_brand_name]").val(res.data.tyre_brand_name)
            $("[name=tyre_front_number]").val(res.data.tyre_front_number)
            $("[name=tyre_rear_number]").val(res.data.tyre_rear_number)
            $("[name=tyre_brand_id]").val(res.data.tyre_brand_id)
            $("[name=battery_brand_id]").val(res.data.battery_brand_id)
            $("[name=battery_brand]").val(res.data.battery_brand)
            $("[name=battery_number]").val(res.data.battery_number)
            $("[name=purchase_invoice_amount]").val(res.data.purchase_invoice_amount)
            $("[name=purchase_invoice_number]").val(res.data.purchase_invoice_number)
            $("[name=purchase_invoice_date]").val(res.data.purchase_invoice_date)
            $("[name=bike_description]").val(res.data.bike_description)
        });
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
                    data: "branch.branch_name",
                    name: "branch.branch_name",
                },
                {
                    data: "dealer.company_name",
                    name: "dealer.company_name",
                },
                {
                    data: "customer",
                    name: "customer",
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
