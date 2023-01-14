$(document).ready(function () {
    // $(".select2").select2();

    $('select[name="document_section_type"]').change(function () {
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
                    'select[name="document_section_type"] option:selected'
                ).val();
                if (selVal == "") {
                    return false;
                }
            },
            data: function (params) {
                var query = {
                    search: params.term,
                    type_id: $(
                        'select[name="document_section_type"] option:selected'
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
                    data: "section_type",
                    name: "section_type",
                },
                {
                    data: "file_description",
                    name: "file_description",
                },
                {
                    data: "file_name",
                    name: "file_name",
                },
                {
                    data: "file_extention",
                    name: "file_extention",
                },
                {
                    data: "file_size",
                    name: "file_size",
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
            order: [[0, "desc"]],
        });
    }
    mainDataTable();
});
