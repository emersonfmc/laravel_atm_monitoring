@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM Monitoring @endslot
        @slot('title') Branch Office ATM Lists @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Branch Office ATM Lists</h4>
                            <p class="card-title-desc">
                                A Centralized Record of all ATMs managed by the branch office
                            </p>
                        </div>
                        {{-- <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAreaModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Area</button>
                        </div> --}}
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Action</th>
                                    <th>Transaction / Pending By</th>
                                    <th>Reference No</th>
                                    <th>Client</th>
                                    <th>Branch</th>
                                    <th>Pension No. / Type</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    <th>Box</th>
                                    <th>ATM / Passbook / Simcard No & Bank</th>
                                    <th>PIN Code</th>
                                    <th>Status</th>
                                    <th>QR</th>
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

    <div class="modal fade" id="ReleasingTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="ReleasingTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Releasing Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="release_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="release_fullname" class="fw-bold h4"></div>
                                    <span id="release_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="release_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="release_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="release_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Account Number</label>
                                        <input type="text" class="form-control" id="release_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="release_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="release_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="release_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="release_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ReleasingWithBalanceTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="ReleasingWithBalanceTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Releasing w/ Balance Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="release_balance_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="release_balance_fullname" class="fw-bold h4"></div>
                                    <span id="release_balance_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="release_balance_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="release_balance_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="release_balance_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Account Number</label>
                                        <input type="text" class="form-control" id="release_balance_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="release_balance_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="release_balance_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="release_balance_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="release_balance_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="BorrowTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="BorrowTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Returning of Borrow Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="borrow_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="borrow_fullname" class="fw-bold h4"></div>
                                    <span id="borrow_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="borrow_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="borrow_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="borrow_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Account Number</label>
                                        <input type="text" class="form-control" id="borrow_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="borrow_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="borrow_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="borrow_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="borrow_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ReplacementTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="ReplacementTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Replacement of ATM / Passbook / Simcard Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="replacement_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="replacement_fullname" class="fw-bold h4"></div>
                                    <span id="replacement_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="replacement_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="replacement_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="replacement_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Account Number</label>
                                        <input type="text" class="form-control" id="replacement_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="replacement_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="replacement_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="replacement_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="replacement_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CancelledLoanTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="CancelledLoanTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Cancelled Loan Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="cancelled_loan_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="cancelled_loan_fullname" class="fw-bold h4"></div>
                                    <span id="cancelled_loan_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="cancelled_loan_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="cancelled_loan_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="cancelled_loan_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Account Number</label>
                                        <input type="text" class="form-control" id="cancelled_loan_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="cancelled_loan_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="cancelled_loan_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="cancelled_loan_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="cancelled_loan_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
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
            var url = '{!! route('BranchOfficeData') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];
            const columns = [
                {
                    data: null,
                    name: 'action', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return row.action; // Use the action rendered from the server
                    },
                    orderable: false,
                    searchable: false,
                },
                // Transaction Type and Pending By
                {
                    data: 'pending_to',
                    name: 'pending_to',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + row.pending_to + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                // Reference No
                {
                    data: 'transaction_number',
                    name: 'transaction_number',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'client_information_id',
                    name: 'client_information.first_name',
                    render: function(data, type, row, meta) {
                        if (row.client_information) {
                            const firstName = row.client_information.first_name || '';
                            const middleName = row.client_information.middle_name ? ' ' + row.client_information.middle_name : '';
                            const lastName = row.client_information.last_name ? ' ' + row.client_information.last_name : '';
                            const suffix = row.client_information.suffix ? ', ' + row.client_information.suffix : '';
                            return `<span>${firstName}${middleName}${lastName}${suffix}</span>`;
                        }
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'branch_id',
                    name: 'branch.branch_location',
                    render: function(data, type, row, meta) {
                        return row.branch ? '<span>' + row.branch.branch_location + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },


                {
                    data: 'client_information_id',
                    name: 'client_information.pension_number',
                    render: function(data, type, row, meta) {
                        if (row.client_information) {
                            const PensionNumber = row.client_information.pension_number || '';
                            const PensionType = row.client_information.pension_type ? ' ' + row.client_information.pension_type : '';
                            const PensionAccountType = row.client_information.pension_account_type ? ' ' + row.client_information.pension_account_type : '';

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
                    data: 'client_information_id',
                    name: 'client_information.created_at',
                    render: function(data, type, row, meta) {
                        if (row.client_information) {
                            const createdAt = row.client_information.created_at ? new Date(row.client_information.created_at) : null;
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
                    data: 'client_information_id',
                    name: 'client_information.birth_date',
                    render: function(data, type, row, meta) {
                        if (row.client_information) {
                            const BirthDate = row.client_information.birth_date ? new Date(row.client_information.birth_date) : null;
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
                    data: 'cash_box_no',
                    name: 'cash_box_no',
                    render: function(data, type, row, meta) {
                        return data ? `<span>${data}</span>` : '';
                    },
                    orderable: true,
                    searchable: true,
                },


                {
                    data: 'bank_account_no',
                    name: 'bank_account_no',
                    render: function(data, type, row, meta) {
                            return `<span class="fw-bold h6" style="color: #5AAD5D;">${row.bank_account_no}</span><br>
                                <span class="fw-bold">${row.bank_name}</span>`;

                    },
                    orderable: true,
                    searchable: true,
                },



                {
                    data: 'pin_no',
                    name: 'pin_no',
                    render: function(data, type, row) {
                        return `<a href="#" class="text-info fs-4 view_pin_code"
                                    data-pin="${row.pin_no}"
                                    data-bank_account_no="${row.bank_account_no}"><i class="fas fa-eye"></i>
                                </a><br>`;

                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'atm_status',
                    name: 'atm_status',
                    render: function(data, type, row, meta) {
                        let BankStatus = ''; // Define BankStatus outside the if block with a default value
                        let atmTypeClass = ''; // Variable to hold the class based on atm_type

                        BankStatus = row.atm_status;

                        // Determine the text color based on atm_type
                        switch (row.atm_type) {
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

                        return `<span class="${atmTypeClass}">${row.atm_type}</span><br>
                                <span class="fw-bold h6">${BankStatus}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                }


            ];
            dataTable.initialize(url, columns);

            $('#FetchingDatatable').on('click', '.release_transaction', function(e) {
                e.preventDefault();
                var new_atm_id = $(this).data('id');

                $.ajax({
                    url: "/AtmClientFetch",
                    type: "GET",
                    data: { new_atm_id : new_atm_id },
                    success: function(data) {
                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#release_fullname').text(data.client_information.last_name +', '
                                                    + data.client_information.first_name +' '
                                                    +(data.client_information.middle_name ?? '') +' '
                                                    + (data.client_information.suffix ?? ''));

                        $('#release_branch_id').val(data.branch_id ?? '').trigger('change');

                        $('#release_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#release_pension_number_display').inputmask("99-9999999-99");

                        $('#release_pension_number').val(data.client_information.pension_number);
                        $('#release_pension_account_type').text(data.client_information.pension_account_type);
                        $('#release_pension_type').val(data.client_information.pension_type);
                        $('#release_birth_date').val(formattedBirthDate);
                        $('#release_branch_location').val(data.branch.branch_location);

                        $('#release_atm_id').val(data.id);
                        $('#release_bank_account_no').val(data.bank_account_no ?? '');
                        $('#release_collection_date').val(data.collection_date ?? '').trigger('change');
                        $('#release_atm_type').val(data.atm_type ?? '');
                        $('#release_bank_name').val(data.bank_name ?? '');
                        $('#release_transaction_number').val(data.transaction_number ?? '');

                        let expirationDate = '';
                        if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }
                        $('#release_expiration_date').val((expirationDate || ''));

                        $('#ReleasingTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            $('#FetchingDatatable').on('click', '.borrow_transaction', function(e) {
                e.preventDefault();
                var new_atm_id = $(this).data('id');

                $.ajax({
                    url: "/AtmClientFetch",
                    type: "GET",
                    data: { new_atm_id : new_atm_id },
                    success: function(data) {
                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#borrow_fullname').text(data.client_information.last_name +', '
                                                    + data.client_information.first_name +' '
                                                    +(data.client_information.middle_name ?? '') +' '
                                                    + (data.client_information.suffix ?? ''));

                        $('#borrow_branch_id').val(data.branch_id ?? '').trigger('change');

                        $('#borrow_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#borrow_pension_number_display').inputmask("99-9999999-99");

                        $('#borrow_pension_number').val(data.client_information.pension_number);
                        $('#borrow_pension_account_type').text(data.client_information.pension_account_type);
                        $('#borrow_pension_type').val(data.client_information.pension_type);
                        $('#borrow_birth_date').val(formattedBirthDate);
                        $('#borrow_branch_location').val(data.branch.branch_location);

                        $('#borrow_atm_id').val(data.id);
                        $('#borrow_bank_account_no').val(data.bank_account_no ?? '');
                        $('#borrow_collection_date').val(data.collection_date ?? '').trigger('change');
                        $('#borrow_atm_type').val(data.atm_type ?? '');
                        $('#borrow_bank_name').val(data.bank_name ?? '');
                        $('#borrow_transaction_number').val(data.transaction_number ?? '');

                        let expirationDate = '';
                        if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }
                        $('#borrow_expiration_date').val((expirationDate || ''));

                        $('#BorrowTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            $('#FetchingDatatable').on('click', '.replacement_atm_transaction', function(e) {
                e.preventDefault();
                var new_atm_id = $(this).data('id');

                $.ajax({
                    url: "/AtmClientFetch",
                    type: "GET",
                    data: { new_atm_id : new_atm_id },
                    success: function(data) {
                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#replacement_fullname').text(data.client_information.last_name +', '
                                                    + data.client_information.first_name +' '
                                                    +(data.client_information.middle_name ?? '') +' '
                                                    + (data.client_information.suffix ?? ''));

                        $('#replacement_branch_id').val(data.branch_id ?? '').trigger('change');

                        $('#replacement_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#replacement_pension_number_display').inputmask("99-9999999-99");

                        $('#replacement_pension_number').val(data.client_information.pension_number);
                        $('#replacement_pension_account_type').text(data.client_information.pension_account_type);
                        $('#replacement_pension_type').val(data.client_information.pension_type);
                        $('#replacement_birth_date').val(formattedBirthDate);
                        $('#replacement_branch_location').val(data.branch.branch_location);

                        $('#replacement_atm_id').val(data.id);
                        $('#replacement_bank_account_no').val(data.bank_account_no ?? '');
                        $('#replacement_collection_date').val(data.collection_date ?? '').trigger('change');
                        $('#replacement_atm_type').val(data.atm_type ?? '');
                        $('#replacement_bank_name').val(data.bank_name ?? '');
                        $('#replacement_transaction_number').val(data.transaction_number ?? '');

                        let expirationDate = '';
                        if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }
                        $('#replacement_expiration_date').val((expirationDate || ''));

                        $('#ReplacementTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            $('#FetchingDatatable').on('click', '.cancelled_loan_transaction', function(e) {
                e.preventDefault();
                var new_atm_id = $(this).data('id');

                $.ajax({
                    url: "/AtmClientFetch",
                    type: "GET",
                    data: { new_atm_id : new_atm_id },
                    success: function(data) {
                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#cancelled_loan_fullname').text(data.client_information.last_name +', '
                                                    + data.client_information.first_name +' '
                                                    +(data.client_information.middle_name ?? '') +' '
                                                    + (data.client_information.suffix ?? ''));

                        $('#cancelled_loan_branch_id').val(data.branch_id ?? '').trigger('change');

                        $('#cancelled_loan_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#cancelled_loan_pension_number_display').inputmask("99-9999999-99");

                        $('#cancelled_loan_pension_number').val(data.client_information.pension_number);
                        $('#cancelled_loan_pension_account_type').text(data.client_information.pension_account_type);
                        $('#cancelled_loan_pension_type').val(data.client_information.pension_type);
                        $('#cancelled_loan_birth_date').val(formattedBirthDate);
                        $('#cancelled_loan_branch_location').val(data.branch.branch_location);

                        $('#cancelled_loan_atm_id').val(data.id);
                        $('#cancelled_loan_bank_account_no').val(data.bank_account_no ?? '');
                        $('#cancelled_loan_collection_date').val(data.collection_date ?? '').trigger('change');
                        $('#cancelled_loan_atm_type').val(data.atm_type ?? '');
                        $('#cancelled_loan_bank_name').val(data.bank_name ?? '');
                        $('#cancelled_loan_transaction_number').val(data.transaction_number ?? '');

                        let expirationDate = '';
                        if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }
                        $('#cancelled_loan_expiration_date').val((expirationDate || ''));

                        $('#CancelledLoanTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            $('#FetchingDatatable').on('click', '.release_balance_transaction', function(e) {
                e.preventDefault();
                var new_atm_id = $(this).data('id');

                $.ajax({
                    url: "/AtmClientFetch",
                    type: "GET",
                    data: { new_atm_id : new_atm_id },
                    success: function(data) {
                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#release_balance_fullname').text(data.client_information.last_name +', '
                                                    + data.client_information.first_name +' '
                                                    +(data.client_information.middle_name ?? '') +' '
                                                    + (data.client_information.suffix ?? ''));

                        $('#release_balance_branch_id').val(data.branch_id ?? '').trigger('change');

                        $('#release_balance_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#release_balance_pension_number_display').inputmask("99-9999999-99");

                        $('#release_balance_pension_number').val(data.client_information.pension_number);
                        $('#release_balance_pension_account_type').text(data.client_information.pension_account_type);
                        $('#release_balance_pension_type').val(data.client_information.pension_type);
                        $('#release_balance_birth_date').val(formattedBirthDate);
                        $('#release_balance_branch_location').val(data.branch.branch_location);

                        $('#release_balance_atm_id').val(data.id);
                        $('#release_balance_bank_account_no').val(data.bank_account_no ?? '');
                        $('#release_balance_collection_date').val(data.collection_date ?? '').trigger('change');
                        $('#release_balance_atm_type').val(data.atm_type ?? '');
                        $('#release_balance_bank_name').val(data.bank_name ?? '');
                        $('#release_balance_transaction_number').val(data.transaction_number ?? '');

                        let expirationDate = '';
                        if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }
                        $('#release_balance_expiration_date').val((expirationDate || ''));

                        $('#ReleasingWithBalanceTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });
        });

        $(document).on('click', '.view_pin_code', function(e) {
            e.preventDefault(); // Prevent the default anchor behavior

            const pinCode = $(this).data('pin'); // Get the PIN code from the data attribute
            const bankAccountNo = $(this).data('bank_account_no'); // Get the bank account number

            // SweetAlert confirmation
            Swal.fire({
                icon: "question",
                title: 'Do you want to view the PIN code?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, show another SweetAlert with the PIN code and bank account number
                    Swal.fire({
                        title: 'PIN Code Details',
                        html: `<br>
                            <span class="fw-bold h3 text-dark">${pinCode}</span><br><br>
                            <span class="fw-bold h4 text-primary">${bankAccountNo}</span><br>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Okay'
                    });
                }
            });
        });



    </script>

@endsection
