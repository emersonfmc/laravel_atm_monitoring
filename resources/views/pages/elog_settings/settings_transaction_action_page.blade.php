@extends('layouts.settings_monitoring.settings_master')
@section('settings')

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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Transaction Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <th style="width: 10%">Sequence</th>
                                        <th style="width: 30%">Assign User</th>
                                        <th style="width: 15%">Type</th>
                                        <th style="width: 24%">Remarks</th>
                                        <th style="width: 13%">OC Trans</th>
                                        <th style="width: 8%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="sequence_no[]" class="form-control" value="1" readonly></td>
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
                                            <select name="type[]" class="form-select">
                                                <option value="Received">Received</option>
                                                <option value="Released">Released</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="remarks[]" class="form-control" minlength="0" maxlength="100" placeholder="Remarks">
                                        </td>
                                        <td>
                                            <select name="oc_transaction[]" class="form-select" required>
                                                <option value="" selected>Default</option>
                                                <option value="Collection">Collection</option>
                                                <option value="Custodian">Custodian</option>
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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                                    <label class="fw-bold h6">Transaction Name</label>
                                    <input type="text" name="name" class="form-control" id="update_name"
                                        placeholder="Enter Transaction Name" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                                        <th style="width: 10%">Sequence</th>
                                        <th style="width: 30%">Assign User</th>
                                        <th style="width: 15%">Type</th>
                                        <th style="width: 24%">Remarks</th>
                                        <th style="width: 13%">OC Trans</th>
                                        <th style="width: 8%">Remove</th>
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
        $(document).ready(function () {
            // Display
                var FetchingDatatableBody = $('#FetchingDatatable tbody');

                const dataTable = new ServerSideDataTable('#FetchingDatatable');
                var url = '{!! route('settings.transaction.action.data') !!}';

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
                            // Check if data_transaction_sequence exists and is an array
                            if (data.data_transaction_sequence && Array.isArray(data.data_transaction_sequence)) {
                                // Initialize an empty string for group names
                                let groups = '';
                                // Use each to loop through the sequences
                                $.each(data.data_transaction_sequence, function(index, sequence) {
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
                const orderBy = { order: [[0, 'asc']] };
                dataTable.initialize(url, columns, orderBy);
            // Display

            // Creation
                $('#createTransactionActionModal').on('shown.bs.modal', function () {
                    $('.user-select').select2({ dropdownParent: $('#createTransactionActionModal'), });
                });

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

                // Add new row on Add Sequence button click
                $('#addSequenceBtn').click(function() {
                    let newRow = `
                        <tr>
                            <td><input type="text" name="sequence_no[]" class="form-control sequence_no"></td>
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
                                <input type="text" name="remarks[]" class="form-control" minlength="0" maxlength="100" placeholder="Remarks">
                            </td>
                            <td>
                                <select name="oc_transaction[]" class="form-select">
                                    <option value="" selected>Default</option>
                                    <option value="Collection">Collection</option>
                                    <option value="Custodian">Custodian</option>
                                </select>
                            </td>
                            <td>
                                <a href="#" class="btn btn-danger removeRow"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    `;

                    sequenceTable.append(newRow); // Append the new row
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

                                            if (typeof response === 'string') {
                                                var res = JSON.parse(response);
                                            } else {
                                                var res = response; // If it's already an object
                                            }

                                            if (res.status === 'success') {
                                                $('#createTransactionActionModal').modal('hide');
                                                Swal.fire({
                                                    title: 'Successfully Created!',
                                                    text: res.message,
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
                                            } else if (res.status === 'error'){
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: res.message,
                                                    icon: 'error',
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Error Occurred Please Try Again',
                                                    icon: 'error',
                                                });
                                            }
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
            // Creation

            // Update
                function initializeUpdateSelect2() {
                    // Initialize select2 for all elements with .user-select
                    $('.user-select-update').each(function() {
                        var newSelectId = 'select' + Date.now(); // Unique ID for each select
                        $(this).attr('id', newSelectId).select2({
                            dropdownParent: $('#updateTransactionActionModal') // Ensure it works in modals
                        });
                    });
                }

                $('#FetchingDatatable').on('click', '.editBtn', function(e) {
                    e.preventDefault();
                    var itemID = $(this).data('id');

                    var url = "/settings/transaction/action/get/" + itemID;

                    $.get(url, function(data) {
                        $('#item_id').val(data.id);
                        $('#update_name').val(data.name);
                        $('#update_status').val(data.status);

                        $('#sequenceTableUpdateBody').empty();
                            data.data_transaction_sequence.forEach(function(rows) {
                                var newRow = '<tr>' +
                                                '<td>' +
                                                    '<input type="text" value="' + rows.sequence_no + '" class="form-control" name="sequence_no[]">' +
                                                    '<input type="hidden" value="' + rows.id + '" class="form-control" name="items_id[]">' +
                                                    '<input type="hidden" value="" class="remove_flag" name="remove_flag[]">' +  // Initially empty, to be set on removal
                                                '</td>' +
                                                '<td>' +
                                                    '<div class="form-group">' +
                                                        '<select name="user_group_id[]" class="form-select select2 user-select-update" required>' +
                                                            '<option value="" disabled>Select User</option>';

                                                            @foreach ($DataUserGroup as $item)
                                                                newRow += '<option value="{{ $item->id }}" ' + (rows.user_group_id == {{ $item->id }} ? 'selected' : '') + '>{{ $item->group_name }}</option>';
                                                            @endforeach

                                                            newRow +=
                                                        '</select>' +
                                                    '</div>' +
                                                '</td>' +

                                                '<td>' +
                                                    '<select name="oc_transaction[]" class="form-select" required>' +
                                                        '<option value="Received" ' + (rows.type == 'Received' ? 'selected' : '') + '>Received</option>' +
                                                        '<option value="Released" ' + (rows.type == 'Released' ? 'selected' : '') + '>Released</option>' +
                                                    '</select>' +
                                                '</td>' +

                                                '<td>' +
                                                    '<input type="text" value="' + rows.remarks + '" class="form-control" name="remarks[]" placeholder="Remarks">' +
                                                '</td>' +

                                                '<td>' +
                                                    '<select name="type[]" class="form-select" required>' +
                                                        '<option value="">Default</option>' +
                                                        '<option value="Collection" ' + (rows.oc_receiver_type == 'Collection' ? 'selected' : '') + '>Collection</option>' +
                                                        '<option value="Custodian" ' + (rows.oc_receiver_type == 'Custodian' ? 'selected' : '') + '>Custodian</option>' +
                                                    '</select>' +
                                                '</td>' +
                                                '<td>' +
                                                    '<a href="#" class="btn btn-danger removeUpdateRow"><i class="fas fa-trash-alt"></i></a>' +
                                                '</td>' +
                                            '</tr>';

                                $('#sequenceTableUpdateBody').append(newRow);
                            });

                            initializeUpdateSelect2();

                            // Add event handler for the remove button to mark rows as removed
                            $('#sequenceTableUpdateBody').on('click', '.removeUpdateRow', function(e) {
                                e.preventDefault();

                                var row = $(this).closest('tr');
                                var itemId = row.find('input[name="items_id[]"]').val();  // Get the ID for this row
                                row.find('.remove_flag').val(itemId);  // Set remove_flag to the row's ID for deletion in database
                                row.css('background-color', '#f8d7da');  // Optional: Change background color to indicate removed row
                                row.find('input, select').attr('disabled', true);  // Disable inputs to prevent further editing
                            });

                        $('#updateTransactionActionModal').modal('show');
                    });
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
                                text: 'Are you sure you want to update this?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: "#28A745",
                                cancelButtonColor: "#6C757D",
                                confirmButtonText: "Yes, Update it!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const currentPage = dataTable.table.page();
                                    $.ajax({
                                        url: form.action,
                                        type: form.method,
                                        data: $(form).serialize(),
                                        success: function(response) {
                                            if (typeof response === 'string') {
                                                var res = JSON.parse(response);
                                            } else {
                                                var res = response; // If it's already an object
                                            }

                                            if (res.status === 'success') {
                                                $('#updateTransactionActionModal').modal('hide');
                                                Swal.fire({
                                                    title: 'Successfully Updated!',
                                                    text: res.message,
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
                                            } else if (res.status === 'error'){
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: res.message,
                                                    icon: 'error',
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Error Occurred Please Try Again',
                                                    icon: 'error',
                                                });
                                            }
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

                initializeUpdateSelect2();
                let sequenceTableUpdate = $('#sequenceTableUpdate tbody');

                // Add new row on Add Sequence button click
                $('#addSequenceUpdateBtn').click(function() {
                    let newRow = `
                        <tr>
                            <td><input type="text" name="sequence_no[]" class="form-control"></td>
                            <td>
                                <div class="form-group">
                                    <select name="user_group_id[]" class="form-select select2 user-select-update" required>
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
                                <input type="text" name="remarks[]" class="form-control" minlength="0" maxlength="100" placeholder="Remarks">
                            </td>
                            <td>
                                <select name="oc_transaction[]" class="form-select" required>
                                    <option value="" selected>Default</option>
                                    <option value="Collection">Collection</option>
                                    <option value="Custodian">Custodian</option>
                                </select>
                            </td>
                            <td>
                                <a href="#" class="btn btn-danger removeUpdateRow"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    `;

                    sequenceTableUpdate.append(newRow); // Append the new row
                    initializeUpdateSelect2();
                });

                // Remove row on Remove button click
                $(document).on('click', '.removeUpdateRow', function(e) {
                    e.preventDefault();
                    $(this).closest('tr').remove(); // Remove the row
                });
            // Update

        });

    </script>






@endsection
@section('script')

@endsection
