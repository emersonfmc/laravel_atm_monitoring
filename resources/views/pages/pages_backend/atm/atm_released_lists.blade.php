@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Released Lists @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Released ATM, Passbook, and SIM Card List</h4>
                            <p class="card-title-desc">
                                This list records all ATMs, passbooks, and SIM cards that have been directly issued to clients.
                            </p>
                        </div>

                        {{-- <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAreaModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Area</button>
                        </div> --}}
                    </div>
                    <hr>
                        @if(in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin','Collection Receiving Clerk']))
                            <form id="filterForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6">Branch</label>
                                            <select name="branch_id" id="branch_id_select" class="form-select select2" required>
                                                <option value="0">Select Branches</option>
                                                @foreach($Branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ $branch->id == $branch_id ? 'selected' : '' }}>
                                                        {{ $branch->branch_location }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="margin-top: 25px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <button type="button" class="btn btn-success">Generate Reports</button>
                                        </div>
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
                                    <th>Transaction / Pending By</th>
                                    <th>Transaction No</th>
                                    <th>Client</th>
                                    <th>Branch</th>
                                    <th>Pension No. / Type</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    <th>Card No & Bank</th>
                                    <th>Coll Date</th>
                                    <th>PIN Code</th>
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

    <div class="modal fade" id="returnClientTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="pulloutBranchTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Return Client / Balik Loob Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transaction.return-client') }}" method="POST" id="TransactionReturnClientValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="return_atm_id">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <div id="return_fullname" class="fw-bold h4"></div>
                                    <span id="return_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="return_pension_type" class="fw-bold h5"></span>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Transaction Number</label>
                                <input type="text" class="form-control" id="return_transaction_number" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Birthdate</label>
                                <input type="text" class="form-control" id="return_birth_date" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Card No.</label>
                                <input type="text" class="form-control" id="return_bank_account_no" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Bank Name</label>
                                <input type="text" class="form-control" id="return_bank_name" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Type</label>
                                <input type="text" class="form-control" id="return_atm_type" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Expiration Date</label>
                                <input type="text" class="form-control" id="return_expiration_date" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Collection Date</label>
                                <select name="collection_date" id="return_collection_date" class="form-select select2">
                                    <option value="">Collection Date</option>
                                    @foreach ($DataCollectionDate as $collection)
                                        <option value="{{ $collection->collection_date }}">{{ $collection->collection_date }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <hr>
                            <div class="col-md-12">
                                <div class="form-group col-4 mb-3">
                                    <label class="fw-bold h6">Transaction</label>
                                    <select name="client_transaction" class="form-select" id="client_transaction" required>
                                        <option value="" selected disabled>Select Transaction</option>
                                        <option value="return_old">Return Same ATM / PB Information</option>
                                        <option value="return_new">Return New ATM / PB Information</option>
                                    </select>
                                </div>
                                <hr>

                                <span id="same_information" style="display:none;">
                                    <div class="row">
                                      <div class="col-md-3" id="ReturnPinCodeDetails" style="display:block;">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6 mt-3">PIN Code <span class="text-danger">*</span></label>
                                            <input type="number" id="pin_code"
                                                  name="old_pin_code" class="form-control" placeholder="PIN Code" min="0">
                                        </div>
                                      </div>

                                      <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6 mt-3">Balance <span class="text-danger">*</span></label>
                                                <input type="text" name="old_balance" id="atm_balance" class="balance_input_mask form-control"
                                                        placeholder="ATM / Passbook Balance" required>

                                        </div>
                                      </div>

                                      <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6 mt-3">Remarks<span class="text-danger"> *</span></label>
                                            <input type="text" id="remarks" name="old_remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="100">
                                        </div>
                                      </div>
                                    </div>
                                </span>

                                <span id="new_information" style="display:none;">
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
                                                        <label class="col-form-label col-sm-4 fw-bold">Card No.</label>
                                                        <div class="col-8">
                                                            <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="Card No." required>
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
                                                    <div class="form-group mb-3 row align-items-center">
                                                        <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                                        <div class="col-8">
                                                            <input type="number" name="pin_code[]" class="form-control" placeholder="PIN Code" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2 row align-items-center">
                                                        <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                                        <div class="col-8">
                                                            <input type="month" name="expiration_date[]" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-2 row align-items-center">
                                                        <label class="col-form-label col-4 fw-bold">Balance</label>
                                                        <div class="col-8">
                                                            <input type="text" name="atm_balance[]" class="balance_input_mask form-control" placeholder="Balance" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-2 row align-items-center">
                                                        <label class="col-form-label col-4 fw-bold">Remarks</label>
                                                        <div class="col-8">
                                                            <input type="text" name="remarks[]" class="form-control" placeholder="Remarks" minlength="0" maxlength="100">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-2 row align-items-center">
                                                        {{-- <label class="col-form-label col-4 fw-bold">Remove</label>
                                                        <div class="col-8">
                                                            <a href="#" class="btn btn-danger remove-atm-row"><i class="fas fa-trash"></i></a>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <hr class="mt-2 mb-2">
                                            </div>
                                        </div>
                                    </div>
                                </span>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Return Client</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="transferBranchTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 30%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Transfer to Other Branch Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('TransactionTransferBranch') }}" method="POST" id="TransactionTransferBranchValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="transfer_atm_id">
                            <div class="col-12">
                                <div class="form-group">
                                    <div id="transfer_fullname" class="fw-bold h4"></div>
                                    <span id="transfer_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="transfer_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-8 mb-3">
                                        <label class="fw-bold h6">Branch</label>
                                        <select name="branch_id" id="transfer_branch_id" class="form-select select2">
                                            @foreach ($Branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12 mb-3">
                                        <label class="fw-bold h6">Remarks</label>
                                        <textarea name="remarks" id="" rows="4" minlength="0" class="form-control" placeholder="Remarks"
                                                    maxlength="300" style="resize:none;" required></textarea>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="transfer_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="transfer_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card No.</label>
                                        <input type="text" class="form-control" id="transfer_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="transfer_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="transfer_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="transfer_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="transfer_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Transfer to Other Branch</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Displaying of Data
                var FetchingDatatableBody = $('#FetchingDatatable tbody');

                const dataTable = new ServerSideDataTable('#FetchingDatatable');
                var url = '{!! route('ReleasedData') !!}';
                const columns = [
                    {
                        data: 'action',
                        render: function(data, type, row) {
                            return row.action; // Use the action rendered from the server
                        },
                        orderable: false,
                        searchable: false,
                    },
                    // Transaction Type and Pending By
                    {
                        data: 'pending_to',
                        render: function(data, type, row, meta) {
                            return '<span class="fw-bold text-primary">' + data + '</span>';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    // Transaction No
                    {
                        data: 'transaction_number',
                        render: function(data, type, row, meta) {
                            return '<span class="fw-bold">' + data + '</span>';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'full_name',
                        render: function(data, type, row, meta) {
                            return '<span>' + row.full_name + '</span>';
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
                        data: 'pension_details',
                        render: function(data, type, row, meta) {
                            return `<span>${row.pension_details ?? ''}</span>`;
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
                                        month: 'short',
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
                                        month: 'short',
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
                        data: 'bank_details',
                        render: function(data, type, row) {
                            return `<span>${row.bank_details ?? ''}</span>`;

                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'collection_date',
                        render: function(data, type, row, meta) {
                            return `<span>${row.collection_date}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'pin_code_details',
                        render: function(data, type, row) {
                            return `<span>${row.pin_code_details ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'bank_status',
                        render: function(data, type, row, meta) {
                            return data ? `<span>${data}</span>` : '';
                        },
                        orderable: true,
                        searchable: true,
                    },
                ];
                dataTable.initialize(url, columns);

                // Filtering of Transaction
                    var branchId = @json($branch_id);
                    var userHasBranchId = {!! Auth::user()->branch_id ? 'true' : 'false' !!};

                    if (userHasBranchId) {
                        $('#branch_id_select').val(branchId).prop('disabled', true);
                    }

                    $('#filterForm').submit(function(e) {
                        e.preventDefault();
                        var selectedBranch = $('#branch_id_select').val();

                        // Get the base URL for filtering
                        var targetUrl = '{!! route('ReleasedData') !!}';

                        // Add branch_id as a query parameter if user doesn't have a fixed branch and has selected a branch
                        if (!userHasBranchId && selectedBranch) {
                            targetUrl += '?branch_id=' + selectedBranch;
                        }

                        // Update the DataTable with the filtered data
                        dataTable.table.ajax.url(targetUrl).load();
                    });
                // Filtering of Transaction
            // Displaying of Data

            // Returning Client Transaction
                $('#returnClientTransactionModal').on('shown.bs.modal', function () {
                    $('#return_collection_date').select2({ dropdownParent: $('#returnClientTransactionModal'), });
                });

                // Fetching of Data
                $('#FetchingDatatable').on('click', '.returnClientTransaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#return_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#return_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#return_pension_number_display').text(data.pension_number ?? '');
                            $('#return_pension_number_display').inputmask("99-9999999-99");

                            $('#return_pension_number').val(data.pension_number ?? '');
                            $('#return_pension_type').text(data.pension_type ?? '');
                            $('#return_birth_date').val(formattedBirthDate);
                            $('#return_branch_location').val(data.branch.branch_location);

                            $('#return_atm_id').val(data.id);
                            $('#return_bank_account_no').val(data.bank_account_no ?? '');
                            $('#return_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#return_atm_type').val(data.atm_type ?? '');

                            var ReturnAtmType = data.atm_type;
                            if(ReturnAtmType == 'ATM'){
                                $('#ReturnPinCodeDetails').show();
                            } else {
                                $('#ReturnPinCodeDetails').hide();
                            }

                            $('#return_bank_name').val(data.bank_name ?? '');
                            $('#return_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#return_expiration_date').val((expirationDate || ''));

                            $('#returnClientTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                $("#client_transaction").on("change", function() {
                    const transaction_type = $("#client_transaction").val();

                    if (transaction_type === "return_old") {
                        $('#same_information').show();
                        $('#new_information').hide();
                    }
                    else {
                        $('#same_information').hide();
                        $('#new_information').show();
                    }
                });

                $(function() {
                    const maxRows = 5;

                    const newRowTemplate = `
                        <div class="row atm-details mb-3 mt-2">
                            <label class="fw-bold h6 text-center mb-3 text-primary">Add ATM / Passbook / Simcard No.</label>
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
                                        <select name="atm_status[]" class="form-select" required>
                                            <option value="">ATM Status</option>
                                            <option value="New" selected>New</option>
                                            <option value="Old">Old</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-sm-4 fw-bold">Card No.</label>
                                    <div class="col-8">
                                        <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="Card No." required>
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="font-size col-form-label col-4 fw-bold">Banks</label>
                                    <div class="col-8">
                                        <select name="bank_id[]" class="form-select select2">
                                            <option value="" selected disabled>Banks</option>
                                            @foreach ($DataBankLists as $bank)
                                                <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                    <div class="col-8">
                                        <input type="number" name="pin_code[]" class="form-control" placeholder="PIN Code" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                    <div class="col-8">
                                        <input type="month" name="expiration_date[]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Balance</label>
                                    <div class="col-8">
                                        <input type="text" name="atm_balance[]" class="balanceCurrency form-control" placeholder="Balance" required>
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Remarks</label>
                                    <div class="col-8">
                                        <input type="text" name="remarks[]" class="form-control" placeholder="Remarks" minlength="0" maxlength="100">
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Remove</label>
                                    <div class="col-8">
                                        <a href="#" class="btn btn-danger remove-atm-row"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-3">
                        </div>`;

                    function applyMasks() {
                        applyInputMaskCurrency();
                        applyCardNumberInputMask();
                    }

                    $('#addMoreAtmBtn').on('click', function(e) {
                        e.preventDefault();
                        const rowCount = $('.atm-details').length; // Get updated row count
                        if (rowCount < maxRows) {
                            $('#AddMoreAtmContainer').append(newRowTemplate);
                            applyMasks();
                        } else {
                            Swal.fire({ icon: "error", title: "Oops...", text: "Limit of 5 ATM Rows Only!" });
                        }
                    });

                    $('#AddMoreAtmContainer').on('click', '.remove-atm-row', function(e) {
                        e.preventDefault();
                        const rowCount = $('.atm-details').length; // Get updated row count
                        if (rowCount > 1) { // Ensure at least one row remains
                            $(this).closest('.atm-details').remove();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "At least one row must remain!"
                            });
                        }
                    });

                    applyMasks();
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

                function closeTransactionModal() {
                    $('#returnClientTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#TransactionReturnClientValidateForm').validate({
                    rules: {
                        remarks: { required: true },
                        old_pin_code: {
                            required: function (element) {
                                return $('#return_atm_type').val() === 'ATM'; // Pin code required only if ATM type is 'ATM'
                            }
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

                                            if (res.status === 'success'){
                                                closeTransactionModal();
                                                Swal.fire({
                                                    title: 'Successfully Created!',
                                                    text: 'Transaction is successfully Created!',
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
                                            } else if (res.status === 'error') {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: res.message,
                                                    icon: 'error',
                                                });
                                            } else {
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
            // Returning Client Transaction

            // Transfer to Other Branch
                $('#transferBranchTransactionModal').on('shown.bs.modal', function () {
                    $('#transfer_branch_id').select2({ dropdownParent: $('#transferBranchTransactionModal'), });
                });

                $('#FetchingDatatable').on('click', '.transferBranchTransaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#transfer_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#transfer_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#transfer_pension_number_display').text(data.pension_number ?? '');
                            $('#transfer_pension_number_display').inputmask("99-9999999-99");

                            $('#transfer_pension_number').val(data.pension_number ?? '');
                            $('#transfer_pension_type').text(data.pension_type ?? '');
                            $('#transfer_pension_account_type').val(data.account_type ?? '');
                            $('#transfer_birth_date').val(formattedBirthDate);
                            $('#transfer_branch_location').val(data.branch.branch_location ?? '');

                            $('#transfer_atm_id').val(data.id);
                            $('#transfer_bank_account_no').val(data.bank_account_no ?? '');
                            $('#transfer_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#transfer_atm_type').val(data.atm_type ?? '');
                            $('#transfer_bank_name').val(data.bank_name ?? '');
                            $('#transfer_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#transfer_expiration_date').val((expirationDate || ''));

                            $('#transferBranchTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function TransferBranchTransactionModal() {
                    $('#transferBranchTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#TransactionTransferBranchValidateForm').validate({
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

                                            if (res.status === 'success') {
                                                TransferBranchTransactionModal();
                                                Swal.fire({
                                                    title: 'Successfully Transfer!',
                                                    text: 'Transaction is successfully Transfer!',
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
                                            } else if (res.status === 'error') {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: res.message,
                                                    icon: 'error',
                                                });
                                            } else {
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
            // Transfer to Other Branch
        });

        $(document).on('click', '.view_pin_code', function(e) {
            e.preventDefault(); // Prevent the default anchor behavior

            const pinCode = $(this).data('pin');
            const bankAccountNo = $(this).data('bank_account_no');
            const atmId = $(this).data('atm_id');

            Swal.fire({
                icon: "question",
                title: 'Do you want to view the PIN code?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('system.pin-code.logs') }}",
                        type: "POST",
                        data: {
                            atm_id: atmId,
                            location: 'Released',
                            _token: csrfToken
                        },
                        success: function(response) {
                            if (typeof response === 'string') {
                                var res = JSON.parse(response);
                            } else {
                                var res = response; // If it's already an object
                            }

                            if (res.status === 'success') {
                                Swal.fire({
                                    title: 'PIN Code Details',
                                    html: `<br>
                                        <span class="fw-bold h3 text-dark">${pinCode}</span><br><br>
                                        <span class="fw-bold h4 text-primary">${bankAccountNo}</span><br>`,
                                    icon: 'info',
                                    confirmButtonText: 'Okay'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Unable to Display PIN code details. Please try again.'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong!',
                                text: 'Unable to log or fetch PIN code. Please try again.'
                            });
                            console.error('AJAX Error:', xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>

@endsection
