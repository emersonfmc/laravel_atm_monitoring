class ServerSideDataTable {
    constructor(tableSelector) {
        this.tableSelector = tableSelector;
        this.table = null;
    }

    initialize(url, columns, options = {}) {
        this.table = $(this.tableSelector).DataTable({
            serverSide: true,
            processing: true,
            ajax: url,
            columns: columns,
            ordering: options.ordering !== undefined ? options.ordering : true,  // Default to true
            order: options.order || [[0, 'desc']], // Default order if not provided
            pageLength: options.pageLength !== undefined ? options.pageLength : 20, // Default to 20 rows per page
            lengthMenu: options.lengthMenu !== undefined ? options.lengthMenu : [20, 25, 50, 100, 250], // Default options
            searching: options.searching !== undefined ? options.searching : true, // Make searching optional (default enabled)
            lengthChange: options.lengthChange !== undefined ? options.lengthChange : true, // Make "Show Entries" dropdown optional (default enabled)
            drawCallback: function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                $(".pension_number_mask").inputmask("99-9999999-99");
            },
            language: {
                searchPlaceholder: "Enter to search ...",
                paginate: {
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>",
                },
                processing: function () {
                    Swal.fire({
                        title: 'Loading Please Wait',
                        html: "<div class='d-flex justify-content-center align-items-center' style='height:60px;'><div class='loader'></div></div>",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        width: "350px" // Adjust width as needed
                    });
                    return "<span class='fw-bold h6'>Please wait for a moment ....</span>";

                },
            },
        });

        $(this.tableSelector)
            .on("length.dt", function (e, settings, len) {
                Swal.fire({
                    title: 'Loading Please Wait',
                    html: "<div class='d-flex justify-content-center align-items-center' style='height:60px;'><div class='loader'></div></div>",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    width: "350px" // Adjust width as needed
                });
            })
            .on("draw.dt", function () {
                Swal.close();
            });
    }

    updateSearchQuery(query) {
        if (this.table) {
            this.table.search(query).draw();
        }
    }
}
