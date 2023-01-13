$(document).ready(function () {
    // $(".select2").select2();

    // let

    $(".select2").select2({
        ajax: {
            delay: 250,
            url: "https://api.github.com/search/repositories",
            dataType: "json",
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        },
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
                    data: "section_id",
                    name: "section_id",
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
            order: [[0, "asc"]],
        });
    }
    mainDataTable();
});
