@extends('layouts.settings.settings_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('settings')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') System Announcements @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">System Announcements</h4>
                            <p class="card-title-desc">
                                A district head is responsible for managing and overseeing the operations,
                                administration, and development activities within a district
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementsModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Announcement</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Type</th>
                                    <th>Title & Description</th>
                                    <th>Announce By</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body content goes here -->
                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="createAnnouncementsModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="createAnnouncementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Announcements</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('system.announcement.create') }}" id="createValidateForm">
                        @csrf
                        <div class="col-md-6 form-group mb-3">
                            <label class="fw-bold h6">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="" selected disabled>Select Types</option>
                                <option value="New Features">New Features</option>
                                <option value="Enhancements">Enhancements</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Notification">Notification</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Title</label>
                            <input type="text" name="title" class="form-control" minlength="0" maxlength="100" placeholder="Title" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Description</label>
                            <textarea name="description" class="form-control" minlength="0" maxlength="800"
                                      rows="9" placeholder="Enter Description" style="resize: none;" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date Start</label>
                                    <input type="date" id="date_start_create" name="date_start" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date End</label>
                                    <input type="date" id="date_end_create" name="date_end" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary closeCreateModal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateAnnouncementsModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="updateAnnouncementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Announcements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('system.announcement.update') }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" name="item_id" id="update_item_id">
                        <div class="col-md-6 form-group mb-3">
                            <label class="fw-bold h6">Type</label>
                            <select name="type" class="form-select" id="update_type" required>
                                <option value="" selected disabled>Select Types</option>
                                <option value="New Features">New Features</option>
                                <option value="Enhancements">Enhancements</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Notification">Notification</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Title</label>
                            <input type="text" name="title" id="update_title" class="form-control" minlength="0" maxlength="100" placeholder="Title" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Description</label>
                            <textarea name="description" class="form-control" minlength="0" maxlength="800" id="update_description"
                                      rows="9" placeholder="Enter Description" style="resize: none;" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date Start</label>
                                    <input type="date" id="update_date_start" name="date_start" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date End</label>
                                    <input type="date" id="update_date_end" name="date_end" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format

            $('#date_start_create').attr('min', today); // Set the minimum date to today for date_start
            $('#date_end_create').attr('min', today); // Set the minimum date to today for date_end

            $('#date_start_create').on('change', function() {
                var startDate = $(this).val();
                $('#date_end_create').attr('min', startDate).val(''); // Set min date for date_end and clear its value
            });

            $('#date_end_create').on('change', function() {
                var endDate = $(this).val();
                var startDate = $('#date_start_create').val();
                if (startDate && endDate < startDate) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "End date must be the same or after the start date!"
                    });
                    $(this).val(''); // Clear the end date input if invalid
                }
            });
        });

        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('system.announcement.data') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];

            const columns = [
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span">' + data + '</span>'; // Display user's ID
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'type',
                    name: 'type',
                    render: function(data, type, row, meta) {
                        return '<span">' + data + '</span>'; // Display user's ID
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<span class="fw-bold text-primary h6">${row.title}</span><br>
                                <span class="text-muted">${row.description}</span>`; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'employee_id',
                    name: 'employee_id',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'date_start',
                    name: 'date_start',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });
                    }

                },

                {
                    data: 'date_end',
                    name: 'date_end',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });
                    }

                },
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="#" class="text-warning editBtn me-2" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit ">
                                <i class="fas fa-pencil-alt me-2"></i>
                            </a>

                            <a href="#" class="text-danger deleteBtn me-2" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete ">
                                <i class="fas fa-trash-alt me-2"></i>
                            </a>`;
                    },
                    orderable: false,
                    searchable: false,
                }
            ];
            dataTable.initialize(url, columns);

            $('#createValidateForm').validate({
                rules: {
                    area: { required: true, },
                    area_supervisor: { required: true, },
                    district_id: { required: true, },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    var hasRows = FetchingDatatableBody.children('tr').length > 0;
                    if (hasRows) {
                        Swal.fire({
                            title: 'Confirmation',
                            text: 'Are you sure you want to save this?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: "#007BFF",
                            cancelButtonColor: "#6C757D",
                            confirmButtonText: "Yes, Save it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const currentPage = dataTable.table.page();
                                $.ajax({
                                    url: form.action,
                                    type: form.method,
                                    data: $(form).serialize(),
                                    success: function(response) {
                                        closeCreateModal();
                                        Swal.fire({
                                            title: 'Successfully Added!',
                                            text: 'Announcement is successfully added!',
                                            icon: 'success',
                                            showCancelButton: false,
                                            showConfirmButton: true,
                                            confirmButtonText: 'OK',
                                            preConfirm: () => {
                                                return new Promise(( resolve
                                                ) => {
                                                    Swal.fire({
                                                        title: 'Please Wait...',
                                                        allowOutsideClick: false,
                                                        allowEscapeKey: false,
                                                        showConfirmButton: false,
                                                        showCancelButton: false,
                                                        didOpen: () => {
                                                            Swal.showLoading();
                                                            // here the reload of datatable
                                                            dataTable.table.ajax.reload( () =>
                                                            {
                                                                Swal.close();
                                                                $(form)[0].reset();
                                                                dataTable.table.page(currentPage).draw( false );
                                                            },
                                                            false );
                                                        }
                                                    })
                                                });
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        var errorMessage =
                                            'An error occurred. Please try again later.';
                                        if (xhr.responseJSON && xhr.responseJSON
                                            .error) {
                                            errorMessage = xhr.responseJSON.error;
                                        }
                                        Swal.fire({
                                            title: 'Error!',
                                            text: errorMessage,
                                            icon: 'error',
                                        });
                                    }
                                })
                            }
                        })
                    } else {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Empty Record!',
                            text: 'Table is empty, add row to proceed!',
                        });
                    }
                }
            });

            // Update Function
                $('#updateValidateForm').validate({
                    rules: {
                        area: { required: true, },
                        area_supervisor: { required: true, },
                        district_id: { required: true, },
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    },
                    submitHandler: function(form) {
                        var hasRows = FetchingDatatableBody.children('tr').length > 0;
                        if (hasRows) {
                            Swal.fire({
                                title: 'Confirmation',
                                text: 'Are you sure you want to save this?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: "#28A745",
                                cancelButtonColor: "#6C757D",
                                confirmButtonText: "Yes, Save it!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const currentPage = dataTable.table.page();
                                    $.ajax({
                                        url: form.action,
                                        type: form.method,
                                        data: $(form).serialize(),
                                        success: function(response) {
                                            closeUpdateModal();
                                            Swal.fire({
                                                title: 'Successfully Updated!',
                                                text: 'Announcement is successfully Updated!',
                                                icon: 'success',
                                                showCancelButton: false,
                                                showConfirmButton: true,
                                                confirmButtonText: 'OK',
                                                preConfirm: () => {
                                                    return new Promise(( resolve
                                                    ) => {
                                                        Swal.fire({
                                                            title: 'Please Wait...',
                                                            allowOutsideClick: false,
                                                            allowEscapeKey: false,
                                                            showConfirmButton: false,
                                                            showCancelButton: false,
                                                            didOpen: () => {
                                                                Swal.showLoading();
                                                                // here the reload of datatable
                                                                dataTable.table.ajax.reload( () =>
                                                                {
                                                                    Swal.close();
                                                                    $(form)[0].reset();
                                                                    dataTable.table.page(currentPage).draw( false );
                                                                },
                                                                false );
                                                            }
                                                        })
                                                    });
                                                }
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            var errorMessage =
                                                'An error occurred. Please try again later.';
                                            if (xhr.responseJSON && xhr.responseJSON
                                                .error) {
                                                errorMessage = xhr.responseJSON.error;
                                            }
                                            Swal.fire({
                                                title: 'Error!',
                                                text: errorMessage,
                                                icon: 'error',
                                            });
                                        }
                                    })
                                }
                            })
                        } else {

                            Swal.fire({
                                icon: 'warning',
                                title: 'Empty Record!',
                                text: 'Table is empty, add row to proceed!',
                            });
                        }
                    }
                });

                $('#FetchingDatatable').on('click', '.editBtn', function(e) {
                    e.preventDefault();
                    var itemID = $(this).data('id');

                    var url = "/system/announcement/get/" + itemID;

                    $.get(url, function(data) {
                        $('#update_item_id').val(data.id);
                        $('#update_type').val(data.type).trigger('change');
                        $('#update_title').val(data.title);
                        $('#update_description').val(data.description);
                        $('#update_date_start').val(data.date_start);
                        $('#update_date_end').val(data.date_end);

                        $('#updateAnnouncementsModal').modal('show');
                    });
                });

                function closeUpdateModal() {
                    $('#updateAnnouncementsModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                    // $('#usersGroupPageTable').addClass('d-none');
                }

                var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
                $('#update_date_start').attr('min', today); // Set the minimum date to today for date_start
                $('#update_date_end').attr('min', today); // Set the minimum date to today for date_end

                $('#update_date_start').on('change', function() {
                    var startDate = $(this).val();
                    $('#update_date_end').attr('min', startDate).val(''); // Set min date for date_end and clear its value
                });

                $('#update_date_end').on('change', function() {
                    var endDate = $(this).val();
                    var startDate = $('#update_date_start').val();
                    if (startDate && endDate < startDate) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "End date must be the same or after the start date!"
                        });
                        $(this).val(''); // Clear the end date input if invalid
                    }
                });
            // Update Function

            function closeCreateModal() {
                $('#createAnnouncementsModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#FetchingDatatable').addClass('d-none');
            }

        });
    </script>






@endsection
@section('script')

@endsection
