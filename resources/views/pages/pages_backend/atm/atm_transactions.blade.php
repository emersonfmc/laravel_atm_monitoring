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
                        <div class="col-md-8 text-start">
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


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Action</th>
                                    <th>Transaction / Pending By</th>
                                    <th>Transaction Number</th>
                                    <th>Client</th>
                                    <th>Pension No. / Type</th>
                                    <th>ATM / Passbook / Simcard No & Bank</th>
                                    <th>Type</th>
                                    <th>PIN Code</th>
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
                // {
                //     data: null,
                //     name: 'action', // This matches the name you used in your server-side code
                //     render: function(data, type, row) {
                //         return row.action; // Use the action rendered from the server
                //     },
                //     orderable: false,
                //     searchable: false,
                // },
                // Transaction Type and Pending By
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
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
                        if (row.atm_client_banks) {
                            if (row.atm_client_banks.client_information) {
                                const firstName = row.atm_client_banks.client_information.first_name || '';
                                const middleName = row.atm_client_banks.client_information.middle_name ? ' ' + row.atm_client_banks.client_information.middle_name : '';
                                const lastName = row.atm_client_banks.client_information.last_name ? ' ' + row.atm_client_banks.client_information.last_name : '';
                                const suffix = row.atm_client_banks.client_information.suffix ? ', ' + row.atm_client_banks.client_information.suffix : '';
                                return `<span>${firstName}${middleName}${lastName}${suffix}</span>`;
                            }
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
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
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
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
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
