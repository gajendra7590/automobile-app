$(document).ready(function () {
    $(".loadAjaxAccountTab").click(function (e) {
        e.preventDefault();
        $(".loadAjaxAccountTab").parent("li").removeClass("active");
        $(this).parent("li").addClass("active");
        let URL = $(this).attr("href");
        loadTabData(URL);
    });

    function loadTabData(URL) {
        $("#tabContainer").html(
            '<div class="dumyContent class="box box-info"">LOADING...</div>'
        );
        CRUD.AJAXDATA(URL, "GET", [])
            .then(function (res) {
                if (typeof res.status != "undefined" && res.status == true) {
                    $("#tabContainer").html(res.html);
                }
            })
            .catch(function (e) {
                console.log(e);
            });
    }

    //OnLOAd
    let URL = $("#primaryTab").attr("href");
    loadTabData(URL);

    $(document).on(
        "keyup keypress",
        "input[name='deposite_amount']",
        function () {
            PaymentCalculate();
        }
    );

    function PaymentCalculate() {
        let deposite_amount = $('input[name="deposite_amount"]').val();
        let sales_total_amount = $('input[name="sales_total_amount"').val();

        let due_amount = sales_total_amount - deposite_amount;
        if (deposite_amount < 0) {
            toastr.error("Sorry! down payment Can not be an negative value.");
            $('input[name="deposite_amount"]').val("");
            $('input[name="due_amount"').val("");
            return false;
        }
        if (due_amount < 0) {
            toastr.error(
                "Sorry! down payment Can not be more then sales amount."
            );
            $('input[name="deposite_amount"]').val("");
            $('input[name="due_amount"').val("");
            return false;
        }
        $('input[name="due_amount"').val(due_amount);
    }

    $(document).on(
        "keyup keypress",
        "input[name='total_finance_amount'],input[name='processing_fees']",
        function () {
            let total_outstading = parseFloat(
                $("input[name='total_outstanding']").val()
            );
            let finance_amount = parseFloat(
                $("input[name='total_finance_amount']").val()
            );
            let processing_fees = parseFloat(
                $("input[name='processing_fees']").val()
            );
            finance_amount = !isNaN(finance_amount) ? finance_amount : 0;
            processing_fees = !isNaN(processing_fees) ? processing_fees : 0;

            if (finance_amount > total_outstading) {
                $("input[name='total_finance_amount']").val(0);
                $("input[name='processing_fees']").val(0);
                $("input[name='grand_finance_amount']").val(0);
                toastr.error(
                    "ERROR! Finance amount can not be exceed the outstanding amount."
                );
                return false;
            } else {
                let grandTotal = parseFloat(finance_amount + processing_fees);
                $("input[name='grand_finance_amount']").val(grandTotal);
            }
        }
    );

    $(document).on("keyup", "input[name='no_of_emis']", function () {
        let val = $(this).val();
        val = parseInt(val);
        if (val <= 0) {
            toastr.error("ERROR! Please enter valid EMI Number.");
            $(this).val("");
            return false;
        }
        $(this).val(val);
    });

    $(document).on(
        "keyup",
        "input[name='rate_of_interest'],input[name='total_finance_amount'],input[name='processing_fees']",
        function () {
            let val = $(this).val();
            val = parseInt(val);
            if (val <= 0) {
                toastr.error("ERROR! Please enter valid value.");
                $(this).val("");
                return false;
            }
        }
    );
});
