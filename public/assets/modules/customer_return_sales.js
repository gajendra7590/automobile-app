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
                    data: "branch_name",
                    name: "branch_name",
                },
                {
                    data: "dealer_name",
                    name: "dealer_name",
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
                    data: "action",
                    name: "action",
                },
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [-1, 4],
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

    $(document).on("click", "#createRefundButton", function (e) {
        e.preventDefault();
        $(".refund-table").hide();
        $(".refund-form").show();
    });

    $(document).on("click", "#createRefundButtonBack", function (e) {
        e.preventDefault();
        $(".refund-table").show();
        $(".refund-form").hide();
    });

    $(document).on(
        "keyup keypress change",
        'input[name="amount_refund"]',
        function (e) {
            let amount = parseFloat($(this).val());
            let total_refund_due = parseFloat($("#total_refund_due").val());
            if (amount > total_refund_due) {
                toastr.error("PLEASE ENTER VALID REFUNDABLE AMOUNT", "ERROR");
                $(this).val(0);
            }
        }
    );
});
