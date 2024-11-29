@extends('layouts.master')

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
                            <h4 class="card-title">Passbook For Collection Transaction</h4>
                            <p class="card-title-desc ms-2">
                                Where Head Offices and Branches can view their Transactions.
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
                                    <th>Action</th>
                                    <th>Pending By</th>
                                    <th>Reference No</th>
                                    <th>Client</th>
                                    <th>Branch</th>
                                    <th>Pension No. / Type</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    <th>Passbook</th>
                                    <th>Type / Status</th>
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

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('PassbookCollectionTransactionData') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];
            // const columns = [
            //     {
            //         data: 'request_number',
            //         name: 'request_number',
            //         render: function(data, type, row, meta) {
            //             return '<span>' + data + '</span>';
            //         },
            //         orderable: true,
            //         searchable: true,
            //     },
            //     {
            //         data: 'branch_location',
            //         name: 'branch_location',
            //         render: function(data, type, row, meta) {
            //             return '<span>' + data + '</span>';
            //         },
            //         orderable: true,
            //         searchable: true,
            //     },
            //     {
            //         data: 'transaction_count',
            //         name: 'transaction_count',
            //         render: function(data, type, row, meta) {
            //             return '<span>' + data + '</span>';
            //         },
            //         orderable: true,
            //         searchable: true,
            //     },
            //     {
            //         data: 'created_at',
            //         name: 'created_at',
            //         render: function(data, type, row, meta) {
            //             return '<span>' + data + '</span>';
            //         },
            //         orderable: true,
            //         searchable: true,
            //     },
            //     {
            //         data: 'overall_status',
            //         name: 'overall_status',
            //         render: function(data, type, row, meta) {
            //             var badgeClass = '';
            //             var textClass = '';

            //             // Determine badge class based on the status
            //             if (data === 'On Going') {
            //                 badgeClass = 'text-primary';
            //                 textClass = 'On Going';
            //             } else if (data === 'Completed') {
            //                 badgeClass = 'text-success';
            //                 textClass = 'Completed';
            //             } else if (data === 'Cancelled') {
            //                 badgeClass = 'text-danger';
            //                 textClass = 'Cancelled';
            //             } else if (data === 'Returning to Branch') {
            //                 badgeClass = 'text-success';
            //                 textClass = 'Returning to Branch';
            //             }
            //             else {
            //                 badgeClass = 'text-secondary';
            //                 textClass = 'Unknown';
            //             }

            //             // Return the badge with the correct class
            //             return '<span class="fw-bold h6 ' + badgeClass + '">' + textClass + '</span>';
            //         },
            //         orderable: true,
            //         searchable: true,
            //     }

            // ];
            const columns = [
                {
                    data: 'action',
                    name: 'action',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'pending_to',
                    render: function(data, type, row, meta) {
                        return `<span class="fw-bold h6 text-primary">${row.transaction_name}</span><br>
                                <span class="fw-bold">${row.pending_to}</span>`;

                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'reference_no',
                    name: 'reference_no',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'client_banks_id',
                    render: function(data, type, row, meta) {
                        if (row.atm_client_banks && row.atm_client_banks.client_information) {
                            const firstName = row.atm_client_banks.client_information.first_name || '';
                            const middleName = row.atm_client_banks.client_information.middle_name ? ' ' + row.atm_client_banks.client_information.middle_name : '';
                            const lastName = row.atm_client_banks.client_information.last_name ? ' ' + row.atm_client_banks.client_information.last_name : '';
                            const suffix = row.atm_client_banks.client_information.suffix ? ', ' + row.atm_client_banks.client_information.suffix : '';
                            return `<span>${firstName}${middleName}${lastName}${suffix}</span>`;
                        }
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return row.branch ? '<span>' + row.branch.branch_location + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (row.atm_client_banks && row.atm_client_banks.client_information) {
                            const PensionNumber = row.atm_client_banks.client_information.pension_number || '';
                            const PensionType = row.atm_client_banks.client_information.pension_type ? ' ' + row.atm_client_banks.client_information.pension_type : '';
                            const PensionAccountType = row.atm_client_banks.client_information.pension_account_type ? ' ' + row.atm_client_banks.client_information.pension_account_type : '';

                            return `<span class="fw-bold text-primary h6 pension_number_mask_display">${PensionNumber}</span><br>
                                <span class="fw-bold">${PensionType}</span><br>
                                <span class="fw-bold text-success">${PensionAccountType}</span>`;
                        }
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (row.atm_client_banks && row.atm_client_banks.client_information) {
                            const createdAt = row.atm_client_banks.client_information.created_at ? new Date(row.atm_client_banks.client_information.created_at) : null;
                            const formattedDate = createdAt ? createdAt.toLocaleDateString('en-US',
                                {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })
                                : '';

                            return `<span class="text-muted">${formattedDate}</span>`;
                        }
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (row.atm_client_banks && row.atm_client_banks.client_information) {
                            const BirthDate = row.atm_client_banks.client_information.birth_date ? new Date(row.atm_client_banks.client_information.birth_date) : null;
                            const formattedBirthDate = BirthDate ? BirthDate.toLocaleDateString('en-US',
                                {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })
                                : '';

                            return `<span class="text-muted">${formattedBirthDate}</span>`;
                        }
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        // Initialize the variable for replacement count
                        let replacementCountDisplay = '';

                        // Check if replacement_count is greater than 0
                        if (row.atm_client_banks.replacement_count > 0) {
                        replacementCountDisplay = `<span class="text-danger fw-bold h6"> / ${row.atm_client_banks.replacement_count}</span>`;
                        }

                        return `<span class="fw-bold h6" style="color: #5AAD5D;">${row.atm_client_banks.bank_account_no}</span>
                                ${replacementCountDisplay}<br>
                                <span>${row.atm_client_banks.bank_name}</span>`;

                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let BankStatus = ''; // Define BankStatus outside the if block with a default value
                        let atmTypeClass = ''; // Variable to hold the class based on atm_type

                        BankStatus = row.atm_client_banks.atm_status;

                        // Determine the text color based on atm_type
                        switch (row.atm_client_banks.atm_type) {
                            case 'ATM':
                                atmTypeClass = 'text-primary';
                                break;
                            case 'Passbook':
                                atmTypeClass = 'text-danger';
                                break;
                            case 'Sim Card':
                                atmTypeClass = 'text-info';
                                break;
                            default:
                                atmTypeClass = 'text-secondary'; // Default color if none match
                        }

                        return `<span class="${atmTypeClass}">${row.atm_client_banks.atm_type}</span><br>
                                <span class="fw-bold h6">${BankStatus}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },

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
                        url: "/PassbookCollectionTransactionGet",
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
