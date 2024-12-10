@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Passbook Transaction @endslot
        @slot('title') Passbook Transactions @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6 text-start">
                            <h4 class="card-title">Passbook For Collection Transaction</h4>
                            <p class="card-title-desc ms-2">
                                Where Head Offices and Branches can view their specific Transactions.
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if(in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin','Branch Head']))
                                <form id="filterForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
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

                                        <div class="col-md-6 align-items-end" style="margin-top:25px;">
                                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Action</th>
                                    <th>Pending By</th>
                                    <th>Reference No</th>
                                    <th>Client</th>
                                    <th>Pension No. / Type</th>
                                    <th>Passbook No.</th>
                                    <th>Type / Status</th>
                                    <th>Collection Date</th>
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

    <div class="modal fade" id="viewTransactionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <div class="h6 text-uppercase fw-bold">REQUEST NUMBER : <span class="fw-bold text-primary" id="view_request_number"></span></div>
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

    <div class="modal fade" id="editTransactionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
            <div class="modal-content">
                <div class="modal-header">
                        <div class="modal-title">
                        <div class="h5 text-uppercase fw-bold">EDIT PASSBOOK FOR COLLECTION</div>
                        </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('PassbookCollectionTransactionUpdate') }}" method="POST" id="updateTransactionValidateForm">
                        @csrf
                        <input type="hidden" name="transaction_id" id="edit_transaction_id">
                        <div class="h6 text-uppercase fw-bold">REQUEST NUMBER : <span class="fw-bold text-primary" id="edit_request_number"></span></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Reference No. : <span class="text-primary" id="edit_reference_no"></span>
                                </div>
                                <div class="fw-bold">
                                    Transaction : <span class="text-primary">Passbook for Collection</span>
                                </div>
                                <div class="fw-bold">
                                    Date Requested : <span class="text-primary" id="edit_created_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Name : <span class="text-primary" id="edit_full_name"></span>
                                </div>
                                <div class="fw-bold">
                                    Pension Number : <span class="text-primary" id="edit_pension_number_display"></span> /
                                    <span id="edit_pension_account_type_display" class="fw-bold h6"></span> /
                                    <span id="edit_pension_type_display" class="fw-bold h6"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Bank Account No : <span class="text-primary" id="edit_bank_account_no"></span>
                                </div>
                                <div class="fw-bold">
                                    Bank Name : <span class="text-primary" id="edit_bank_name"></span>
                                </div>
                                <div class="fw-bold">
                                    Bank Type : <span class="text-danger" id="edit_atm_type"></span>
                                </div>
                                <div class="fw-bold">
                                    Collection Date : <span class="text-primary" id="edit_collection_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="#" id="edit_display_image"></a>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="form-group col-2 mb-3">
                            <label class="fw-bold h6">Transaction Status</label>
                            <select name="transaction_status" id="edit_transaction_status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="On Going">On Going</option>
                                    <option value="Returning to Branch">Returning to Branch</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                            </select>
                            </div>

                            <!-- Cancellation Remarks -->
                            <div class="form-group col-2 mb-3" id="cancellation_remarks_group" style="display:none;">
                                <label class="fw-bold h6">Cancellation Remarks</label>
                                <input type="text" class="form-control" name="cancellation_remarks" id="edit_passbook_remarks" placeholder="Cancellation Remarks">
                            </div>

                            <!-- Cancellation Date -->
                            <div class="form-group col-2 mb-3" id="cancellation_date_group" style="display:none;">
                                <label class="fw-bold h6">Cancellation Date</label>
                                <input type="datetime-local" class="form-control" name="cancellation_date" id="edit_date_of_decline">
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive table-scrollable">
                            <table class="table table-border dt-responsive wrap table-design">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:7%;">ID</th>
                                        <th style="width:20%;">Employee ID</th>
                                        <th style="width:20%;">Name</th>
                                        <th style="width:8%;">Sequence No</th>
                                        <th style="width:15%;">Date Received</th>
                                        <th style="width:15%;">Status</th>
                                        <th style="width:15%;">Transaction</th>
                                    </tr>
                                </thead>
                                <tbody id="EditTransactionApprovalBody" class="text-center">
                                    <!-- Your data rows here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelTransactionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                    <div class="modal-title">
                    <div class="h5 text-uppercase fw-bold">CANCELLED PASSBOOK FOR COLLECTION</div>
                    </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <form action="{{ route('PassbookCollectionTransactionCancelled') }}" method="POST" id="cancelledTransactionValidateForm">
                        @csrf
                        <input type="hidden" name="transaction_id" id="cancelled_transaction_id">
                        <div class="h6 text-uppercase fw-bold">REQUEST NUMBER : <span class="fw-bold text-primary" id="cancelled_request_number"></span></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Reference No. : <span class="text-primary" id="cancelled_reference_no"></span>
                                </div>
                                <div class="fw-bold">
                                    Transaction : <span class="text-primary">Passbook for Collection</span>
                                </div>
                                <div class="fw-bold">
                                    Date Requested : <span class="text-primary" id="cancelled_created_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Name : <span class="text-primary" id="cancelled_full_name"></span>
                                </div>
                                <div class="fw-bold">
                                    Pension Number : <span class="text-primary" id="cancelled_pension_number_display"></span> /
                                    <span id="cancelled_pension_account_type_display" class="fw-bold h6"></span> /
                                    <span id="cancelled_pension_type_display" class="fw-bold h6"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Bank Account No : <span class="text-primary" id="cancelled_bank_account_no"></span>
                                </div>
                                <div class="fw-bold">
                                    Bank Name : <span class="text-primary" id="cancelled_bank_name"></span>
                                </div>
                                <div class="fw-bold">
                                    Bank Type : <span class="text-danger" id="cancelled_atm_type"></span>
                                </div>
                                <div class="fw-bold">
                                    Collection Date : <span class="text-primary" id="cancelled_collection_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="#" id="cancelled_display_image"></a>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div id="forCancellation" style="display:none;">
                                <div class="row">
                                    <div class="col-md-5 form-group mb-3">
                                        <label class="fw-bold h6">Cancellation Remarks</label>
                                                    <textarea id="cancelled_remarks_value" name="cancellation_remarks" rows="5" cols="50"
                                                            class="form-control" style="resize:none;" minlength="0" maxlength="200" placeholder="Remarks"></textarea>
                                    </div>
                            </div>
                            </div>
                            <div id="AlreadyCancelled" style="display:none;">
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="fw-bold h6">Cancellation Remarks</label>
                                                    <textarea id="cancelled_remarks_value_cancelled" name="cancellation_remarks_value" rows="5" cols="50"
                                                            class="form-control" style="resize:none;" minlength="0" maxlength="200" placeholder="Remarks" readonly></textarea>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="fw-bold h6">Cancellation Date</label>
                                            <input type="text" class="form-control" id="cancelled_date_cancelled" readonly>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="fw-bold h6">Cancelled By</label>
                                            <input type="text" class="form-control" id="cancelled_by_name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <span id="ForCancellationButton" style="display:none;">
                                <button type="submit" class="btn btn-danger">Cancelled</button>
                            </span>
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
            var url = '{!! route('PassbookCollectionTransactionData') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];
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
                        let branchLocation = ''; // Default value in case branch or location is missing
                        if (row.branch && row.branch.branch_location) {
                            branchLocation = row.branch.branch_location;
                        }

                        if (row.atm_client_banks && row.atm_client_banks.client_information) {
                            const firstName = row.atm_client_banks.client_information.first_name || '';
                            const middleName = row.atm_client_banks.client_information.middle_name
                                ? ' ' + row.atm_client_banks.client_information.middle_name
                                : '';
                            const lastName = row.atm_client_banks.client_information.last_name
                                ? ' ' + row.atm_client_banks.client_information.last_name
                                : '';
                            const suffix = row.atm_client_banks.client_information.suffix
                                ? ', ' + row.atm_client_banks.client_information.suffix
                                : '';

                            return `
                                <span>${firstName}${middleName}${lastName}${suffix}</span><br>
                                <span class="text-primary">${branchLocation}</span>
                            `;
                        }

                        return ''; // Fallback if no client information exists
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'pension_details',
                    render: function(data, type, row, meta) {
                        return `<span>${row.pension_details}</span>`;

                    },
                    orderable: true,
                    searchable: true,
                },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         if (row.atm_client_banks && row.atm_client_banks.client_information) {
                //             const createdAt = row.atm_client_banks.client_information.created_at ? new Date(row.atm_client_banks.client_information.created_at) : null;
                //             const formattedDate = createdAt ? createdAt.toLocaleDateString('en-US',
                //                 {
                //                     year: 'numeric',
                //                     month: 'long',
                //                     day: 'numeric'
                //                 })
                //                 : '';

                //             return `<span class="text-muted">${formattedDate}</span>`;
                //         }
                //         return '';
                //     },
                //     orderable: true,
                //     searchable: true,
                // },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         if (row.atm_client_banks && row.atm_client_banks.client_information) {
                //             const BirthDate = row.atm_client_banks.client_information.birth_date ? new Date(row.atm_client_banks.client_information.birth_date) : null;
                //             const formattedBirthDate = BirthDate ? BirthDate.toLocaleDateString('en-US',
                //                 {
                //                     year: 'numeric',
                //                     month: 'long',
                //                     day: 'numeric'
                //                 })
                //                 : '';

                //             return `<span class="text-muted">${formattedBirthDate}</span>`;
                //         }
                //         return '';
                //     },
                //     orderable: true,
                //     searchable: true,
                // },
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
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (row.atm_client_banks) {
                            return `<span>${row.atm_client_banks.collection_date}</span>`;
                        }
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        let badgeClass = '';
                        let statusClass = '';

                        // Determine the badge class and status label based on status value
                        switch (row.status) {
                            case 'On Going':
                                badgeClass = 'pt-1 pb-1 ps-2 ps-2 pe-2 badge bg-warning';
                                statusClass = 'On Going';
                                break;
                            case 'Cancelled':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-danger';
                                statusClass = 'Cancelled';
                                break;
                            case 'Completed':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-success';
                                statusClass = 'Completed';
                                break;
                            case 'Returning to Branch':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-success';
                                statusClass = 'Returning to Branch';
                                break;
                            default:
                                badgeClass = 'badge bg-secondary'; // Default badge class
                                statusClass = 'Unknown Status';
                        }

                        // Return the status wrapped in a span with the appropriate badge and status class
                        return `<span class="${badgeClass}">${statusClass}</span>`;
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
                $('#FetchingDatatable').on('click', '.viewTransaction', function(e) {
                    e.preventDefault();
                    var transaction_id = $(this).data('transaction_id');

                    $.ajax({
                        url: "/PassbookCollectionTransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            $('#view_collection_date').text(data.atm_client_banks?.collection_date ?? NULL);
                            $('#view_bank_account_no').text(data.atm_client_banks?.bank_account_no ?? NULL);
                            $('#view_bank_name').text(data.atm_client_banks?.bank_name ?? NULL);
                            $('#view_atm_type').text(data.atm_client_banks?.atm_type ?? NULL);
                            $('#view_atm_status').val(data.atm_client_banks?.atm_status ?? NULL);
                            $('#view_replacement_count').val(data.atm_client_banks?.replacement_count ?? NULL);

                            $('#view_request_number').text(data.request_number);
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

                            $('#viewTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });
            // View Transaction

            // Edit Transaction
                $('#FetchingDatatable').on('click', '.editTransaction', function(e) {
                    e.preventDefault();
                    var transaction_id = $(this).data('transaction_id');

                    $.ajax({
                        url: "/PassbookCollectionTransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            $('#edit_transaction_id').val(data.id);
                            $('#edit_collection_date').text(data.atm_client_banks?.collection_date ?? '');
                            $('#edit_bank_account_no').text(data.atm_client_banks?.bank_account_no ?? '');
                            $('#edit_bank_name').text(data.atm_client_banks?.bank_name ?? '');
                            $('#edit_atm_type').text(data.atm_client_banks?.atm_type ?? '');
                            $('#edit_atm_status').val(data.atm_client_banks?.atm_status ?? '');
                            $('#edit_replacement_count').val(data.atm_client_banks?.replacement_count ?? '');

                            $('#edit_transaction_status').val(data.status).trigger('change');

                            if (data.status == 'Cancelled'){
                                $('#cancellation_remarks_group').show();
                                $('#cancellation_date_group').show();
                            } else {
                                $('#cancellation_remarks_group').hide();
                                $('#cancellation_date_group').hide();
                            }

                            $('#edit_passbook_remarks').val(data.remarks)
                            $('#edit_date_of_decline').val(data.cancelled_date)

                            $('#edit_request_number').text(data.request_number);
                            $('#edit_full_name').text(data.full_name);
                            $('#edit_pension_number_display').text(data.atm_client_banks?.client_information.pension_number ?? '');
                            $('#edit_pension_type_display').text(data.atm_client_banks?.client_information.pension_type ?? '');
                            $('#edit_pension_account_type_display').text(data.atm_client_banks?.client_information.pension_account_type ?? '');

                            let formattedCreatedDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';
                            $('#edit_reference_no').text(data.reference_no);
                            $('#edit_created_date').text(formattedCreatedDate);

                                                        // Fetch elog_users data from the server
                            $.ajax({
                                url: 'UserSelect',
                                method: 'GET',
                                dataType: 'json',
                                success: function(GetUsers) {
                                    var hasUsers = GetUsers && GetUsers.length > 0;

                                    $('#EditTransactionApprovalBody').empty();
                                        $.each(data.passbook_for_collection_transaction_approval, function(key, rows) {
                                            var dateApprovedFormatted = rows.date_approved ? rows.date_approved.replace(' ', 'T').slice(0, 16) : '';

                                            var statusOptions = {
                                                'Completed': 'Completed',
                                                'Pending': 'Pending',
                                                'Returning to Branch': 'Received By Branch',
                                                'Cancelled': 'Cancelled',
                                                'Stand By': 'Stand By'
                                            };

                                            var selectedStatus = rows.status || '';
                                            var statusSelect = '<select class="form-control" name="status[]">';
                                            $.each(statusOptions, function(value, text) {
                                                statusSelect += '<option value="' + value + '" ' + (value == selectedStatus ? 'selected' : '') + '>' + text + '</option>';
                                            });
                                            statusSelect += '</select>';

                                            var employeeSelect = '<select name="employee_id[]" class="employee_select form-select select2">';
                                            employeeSelect += '<option value="">Select Employee</option>';

                                            if (hasUsers) {
                                                $.each(GetUsers, function(index, user) {
                                                    // Check if rows.employee_id matches user.employee_code; if null/empty, default to "Select Employee"
                                                    var selected = (rows.employee_id == user.employee_id) ? 'selected' : '';
                                                    employeeSelect += '<option value="' + user.employee_id + '" ' + selected + '>' + user.employee_id + ' - ' + user.name + '</option>';
                                                });
                                            } else {
                                                // Show "Select Employee" if no employees are available
                                                employeeSelect += '<option value="" disabled>No employees available</option>';
                                            }

                                            employeeSelect += '</select>';

                                            // Use optional chaining or fallback values for null checks
                                            var employeeName = rows.employee?.name || ''; // If `rows.employee` or `rows.employee.name` is null, fallback to ''
                                            var groupName = rows.data_user_group?.group_name || ''; // If null, fallback to ''
                                            var transactionAction = rows.data_transaction_action?.name || ''; // If null, fallback to ''

                                            // Append new row to the table
                                            var newRow = '<tr>' +
                                                            '<td>'
                                                                + rows.id +
                                                                '<input type="hidden" name="approval_id[]" value="'+ rows.id +'">' +
                                                            '</td>' +
                                                            '<td>' +
                                                                '<div class="form-group">' + employeeSelect + '</div>' +
                                                            '</td>' +
                                                            '<td>'  + '<span class="fw-bold h6 text-primary">' + employeeName + '</span><br>' + groupName + '</td>' +
                                                            '<td>' + (rows.sequence_no || '') + '</td>' +
                                                            '<td>' + '<input type="datetime-local" class="form-control" name="date_approved[]" value="'+ dateApprovedFormatted +'"</td>' +
                                                            '<td>' + statusSelect + '</td>' +
                                                            '<td>' + transactionAction + '</td>' +
                                                        '</tr>';

                                            $('#EditTransactionApprovalBody').append(newRow);
                                        });

                                        $('.employee_select').select2({
                                            dropdownParent: $('#editTransactionModal')
                                        });

                                        // Serverside
                                        // $('.employee_select').select2({
                                        //     dropdownParent: $('#editTransactionModal'),
                                        //     placeholder: 'Search for Employee',
                                        //     allowClear: true, // Enables the clear button
                                        //     ajax: {
                                        //         url: 'UserSelectServerSide', // Replace with your server-side endpoint
                                        //         dataType: 'json',
                                        //         delay: 250,
                                        //         data: function(params) {
                                        //             return {
                                        //                 search: params.term,
                                        //                 page: params.page || 1
                                        //             };
                                        //         },
                                        //         processResults: function(data, params) {
                                        //             params.page = params.page || 1;

                                        //             return {
                                        //                 results: $.map(data.users, function(user) {
                                        //                     return {
                                        //                         id: user.employee_id,
                                        //                         text: user.employee_id + ' - ' + user.name
                                        //                     };
                                        //                 }),
                                        //                 pagination: {
                                        //                     more: data.pagination.more
                                        //                 }
                                        //             };
                                        //         }
                                        //     },
                                        //     minimumInputLength: 1
                                        // });

                                    }
                            });

                            $('#editTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function UpdateTransactionModal() {
                    $('#editTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $("#edit_transaction_status").on("change", function() {
                    const transaction_status = $("#edit_transaction_status").val();
                    if(transaction_status == 'Cancelled'){
                        $('#cancellation_remarks_group').show();
                        $('#cancellation_date_group').show();
                    } else {
                        $('#cancellation_remarks_group').hide();
                        $('#cancellation_date_group').hide();
                    }
                });

                $('#updateTransactionValidateForm').validate({
                    rules: {
                        remarks: {
                            required: true
                        }
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
                                    var formData = new FormData(form);
                                    $.ajax({
                                        url: form.action,
                                        type: form.method,
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(response) {

                                            if (typeof response === 'string') {
                                                var res = JSON.parse(response);
                                            } else {
                                                var res = response; // If it's already an object
                                            }

                                            if (res.status === 'success')
                                            {
                                                UpdateTransactionModal();
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
                                            }
                                            else if (res.status === 'error')
                                            {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: res.message,
                                                    icon: 'error',
                                                });
                                            }
                                            else
                                            {
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
            // Edit Transaction

            // Cancel Transaction
                $('#FetchingDatatable').on('click', '.cancelTransaction', function(e) {
                    e.preventDefault();
                    var transaction_id = $(this).data('transaction_id');

                    $.ajax({
                        url: "/PassbookCollectionTransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            $('#cancelled_transaction_id').val(data.id);
                            $('#cancelled_bank_account_no').text(data.atm_client_banks?.bank_account_no ?? '');
                            $('#cancelled_bank_name').text(data.atm_client_banks?.bank_name ?? '');
                            $('#cancelled_collection_date').text(data.atm_client_banks?.collection_date ?? '');
                            $('#cancelled_atm_type').text(data.atm_client_banks?.atm_type ?? '');
                            $('#cancelled_atm_status').val(data.atm_client_banks?.atm_status ?? '');
                            $('#cancelled_replacement_count').val(data.atm_client_banks?.replacement_count ?? '');
                            $('#cancellation_remarks').val(data.remarks ?? '');

                            if (data.status === 'Cancelled'){
                                $('#AlreadyCancelled').show();
                                $('#forCancellation').hide();
                                $('#ForCancellationButton').hide();
                            } else {
                                $('#AlreadyCancelled').hide();
                                $('#forCancellation').show();
                                $('#ForCancellationButton').show();
                            }

                            $('#cancelled_remarks_value_cancelled').val(data.remarks ?? '');
                            $('#cancelled_by_cancelled').val(data.cancelled_by_employee_id ?? '');
                            $('#cancelled_by_name').val((data.cancelled_by_employee_id ?? '') + ' - ' + (data.cancelled_by?.name ?? ''));

                            $('#cancelled_date_cancelled').val(
                                data.cancelled_date
                                    ? new Date(data.cancelled_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) +
                                    ' - ' +
                                    new Date(data.cancelled_date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })
                                    : ''
                            );

                            $('#cancelled_request_number').text(data.request_number);
                            $('#cancelled_full_name').text(data.full_name);
                            $('#cancelled_pension_number_display').text(data.atm_client_banks.client_information.pension_number ?? '');
                            $('#cancelled_pension_type_display').text(data.atm_client_banks.client_information.pension_type ?? '');
                            $('#cancelled_pension_account_type_display').text(data.atm_client_banks.client_information.pension_account_type ?? '');

                            let formattedCreatedDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';
                            $('#cancelled_reference_no').text(data.reference_no);
                            $('#cancelled_created_date').text(formattedCreatedDate);

                            $('#cancelTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function CancelledTransactionModal() {
                    $('#cancelTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#cancelledTransactionValidateForm').validate({
                    rules: {
                        remarks: {
                            required: true
                        }
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
                                    var formData = new FormData(form);
                                    $.ajax({
                                        url: form.action,
                                        type: form.method,
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(response) {

                                            if (typeof response === 'string') {
                                                var res = JSON.parse(response);
                                            } else {
                                                var res = response; // If it's already an object
                                            }

                                            if (res.status === 'success')
                                            {
                                                CancelledTransactionModal();
                                                Swal.fire({
                                                    title: 'Successfully Updated!',
                                                    text: 'Transaction is successfully Cancelled!',
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
                                            }
                                            else if (res.status === 'error')
                                            {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: res.message,
                                                    icon: 'error',
                                                });
                                            }
                                            else
                                            {
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
            // Cancel Transaction

        });
    </script>

    <style>
        .table-scrollable {
            max-height: 400px; /* Adjust the height as needed */
            overflow-y: auto;
        }
    </style>

@endsection
