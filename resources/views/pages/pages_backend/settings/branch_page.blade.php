@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Branch @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Branch</h4>
                            <p class="card-title-desc">
                                This branch provides loans to clients with GSIS and SSS loans.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBranchModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Branch</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Branch Location</th>
                                    <th>Branch Head</th>
                                    <th>District</th>
                                    <th>Area</th>
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

    <div class="modal fade" id="createBranchModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Branch</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.branch.create') }}" id="createValidateForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Location</label>
                            <input type="text" name="branch_location" class="form-control" id="branch_location"
                                   placeholder="Enter Branch Location" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Branch Head</label>
                            <input type="text" name="branch_head" class="form-control" id="branch_head"
                                   placeholder="Enter Branch Head" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">District Manager</label>
                            <select name="district_id" id="district_id" class="form-select select2">
                                <option value="" selected disabled>Select District Manager</option>
                                @foreach ($TblDistrict as $item)
                                    <option value="{{ $item->id }}">{{ $item->district_number .' - '. $item->district_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Area Supervisor</label>
                            <select name="area_id" id="area_id" class="form-select select2" required disabled>
                                <option value="" selected disabled>Area Supervisor</option>
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

    <div class="modal fade" id="updateBranchModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="updateBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Branch</h5>
                    <button type="button" class="btn-close closeUpdateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.branch.update')  }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" id="item_id" name="item_id">

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Status</label>
                            <select name="status" id="update_status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Branch Abbreviation</label>
                            <input type="text" name="branch_abbreviation" class="form-control" id="update_branch_abbreviation"
                                   placeholder="Enter Branch Abbreviation" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Location</label>
                            <input type="text" name="branch_location" class="form-control" id="update_branch_location"
                                placeholder="Enter Branch Location" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Branch Head</label>
                            <input type="text" name="branch_head" class="form-control" id="update_branch_head"
                                placeholder="Enter Branch Head" minlength="0" maxlength="50">
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">District Manager</label>
                            <select name="district_id" id="update_district_id" class="form-select select2">
                                <option value="" selected disabled>Select District Manager</option>
                                @foreach ($TblDistrict as $item)
                                    <option value="{{ $item->id }}">{{ $item->district_number .' '. $item->district_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" id="update_get_area_id">

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Area Supervisor</label>
                            <select name="area_id" id="update_area_id" class="form-select select2">
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
            var url = '{!! route('settings.branch.data') !!}';
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
                    data: null,
                    name: 'branch_location',
                    render: function(data, type, row, meta) {
                        var abbreviation = row.branch_abbreviation ? row.branch_abbreviation : '';
                        var separator = row.branch_abbreviation ? ' - ' : '';
                        return '<span class="fw-bold h6">' + '<span class="fw-bold h6 text-primary">' + abbreviation + '</span>' + separator + row.branch_location + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'branch_head',
                    name: 'branch_head',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6">' + (row.branch_head !== null && row.branch_head !== undefined ? row.branch_head : '') + '</span>'; // Display user's name or empty if null
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'district_id',
                    name: 'district.district_name',
                    render: function(data, type, row, meta) {
                        return row.district ? '<span>' + row.district.district_name + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'area_id',
                    name: 'district.area',
                    render: function(data, type, row, meta) {
                        return row.area ? '<span>' + row.area.area_supervisor + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
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


            // Create Start
                $('#createBranchModal').on('shown.bs.modal', function () {
                    $('#district_id').select2({ dropdownParent: $('#createBranchModal'), });
                    $('#area_id').select2({  dropdownParent: $('#createBranchModal') });
                });

                $(document).on('change', '#district_id', function() {
                    var district_id = $(this).val();

                    $.ajax({
                        url: "{{ route('settings.area.using.district') }}",
                        type: "GET",
                        data: { district_id: district_id },
                        success: function(data) {
                            console.log(data);
                            var html = '<option value="" selected disabled>Select Area Supervisor</option>';
                            // Loop through the array of areas
                            $.each(data, function(index, area) {
                                html += '<option value="' + area.id + '">' + area.area_no + ' - ' + area.area_supervisor + '</option>';
                            });

                            $('#area_id').html(html);
                            $('#area_id').removeAttr('disabled');
                        }
                    });
                });

                $('#createValidateForm').validate({
                    rules: {
                        location: { required: true, },
                        branch_head: { required: true, },
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
                                                text: 'Branch is successfully added!',
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
            // Create End

            // Update Start
                $('#updateBranchModal').on('shown.bs.modal', function () {
                    $('#update_district_id').select2({ dropdownParent: $('#updateBranchModal'), });
                    $('#update_area_id').select2({  dropdownParent: $('#updateBranchModal') });
                });

                $('#FetchingDatatable').on('click', '.editBtn', function(e) {
                    e.preventDefault();
                    var itemID = $(this).data('id');

                    var url = "/settings/branch/get/" + itemID;

                    $.get(url, function(data) {
                        $('#item_id').val(data.id);
                        $('#update_branch_abbreviation').val(data.branch_abbreviation);
                        $('#update_branch_location').val(data.branch_location );
                        $('#update_branch_head').val(data.branch_head ?? '');
                        $('#update_district_id').val(data.district_id ?? '').trigger('change');
                        $('#update_get_area_id').val(data.area_id ?? '');

                        $('#updateBranchModal').modal('show');
                    });


                });

                $('#update_district_id').on('change', function() {
                    var selectedDistrict = $(this).val(); // Get selected district value

                    setTimeout(function() {
                        var previousAreaId = $('#update_get_area_id').val(); // Get the latest area ID value after a brief delay

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

                $('#updateValidateForm').validate({
                    rules: {
                        location: { required: true, },
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
                                                text: 'Branch is successfully Updated!',
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
            // Update End

            function closeCreateModal() {
                $('#createBranchModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#FetchingDatatable').addClass('d-none');
            }

            function closeUpdateModal() {
                $('#updateBranchModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#usersGroupPageTable').addClass('d-none');
            }

        });
    </script>






@endsection
@section('script')

@endsection
