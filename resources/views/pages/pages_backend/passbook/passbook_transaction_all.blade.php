@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Passbook Transaction @endslot
        @slot('title') Passbook Transaction @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Passbook For Collection Transaction</h4>
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
                    @endif
                    <hr>


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
                        <table class="table table-design" id="datatableViewTransaction">
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
                            <tbody id="TransactionApprovalBody">

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
                $('#FetchingDatatable').on('click', '.viewPassbookTransaction', function(e) {
                    e.preventDefault();
                    var request_number = $(this).data('request_number');

                    $.ajax({
                        url: "/PassbookCollectionAllTransactionGet",
                        type: "GET",
                        data: { request_number : request_number },
                        success: function(data) {
                            $('#view_request_number').text(data.request_number);

                            // Clear the table body to remove previously appended rows
                            $('#TransactionApprovalBody').empty();

                            data.passbook_collection_data.forEach(function (rows) {
                                var lastName = rows.atm_client_banks.client_information.last_name;
                                var firstName = rows.atm_client_banks.client_information.first_name;
                                var middleName = rows.atm_client_banks.client_information.middle_name;
                                var suffix = rows.atm_client_banks.client_information.suffix;
                                var fullName = lastName + ', ' + firstName + ' ' + (middleName || '') + ' ' + (suffix || '');

                                var pensionNumber = rows.atm_client_banks.client_information.pension_number;
                                var pensionAccountType = rows.atm_client_banks.client_information.pension_account_type;
                                var pensionType = rows.atm_client_banks.client_information.pension_type;

                                var statusBadgeClass;
                                var statusTextClass;
                                var statusText = rows.status; // Assuming `rows.status` holds the status

                                // Determine the badge class based on status
                                if (statusText === "On Going") {
                                    statusBadgeClass = 'badge bg-primary';
                                    statusTextClass = 'On Going';
                                } else if (statusText === "Cancelled") {
                                    statusBadgeClass = 'badge bg-danger';
                                    statusTextClass = 'Cancelled';
                                } else if (statusText === "Returning to Branch") {
                                    statusBadgeClass = 'badge bg-success';
                                    statusTextClass = 'Returning to Branch';
                                } else if (statusText === "Completed") {
                                    statusBadgeClass = 'badge bg-success';
                                    statusTextClass = 'Completed';
                                } else {
                                    statusBadgeClass = 'badge bg-secondary'; // Default for unknown statuses
                                    statusTextClass = 'Unknown';
                                }

                                var newRow = '<tr>' +
                                                '<td>'
                                                    + '<span class="fw-bold text-primary">' + rows.transaction_action + '</span><br>'
                                                    + '<span class="text-dark">' + rows.pending_to + '</span>' +
                                                '</td>' +
                                                '<td>'
                                                    + '<span class="fw-bold text-primary">' + pensionNumber + '</span><br>'
                                                    + '<span class="text-dark">' + pensionAccountType + '</span><br>'
                                                    + '<span class="text-success">' + pensionType + '</span>' +
                                                '</td>' +
                                                '<td>' + rows.id + '</td>' +
                                                '<td>' + rows.branch.branch_location + '</td>' +
                                                '<td>' + fullName + '</td>' +
                                                '<td>'
                                                    + '<span class="fw-bold text-success">' + rows.atm_client_banks.bank_account_no + '</span><br>'
                                                    + rows.atm_client_banks.bank_name +
                                                '</td>' +
                                                '<td>'
                                                    + '<span class="text-danger">' + rows.atm_client_banks.atm_type + '</span><br>'
                                                    + rows.atm_client_banks.atm_status +
                                                '</td>' +
                                                '<td>' + '<span class="' + statusBadgeClass + '">' + statusTextClass + '</span>' + '</td>' + // Status badge here
                                                '<td>' + (rows.remarks !== null ? rows.remarks : '') + '</td>' +
                                                '<td>' +
                                                    '<a href="#" class="text-success viewPassbookTransaction" data-id="' + rows.id + '">' +
                                                        '<i class="fas fa-eye fs-5"></i>' + // Add the icon here
                                                    '</a>' +
                                                '</td>' +

                                             '</tr>';
                                $('#TransactionApprovalBody').append(newRow);
                            });


                            $('#viewPassbookTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
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

        });
    </script>

@endsection
