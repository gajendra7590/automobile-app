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
                    data: "branchName",
                    name: "branchName",
                },
                // {
                //     data: "agentName",
                //     name: "agentName",
                // },
                {
                    data: "contact_name",
                    name: "contact_name",
                },
                {
                    data: "total_amount",
                    name: "total_amount",
                },
                // {
                //     data: "payment_amount",
                //     name: "payment_amount",
                // },
                // {
                //     data: "payment_date",
                //     name: "payment_date",
                // },
                // {
                //     data: "rc_number",
                //     name: "rc_number",
                // },
                // {
                //     data: "rc_status",
                //     name: "rc_status",
                // },
                {
                    data: "bike_number",
                    name: "bike_number",
                },
                {
                    data: "submit_date",
                    name: "submit_date",
                },
                {
                    data: "recieved_date",
                    name: "recieved_date",
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
    $(document).on("change", 'select[name="sale_id"]', function () {
        let selected_id = $(this).val();
        let url = $(this).data("url");
        let ele = $(this).data("ele");
        if (selected_id > 0) {
            CRUD.AJAXDATA(url + "?id=" + selected_id, "GET").then(function (
                res
            ) {
                if (typeof res.status != "undefined" && res.status == true) {
                    $("#" + ele).html(res.data);
                    $("#rtoAjaxContainer").removeClass("hideElement");
                }
            });
        } else {
            $("#rtoAjaxContainer").addClass("hideElement");
        }
    });

    $(document).on("change", ".onChangeSelect", function () {
        let rto_rate = $(this).val();
        if (rto_rate == "") {
            $('input[name="gst_rto_rate_percentage"]').val(0);
            $(".onChangeInput").attr("readonly", true);
        } else {
            $(".onChangeInput").attr("readonly", false);
            CalculatTotal();
        }
    });

    $(document).on("keyup keypress", ".onChangeInput", function () {
        CalculatTotal();
    });

    //onChangeSelect

    function CalculatTotal() {
        let rto_rate = $("select[name='gst_rto_rate_id'] option:selected").data(
            "percentage"
        );
        $('input[name="gst_rto_rate_percentage"]').val(rto_rate);
        let ex_showroom_amount = $('input[name="ex_showroom_amount"]').val();
        let Tax = (rto_rate / 100) * parseFloat(ex_showroom_amount);
        $('input[name="tax_amount"]').val(Tax);
        let tax_amount = $('input[name="tax_amount"]').val();
        let hyp_amount = $('input[name="hyp_amount"]').val();
        let tr_amount = $('input[name="tr_amount"]').val();
        let fees = $('input[name="fees"]').val();
        let total =
            // parseFloat(tax_amount) +
            parseFloat(hyp_amount) + parseFloat(tr_amount) + parseFloat(fees);
        $('input[name="total_amount"]').val(total);
    }
});
