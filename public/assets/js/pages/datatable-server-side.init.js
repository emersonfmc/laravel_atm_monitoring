class ServerSideDataTable {
    constructor(tableSelector) {
        this.tableSelector = tableSelector;
        this.table = null;
    }

    initialize(url, columns) {
        this.table = $(this.tableSelector).DataTable({
            serverSide: true,
            processing: true,
            ajax: url,
            columns: columns,
            pageLength: 20, // Default to 10 rows per page
            lengthMenu: [20, 25, 50, 100], // Allow user to select number of rows
            ordering: true,
            drawCallback: function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            language: {
                searchPlaceholder: "Enter to search ...",
                paginate: {
                    previous: "<i class='fas fa-chevron-left text-dark'></i>",
                    next: "<i class='fas fa-chevron-right text-dark'></i>",
                },
                processing: function () {
                    Swal.fire({
                        title: 'Loading Please Wait',
                        html: "<div class='d-flex justify-content-center align-items-center mt-2 mb-2' style='height:60px;'><div class='loader'></div></div>",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        width: "350px" // Adjust width as needed
                    });
                    return "Please wait for a moment ....";
                },
            },


        });

        $(this.tableSelector)
            .on("length.dt", function (e, settings, len) {
                // Show a custom processing message when changing the number of entries
                Swal.fire({
                    title: 'Loading Please Wait',
                    html: "<div class='d-flex justify-content-center align-items-center' style='height:60px;'><div class='loader'></div></div>",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    width: "350px" // Adjust width as needed
                });
                // Swal.fire({
                //     title: "Please Wait...",
                //     text: "Please wait for a moment",
                //     allowEscapeKey: false,
                //     allowOutsideClick: false,
                //     didOpen: () => {
                //         Swal.showLoading();
                //     },
                // });
            })
            .on("draw.dt", function () {
                Swal.close();
            });
        // $(this.tableSelector + "_wrapper .dataTables_filter").hide();
    }

    updateSearchQuery(query) {
        if (this.table) {
            this.table.search(query).draw();
        }
    }
}
