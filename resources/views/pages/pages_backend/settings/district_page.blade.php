@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') District @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">District</h4>
                            <p class="card-title-desc">
                                A district head is responsible for managing and overseeing the operations,
                                administration, and development activities within a district
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDistrictModal">
                                <i class="fas fa-plus-circle me-1"></i> Create District</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="SettingsDistrictTable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>District Number</th>
                                    <th>District Name</th>
                                    <th>Email</th>
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

    <div class="modal fade" id="createDistrictModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="createDistrictLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="createDistrictLabel">Create District</h5>
                    <button type="button" class="btn-close closeDistrictModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.districts.create')  }}"  id="addDistrictForm">
                        @csrf

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">District Number</label>
                            <input type="text" name="district_number" id="district_number" class="form-control" placeholder="District Number" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">District Name</label>
                            <input type="text" name="district_name" id="district_name" class="form-control" placeholder="District Name" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary closeDistrictModal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateDistrictModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="createDistrictLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="createDistrictLabel">Update District</h5>
                    <button type="button" class="btn-close closeDistrictModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.districts.update')  }}"  id="updateDistrictForm">
                        @csrf
                        <input type="hidden" id="item_id" name="item_id">

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">District Number</label>
                            <input type="text" name="district_number" id="update_district_number" class="form-control" placeholder="District Number" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">District Name</label>
                            <input type="text" name="district_name" id="update_district_name" class="form-control" placeholder="District Name" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">Email</label>
                            <input type="text" name="email" id="update_email" class="form-control" placeholder="Email" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            var SettingsDistrictTableBody = $('#SettingsDistrictTable tbody');

            const dataTable = new ServerSideDataTable('#SettingsDistrictTable');
            var url = '{!! route('settings.district.data') !!}';
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
                    data: 'district_number',
                    name: 'district_number',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'district_name',
                    name: 'district_name',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'email',
                    name: 'email',
                    render: function(data, type, row, meta) {
                        return '<span">' + data + '</span>';
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
                            <a href="#" class="text-danger deleteBtn me-2" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                <i class="fas fa-trash-alt me-2"></i>
                            </a>

                            <a href="#" class="text-warning editBtn me-2" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="fas fa-pencil-alt me-2"></i>
                            </a>`;
                    },
                    orderable: false,
                    searchable: false,
                }
            ];
            dataTable.initialize(url, columns);

            function closeAddDistrictModal() {
                $('#createDistrictModal').modal('hide');
                $('#SettingsDistrictTable tbody').empty();
            }

            function closeUpdateDistrictModal() {
                $('#updateDistrictModal').modal('hide');
                $('#SettingsDistrictTable tbody').empty();
            }

            $('#addDistrictForm').validate({
                rules: {
                    district_number: { required: true },
                    district_name: { required: true },
                    email: { required: true }
                },
                messages: {
                    district_number: { required: 'Please Enter District Number' },
                    district_name: { required: 'Please Enter District Name' },
                    email: { required: 'Please Enter Email' }
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
                    var hasRows = SettingsDistrictTableBody.children('tr').length > 0;
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
                                        closeAddDistrictModal();
                                        Swal.fire({
                                            title: 'Successfully Added!',
                                            text: 'District is successfully Created!',
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

            $('#updateDistrictForm').validate({
                rules: {
                    district_number: { required: true },
                    district_name: { required: true },
                    email: { required: true }
                },
                messages: {
                    district_number: { required: 'Please Enter District Number' },
                    district_name: { required: 'Please Enter District Name' },
                    email: { required: 'Please Enter Email' }
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
                    var hasRows = SettingsDistrictTableBody.children('tr').length > 0;
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
                                        closeUpdateDistrictModal();
                                        Swal.fire({
                                            title: 'Successfully Updated!',
                                            text: 'Disrict is successfully Updated!',
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

            $('#SettingsDistrictTable').on('click', '.editBtn', function(e) {
                e.preventDefault();
                var itemID = $(this).data('id');
                console.log(itemID);

                var url = "/settings/districts/get/" + itemID;

                $.get(url, function(data) {
                    console.log(data);
                    $('#item_id').val(data.id);
                    $('#update_district_number').val(data.district_number);
                    $('#update_district_name').val(data.district_name);
                    $('#update_email').val(data.email);

                    $('#updateDistrictModal').modal('show');
                });
            });


        });
    </script>








@endsection
@section('script')

@endsection
