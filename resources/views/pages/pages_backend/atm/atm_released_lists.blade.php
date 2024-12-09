@extends('layouts.atm_monitoring_master')

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
                                    <th>Safekeep Box</th>
                                    <th>ATM / Passbook / Simcard No & Bank</th>
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
                    <form action="#" method="POST" id="TransactionReturnClientValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="return_atm_id">
                            <input type="hidden" name="reason_for_pull_out" value="9">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <div id="return_fullname" class="fw-bold h4"></div>
                                    <span id="return_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="return_pension_account_type" class="fw-bold h5"></span>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Birthdate</label>
                                <input type="text" class="form-control" id="return_birth_date" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Transaction Number</label>
                                <input type="text" class="form-control" id="return_transaction_number" readonly>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Bank Account Number</label>
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
                                <input type="text" class="form-control" id="return_collection_date" readonly>
                            </div>

                            <hr>
                            <div class="col-md-12">
                                <div class="form-group col-4 mb-3">
                                    <label class="fw-bold h6">Transaction</label>
                                    <select name="client_transaction" class="form-select" id="client_transaction" required>
                                        <option value="" selected disabled>Select Transaction</option>
                                        <option value="return_old">Return Same Information</option>
                                        <option value="return_new">Return New Information</option>
                                    </select>
                                </div>
                                <hr>

                                <span id="same_information" style="display:none;">
                                    <div class="row">
                                      <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6 mt-3">PIN Code <span class="text-danger">*</span></label>
                                            <input type="text" id="pin_code"
                                                  name="old_pin_code" class="form-control" placeholder="PIN Code">
                                        </div>
                                      </div>

                                      <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6 mt-3">Balance <span class="text-danger">*</span></label>
                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text">₱</span>
                                                  </div>
                                                  <input type="text" name="old_balance" id="atm_balance" class="balance_input_mask form-control"
                                                         placeholder="ATM / Passbook Balance" required>
                                              </div>
                                        </div>
                                      </div>

                                      <div class="col-md-3">
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
                                                        <label class="col-form-label col-sm-4 fw-bold">ATM / Passbook / Sim No.</label>
                                                        <div class="col-8">
                                                            <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="ATM / Passbook / Sim No." required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3 row align-items-center">
                                                        <label class="col-form-label col-4 fw-bold">Balance</label>
                                                        <div class="col-8">
                                                            <input type="text" name="atm_balance[]" class="balance_input_mask form-control" placeholder="Balance" required>
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
                                                    <div class="form-group mb-2 row align-items-center">
                                                        <label class="col-form-label col-4 fw-bold">Remove</label>
                                                        <div class="col-8">
                                                            <a href="#" class="btn btn-danger remove-atm-row"><i class="fas fa-trash"></i></a>
                                                        </div>
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

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('ReleasedData') !!}';
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
                    data: null,
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
                    data: null,
                    render: function(data, type, row, meta) {
                        return '<span>' + row.pension_details + '</span>';
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
                    data: 'safekeep_cash_box_no',
                    name: 'safekeep_cash_box_no',
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
                        // Initialize the variable for replacement count
                        let replacementCountDisplay = '';

                        // Check if replacement_count is greater than 0
                        if (row.replacement_count > 0) {
                        replacementCountDisplay = `<span class="text-danger fw-bold h6"> / ${row.replacement_count}</span>`;
                        }

                        return `<span class="fw-bold h6" style="color: #5AAD5D;">${row.bank_account_no}</span>
                                ${replacementCountDisplay}<br>
                                <span>${row.bank_name}</span>`;

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
                }
            ];
            dataTable.initialize(url, columns);


            // Returning Client Transaction
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

                $('#returnClientTransactionModal').on('shown.bs.modal', function () {
                    $('#bank_id').select2({ dropdownParent: $('#returnClientTransactionModal'), });
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
                                    <label class="col-form-label col-sm-4 fw-bold">ATM / Passbook / Sim No.</label>
                                    <div class="col-8">
                                        <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="ATM / Passbook / Sim No." required>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Balance</label>
                                    <div class="col-8">
                                        <input type="text" name="atm_balance[]" class="balance_input_mask form-control" placeholder="Balance" required>
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

                            $('#return_pension_number_display').text(data.client_information.pension_number ?? '');
                            $('#return_pension_number_display').inputmask("99-9999999-99");

                            $('#return_pension_number').val(data.client_information.pension_number);
                            $('#return_pension_account_type').text(data.client_information.pension_account_type);
                            $('#return_pension_type').val(data.client_information.pension_type);
                            $('#return_birth_date').val(formattedBirthDate);
                            $('#return_branch_location').val(data.branch.branch_location);

                            $('#return_atm_id').val(data.id);
                            $('#return_bank_account_no').val(data.bank_account_no ?? '');
                            $('#return_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#return_atm_type').val(data.atm_type ?? '');
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

                $('#TransactionReturnClientValidateForm').validate({
                    rules: {
                        remarks: { required: true }
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
             // Returning Client Transaction
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

    <script>
        function closeTransactionModal() {
            $('#pulloutBranchTransactionModal').modal('hide');
            $('#FetchingDatatable tbody').empty();
        }
    </script>

@endsection
