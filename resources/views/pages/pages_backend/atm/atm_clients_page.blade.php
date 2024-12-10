@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Clients @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('pension.number.validate') }}" method="POST" id="validatePensionNumber" class="d-flex">
                        @csrf
                        <div class="form-group">
                            <label class="fw-bold h6">Validate SSS / GSIS : <span class="fs-6 text-danger">( Ex. SSS 00-0000000-0 / GSIS 00-0000000-00 )</span></label>
                            <input type="text" name="pension_number" id="pension_number"
                                    class="pension_number_mask form-control" placeholder="Enter SSS Number"
                                    required>
                        </div>
                        <div class="col-md-3 ms-3 mt-4">
                            <button type="submit" class="btn btn-primary">Validate</button>
                            <span id="AddClientButton" style="display: none;">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClientModal"><i
                                    class="fas fa-plus-circle me-1"></i> Create Client Information
                                </button>
                            </span>
                        </div>

                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-start">
                            <h4 class="card-title">Clients</h4>
                            <p class="card-title-desc">
                                Clients financial assets are carefully monitored, including their ATM transactions, Passbook updates,
                                and SIM card management to ensure seamless and secure banking operations.
                            </p>
                        </div>
                        <div class="col-md-6 text-start">
                            @if(in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin','Branch Head']))
                                <form id="filterForm">
                                    @csrf
                                    <div class="row ms-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="fw-bold h6">Branch</label>
                                                <select name="branch_id" id="branch_id_select" class="form-select select2" required>
                                                    <option value="">Select Branches</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" {{ $branch->id == $branch_id ? 'selected' : '' }}>
                                                            {{ $branch->branch_location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 25px;">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                <button type="button" class="btn btn-success">Generate Reports</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Clients</th>
                                    <th>Pension No. / Type</th>
                                    <th>Transaction Number</th>
                                    <th>ATM / Passbook / Simcard</th>
                                    <th>Bank</th>
                                    <th>PIN Code</th>
                                    <th>Type</th>
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

    <div class="modal fade" id="createClientModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Client</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('clients.create') }}" method="POST" id="createValidateForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Pension Number</label>
                                    <input type="text" name="pension_number" id="pension_number_get"
                                            class="form-control" placeholder="Enter Pension Number"  readonly required>
                                </div>
                            </div>

                            @if(in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin']))
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Branch</label>
                                        <select name="branch_id" id="branch_id" class="form-select select2" required>
                                            <option value="" selected disabled>Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch_abbreviation .' - '. $branch->branch_location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Pension Number Type</label>
                                    <select name="pension_type" id="pension_type" class="form-select" required>
                                        <option value="" selected disabled>Pension Number Type</option>
                                        <option value="SSS">SSS</option>
                                        <option value="GSIS">GSIS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Pension Account Type</label>
                                    <select name="pension_account_type" id="pension_account_type" class="form-select select2" required disabled>
                                        <option value="" selected disabled>Pension Account Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Collection Date</label>
                                    <select name="collection_date" id="collection_date" class="form-select" required>
                                        <option value="" selected disabled>Collection Date</option>
                                        @foreach ($DataCollectionDates as $DataCollectionDate)
                                            <option value="{{ $DataCollectionDate->collection_date }}">{{ $DataCollectionDate->collection_date }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Firstname</label>
                                    <input type="text" name="first_name" id="first_name"
                                            class="form-control" placeholder="Enter Firstname" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Middlename</label>
                                    <input type="text" name="middle_name" id="middle_name"
                                            class="form-control" placeholder="Enter Middlename" minlength="0" maxlength="50">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Lastname</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                            placeholder="Enter Lastname" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Suffix</label>
                                    <select name="suffix" id="suffix" class="form-select">
                                        <option value="" selected disabled>Suffix</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="Ma.">Ma.</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Birthdate</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control" placeholder="Enter Birthdate" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="text-start">
                            <a href="#" class="btn btn-primary" id="addMoreAtmBtn">Add More</a>
                        </div>

                        <div class="atm-details-wrapper">
                            <div id="AddMoreAtmContainer" class="mb-3">
                                <div class="row atm-details mt-2">
                                    <hr>
                                    <label class="fw-bold h6 text-center mb-3 text-primary">
                                        Add ATM / Passbook / Simcard No.
                                    </label>

                                    <hr>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-sm-4 fw-bold">Type</label>
                                            <div class="col-5">
                                                <select name="atm_type[]" class="form-select" required>
                                                    <option value="" selected disabled>Type</option>
                                                    <option value="ATM">ATM</option>
                                                    <option value="Passbook">Passbook</option>
                                                    <option value="Sim Card">Sim Card</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="atm_status[]" id="atm_status" class="form-select" required>
                                                  <option value="">ATM Status</option>
                                                  <option value="New" selected>New</option>
                                                  <option value="Old">Old</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-sm-4 fw-bold">ATM / Passbook / Sim No.</label>
                                            <div class="col-8">
                                                <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="ATM / Passbook / Sim No." required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Balance</label>
                                            <div class="col-8">
                                                <input type="text" name="atm_balance[]" class="balanceCurrency form-control" value="0" placeholder="Balance" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="font-size col-form-label col-4 fw-bold">Banks</label>
                                            <div class="col-8">
                                                <div class="form-group">
                                                <select name="bank_id[]" id="bank_id" class="form-select select2">
                                                    <option value="" selected disabled>Banks</option>
                                                    @foreach ($DataBankLists as $bank)
                                                            <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                            <div class="col-8">
                                                <input type="number" name="pin_code[]" class="form-control" placeholder="PIN Code">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                            <div class="col-8">
                                                <input type="month" name="expiration_date[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Remarks</label>
                                            <div class="col-8">
                                                <input type="text" name="remarks[]" class="form-control" placeholder="Remarks" minlength="0" maxlength="100">
                                            </div>
                                        </div>
                                        <!-- <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Remove</label>
                                        <div class="col-8">
                                            <a href="#" class="btn btn-danger remove-atm-row"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                        </div> -->
                                    </div>
                                    <hr class="mt-2 mb-2">
                                </div>
                            </div>
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

    <div class="modal fade" id="viewClientModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 75%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Client Information</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div id="fetch_full_name" class="fw-bold h4"></div>
                                <span id="fetch_pension_number" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="fetch_pension_account_type" class="fw-bold h5"></span>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="fw-bold h6">Created Date</span><br>
                            <span class="fw-bold h6 text-primary" id="fetch_created_at"></span>
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-2">
                        <div class="form-group col-3">
                            <label class="fw-bold h6">Birthdate</label>
                            <input type="text" class="form-control" id="fetch_birth_date" readonly>
                        </div>
                        <div class="form-group col-3">
                            <label class="fw-bold h6">Branch</label>
                            <input type="text" class="form-control" id="fetch_branch_location" readonly>
                        </div>
                        <div class="form-group col-3">
                            <label class="fw-bold h6">Pension Type</label>
                            <input type="text" class="form-control" id="fetch_pension_type" readonly>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table class="table table-border dt-responsive wrap table-design">
                            <thead>
                                <th>Location</th>
                                <th>Transaction Number</th>
                                <th>Card No.</th>
                                <th>Bank Name</th>
                                <th>PIN Code</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Collection Date</th>
                                <th>Expiration Date</th>
                            </thead>

                            <tbody id="displayClientInformation">

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
            var url = '{!! route('clients.data') !!}';
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
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="#" class="text-info viewBtn me-1" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="View ">
                                <i class="fas fa-eye me-2 fs-5"></i>
                            </a>`;
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    "data": function(row, type, set) {
                        const fullName = (row.last_name ? row.last_name : '') + ', ' +
                                        (row.first_name ? row.first_name : '') + ' ' +
                                        (row.middle_name ? row.middle_name : '') + ' ' +
                                        (row.suffix ? row.suffix : '');

                        // Format the created_at field if it exists
                        const createdAtFormatted = row.created_at ? new Date(row.created_at).toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        }) : '';

                        // Check for branch location
                        const branchLocation = row.branch ? row.branch.branch_location : 'N/A';

                        return `<span class="fw-bold" style="font-size : 14px;">${fullName}</span><br>
                                <span class="text-primary">${branchLocation}</span><br>
                                <span style="font-size:12px;">${createdAtFormatted}</span>`;
                    }
                },
                {
                    data: 'pension_number', // Correct field name
                    name: 'pension_number', // Ensure it matches the database column
                    render: function(data, type, row, meta) {
                        return `<span class="fw-bold text-primary h6 pension_number_mask_display">${row.pension_number}</span><br>
                                <span class="fw-bold">${row.pension_type}</span><br>
                                <span class="fw-bold text-success">${row.pension_account_type}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_client_banks exists and is an array
                        if (data.atm_client_banks && Array.isArray(data.atm_client_banks)) {
                            // Initialize an empty string for PincODE
                            let AtmTransactionNumber = '';

                            // Use each to loop through the sequences
                            $.each(data.atm_client_banks, function(index, rows) {
                                AtmTransactionNumber += `<span class="fw-bold h6">${rows.transaction_number}</span><br>`;
                            });

                            return AtmTransactionNumber; // Return the concatenated PIN display
                        }
                        return ''; // Return empty string if no bank names
                    }
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_client_banks exists and is an array
                        if (data.atm_client_banks && Array.isArray(data.atm_client_banks)) {
                            // Initialize an empty string for bank details
                            let bankDetails = '';

                            // Use each to loop through the sequences
                            $.each(data.atm_client_banks, function(index, rows) {
                                // Always display the bank_account_no
                                bankDetails += `<span class="fw-bold h6" style="color : #4B9B43;">${rows.bank_account_no}</span>`;

                                // Check if replacement_count is greater than 0
                                if (rows.replacement_count > 0) {
                                    bankDetails += ` / <span class="fw-bold h6 text-danger">${rows.replacement_count}</span>`;
                                }

                                // Add a line break after each entry
                                bankDetails += `<br>`;
                            });

                            return bankDetails; // Return the concatenated bank details
                        }
                        return ''; // Return empty string if no bank details
                    }
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_client_banks exists and is an array
                        if (data.atm_client_banks && Array.isArray(data.atm_client_banks)) {
                            // Initialize an empty string for group names
                            let bankNames = '';

                            // Use each to loop through the sequences
                            $.each(data.atm_client_banks, function(index, rows) {
                                bankNames += `<span class="fw-bold h6">${rows.bank_name}</span><br>`;
                            });

                            return bankNames; // Return the concatenated bank names
                        }
                        return ''; // Return empty string if no bank names
                    }
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_client_banks exists and is an array
                        if (data.atm_client_banks && Array.isArray(data.atm_client_banks)) {
                            // Initialize an empty string for PincODE
                            let PincODE = '';

                            // Use each to loop through the sequences
                            $.each(data.atm_client_banks, function(index, rows) {
                                PincODE += `<a href="#" class="badge bg-danger view_pin_code"
                                                data-pin="${rows.pin_no}"
                                                data-bank_account_no="${rows.bank_account_no}">Encrypted
                                            </a><br>`;
                            });

                            return PincODE; // Return the concatenated PIN display
                        }
                        return ''; // Return empty string if no bank names
                    }
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_client_banks exists and is an array
                        if (data.atm_client_banks && Array.isArray(data.atm_client_banks)) {
                            // Initialize an empty string for bank types
                            let bankTypes = '';

                            // Use each to loop through the sequences
                            $.each(data.atm_client_banks, function(index, rows) {
                                let className;

                                // Determine class based on atm_type
                                if (rows.atm_type === 'ATM') {
                                    className = 'fw-bold h6 text-primary';
                                } else if (rows.atm_type === 'Passbook') {
                                    className = 'fw-bold h6 text-danger';
                                } else if (rows.atm_type === 'Sim Card') {
                                    className = 'fw-bold h6 text-info';
                                } else {
                                    className = 'fw-bold h6'; // Default class if no match
                                }

                                bankTypes += `<span class="${className}">${rows.atm_type}</span><br>`;
                            });

                            return bankTypes; // Return the concatenated bank names
                        }
                        return ''; // Return empty string if no bank names
                    }
                },
                {
                    data: null, // No direct data for ATM sequences; will be created from response
                    render: function(data, type, row) {
                        // Check if atm_client_banks exists and is an array
                        if (data.atm_client_banks && Array.isArray(data.atm_client_banks)) {
                            // Initialize an empty string for PincODE
                            let AtmStatus = '';

                            // Use each to loop through the sequences
                            $.each(data.atm_client_banks, function(index, rows) {
                                AtmStatus += `<span class="fw-bold h6">${rows.atm_status}</span><br>`;
                            });

                            return AtmStatus; // Return the concatenated PIN display
                        }
                        return ''; // Return empty string if no bank names
                    }
                }
            ];
            dataTable.initialize(url, columns, {
                drawCallback: function() {
                    // Apply Inputmask to the pension_number column after the table is drawn
                    $('.pension_number_mask_display').inputmask('99-9999999-99', {
                        placeholder: "",
                        removeMaskOnSubmit: true
                    });
                }
            });

            $('#FetchingDatatable').on('click', '.viewBtn', function(e) {
                e.preventDefault();
                var itemID = $(this).data('id');

                var url = "/clients/get/" + itemID;

                $.get(url, function(data) {
                    $('#item_id').val(data.id);
                    $('#userTypeSelectUpdate').val(data.user_types).trigger('change');

                    // Format birth_date and created_at
                    let formattedBirthDate = data.birth_date ? new Date(data.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';
                    let formattedCreatedAt = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                    // Display formatted dates or blank if not valid
                    $('#fetch_birth_date').val(formattedBirthDate);
                    $('#fetch_created_at').text(formattedCreatedAt);

                    $('#fetch_full_name').text(
                        `${data.last_name} ${data.first_name} ${data.middle_name ?? ''} ${data.suffix ?? ''}`.trim()
                    );
                    $('#fetch_pension_number').val(data.pension_number ?? '');
                    $('#fetch_pension_number').inputmask("99-9999999-99");

                    $('#fetch_pension_type').val(data.pension_type ?? '');
                    $('#fetch_pension_account_type').text(data.pension_account_type);
                    $('#fetch_branch_location').val(data.branch.branch_location ?? '');

                    $('#displayClientInformation').empty();
                    data.atm_client_banks.forEach(function (rows) {
                        // Format expiration_date to "Month Year"
                        let expirationDate = '';
                        if (rows.expiration_date && rows.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(rows.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }

                        // Conditionally apply class for atm_type
                        let atmTypeClass = '';
                        if (rows.atm_type === 'ATM') atmTypeClass = 'text-primary';
                        else if (rows.atm_type === 'Passbook') atmTypeClass = 'text-danger';
                        else if (rows.atm_type === 'Sim Card') atmTypeClass = 'text-info';

                        var newRow = '<tr>' +
                            '<td>' + (rows.location ?? '') + '</td>' +
                            '<td class="fw-bold h6">' + (rows.transaction_number ?? '') + '</td>' +
                            '<td class="fw-bold h6 text-success">' + (rows.bank_account_no ?? '') + '</td>' +
                            '<td>' + (rows.bank_name ?? '') + '</td>' +
                            '<td><span class="badge bg-danger">Encrypted</span></td>' +
                            '<td class="' + atmTypeClass + '">' + (rows.atm_type ?? '') + '</td>' +
                            '<td>' + (rows.atm_status ?? '') + '</td>' +
                            '<td>' + (rows.collection_date ?? '') + '</td>' +
                            '<td>' + (expirationDate || '') + '</td>' +
                            '</tr>';

                        $('#displayClientInformation').append(newRow);
                    });



                    $('#viewClientModal').modal('show');
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

            $('#createValidateForm').validate({
                rules: {
                    'atm_type[]': {
                        required: true
                    },
                    'atm_number[]': {
                        required: true,
                    },
                    'atm_balance[]': {
                        required: true
                    },
                    'bank_name[]': {
                        required: true
                    },
                    'pin_code[]': {
                        required: {
                            depends: function(element) {
                                // Check if the closest parent div .atm-details contains the value 'ATM' in the select
                                return $(element).closest('.atm-details').find('select[name="atm_type[]"]').val() === 'ATM';
                            }
                        },
                        minlength: 4,
                        maxlength: 8,
                        digits: true
                    }
                },
                messages: {
                    'atm_number[]': {
                        required: "ATM number is required",
                        remote: "ATM number already exists"  // Custom error message for existing ATM numbers
                    },
                    'pin_code[]': {
                        required: "PIN code is required when ATM is selected.",
                        minlength: "Please enter at least 4 digits",
                        maxlength: "Please enter no more than 8 digits",
                        digits: "Only numbers are allowed"
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

                                        if (res.status === 'success')
                                        {
                                            closeCreateClientModal();
                                            Swal.fire({
                                                title: 'Successfully Added!',
                                                text: 'Branch is successfully added!',
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

            $('#pension_type').on('change', function() {
                var selected_pension_types = $(this).val();

                // Make the AJAX GET request
                $.ajax({
                    url: '/pension/types/fetch',
                    type: 'GET',
                    data: {
                        selected_pension_types: selected_pension_types
                    },
                    success: function(response) {
                        var options = '<option value="" selected disabled>Pension Types</option>';
                        $.each(response, function(index, item) {
                            options += `<option value="${item.pension_name}">${item.pension_name}</option>`;
                        });
                        $('#pension_account_type').prop('disabled', false); // Remove disabled attribute
                        $('#pension_account_type').html(options); // Set the dropdown options
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('AJAX Error:', status, error);
                    }
                });
            });

            $('#createClientModal').on('shown.bs.modal', function () {
                $('#branch_id').select2({ dropdownParent: $('#createClientModal'), });
                $('#pension_account_type').select2({  dropdownParent: $('#createClientModal') });
                $('#bank_id').select2({  dropdownParent: $('#createClientModal') });
            });

            function closeCreateClientModal() {
                $('#createClientModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
            }
        });


        $(document).ready(function () {
            var maxRows = 5; // Maximum number of rows
            var rowCount = $('.atm-details').length; // Initial row count

            // Initialize input mask for existing rows
            applyInputMaskCurrency();
            applyCardNumberInputMask();

            // Add new row on Add button click
            $('#addMoreAtmBtn').click(function(e) {
                e.preventDefault();
                if (rowCount < maxRows) {
                    let newRow = `
                        <div class="row atm-details mt-2">
                            <hr>
                            <label class="fw-bold h6 text-center mb-3 text-primary">
                                Add ATM / Passbook / Simcard No.
                            </label>

                            <hr>
                            <div class="col-md-6">
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-sm-4 fw-bold">Type</label>
                                    <div class="col-5">
                                        <select name="atm_type[]" class="form-select" required>
                                        <option value="" selected disabled>Type</option>
                                        <option value="ATM">ATM</option>
                                        <option value="Passbook">Passbook</option>
                                        <option value="Sim Card">Sim Card</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <select name="atm_status[]" id="atm_status" class="form-select" required>
                                            <option value="">ATM Status</option>
                                            <option value="New" selected>New</option>
                                            <option value="Old">Old</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-sm-4 fw-bold">ATM / Passbook / Sim No.</label>
                                    <div class="col-8">
                                        <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="ATM / Passbook / Sim No." required>
                                    </div>
                                </div>

                                <div class="form-group mb-3 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Balance</label>
                                    <div class="col-8">
                                        <input type="text" name="atm_balance[]" class="balanceCurrency form-control" value="0" placeholder="Balance" required>
                                    </div>
                                </div>

                                <div class="form-group mb-2 row align-items-center">
                                    <label class="font-size col-form-label col-4 fw-bold">Banks</label>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <select name="bank_id[]" id="bank_id" class="form-select">
                                                <option value="" selected disabled>Banks</option>
                                                @foreach ($DataBankLists as $bank)
                                                        <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                    <div class="col-8">
                                        <input type="number" name="pin_code[]" class="form-control" placeholder="PIN Code">
                                    </div>
                                </div>

                                <div class="form-group mb-3 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                    <div class="col-8">
                                        <input type="month" name="expiration_date[]" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group mb-3 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Remarks</label>
                                    <div class="col-8">
                                        <input type="text" name="remarks[]" class="form-control" placeholder="Remarks" minlength="0" maxlength="100">
                                    </div>
                                </div>

                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Remove</label>
                                    <div class="col-8">
                                        <a href="#" class="btn btn-danger remove-atm-row">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2">
                        </div>`;

                    $('#AddMoreAtmContainer').append(newRow); // Append the new row
                    applyCardNumberInputMask(); // Apply input mask to the new row
                    applyInputMaskCurrency(); // Apply input mask for balance
                    rowCount++; // Increase row count
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Limit of 5 ATM Rows Only!"
                    });
                }
            });

            // Remove row and update the count
            $(document).on('click', '.remove-atm-row', function(e) {
                e.preventDefault();
                if (rowCount > 1) { // Ensure at least one row remains
                    $(this).closest('.atm-details').remove();
                    rowCount--;
                }
            });

            // Function to apply input mask to balance fields
            function applyInputMaskCurrency() {
                $('.balanceCurrency').inputmask({
                    'alias': 'currency',
                    allowMinus: false,
                    'prefix': "₱ ",
                    max: 999999999999.99,
                });
            }

            // Function to apply card number input mask
            function applyCardNumberInputMask() {
                $(".atm_card_input_mask").inputmask({
                    mask: '9999-9999-9999-9999-9999', // Custom mask for the card number
                    placeholder: '', // Placeholder to show the expected format
                    showMaskOnHover: false,  // Hide the mask when the user is not interacting with the field
                    showMaskOnFocus: true,   // Show the mask when the field is focused
                    rightAlign: false       // Align the input to the left
                });
            }
        });

        // Validating Existing Pension Number
        $(document).ready(function () {
            $('#validatePensionNumber').validate({
                rules: {
                    pension_number: { required: true },  // Rule for pension_number field
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
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.success,
                                    icon: 'success',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Prompt the user with Yes/No confirmation
                                        Swal.fire({
                                            title: 'Proceed to create client?',
                                            text: 'Do you want to create a new client?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes',
                                            cancelButtonText: 'No'
                                        }).then((confirmation) => {
                                            if (confirmation.isConfirmed) {
                                                // Display the "Create Client" button
                                                $('#AddClientButton').show();

                                                // Set the validated pension number in the modal's input field
                                                $('#pension_number_get').val($('#pension_number').val());

                                                // Open the create client modal
                                                $('#createClientModal').modal('show');
                                            }
                                        });
                                    }
                                });
                            }
                            else if (response.error) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.error,
                                    icon: 'error',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = 'An error occurred. Please try again later.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }
                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });

        // Global Used Script in the Page
            $(document).ready(function () {
                $('.balanceCurrency').inputmask({
                    'alias': 'currency',
                    allowMinus: false,
                    'prefix': "₱ ",
                    max: 999999999999.99,
                });

                $('.pension_number_mask').inputmask('99-9999999-99', {
                    placeholder: "",  // Placeholder for the input
                    removeMaskOnSubmit: true  // Removes the mask when submitting the form
                });
            });
        // Global Used Script in the Page
    </script>


@endsection
