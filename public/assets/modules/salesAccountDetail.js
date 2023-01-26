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
});
