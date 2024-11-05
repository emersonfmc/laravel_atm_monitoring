@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Transaction Lists @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-8 text-start">
                            <h4 class="card-title">Branch Transaction</h4>
                            <p class="card-title-desc">
                                The Branch Transaction section provides a centralized record where Branch
                                Offices and Head Office can view and manage all transactions related to their ATMs, Passbook and Simcards. It allows branch managers
                                to monitor activity and easily access detailed
                                transaction reports in one location.
                            </p>
                        </div>
                        {{-- <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAreaModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Area</button>
                        </div> --}}
                    </div>
                    <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="fw-bold h6">Branch</label>
                                    <select name="branch_id" id="branch_id" class="form-select select2">
                                        @foreach ($Branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="fw-bold h6">Transaction</label>
                                    <select name="transaction_id" id="transaction_id" class="form-select select2">
                                        @foreach ($AtmTransactionAction as $transaction)
                                            <option value="{{ $transaction->id }}">{{ $transaction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="fw-bold h6">Status</label>
                                    <select name="status" id="status_select" class="form-select">
                                        <option value="ON GOING">ON GOING</option>
                                        <option value="CANCELLED">CANCELLED</option>
                                        <option value="COMPLETED">COMPLETED</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Action</th>
                                    <th>Transaction / Pending By</th>
                                    <th>Transaction Number</th>
                                    <th>Client</th>
                                    <th>Pension No. / Type</th>
                                    <th>Status</th>
                                    <th>ATM / Passbook / Simcard No & Bank</th>
                                    <th>Type</th>
                                    <th>PIN Code</th>
                                    <th>Collection Date</th>
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

    <div class="modal fade" id="viewTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 70%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">View Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive mt-3">
                        <table class="table table-design">
                            <thead>
                                <th>ID</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Sequence</th>
                                <th>Balance Amount</th>
                                <th>Remarks</th>
                                <th>Image</th>
                                <th>Date Received</th>
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

    <div class="modal fade" id="EditClientInformationModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Edit Client Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button type="submit" class="btn btn-success">Edit Information</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelledTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Transfer to Other Branch Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button type="submit" class="btn btn-danger">Cancelled Transaction</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('TransactionData') !!}';
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
                        return data;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'pending_to',
                    name: 'pending_to',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
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
                    data: 'client_banks_id',
                    name: 'client_banks_id',
                    render: function(data, type, row, meta) {
                        let clientName = '';
                        let branchLocation = '';

                        // Construct the client's full name
                        if (row.atm_client_banks && row.atm_client_banks.client_information) {
                            const firstName = row.atm_client_banks.client_information.first_name || '';
                            const middleName = row.atm_client_banks.client_information.middle_name ? ' ' + row.atm_client_banks.client_information.middle_name : '';
                            const lastName = row.atm_client_banks.client_information.last_name ? ' ' + row.atm_client_banks.client_information.last_name : '';
                            const suffix = row.atm_client_banks.client_information.suffix ? ', ' + row.atm_client_banks.client_information.suffix : '';

                            clientName = `${firstName}${middleName}${lastName}${suffix}`;
                        }

                        // Get branch location
                        if (row.branch && row.branch.branch_location) {
                            branchLocation = row.branch.branch_location;
                        }

                        // Return client name and branch location separated by <br>
                        return `<span>${clientName}</span><br><span class="text-primary">${branchLocation}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'client_banks_id',
                    name: '',
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
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        let badgeClass = '';
                        let statusClass = '';

                        // Determine the badge class and status label based on status value
                        switch (row.status) {
                            case 'ON GOING':
                                badgeClass = 'pt-1 pb-1 ps-2 ps-2 pe-2 badge bg-warning fw-bold h6';
                                statusClass = 'On Going';
                                break;
                            case 'CANCELLED':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-danger fw-bold h6';
                                statusClass = 'Cancelled';
                                break;
                            case 'COMPLETED':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-success fw-bold h6';
                                statusClass = 'Completed';
                                break;
                            default:
                                badgeClass = 'badge bg-secondary fw-bold h6'; // Default badge class
                                statusClass = 'Unknown Status';
                        }

                        // Return the status wrapped in a span with the appropriate badge and status class
                        return `<span class="${badgeClass} fw-bold h6">${statusClass}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'bank_account_no',
                    name: 'bank_account_no',
                    render: function(data, type, row, meta) {
                        let BankName = ''; // Define BankName outside the if block with a default value

                        if (row.atm_client_banks && row.atm_client_banks.bank_name) {
                            BankName = row.atm_client_banks.bank_name;
                        }

                        return `<span class="fw-bold h6 text-success">${row.bank_account_no}</span><br>
                                <span class="fw-bold h6">${BankName}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'atm_type',
                    name: 'atm_type',
                    render: function(data, type, row, meta) {
                        let BankStatus = ''; // Define BankStatus outside the if block with a default value
                        let atmTypeClass = ''; // Variable to hold the class based on atm_type

                        // Determine the BankStatus if it exists
                        if (row.atm_client_banks && row.atm_client_banks.atm_status) {
                            BankStatus = row.atm_client_banks.atm_status;
                        }

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
                    data: 'client_banks_id',
                    name: '',
                    render: function(data, type, row) {
                        let PinCode = '';
                        let BankAccountNo = '';

                        // Check if atm_type is not "ATM" and pin_no exists
                        if (row.atm_client_banks && row.atm_client_banks.pin_no && row.atm_type == 'ATM') {
                            PinCode = row.atm_client_banks.pin_no;
                            BankAccountNo = row.atm_client_banks.bank_account_no;

                            // Return the eye icon with the data attributes
                            return `<a href="#" class="text-info fs-4 view_pin_code"
                                        data-pin="${PinCode}"
                                        data-bank_account_no="${BankAccountNo}">
                                        <i class="fas fa-eye"></i>
                                    </a><br>`;
                        }

                        // If conditions are not met, return an empty string
                        return 'No Pin Code Detected';
                    },
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'client_banks_id',
                    name: '',
                    render: function(data, type, row, meta) {
                        let CollectionDate = ''; // Define CollectionDate outside the if block with a default value

                        if (row.atm_client_banks && row.atm_client_banks.collection_date) {
                            CollectionDate = row.atm_client_banks.collection_date;
                        }

                        return `<span>${CollectionDate}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },

            ];
            dataTable.initialize(url, columns);

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

            $('#FetchingDatatable').on('click', '.viewTransaction', function(e) {
                e.preventDefault();
                var transaction_id = $(this).data('id');

                $.ajax({
                    url: "/TransactionGet",
                    type: "GET",
                    data: { transaction_id : transaction_id },
                    success: function(data) {

                        $('#TransactionApprovalBody').empty();

                        data.atm_banks_transaction_approval.forEach(function (rows) {
                            var employee_id = rows.employee_id !== null ? rows.employee_id : '';
                            var employee_name = rows.employee && rows.employee.name ? rows.employee.name : '';

                            var balance = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.balance !== null
                                        ? new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(rows.atm_transaction_approvals_balance_logs.balance)
                                        : '';
                            balance = balance ? balance.replace('PHP', '₱ ') : '';

                            var remarks = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.remarks !== null
                                        ? rows.atm_transaction_approvals_balance_logs.remarks
                                        : '';

                            // Format the date_approved if it's not null
                            var dateApproved = rows.date_approved ? new Date(rows.date_approved).toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            }) : ''; // Leave blank if null

                            var newRow = '<tr>' +
                                '<td>' + rows.id + '</td>' +
                                '<td>' + employee_id + '</td>' +
                                '<td>' + employee_name + '</td>' +
                                '<td>' + rows.data_user_group.group_name + '</td>' +
                                '<td>' + rows.sequence_no + '</td>' +
                                '<td>' + balance + '</td>' +
                                '<td>' + remarks + '</td>' +
                                '<td>' + '' + '</td>' +
                                '<td>' + dateApproved + '</td>' + // Use formatted date here
                                '</tr>';

                            $('#TransactionApprovalBody').append(newRow);
                        });


                        $('#viewTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            $('#FetchingDatatable').on('click', '.editClientTransaction', function(e) {
                e.preventDefault();
                var transaction_id = $(this).data('id');

                $.ajax({
                    url: "/TransactionGet",
                    type: "GET",
                    data: { transaction_id : transaction_id },
                    success: function(data) {
                        console.log(data);

                        $('#EditClientInformationModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });


            $('#FetchingDatatable').on('click', '.cancelledTransaction', function(e) {
                e.preventDefault();
                var transaction_id = $(this).data('id');

                $.ajax({
                    url: "/TransactionGet",
                    type: "GET",
                    data: { transaction_id : transaction_id },
                    success: function(data) {
                        console.log(data);

                        $('#cancelledTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            // $('#FetchingDatatable').on('click', '.createTransaction', function(e) {
            //     e.preventDefault();
            //     var new_atm_id = $(this).data('id');

            //     $.ajax({
            //         url: "/AtmClientFetch",
            //         type: "GET",
            //         data: { new_atm_id : new_atm_id },
            //         success: function(data) {
            //             console.log(data);
            //             let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

            //             $('#create_fullname').text(data.client_information.last_name +', '+ data.client_information.first_name +' '+ data.client_information.middle_name +' '+ data.client_information.suffix ?? '');

            //             $('#create_pension_number_display').text(data.client_information.pension_number ?? '');
            //             $('#create_pension_number_display').inputmask("99-9999999-99");

            //             $('#create_pension_number').val(data.client_information.pension_number);
            //             $('#create_pension_account_type').text(data.client_information.pension_account_type);
            //             $('#create_pension_type').val(data.client_information.pension_type);
            //             $('#create_birth_date').val(formattedBirthDate);
            //             $('#create_branch_location').val(data.branch.branch_location);

            //             $('#create_atm_id').val(data.id);
            //             $('#create_bank_account_no').val(data.bank_account_no ?? '');
            //             $('#create_collection_date').val(data.collection_date ?? '');
            //             $('#create_atm_type').val(data.atm_type ?? '');
            //             $('#create_bank_name').val(data.bank_name ?? '');
            //             $('#create_transaction_number').val(data.transaction_number ?? '');

            //             let expirationDate = '';
            //             if (data.expiration_date && data.expiration_date !== '0000-00-00') {
            //                 expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            //             }
            //             $('#create_expiration_date').val((expirationDate || ''));


            //             $('#createTransactionModal').modal('show');
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("An error occurred: " + error);
            //         }
            //     });
            // });

            // $('#FetchingDatatable').on('click', '.addAtmTransaction', function(e) {
            //     e.preventDefault();
            //     var new_atm_id = $(this).data('id');

            //     $.ajax({
            //         url: "/AtmClientFetch",
            //         type: "GET",
            //         data: { new_atm_id : new_atm_id },
            //         success: function(data) {
            //             console.log(data);

            //             $('#addAtmTransactionModal').modal('show');
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("An error occurred: " + error);
            //         }
            //     });
            // });

            // $('#FetchingDatatable').on('click', '.transferBranchTransaction', function(e) {
            //     e.preventDefault();
            //     var new_atm_id = $(this).data('id');

            //     $.ajax({
            //         url: "/AtmClientFetch",
            //         type: "GET",
            //         data: { new_atm_id : new_atm_id },
            //         success: function(data) {
            //             console.log(data);

            //             $('#transferBranchTransactionModal').modal('show');
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("An error occurred: " + error);
            //         }
            //     });
            // });

            // $('#FetchingDatatable').on('click', '.EditInformationTransaction', function(e) {
            //     e.preventDefault();
            //     var new_atm_id = $(this).data('id');

            //     $.ajax({
            //         url: "/AtmClientFetch",
            //         type: "GET",
            //         data: { new_atm_id : new_atm_id },
            //         success: function(data) {
            //             console.log(data);

            //             $('#EditInformationTransactionModal').modal('show');
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("An error occurred: " + error);
            //         }
            //     });
            // });

        });

    </script>

@endsection
