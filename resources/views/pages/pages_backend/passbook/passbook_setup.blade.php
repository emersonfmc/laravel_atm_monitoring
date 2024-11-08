@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Passbook Transaction @endslot
        @slot('title') Passbook Setup @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Passbook Setup</h4>
                            <p class="card-title-desc">
                                A Centralized Record of all ATMs managed by the head office
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
                                    <th>Checkbox</th>
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
            var url = '{!! route('PassbookCollectionData') !!}';
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
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
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
                }


            ];
            dataTable.initialize(url, columns);
        });
    </script>

@endsection
