@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Transaction Action @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Transaction Action</h4>
                            <p class="card-title-desc">
                                This feature enables users to perform various transactional
                                tasks, such as creating, editing, and managing financial
                                transactions, ensuring seamless and efficient workflow management.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTransactionActionModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Action</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Transaction Name</th>
                                    <th>Sequence / User Selected</th>
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

    <div class="modal fade" id="createTransactionActionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="createTransactionActionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Transaction Action</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.transaction.action.create') }}" id="createValidateForm">
                        @csrf

                        <div class="form-group mb-3 col-md-6">
                            <label class="fw-bold h6">Transaction Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter Transaction Name" minlength="0" maxlength="50" required>
                        </div>
                        <hr>

                        <div class="text-end mb-2">
                            <button type="button" id="addSequenceBtn" class="btn btn-primary">Add Sequence</button>
                        </div>

                        <div class="table-responsive">
                            <table id="sequenceTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Sequence No</th>
                                        <th class="col-md-4">Assign User</th>
                                        <th class="col-md-3">Type</th>
                                        <th class="col-md-2">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="sequence_no[]" class="form-control sequence_no" value="1" readonly></td>
                                        <td>
                                            <div class="form-group">
                                                <select name="user_group_id[]" class="form-select select2 user-select" required required>
                                                    <option value="" selected disabled>Select User</option>
                                                    @foreach ($DataUserGroup as $item)
                                                        <option value="{{ $item->id }}">{{ $item->group_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="type[]" class="form-select" required>
                                                <option value="Received">Received</option>
                                                <option value="Released">Released</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{-- <a href="#" class="btn btn-danger removeRow" disabled><i class="fas fa-trash-alt"></i></a> --}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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

    <div class="modal fade" id="updateTransactionActionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"update_district_id
        aria-labelledby="updateTransactionActionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Transaction Action</h5>
                    <button type="button" class="btn-close closeUpdateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.transaction.action.update') }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" id="item_id" name="item_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Area</label>
                                    <input type="text" name="name" class="form-control" id="update_name"
                                        placeholder="Enter Transaction Name" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Status</label>
                                    <select name="status" id="update_status" class="form-select" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="text-end mb-2">
                            <button type="button" id="addSequenceUpdateBtn" class="btn btn-primary">Add Sequence</button>
                        </div>

                        <div class="table-responsive">
                            <table id="sequenceTableUpdate" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Sequence No</th>
                                        <th class="col-md-4">Assign User</th>
                                        <th class="col-md-3">Type</th>
                                        <th class="col-md-2">Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="sequenceTableUpdateBody">

                                </tbody>
                            </table>
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
        // Fetching of Data
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('settings.transaction.action.data') !!}';
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
                        return '<span>' + data + '</span>'; // Display user's ID
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_transaction_sequence exists and is an array
                        if (data.atm_transaction_sequence && Array.isArray(data.atm_transaction_sequence)) {
                            // Initialize an empty string for group names
                            let groups = '';
                            // Use each to loop through the sequences
                            $.each(data.atm_transaction_sequence, function(index, sequence) {
                                // Check if data_user_group exists before accessing group_name
                                if (sequence.data_user_group) {
                                    // Set text class based on sequence type
                                    let textClass = sequence.type === 'Received' ? 'text-primary' : (sequence.type === 'Released' ? 'text-danger' : '');

                                    groups += sequence.sequence_no + ' - ' + sequence.data_user_group.group_name + ' - <span class="' + textClass + '"> ' + sequence.type + '</span><br>'; // Use <br> for line breaks
                                }
                            });
                            return '<div class="fw-bold h6 text-start">' + (groups.length ? groups : 'No Sequence Yet') + '</div>';
                        }
                        return '<div class="fw-bold h6">No Sequence Yet</div>'; // Fallback if there's no data
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        if (data.toLowerCase() === 'active') {
                            return '<div class="badge bg-primary">Active</div>';
                        } else if (data.toLowerCase() === 'inactive') {
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
                                            text: 'Transaction is successfully Updated!',
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

                var url = "/settings/transaction/action/get/" + itemID;

                $.get(url, function(data) {
                    $('#item_id').val(data.id);
                    $('#update_name').val(data.name);
                    $('#update_status').val(data.status);

                    $('#sequenceTableUpdateBody').empty();
                        data.atm_transaction_sequence.forEach(function(rows) {
                            var newRow = '<tr>' +
                                            '<td>' +
                                                '<input type="text" value="' + rows.sequence_no + '" class="sequence_no_update form-control" name="sequence_no[]" readonly>' +
                                                '<input type="hidden" value="' + rows.id + '" class="form-control" name="items_id[]">' +
                                            '</td>' +
                                            '<td>' +
                                                '<div class="form-group">' +
                                                    '<select name="user_group_id[]" class="form-select select2 user-select" required>' +
                                                        '<option value="" disabled>Select User</option>';

                                                        // Loop through your `$DataUserGroup` to create the dropdown options
                                                        @foreach ($DataUserGroup as $item)
                                                            // Check if the current item ID matches the `user_group_id` to pre-select it
                                                            newRow += '<option value="{{ $item->id }}" ' + (rows.user_group_id == {{ $item->id }} ? 'selected' : '') + '>{{ $item->group_name }}</option>';
                                                        @endforeach

                                                        newRow +=
                                                    '</select>' +
                                                '</div>' +
                                            '</td>' +
                                            '<td>' +
                                                '<select name="type[]" class="form-select" required>' +
                                                    '<option value="Received" ' + (rows.type == 'Received' ? 'selected' : '') + '>Received</option>' +
                                                    '<option value="Released" ' + (rows.type == 'Released' ? 'selected' : '') + '>Released</option>' +
                                                '</select>' +
                                            '</td>' +
                                            '<td>' +
                                                '<a href="#" class="btn btn-danger removeUpdateRow" disabled><i class="fas fa-trash-alt"></i></a>' +
                                            '</td>' +
                                        '</tr>';

                            $('#sequenceTableUpdateBody').append(newRow);
                        });
                    $('#updateTransactionActionModal').modal('show');
                });
            });

            function closeCreateModal() {
                $('#createTransactionActionModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#FetchingDatatable').addClass('d-none');
            }

            function closeUpdateModal() {
                $('#updateTransactionActionModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
                // $('#usersGroupPageTable').addClass('d-none');
            }

        // Creation Function

            function initializeSelect2() {
                // Initialize select2 for all elements with .user-select
                $('.user-select').each(function() {
                    var newSelectId = 'select' + Date.now(); // Unique ID for each select
                    $(this).attr('id', newSelectId).select2({
                        dropdownParent: $('#createTransactionActionModal') // Ensure it works in modals
                    });
                });
            }

            // Call the initializeSelect2 function for the first select2 element on page load
            initializeSelect2();

            let sequenceTable = $('#sequenceTable tbody');

            // Function to update sequence numbers
            function updateSequenceNumbers() {
                sequenceTable.find('tr').each(function(index, row) {
                    $(row).find('.sequence_no').val(index + 1); // Update the sequence number based on the row index
                });
            }

            // Add new row on Add Sequence button click
            $('#addSequenceBtn').click(function() {
                let newRow = `
                    <tr>
                        <td><input type="text" name="sequence_no[]" class="form-control sequence_no" value="${sequenceTable.children('tr').length + 1}" readonly></td>
                        <td>
                            <div class="form-group">
                                <select name="user_group_id[]" class="form-select select2 user-select" required>
                                    <option value="" selected disabled>Select User</option>
                                    @foreach ($DataUserGroup as $item)
                                        <option value="{{ $item->id }}">{{ $item->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <select name="type[]" class="form-select" required>
                                <option value="Received">Received</option>
                                <option value="Released">Released</option>
                            </select>
                        </td>
                        <td>
                            <a href="#" class="btn btn-danger removeRow"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                `;

                sequenceTable.append(newRow); // Append the new row
                updateSequenceNumbers(); // Update sequence numbers
                initializeSelect2(); // Initialize Select2 for the newly added row
            });

            // Remove row on Remove button click
            $(document).on('click', '.removeRow', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove(); // Remove the row
                updateSequenceNumbers(); // Update sequence numbers after removal
            });

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
                    var hasRows = sequenceTable.children('tr').length > 0;
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
                                            text: 'Transaction is successfully added!',
                                            icon: 'success',
                                            showCancelButton: false,
                                            showConfirmButton: true,
                                            confirmButtonText: 'OK',
                                            preConfirm: () => {
                                                return new Promise((resolve) => {
                                                    Swal.fire({
                                                        title: 'Please Wait...',
                                                        allowOutsideClick: false,
                                                        allowEscapeKey: false,
                                                        showConfirmButton: false,
                                                        showCancelButton: false,
                                                        didOpen: () => {
                                                            Swal.showLoading();
                                                            // here the reload of datatable
                                                            dataTable.table.ajax.reload(() => {
                                                                Swal.close();
                                                                $(form)[0].reset();
                                                                dataTable.table.page(currentPage).draw(false);
                                                            }, false);
                                                        }
                                                    })
                                                });
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        var errorMessage = 'An error occurred. Please try again later.';
                                        if (xhr.responseJSON && xhr.responseJSON.error) {
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



            let sequenceTableUpdate = $('#sequenceTableUpdate tbody');

            // Function to update sequence numbers
            function updateSequenceNumbers() {
                sequenceTableUpdate.find('tr').each(function(index, row) {
                    $(row).find('.sequence_no_update').val(index + 1); // Update the sequence number based on the row index
                });
            }
            // Add new row on Add Sequence button click
            $('#addSequenceUpdateBtn').click(function() {
                let newRow = `
                    <tr>
                        <td><input type="text" name="sequence_no[]" class="form-control sequence_no_update" value="${sequenceTableUpdate.children('tr').length + 1}" readonly></td>
                        <td>
                            <div class="form-group">
                                <select name="user_group_id[]" class="form-select select2 user-select" required>
                                    <option value="" selected disabled>Select User</option>
                                    @foreach ($DataUserGroup as $item)
                                        <option value="{{ $item->id }}">{{ $item->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <select name="type[]" class="form-select" required>
                                <option value="Received">Received</option>
                                <option value="Released">Released</option>
                            </select>
                        </td>
                        <td>
                            <a href="#" class="btn btn-danger removeUpdateRow"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                `;

                sequenceTableUpdate.append(newRow); // Append the new row
                updateSequenceNumbers(); // Update sequence numbers
            });

            // Remove row on Remove button click
            $(document).on('click', '.removeUpdateRow', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove(); // Remove the row
                updateSequenceNumbers(); // Update sequence numbers after removal
            });
        });

    </script>






@endsection
@section('script')

@endsection
