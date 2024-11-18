@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Receiving of Transaction @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Checkbox</th>
                                    <th>Action</th>
                                    <th>Reference No</th>
                                    <th>Branch</th>
                                    <th>Approval ID</th>
                                    <th>Transaction / Date Requested</th>
                                    <th>APRB No.</th>
                                    <th>Client</th>
                                    <th>Pension No</th>
                                    <th>Type</th>
                                    <th>Card No. & Bank</th>
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
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('TransactionReceivingData') !!}';
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
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'banks_transactions_id',
                    name: 'atm_banks_transaction.transaction_number',
                    render: function(data, type, row, meta) {
                        return row.atm_banks_transaction ? '<span>' + row.atm_banks_transaction.transaction_number + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: 'atm_banks_transaction.branch.branch_location',
                    render: function(data, type, row, meta) {
                        return row.atm_banks_transaction.branch ? '<span>' + row.atm_banks_transaction.branch.branch_location + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'banks_transactions_id',
                    name: '',
                    render: function(data, type, row, meta) {
                        let transactionName = '';
                        let createdAt = '';

                        // Check if `data_transaction_action` exists and has a `name`
                        if (row.data_transaction_action) {
                            transactionName = row.data_transaction_action.name || '';
                        }

                        // Check if `atm_banks_transaction` exists and has `created_at`
                        if (row.atm_banks_transaction && row.atm_banks_transaction.created_at) {
                            // Convert `created_at` to desired format
                            const date = new Date(row.atm_banks_transaction.created_at);
                            createdAt = date.toLocaleString('en-US', {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                        }

                        // Return formatted HTML
                        return `
                            <span class="fw-bold text-primary">${transactionName}</span><br>
                            <span style="font-size: 12px;">${createdAt}</span>
                        `;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: 'atm_banks_transaction.aprb_no',
                    render: function(data, type, row, meta) {
                        return row.atm_banks_transaction.aprb_no ? '<span>' + row.atm_banks_transaction.aprb_no + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '',
                    render: function(data, type, row, meta) {
                        // Check if all nested objects exist
                        if (row.atm_banks_transaction && row.atm_banks_transaction.atm_client_banks && row.atm_banks_transaction.atm_client_banks.client_information ) {
                            const clientInfo = row.atm_banks_transaction.atm_client_banks.client_information;

                            // Safely retrieve client information with defaults
                            const firstName = clientInfo.first_name || '';
                            const middleName = clientInfo.middle_name ? ` ${clientInfo.middle_name}` : '';
                            const lastName = clientInfo.last_name ? ` ${clientInfo.last_name}` : '';
                            const suffix = clientInfo.suffix ? `, ${clientInfo.suffix}` : '';

                            // Return formatted client name
                            return `<span>${firstName}${middleName}${lastName}${suffix}</span>`;
                        }
                        // Return empty string if data is missing
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '',
                    render: function(data, type, row, meta) {
                        // Check if all nested objects exist
                        if (row.atm_banks_transaction && row.atm_banks_transaction.atm_client_banks && row.atm_banks_transaction.atm_client_banks.client_information) {
                            const clientInfo = row.atm_banks_transaction.atm_client_banks.client_information;

                            const PensionNumber = clientInfo.pension_number || '';
                            const PensionType = clientInfo.pension_type ? ` ${clientInfo.pension_type}` : '';
                            const PensionAccountType = clientInfo.pension_account_type ? ` ${clientInfo.pension_account_type}` : '';

                            // Return formatted client name
                            return `<span class="fw-bold h6 text-primary">${PensionNumber}</span><br>
                                    <span>${PensionAccountType}</span><br>
                                    <span class="text-success">${PensionType}</span>`;
                        }
                        // Return empty string if data is missing
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        if (row.atm_banks_transaction && row.atm_banks_transaction.atm_client_banks) {
                            const atmType = row.atm_banks_transaction.atm_client_banks.atm_type;
                            const atmStatus = row.atm_banks_transaction.atm_client_banks.atm_status;

                            // Determine class based on atmType
                            let atmTypeClass = '';
                            if (atmType === 'ATM') {
                                atmTypeClass = 'text-primary';
                            } else if (atmType === 'Passbook') {
                                atmTypeClass = 'text-danger';
                            } else if (atmType === 'Sim Card') {
                                atmTypeClass = 'text-info';
                            }

                            // Return formatted HTML with dynamic class
                            return `
                                <span class="${atmTypeClass}">${atmType}</span><br>
                                <span>${atmStatus}</span>
                            `;
                        }
                        return ''; // Fallback if data is missing
                    },
                    orderable: false,
                    searchable: false,
                },

                {
                    data: 'banks_transactions_id',
                    name: '', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                            if (row.atm_banks_transaction) {
                                if(row.atm_banks_transaction.atm_client_banks){
                                    const bankAccountNo = row.atm_banks_transaction.atm_client_banks.bank_account_no;
                                    const bankname = row.atm_banks_transaction.atm_client_banks.bank_name;

                                    return `<span class="fw-bold h6 text-success">${bankAccountNo}</span><br>
                                            <span>${bankname}</span>`;
                                }
                            }
                            return '';

                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'banks_transactions_id',
                    name: '',
                    render: function(data, type, row) {
                        if (row.atm_banks_transaction && row.atm_banks_transaction.atm_client_banks) {
                            const clientBank = row.atm_banks_transaction.atm_client_banks;

                            // Extract data
                            const bankAccountNo = clientBank.bank_account_no || '';
                            const bankPinCode = clientBank.pin_no;
                            const bankType = clientBank.atm_type;

                            // Check if atm_type is "ATM"
                            if (bankType === 'ATM') {
                                // Check if pin_code is null or empty
                                if (!bankPinCode) {
                                    return `<span class="text-danger">No Pin Code</span>`;
                                }

                                // Display view pin code link if pin_code is not empty
                                return `
                                    <a href="#" class="text-info fs-4 view_pin_code"
                                        data-pin="${bankPinCode}"
                                        data-bank_account_no="${bankAccountNo}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                `;
                            }
                        }

                        // Default return if condition not met
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },


                {
                    data: 'banks_transactions_id',
                    name: '', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                            if (row.atm_banks_transaction) {
                                if(row.atm_banks_transaction.atm_client_banks){
                                    const CollectionDate = row.atm_banks_transaction.atm_client_banks.collection_date;

                                    return `<span>${CollectionDate}</span>`;
                                }
                            }
                            return '';

                    },
                    orderable: false,
                    searchable: false,
                },


            ];
            dataTable.initialize(url, columns);
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
