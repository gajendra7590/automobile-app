$(document).ready(function () {
    $('select[name="section_type"]').change(function () {
        let val = $(this).val();
        $(".select2").val(null).trigger("change");
        $("#nextContainer").addClass("hideElement");
        if (val == "") {
            $(".select2").prop("disabled", true);
        } else {
            $(".select2").prop("disabled", false);
        }
    });

    $(".select2").select2({
        ajax: {
            delay: 1000,
            allowClear: true,
            minimumInputLength: 1,
            dataType: "json",
            url: function () {
                return $("#select2SearchURL").val();
            },
            beforeSend: function () {
                let selVal = $(
                    'select[name="section_type"] option:selected'
                ).val();
                if (selVal == "") {
                    return false;
                }
            },
            data: function (params) {
                var query = {
                    search: params.term,
                    section_type: $(
                        'select[name="section_type"] option:selected'
                    ).val(),
                };
                return query;
            },
        },
    });

    $(".select2").on("select2:selecting", function (e) {
        let data = e.params.args.data;
        if (data.id > 0) {
            $("#nextContainer").removeClass("hideElement");
        }
    });

    $(document).on("click", ".getDetailAjaxModal", function (e) {
        e.preventDefault();
        let URL = $(this).data("url");
        let document_type = $(
            'select[name="section_type"] option:selected'
        ).val();
        let model_id = $(
            'select[name="model_section_id"] option:selected'
        ).val();
        if (
            document_type == "" ||
            document_type == null ||
            document_type == "0"
        ) {
            toastr.error("PLEASE SELECT SECTION TYPE..");
            return false;
        }
        if (model_id == "" || model_id == undefined || model_id == "0") {
            toastr.error("PLEASE SELECT SECTION MODEL ITEMS..");
            return false;
        }

        URL = URL + "?document_type=" + document_type + "&model_id=" + model_id;
        $("#ajaxModalSize").addClass("modal-lg");
        $(".ajaxModalTitle").html("UPDATE INFO");
        $(".ajaxModalBody").attr("tabindex", 1);

        $(".ajaxModalBody").html(
            `<div style="text-align: center;min-height: 174px;padding: 57px;"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true" style="color: #ea6d09;"></i></div>`
        );
        $("#ajaxModalCommon").modal("show");
        CRUD.AJAXMODAL(URL, "GET", null).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                $(".ajaxModalBody").html(result.data);
            } else {
                toastr.error("Something went wrong");
                // to do
            }
        });

        //AJAX
    });

    /**
     * Function for update single input common submit
     */
    $(document).on("click", ".updateFormInfo", function (e) {
        e.preventDefault();
        let field_name = $(this).data("field_name");
        let input_type = $(this).data("input_type");
        let field_value = "";
        if (input_type == "select") {
            field_value = $(
                "select[name='" + field_name + "'] option:selected"
            ).val();
        } else {
            field_value = $("input[name='" + field_name + "']").val();
        }
        let URL = $("input[name='url']").val();
        let id = $("input[name='id']").val();
        let type = $("input[name='type']").val();

        let fd = new FormData();
        fd.append("id", id);
        fd.append("type", type);
        fd.append(field_name, field_value);

        CRUD.AJAXSUBMIT(URL, "POST", fd).then(function (result) {
            if (typeof result.status != "undefined" && result.status == true) {
                //$("#ajaxModalCommon").modal("hide");
            } else {
                //toastr.error("Something went wrong");
                // to do
            }
        });
    });
});
