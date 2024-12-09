@extends('layouts.atm_monitoring_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Area @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Area</h4>
                            <p class="card-title-desc">
                                A district head is responsible for managing and overseeing the operations,
                                administration, and development activities within a district
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAreaModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Area</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Area No.</th>
                                    <th>Area Name</th>
                                    <th>District</th>
                                    {{-- <th>Email</th> --}}
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

    <div class="modal fade" id="createAreaModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="createAreaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Area</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.area.create') }}" id="createValidateForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Area</label>
                            <input type="text" name="area_no" class="form-control" id="area_no"
                                placeholder="Enter Area No" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Area Supervisor</label>
                            <input type="text" name="area_supervisor" class="form-control" id="area_supervisor"
                                placeholder="Enter Area Supervisor" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">District Manager</label>
                            <select name="district_id" id="district_id" class="form-select select2" required>
                                <option value="" selected disabled>Select District Manager</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{  $district->district_number .' / '. $district->district_name }}</option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="updateAreaModal" data-bs-backdrop="static" tabindex="-1" role="dialog"update_district_id
        aria-labelledby="updateAreaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Area</h5>
                    <button type="button" class="btn-close closeUpdateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.area.update') }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" id="item_id" name="item_id">

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Area</label>
                            <input type="text" name="area_no" class="form-control" id="update_area_no"
                                placeholder="Enter Area" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Area Supervisor</label>
                            <input type="text" name="area_supervisor" class="form-control" id="update_area_supervisor"
                                placeholder="Enter Area Supervisor" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">District Manager</label>
                            <select name="district_id" id="update_district_id" class="form-select select2" required>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{  $district->district_number .' / '. $district->district_name }}</option>
                                @endforeach
                            </select>
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
            $('#district_id').select2({ dropdownParent: $('#createAreaModal') });
            $('#update_district_id').select2({ dropdownParent: $('#updateAreaModal') });

            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('settings.area.data') !!}';
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
                    data: 'area_no',
                    name: 'area_no',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'area_supervisor',
                    name: 'area_supervisor',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'district_id',
                    name: 'district.district_name',
                    render: function(data, type, row, meta) {
                        return row.district ? '<span>' + row.district.district_name + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                // {
                //     data: 'email',
                //     name: 'email',
                //     render: function(data, type, row, meta) {
                //         return '<span">' + data + '</span>';
                //     },
                //     orderable: true,
                //     searchable: true,
                // },
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
                                            text: 'Area is successfully added!',
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
                                            text: 'Area is successfully Updated!',
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

                var url = "/settings/area/get/" + itemID;

                $.get(url, function(data) {
                    $('#item_id').val(data.id);
                    $('#update_area_no').val(data.area_no);
                    $('#update_area_supervisor').val(data.area_supervisor);
                    $('#update_district_id').val(data.district_id);
                    $('#update_status').val(data.status);
                    $('#updateAreaModal').modal('show');
                });
            });

            function closeCreateModal() {
                $('#createAreaModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#FetchingDatatable').addClass('d-none');
            }

            function closeUpdateModal() {
                $('#updateAreaModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#usersGroupPageTable').addClass('d-none');
            }



        });
    </script>






@endsection
@section('script')

@endsection
