@extends('layouts.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Passbook Transaction @endslot
        @slot('title') Passbook Transaction All @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Passbook For Collection Transaction All</h4>
                            <p class="card-title-desc ms-2">
                                Where Head Offices and Branches can view their Overall Transactions.
                            </p>
                        </div>
                        <div class="col-md-4"></div>
                        {{-- <div class="col-md-4 text-end" id="passbookForCollection" style="display: none;">
                            <a href="#" class="btn btn-primary" id="ForCollectionButton"><i class="fas fa-plus-circle me-1"></i>
                                Passbook For Collection
                            </a>
                        </div> --}}

                    </div>
                    <hr>
                    @if(in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin','Branch Head']))
                        <form id="filterForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Branch</label>
                                        <select name="branch_id" id="branch_id_select" class="form-select select2" required>
                                            <option value="">Select Branches</option>
                                            @foreach($Branches as $branch)
                                                <option value="{{ $branch->id }}" {{ $branch->id == $userBranchId ? 'selected' : '' }}>
                                                    {{ $branch->branch_location }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 align-items-end" style="margin-top:25px;">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <span id="passbookForCollection" style="display: none;">
                                        <a href="#" class="btn btn-primary" id="ForCollectionButton">
                                            <i class="fas fa-plus-circle me-1"></i> Passbook For Collection
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </form>
                        <hr>
                    @endif

                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Request Number</th>
                                    <th>Branch</th>
                                    <th>Total Requested</th>
                                    <th>Date Requested</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    <div class="modal fade" id="viewPassbookTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="viewPassbookTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">
                        View Transaction :
                        <span class="text-primary" id="view_request_number"></span>
                     </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-design" id="TransactionApprovalTable" style="width: 100%;">
                            <thead>
                                <th><span class="ps-5 pe-5">Pending By</span></th>
                                <th>Pension</th>
                                <th>Releasing Image</th>
                                <th>Branch</th>
                                <th>Client</th>
                                <th>Passbook No & Bank</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>View</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="viewTransactionApprovalModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 70%;">
            <div class="modal-content">
            <div class="modal-header">
                    <div class="modal-title">
                    <div class="h5 text-uppercase fw-bold">VIEW PASSBOOK FOR COLLECTION</div>
                    </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        <div class="h6 text-uppercase fw-bold">REQUEST NUMBER : <span class="fw-bold text-primary" id="view_request_number_display"></span></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Reference No. : <span class="text-primary" id="view_reference_no"></span>
                                </div>
                                <div class="fw-bold">
                                    Transaction : <span class="text-primary">Passbook for Collection</span>
                                </div>
                                <div class="fw-bold">
                                    Date Requested : <span class="text-primary" id="view_created_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Name : <span class="text-primary" id="view_full_name"></span>
                                </div>
                                <div class="fw-bold">
                                    Pension Number : <span class="text-primary" id="view_pension_number_display"></span> /
                                    <span id="view_pension_account_type_display" class="fw-bold h6"></span> /
                                    <span id="view_pension_type_display" class="fw-bold h6"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Bank Account No : <span class="text-primary" id="view_bank_account_no"></span>
                                </div>
                                <div class="fw-bold">
                                    Bank Name : <span class="text-primary" id="view_bank_name"></span>
                                </div>
                                <div class="fw-bold">
                                    Bank Type : <span class="text-danger" id="view_atm_type"></span>
                                </div>
                                <div class="fw-bold">
                                    Collection Date : <span class="text-primary" id="view_collection_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="#" id="view_display_image"></a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-border dt-responsive wrap table-design">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:7%;">ID</th>
                                        <th style="width:17%;">Employee ID</th>
                                        <th style="width:20%;">Name</th>
                                        <th style="width:8%;">Sequence No</th>
                                        <th style="width:16%;">Date Received</th>
                                        <th style="width:9%;">Status</th>
                                        <th style="width:23%;">Transaction</th>
                                    </tr>
                                </thead>
                                <tbody id="ViewTransactionApprovalBody" class="text-center">
                                    <!-- Your data rows here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
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
            var url = '{!! route('PassbookCollectionAllTransactionData') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];
            const columns = [
                {
                    data: 'request_number',
                    name: 'request_number',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'branch_location',
                    name: 'branch_location',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'transaction_count',
                    name: 'transaction_count',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'overall_status',
                    name: 'overall_status',
                    render: function(data, type, row, meta) {
                        var badgeClass = '';
                        var textClass = '';

                        // Determine badge class based on the status
                        if (data === 'On Going') {
                            badgeClass = 'text-primary';
                            textClass = 'On Going';
                        } else if (data === 'Completed') {
                            badgeClass = 'text-success';
                            textClass = 'Completed';
                        } else if (data === 'Cancelled') {
                            badgeClass = 'text-danger';
                            textClass = 'Cancelled';
                        } else if (data === 'Returning to Branch') {
                            badgeClass = 'text-success';
                            textClass = 'Returning to Branch';
                        }
                        else {
                            badgeClass = 'text-secondary';
                            textClass = 'Unknown';
                        }

                        // Return the badge with the correct class
                        return '<span class="fw-bold h6 ' + badgeClass + '">' + textClass + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                }

            ];
            dataTable.initialize(url, columns);

            // Filtering of Transaction
                var branchId = @json($userBranchId); // Pass the authenticated user's branch ID
                var userHasBranchId = {!! Auth::user()->branch_id ? 'true' : 'false' !!};

                if (userHasBranchId) {
                    // Disable the branch select dropdown if the user has a fixed branch
                    $('#branch_id_select').val(branchId).prop('disabled', true);
                }

                $('#filterForm').submit(function(e) {
                    e.preventDefault();
                    var selectedBranch = $('#branch_id_select').val(); // Get the selected branch from the dropdown

                    // Get the base URL for filtering
                    var targetUrl = '{!! route('PassbookCollectionTransactionData') !!}';

                    // If the user does not have a fixed branch and has selected one, add branch_id to the URL
                    if (!userHasBranchId && selectedBranch) {
                        targetUrl += '?branch_id=' + selectedBranch;
                    }

                    // Update the DataTable with the filtered data
                    dataTable.table.ajax.url(targetUrl).load(); // Reload the DataTable with the new URL
                });
            // End Filtering of Transaction


            // View Transaction
                $('#FetchingDatatable').on('click', '.viewPassbookTransaction', function (e) {
                    e.preventDefault();
                    var request_number = $(this).data('request_number');

                    $.ajax({
                        url: "/PassbookCollectionAllTransactionGet",
                        type: "GET",
                        data: { request_number: request_number },
                        success: function (data) {
                            // Update request number text
                            $('#view_request_number').text(data.request_number);

                            // Initialize DataTable for the fetched data
                            $('#TransactionApprovalTable').DataTable({
                                destroy: true, // Destroy any existing instance before re-initializing
                                data: data.passbook_collection_data, // Use the array of data directly
                                pageLength: 50, // Set minimum display entries to 50
                                lengthMenu: [ [50, 55, 60, 70, 100], [50, 55, 60, 70, 100] ], // Custom length menu options
                                columns: [
                                    {
                                        data: null, // Custom rendering for transaction action and pending_to
                                        render: function (row) {
                                            const transactionAction = row.transaction_action ?? ''; // Allow null/undefined, default to empty string
                                            const pendingTo = row.pending_to ?? ''; // Allow null/undefined, default to empty string

                                            return (
                                                '<span class="fw-bold text-primary">' + transactionAction + '</span><br>' +
                                                '<span class="text-dark">' + pendingTo + '</span>'
                                            );
                                        },
                                    },
                                    {
                                        data: null, // Custom rendering for pension details
                                        render: function (row) {
                                            return (
                                                '<span class="fw-bold text-primary">' + row.atm_client_banks.client_information.pension_number + '</span><br>' +
                                                '<span class="text-dark">' + row.atm_client_banks.client_information.pension_account_type + '</span><br>' +
                                                '<span class="text-success">' + row.atm_client_banks.client_information.pension_type + '</span>'
                                            );
                                        },
                                    },
                                    { data: 'id' },
                                    { data: 'branch.branch_location' },
                                    {
                                        data: null, // Custom rendering for full name
                                        render: function (row) {
                                            var clientInfo = row.atm_client_banks.client_information;
                                            var fullName = clientInfo.last_name + ', ' + clientInfo.first_name + ' ' + (clientInfo.middle_name || '') + ' ' + (clientInfo.suffix || '');
                                            return fullName.trim();
                                        },
                                    },
                                    {
                                        data: null, // Custom rendering for bank details
                                        render: function (row) {
                                            return (
                                                '<span class="fw-bold text-success">' + row.atm_client_banks.bank_account_no + '</span><br>' +
                                                row.atm_client_banks.bank_name
                                            );
                                        },
                                    },
                                    {
                                        data: null, // Custom rendering for ATM details
                                        render: function (row) {
                                            return (
                                                '<span class="text-danger">' + row.atm_client_banks.atm_type + '</span><br>' +
                                                row.atm_client_banks.atm_status
                                            );
                                        },
                                    },
                                    {
                                        data: 'status', // Status with badge
                                        render: function (status) {
                                            var statusBadgeClass = 'badge bg-secondary';
                                            var statusTextClass = 'Unknown';

                                            if (status === 'On Going') {
                                                statusBadgeClass = 'badge bg-primary';
                                                statusTextClass = 'On Going';
                                            } else if (status === 'Cancelled') {
                                                statusBadgeClass = 'badge bg-danger';
                                                statusTextClass = 'Cancelled';
                                            } else if (status === 'Returning to Branch') {
                                                statusBadgeClass = 'badge bg-success';
                                                statusTextClass = 'Returning to Branch';
                                            } else if (status === 'Completed') {
                                                statusBadgeClass = 'badge bg-success';
                                                statusTextClass = 'Completed';
                                            }

                                            return '<span class="' + statusBadgeClass + '">' + statusTextClass + '</span>';
                                        },
                                    },
                                    {
                                        data: 'remarks', // Remarks
                                        defaultContent: '', // Handle null or undefined remarks
                                    },
                                    {
                                        data: null, // Action column with view button
                                        render: function (row) {
                                            return (
                                                `<a href="#" class="text-success viewPassbookTransactionApproval" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="View Transaction" data-transaction_id='${row.id}'>'
                                                      '<i class="fas fa-eye fs-5"></i>'
                                                </a>`
                                            );
                                        },
                                    },
                                ],
                                drawCallback: function () {
                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                                },
                                language: {
                                    searchPlaceholder: "Enter to search ...",
                                },
                                columnDefs: [
                                    {
                                        className: 'dt-center',
                                        targets: '_all', // Center align all columns
                                    },
                                ],
                                order: [[0, 'desc']], // Default order
                            });

                            // Show the modal
                            $('#viewPassbookTransactionModal').modal('show');
                        },
                        error: function (xhr, status, error) {
                            console.error('An error occurred: ' + error);
                        },
                    });
                });
                $('#datatableViewTransaction').DataTable({
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
                                title: "Please Wait...",
                                text: "Please wait for a moment",
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                            return "Please wait for a moment ....";
                        },
                    },
                });

            // View Transaction Approval
                $('#TransactionApprovalTable').on('click', '.viewPassbookTransactionApproval', function(e) {
                    e.preventDefault();
                    var transaction_id = $(this).data('transaction_id');

                    console.log(transaction_id);

                    $.ajax({
                        url: "/PassbookCollectionTransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            $('#view_collection_date').text(data.atm_client_banks?.collection_date ?? '');
                            $('#view_bank_account_no').text(data.atm_client_banks?.bank_account_no ?? '');
                            $('#view_bank_name').text(data.atm_client_banks?.bank_name ?? '');
                            $('#view_atm_type').text(data.atm_client_banks?.atm_type ?? '');
                            $('#view_atm_status').val(data.atm_client_banks?.atm_status ?? '');
                            $('#view_replacement_count').val(data.atm_client_banks?.replacement_count ?? '');

                            $('#view_request_number_display').text(data.request_number);
                            $('#view_full_name').text(data.full_name);
                            $('#view_pension_number_display').text(data.atm_client_banks?.client_information.pension_number ?? '');
                            $('#view_pension_type_display').text(data.atm_client_banks?.client_information.pension_type ?? '');
                            $('#view_pension_account_type_display').text(data.atm_client_banks?.client_information.pension_account_type ?? '');

                            let formattedCreatedDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';
                            $('#view_reference_no').text(data.reference_no);
                            $('#view_created_date').text(formattedCreatedDate);

                            $('#ViewTransactionApprovalBody').empty();
                                $.each(data.passbook_for_collection_transaction_approval, function(key, rows) {
                                    var dateApproved = rows.date_approved ? new Date(rows.date_approved).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    }) : ''; // Leave blank if null

                                    var badgeClass = '';
                                    var StatusName = '';

                                    switch (rows.status) {
                                        case 'Completed':
                                            badgeClass = 'badge bg-success';
                                            StatusName = 'Completed';
                                            break;
                                        case 'Pending':
                                            badgeClass = 'badge bg-warning';
                                            StatusName = 'Pending';
                                            break;
                                        case 'Returning to Branch':
                                            badgeClass = 'badge bg-success';
                                            StatusName = 'Returning to Branch';
                                            break;
                                        case 'Cancelled':
                                            badgeClass = 'badge bg-danger';
                                            StatusName = 'Cancelled';
                                            break;
                                        case 'Stand By':
                                            badgeClass = 'badge bg-primary';
                                            StatusName = 'Stand By';
                                            break;
                                        default:
                                            badgeClass = 'badge bg-secondary';
                                            StatusName = 'Unknown';
                                            break;
                                    }

                                    // Use optional chaining or fallback values for null checks
                                    var employeeName = rows.employee?.name || ''; // If `rows.employee` or `rows.employee.name` is null, fallback to ''
                                    var groupName = rows.data_user_group?.group_name || ''; // If null, fallback to ''
                                    var transactionAction = rows.data_transaction_action?.name || ''; // If null, fallback to ''

                                    // Append new row to the table
                                    var newRow = '<tr>' +
                                                    '<td>'  + (rows.id || '') + '</td>' +
                                                    '<td>' + (rows.employee_id || '') + '</td>' +
                                                    '<td>'  + '<span class="fw-bold h6 text-primary">' + employeeName + '</span><br>' + groupName + '</td>' +
                                                    '<td>' + (rows.sequence_no || '') + '</td>' +
                                                    '<td>' + dateApproved + '</td>' +
                                                    '<td><span class="' + badgeClass + '">' + StatusName + '</span></td>' +
                                                    '<td>' + transactionAction + '</td>' +
                                                '</tr>';

                                    $('#ViewTransactionApprovalBody').append(newRow);
                                });

                            $('#viewTransactionApprovalModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });
            // View Transaction Approval

        });
    </script>

@endsection
