@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Users @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Users</h4>
                            <p class="card-title-desc">
                                Individuals who interact with a system, accessing its features and
                                functionalities based on their roles and permissions.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Users</button>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Usergroup</th>
                                    <th>Password</th>
                                    <th>District</th>
                                    <th>Area</th>
                                    <th>Branch</th>
                                    {{-- <th>Created Date</th> --}}
                                    <th>Roles and Permission</th>
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

    <div class="modal fade" id="createUserModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="createUserModalLabel">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.create')  }}" id="createValidateForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold h6">User type</label>
                                    <select id="userTypeSelect" name="user_type" class="form-select select2">
                                        <option value="">Select User Type</option>
                                        <option value="Head Office">Head Office</option>
                                        <option value="District">District</option>
                                        <option value="Area">Area</option>
                                        <option value="Branch">Branch</option>
                                        <option value="Admin">Admin</option>
                                        @if (Auth::user()->user_types == 'Developer')
                                            <option value="Developer">Developer</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="fw-bold h5 text-primary">Personal Information</label>
                                <hr>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Fullname</label>
                                    <input type="text" name="name" class="form-control" placeholder="Fullname" minlength="0" maxlength="100" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Contact No</label>
                                    <input type="text" name="contact_no" class="form-control contact_input_mask" placeholder="Contact No." required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Address</label>
                                    <input type="text" name="address" class="form-control" placeholder="Address" minlength="0" maxlength="100" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Employee No</label>
                                    <input type="number" name="employee_id" min="0" class="form-control" placeholder="Employee No." required >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold h5 text-primary">User Account Details</label>
                                <hr>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Username" minlength="0" maxlength="50" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Password</label>
                                    <input type="password" name="password" id="create_password" class="form-control" placeholder="Password" minlength="0" maxlength="50" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" minlength="0" maxlength="50" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span id="HeadOfficeDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Head Office Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">User Group</label>
                                        <select id="user_group_id" name="user_group_id" class="form-select select2" required>
                                            <option value="" selected disabled>Select User Type</option>
                                            @foreach ($user_groups as $user_group)
                                                <option value="{{ $user_group->id }}">{{ $user_group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </span>

                                <span id="DistrictDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Districts Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District</label>
                                        <select id="district_id" name="district_id" class="form-select select2" required>
                                            <option value="" selected disabled>Select District</option>
                                            @foreach ($DataDistrict as $item)
                                                <option value="{{ $item->id }}">{{ $item->district_number .' - '. $item->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </span>

                                <span id="AreaDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Area Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District Manageer</label>
                                        <select id="district_manager_area_id" name="district_manager_area_id" class="form-select select2" required>
                                            <option value="" selected disabled>Select District Manager</option>

                                            @foreach ($DataDistrict as $item)
                                                <option value="{{ $item->id }}">{{ $item->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Area Supervisor</label>
                                        <select id="area_supervisor_id" name="area_supervisor_id" class="form-select select2" required disabled >
                                            <option value="" selected disabled>Select Area Supervisor</option>
                                        </select>
                                    </div>
                                </span>

                                <span id="BranchDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Branch Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District Manager</label>
                                        <select id="district_manager_id" name="district_manager_id" class="form-select select2" required>
                                            <option value="" selected disabled>Select District Manager</option>
                                            @foreach ($DataDistrict as $item)
                                                <option value="{{ $item->id }}">{{ $item->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Area</label>
                                        <select id="area_id" name="area_id" class="form-select select2" required disabled>
                                            <option value="" selected disabled>Select Area</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Branch</label>
                                        <select id="branch_id" name="branch_id" class="form-select select2" required disabled>
                                            <option value="" selected disabled>Select Branch</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">User Group</label>
                                        <select id="user_group_branch_id" name="user_group_branch_id" class="form-select select2" required disabled>
                                            <option value="" selected disabled>Select User Type</option>
                                            @foreach ($user_groups as $user_group)
                                                <option value="{{ $user_group->id }}">{{ $user_group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </span>
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

    <div class="modal fade" id="updateUserModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="createUserModalLabel">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.update')  }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" id="item_id" name="item_id">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="fw-bold h6">User type</label>
                                    <select id="userTypeSelectUpdate" name="user_type" class="form-select select2">
                                        <option value="Head Office">Head Office</option>
                                        <option value="District">District</option>
                                        <option value="Area">Area</option>
                                        <option value="Branch">Branch</option>
                                        <option value="Admin">Admin</option>
                                        @if (Auth::user()->user_types == 'Developer')
                                            <option value="Developer">Developer</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="fw-bold h5 text-primary">Personal Information</label>
                                <hr>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Fullname</label>
                                    <input type="text" name="name" id="update_name" class="form-control" placeholder="Fullname" minlength="0" maxlength="100" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Contact No</label>
                                    <input type="text" name="contact_no" id="update_contact_no" class="form-control contact_input_mask" placeholder="Contact No." required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Address</label>
                                    <input type="text" name="address" id="update_address" class="form-control" placeholder="Address" minlength="0" maxlength="100" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Employee No</label>
                                    <input type="number" name="employee_id" id="update_employee_id" min="0" class="form-control" placeholder="Employee No." required >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold h5 text-primary">User Account Details</label>
                                <hr>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Username</label>
                                    <input type="text" name="username" id="update_username" class="form-control" placeholder="Username" minlength="0" maxlength="50" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Password</label>
                                    <input type="password" name="password" id="update_create_password" class="form-control" placeholder="Password" minlength="0" maxlength="50">
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="update_confirm_password" class="form-control" placeholder="Confirm Password" minlength="0" maxlength="50">
                                </div>

                                <div class="form-group mb-2">
                                    <label class="fw-bold h6">Email</label>
                                    <input type="email" name="email" id="update_email" class="form-control" placeholder="Email" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span id="UpdateHeadOfficeDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Head Office Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">User Group</label>
                                        <select id="update_user_group_id" name="user_group_id" class="form-select select2" required>
                                            <option value="">Select User Group</option>
                                            @foreach ($user_groups as $user_group)
                                                <option value="{{ $user_group->id }}">{{ $user_group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </span>

                                <span id="UpdateDistrictDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Districts Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District</label>
                                        <select id="update_district_id" name="district_id" class="form-select select2" required>
                                            <option value="">Select District</option>
                                            @foreach ($DataDistrict as $item)
                                                <option value="{{ $item->id }}">{{ $item->district_number .' - '. $item->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </span>

                                <span id="UpdateAreaDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Area Details</label>
                                    <hr>

                                    <input type="hidden" id="update_val_area_supervisor_id" class="update_val_area_supervisor_id">

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District Manager</label>
                                        <select id="update_district_manager_area_id" name="district_manager_area_id" class="form-select select2" required>
                                            <option value="">Select District Manager</option>
                                            @foreach ($DataDistrict as $item)
                                                <option value="{{ $item->id }}">{{ $item->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Area Supervisor</label>
                                        <select id="update_area_supervisor_id" name="area_supervisor_id" class="form-select select2" required>
                                            <option value="">Select Area Supervisor</option>
                                        </select>
                                    </div>
                                </span>

                                <span id="UpdateBranchDisplay" style="display: none;">
                                    <label class="fw-bold h5 text-primary">Branch Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District Manager</label>
                                        <select id="update_district_manager_id" name="district_manager_id" class="form-select select2" required>
                                            @foreach ($DataDistrict as $item)
                                                <option value="{{ $item->id }}">{{ $item->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" id="update_val_area_id" class="update_val_area_id">
                                    <input type="hidden" id="update_val_branch_id" class="update_val_branch_id">

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Area</label>
                                        <select id="update_area_id" name="area_id" class="form-select select2" required readonly>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Branch</label>
                                        <select id="update_branch_id" name="branch_id" class="form-select select2" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">User Group</label>
                                        <select id="update_user_group_branch_id" name="user_group_branch_id" class="form-select select2" required>
                                            <option value="">Select User Type</option>
                                            @foreach ($user_groups as $user_group)
                                                <option value="{{ $user_group->id }}">{{ $user_group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            var url = '{!! route('users.data') !!}';
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
                        return '<span class="fw-bold h6">' + row.id + '</span>'; // Display user's ID
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row, meta) {
                        return `<div class="fw-bold h6">${row.name}</div>
                                <div class="fw-bold text-primary fs-6">${row.email}</div>`; // Display user's name
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'user_types',
                    name: 'user_types',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>'; // Display user's email
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'user_group_id',
                    name: 'user_group.',
                    render: function(data, type, row, meta) {
                        return row.user_group ? '<span>' + row.user_group.group_name + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'password',
                    name: 'password',
                    render: function(data, type, row, meta) {
                        return '<span class="badge bg-danger">' + 'Encrypted' + '</span>'; // Display user's email
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'district_code_id',
                    name: 'district',
                    render: function(data, type, row, meta) {
                        return row.district ? '<span>' + row.district.district_name + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'area_code_id',
                    name: 'area',
                    render: function(data, type, row, meta) {
                        return row.area ? '<span>' + row.area.area_supervisor + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'branch_id',
                    name: 'branch',
                    render: function(data, type, row, meta) {
                        return row.branch ? '<span>' + row.branch.branch_location + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
                },
                // {
                //     data: 'created_at',
                //     name: 'created_at',
                //     render: function(data, type, row) {
                //         return new Date(data).toLocaleDateString('en-US', {
                //             month: 'long',
                //             day: 'numeric',
                //             year: 'numeric'
                //         });
                //     }

                // },
                {
                    data: null,
                    name: 'roles',
                    render: function(data, type, row, meta) {
                        // Display the roles, or 'N/A' if no roles are assigned
                        // return data ? '<span class="text-primary fw-bold h6">' + data + '</span>' : 'N/A';

                        return '<span>' + 'N / A' + '</span>'; // Display created date
                    },
                    orderable: false,
                    searchable: false,
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

            $.validator.addMethod("passwordMatch", function(value, element) {
                return value === $("#create_password").val();
            }, "Passwords do not match.");

            // Initialize the validation on the form
            $('#createValidateForm').validate({
                rules: {
                    user_type: { required: true },
                    password: { required: true, minlength: 6, maxlength: 50 }, // You can adjust the minlength as needed
                    confirm_password: { required: true, passwordMatch: true } // Use custom method here
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
                                            text: 'User is successfully added!',
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

            $.validator.addMethod("UpdatepasswordMatch", function(value, element) {
                return value === $("#update_create_password").val();
            }, "Passwords do not match.");

            $('#updateValidateForm').validate({
                rules: {
                    user_type: { required: true },
                    password: { minlength: 6, maxlength: 50 }, // You can adjust the minlength as needed
                    confirm_password: { UpdatepasswordMatch: true } // Use custom method here
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
                                            text: 'User is successfully Updated!',
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

                var url = "/users/get/" + itemID;

                $.get(url, function(data) {
                    $('#item_id').val(data.id);
                    $('#userTypeSelectUpdate').val(data.user_types).trigger('change');

                    $('#update_name').val(data.name);
                    $('#update_contact_no').val(data.contact_no ?? '');
                    $('#update_address').val(data.address ?? '');
                    $('#update_employee_id').val(data.employee_id ?? '');
                    $('#update_username').val(data.username);
                    $('#update_email').val(data.email);

                    $('#update_user_group_id').val(data.user_group_id).trigger('change');
                    $('#update_district_id').val(data.district_code_id ?? '').trigger('change');

                    $('#update_district_manager_area_id').val(data.district_code_id ?? '').trigger('change');
                    $('#update_val_area_supervisor_id').val(data.area_code_id ?? '');

                    $('#update_district_manager_id').val(data.district_code_id).trigger('change');

                    $('#update_val_area_id').val(data.area_code_id ?? '');
                    $('#update_val_branch_id').val(data.branch_id ?? '');

                    $('#update_user_group_branch_id').val(data.user_group_id).trigger('change');

                    $('#update_create_password').val(data.password);
                    $('#update_confirm_password').val(data.password);


                    $('#updateUserModal').modal('show');
                });
            });

            function closeCreateModal() {
                $('#createUserModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#FetchingDatatable').addClass('d-none');
            }

            function closeUpdateModal() {
                $('#updateUserModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#usersGroupPageTable').addClass('d-none');
            }
        });

        // Create Function
        $(document).ready(function(){
            $('#createUserModal').on('shown.bs.modal', function () {
                $('#userTypeSelect').select2({ dropdownParent: $('#createUserModal'), });
                $('#user_group_id').select2({  dropdownParent: $('#createUserModal') });
                $('#district_id').select2({  dropdownParent: $('#createUserModal') });
                $('#district_manager_area_id').select2({  dropdownParent: $('#createUserModal') });
                $('#area_supervisor_id').select2({  dropdownParent: $('#createUserModal') });
                $('#district_manager_id').select2({  dropdownParent: $('#createUserModal') });
                $('#area_id').select2({  dropdownParent: $('#createUserModal') });
                $('#branch_id').select2({  dropdownParent: $('#createUserModal') });
                $('#user_group_branch_id').select2({  dropdownParent: $('#createUserModal') });
            });

            $('#userTypeSelect').on('change', function() {
                var selectedUserType = $(this).val();

                if(selectedUserType === 'Head Office')
                {
                    $('#HeadOfficeDisplay').show();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').hide();
                }
                else if(selectedUserType === 'District')
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').show();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').hide();
                }
                else if(selectedUserType === 'Area')
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').show();
                    $('#BranchDisplay').hide();
                }
                else if(selectedUserType === 'Branch')
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').show();
                }
                else
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').hide();
                }


            });

            $('#district_manager_area_id').on('change', function() {
                var selectedDistrict = $(this).val();

                // Make the AJAX GET request
                $.ajax({
                    url: '/settings/area/using/district',
                    type: 'GET',
                    data: {
                        district_id: selectedDistrict
                    },
                    success: function(response) {
                        // Assuming the response is an array of objects with id, area_no, and area_supervisor properties
                        var options = '<option value="" selected disabled>Select Area</option>'; // Initialize an empty string for options
                        $.each(response, function(index, item) {
                            // Create an option for each item in the response
                            options += `<option value="${item.id}">${item.area_no} - ${item.area_supervisor}</option>`;
                        });
                        // Populate the dropdown (make sure to replace '#area_supervisor_id' with the actual dropdown ID)
                        $('#area_supervisor_id').prop('disabled', false); // Remove disabled attribute
                        $('#area_supervisor_id').html(options); // Set the dropdown options
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('AJAX Error:', status, error);
                    }
                });
            });

            $('#district_manager_id').on('change', function() {
                var selectedDistrict = $(this).val();
                // Make the AJAX GET request
                $.ajax({
                    url: '/settings/area/using/district',
                    type: 'GET',
                    data: {
                        district_id: selectedDistrict
                    },
                    success: function(response) {
                        // Assuming the response is an array of objects with id, area_no, and area_supervisor properties
                        var options = '<option value="" selected disabled>Select Supervisor</option>'; // Initialize an empty string for options
                        $.each(response, function(index, item) {
                            // Create an option for each item in the response
                            options += `<option value="${item.id}">${item.area_no} - ${item.area_supervisor}</option>`;
                        });
                        // Populate the dropdown (make sure to replace '#area_supervisor_id' with the actual dropdown ID)
                        $('#area_id').prop('disabled', false); // Remove disabled attribute
                        $('#area_id').html(options); // Set the dropdown options
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('AJAX Error:', status, error);
                    }
                });
            });

            $('#area_id').on('change', function() {
                var selectedArea = $(this).val();
                // Make the AJAX GET request

                $('#branch_id').on('change', function() {
                    var selectedBranch = $(this).val();

                    if (selectedBranch) {
                        // Enable the User Group dropdown
                        $('#user_group_branch_id').prop('disabled', false); // Enable the dropdown
                    } else {
                        // Disable the User Group dropdown if no branch is selected
                        $('#user_group_branch_id').prop('disabled', true); // Keep it disabled if no selection
                    }
                });

                $.ajax({
                    url: '/settings/branch/using/area',
                    type: 'GET',
                    data: {
                        area_id: selectedArea
                    },
                    success: function(response) {
                        // Assuming the response is an array of objects with id, area_no, and area_supervisor properties
                        var options = '<option value="" selected disabled>Select Branch</option>'; // Initialize an empty string for options
                        $.each(response, function(index, item) {
                            // Create an option for each item in the response
                            options += `<option value="${item.id}">${item.branch_location}</option>`;
                        });
                        // Populate the dropdown (make sure to replace '#area_supervisor_id' with the actual dropdown ID)
                        $('#branch_id').prop('disabled', false); // Remove disabled attribute
                        $('#branch_id').html(options); // Set the dropdown options
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
        });

        // Update Function
        $(document).ready(function(){
            $('#updateUserModal').on('shown.bs.modal', function () {
                $('#userTypeSelectUpdate').select2({ dropdownParent: $('#updateUserModal'), });
                $('#user_group_id').select2({  dropdownParent: $('#updateUserModal') });
                $('#update_user_group_branch_id').select2({ dropdownParent: $('#updateUserModal') });
            });

            $('#userTypeSelectUpdate').on('change', function() {
                var selectedUserType = $(this).val();

                if(selectedUserType === 'Head Office')
                {
                    $('#UpdateHeadOfficeDisplay').show();
                    $('#UpdateDistrictDisplay').hide();
                    $('#UpdateAreaDisplay').hide();
                    $('#UpdateBranchDisplay').hide();
                }
                else if(selectedUserType === 'District')
                {
                    $('#UpdateHeadOfficeDisplay').hide();
                    $('#UpdateDistrictDisplay').show();
                    $('#UpdateAreaDisplay').hide();
                    $('#UpdateBranchDisplay').hide();
                }
                else if(selectedUserType === 'Area')
                {
                    $('#UpdateHeadOfficeDisplay').hide();
                    $('#UpdateDistrictDisplay').hide();
                    $('#UpdateAreaDisplay').show();
                    $('#UpdateBranchDisplay').hide();
                }
                else if(selectedUserType === 'Branch')
                {
                    $('#UpdateHeadOfficeDisplay').hide();
                    $('#UpdateDistrictDisplay').hide();
                    $('#UpdateAreaDisplay').hide();
                    $('#UpdateBranchDisplay').show();
                }
                else
                {
                    $('#UpdateHeadOfficeDisplay').hide();
                    $('#UpdateDistrictDisplay').hide();
                    $('#UpdateAreaDisplay').hide();
                    $('#UpdateBranchDisplay').hide();
                }
            });

            $('#userTypeSelect').on('change', function() {
                var selectedUserType = $(this).val();

                if(selectedUserType === 'Head Office')
                {
                    $('#HeadOfficeDisplay').show();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').hide();
                }
                else if(selectedUserType === 'District')
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').show();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').hide();
                }
                else if(selectedUserType === 'Area')
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').show();
                    $('#BranchDisplay').hide();
                }
                else if(selectedUserType === 'Branch')
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').show();
                }
                else
                {
                    $('#HeadOfficeDisplay').hide();
                    $('#DistrictDisplay').hide();
                    $('#AreaDisplay').hide();
                    $('#BranchDisplay').hide();
                }


            });


            $('#update_district_manager_area_id').on('change', function() {
                var selectedDistrict = $(this).val(); // Get selected district value

                setTimeout(function() {
                    var previousAreaIdFromArea = $('#update_val_area_supervisor_id').val(); // Get previous area supervisor ID

                    $.ajax({
                        url: '/settings/area/using/district',
                        type: 'GET',
                        data: {
                            district_id: selectedDistrict
                        },
                        success: function(response) {
                            var options = '<option value="">Select Area Supervisor</option>'; // Default option

                            $.each(response, function(index, item) {
                                // Check if this area matches the previous one and mark it as selected
                                var selected = (item.id == previousAreaIdFromArea) ? 'selected' : '';
                                options += `<option value="${item.id}" ${selected}>${item.area_no} - ${item.area_supervisor}</option>`;
                            });

                            $('#update_area_supervisor_id').html(options); // Update the dropdown with the new options

                            // Automatically select the previous area supervisor if it exists
                            if (previousAreaIdFromArea) {
                                $('#update_area_supervisor_id').val(previousAreaIdFromArea).trigger('change'); // Set the previous area supervisor as selected
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            console.error('AJAX Error:', status, error);
                        }
                    });
                }, 100); // Delay to ensure the value is updated
            });


            $('#update_district_manager_id').on('change', function() {
                var selectedDistrict = $(this).val(); // Get selected district value

                setTimeout(function() {
                    var previousAreaId = $('#update_val_area_id').val(); // Get the latest area ID value after a brief delay

                    // Make the AJAX GET request for areas
                    $.ajax({
                        url: '/settings/area/using/district',
                        type: 'GET',
                        data: {
                            district_id: selectedDistrict
                        },
                        success: function(response) {
                            var options = '<option value="">Select Area</option>'; // Default 'Select Area' option

                            // Build options for each area
                            $.each(response, function(index, item) {
                                // Check if this area matches the previous one and mark it as selected
                                var selected = (item.id == previousAreaId) ? 'selected' : '';
                                options += `<option value="${item.id}" ${selected}>${item.area_no} - ${item.area_supervisor}</option>`;
                            });

                            $('#update_area_id').html(options); // Update the dropdown with the new options

                            // Automatically trigger the area change to load branches
                            if (previousAreaId) {
                                $('#update_area_id').val(previousAreaId).trigger('change'); // Set previous area as selected and trigger change event
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                }, 100); // Small delay to ensure area ID is updated
            });

            $('#update_area_id').on('change', function() {
                var selectedArea = $(this).val();

                setTimeout(function() {
                    var previousBranchId = $('#update_val_branch_id').val(); // Get the latest branch ID value after a brief delay

                    // Make the AJAX GET request for branches
                    $.ajax({
                        url: '/settings/branch/using/area',
                        type: 'GET',
                        data: {
                            area_id: selectedArea
                        },
                        success: function(response) {
                            var options = '<option value="">Select Branch</option>'; // Default 'Select Branch' option

                            // Build options for each branch
                            $.each(response, function(index, item) {
                                // Check if this branch matches the previous one and mark it as selected
                                var selected = (item.id == previousBranchId) ? 'selected' : '';
                                options += `<option value="${item.id}" ${selected}>${item.branch_location}</option>`;
                            });

                            $('#update_branch_id').html(options); // Update the dropdown with the new options

                            // Automatically set the previous branch ID if it exists
                            if (previousBranchId) {
                                $('#update_branch_id').val(previousBranchId); // Set previous branch as selected
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                }, 100);
            });

        });
    </script>




@endsection
@section('script')

@endsection
