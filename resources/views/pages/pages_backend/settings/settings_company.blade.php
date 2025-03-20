@extends('layouts.settings_monitoring.settings_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('settings')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Company @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Company</h4>
                            <p class="card-title-desc">
                                A list Company
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCompanyModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Company</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Company Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="createCompanyModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createCompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.company.create') }}" id="createValidateForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Company Name</label>
                            <input type="text" name="name" class="form-control" id="company_name"
                                placeholder="Enter Company Name" minlength="0" maxlength="50" required>
                        </div>


                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Company Logo</label>
                            <input type="file" name="company_logo" class="form-control" id="company_logo" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Company Image</label>
                            <input type="file" name="company_image" class="form-control" id="company_image" required>
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

    <div class="modal fade" id="updateCompanyModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="updateCompanyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.company.update') }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" name="item_id" id="item_id">

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Company Name</label>
                            <input type="text" name="name" class="form-control" id="update_company_name"
                                placeholder="Enter Pension Name" minlength="0" maxlength="50" required>
                        </div>


                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Status</label>
                            <select name="status" id="update_status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary closeUpdateModal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('settings.company.data') !!}';
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
                    data: 'company_name',
                    name: 'company_name',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        if (data === 'Active') {
                            return '<span class="badge bg-primary">Active</span>';
                        } else if (data === 'Inactive') {
                            return '<span class="badge bg-danger">Inactive</span>';
                        } else {
                            return '<span>No Status</span>';
                        }
                    },
                    orderable: true,
                    searchable: true,
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
                    types: { required: true, },
                    pension_name: { required: true, },
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
                                            text: 'Company is successfully added!',
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

            $('#updateValidateForm').validate({
                rules: {
                    types: { required: true, },
                    pension_name: { required: true, },
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
                                            text: 'Company is successfully Updated!',
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

                var url = "/settings/company/get/" + itemID;

                $.get(url, function(data) {
                    $('#item_id').val(data.id);
                    $('#update_company_name').val(data.company_name);
                    $('#update_status').val(data.status);

                    $('#updateCompanyModal').modal('show');
                });
            });

            function closeCreateModal() {
                $('#createCompanyModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#FetchingDatatable').addClass('d-none');
            }

            function closeUpdateModal() {
                $('#updateCompanyModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#usersGroupPageTable').addClass('d-none');
            }
        });
    </script>






@endsection
@section('script')

@endsection
