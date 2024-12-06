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
                    <form id="filterForm">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="fw-bold h6">Branch</label>
                                    <select name="branch_id" id="branch_id_select" class="form-select select2">
                                        <option value="">Select Branches</option>
                                        @foreach($Branches as $branch)
                                            <option value="{{ $branch->id }}" {{ $branch->id == $branch_id ? 'selected' : '' }}>
                                                {{ $branch->branch_location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="fw-bold h6">Transaction</label>
                                    <select name="transaction_id" id="transaction_id_select" class="form-select select2">
                                        <option value="">Select Transaction</option>
                                        @foreach ($DataTransactionAction as $transaction)
                                            <option value="{{ $transaction->id }}">{{ $transaction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="fw-bold h6">Status</label>
                                    <select name="status" id="status_select" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="ON GOING" selected>ON GOING</option>
                                        <option value="CANCELLED">CANCELLED</option>
                                        <option value="COMPLETED">COMPLETED</option>
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
                                    <th>Card No & Bank</th>
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

    <div class="modal fade" id="viewTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 75%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">View Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fw-bold">
                                Transaction Number : <span class="text-primary" id="view_transaction_number"></span>
                            </div>
                            <div class="fw-bold">
                                Transaction : <span class="text-primary" id="view_transaction_action"></span>
                            </div>
                            <div class="fw-bold">
                                Date Requested : <span class="text-primary" id="view_created_date"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fw-bold">
                                Name : <span class="text-primary" id="view_fullname"></span>
                            </div>
                            <div class="fw-bold">
                                Pension Number : <span class="text-primary" id="view_pension_number_display"></span> /
                                <span id="view_pension_account_type_display" class="fw-bold h6"></span> /
                                <span id="view_pension_type_display" class="fw-bold h6"></span>
                            </div>
                        </div>
                    </div>
                    <hr>

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
                                <th>Status</th>
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
                <form action="#">
                    <div class="modal-body">
                        <input type="hidden" name="atm_id" id="edit_atm_id">
                        <div class="form-group">
                            <div id="edit_fullname" class="fw-bold h4"></div>
                            <span id="edit_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> /
                            <span id="edit_pension_account_type_display" class="fw-bold h5"></span> /
                            <span id="edit_pension_type_display" class="fw-bold h5"></span>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Transaction Number</label>
                                <input type="text" name="transaction_number" id="edit_transaction_number" class="form-control" readonly>
                            </div>
                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Pension Type</label>
                                <select name="pension_type" id="edit_pension_type" class="form-select">
                                    <option value="SSS">SSS</option>
                                    <option value="GSIS">GSIS</option>
                                </select>
                            </div>
                            <div class="col-3 form-group mb-3">
                                <input type="hidden" id="edit_pension_account_type_value">
                                <label class="fw-bold h6">Pension Account Type</label>
                                <select name="pension_account_type" id="edit_pension_account_type_fetch" class="form-select select2">
                                </select>
                            </div>
                            <hr>

                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Firstname</label>
                                <input type="text" name="first_name" id="edit_first_name" class="form-control"
                                       minlength="0" maxlength="50" placeholder="Firstname" required>
                            </div>

                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Middlename</label>
                                <input type="text" name="middle_name" id="edit_middle_name" class="form-control"
                                       minlength="0" maxlength="50" placeholder="Middlename" required>
                            </div>

                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Lastname</label>
                                <input type="text" name="last_name" id="edit_last_name" class="form-control"
                                       minlength="0" maxlength="50" placeholder="Lastname" required>
                            </div>

                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Suffix</label>
                                <select name="suffix" id="edit_suffix" class="form-select">
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="Ma.">Ma.</option>
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>

                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Birthdate</label>
                                <input type="date" name="birth_date" id="edit_birth_date" class="form-control" required>
                            </div>

                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Collection Date</label>
                                <select name="collection_date" id="edit_collection_date" class="form-select" required>
                                    @foreach ($DataCollectionDate as $collection_date)
                                        <option value="{{ $collection_date->collection_date }}">{{ $collection_date->collection_date }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            <div class="col-12">
                                <div class="row mt-2">
                                    <hr>
                                    <label class="fw-bold h6 text-center mb-3 text-primary">
                                      ATM / Passsbook / Simcard Details
                                    </label>
                                    <hr>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3 row">
                                            <label class="col-form-label col-sm-4 fw-bold">Type</label>
                                            <div class="col-sm-5">
                                                <select name="atm_type" id="edit_atm_type" class="form-select" required>
                                                    <option value="ATM">ATM</option>
                                                    <option value="Passbook">Passbook</option>
                                                    <option value="Sim Card">Sim Card</option>
                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <select name="atm_status" id="edit_atm_status" class="form-select" required>
                                                    <option value="">ATM Status</option>
                                                    <option value="new">New</option>
                                                    <option value="old">Old</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="row" id="replaceBankAccountNo" style="display:block;"> --}}

                                        <div class="row mb-2 replaceBankAccountNo">
                                            <label class="col-4 fw-bold">ATM / Passbook / Sim No.</label>
                                            <div class="form-group col-8">
                                                <input type="text" name="atm_number" class="atm_card_input_mask form-control" id="edit_bank_account_no" placeholder="ATM / Passbook / Sim No." required>
                                            </div>
                                        </div>
                                        {{-- <div class="row mb-3" id="replaceBankName" style="display:block;"> --}}

                                        <div class="row mb-2 replaceBankName">
                                            <label class="col-4 fw-bold">Banks</label>
                                            <div class="form-group col-8">
                                                <select name="bank_name" id="edit_bank_name" class="form-select select2" required>
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
                                          <input type="number" name="pin_code" class="form-control" id="edit_pin_no" placeholder="PIN Code">
                                        </div>
                                      </div>

                                      <div class="form-group mb-3 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                        <div class="col-8">
                                          <input type="month" name="expiration_date" id="edit_expiration_date" class="form-control">
                                        </div>
                                      </div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                  </div>

                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-success">Edit Information</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelledTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 34%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Cancelled Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="cancelled_atm_id">
                            <div class="col-12">
                                <div class="form-group">
                                    <div id="cancelled_fullname" class="fw-bold h4"></div>
                                    <span id="cancelled_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="cancelled_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="cancelled_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="cancelled_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Account Number</label>
                                        <input type="text" class="form-control" id="cancelled_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="cancelled_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="cancelled_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="cancelled_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="cancelled_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 mb-3">
                                <label class="fw-bold h6">Remarks</label>
                                <textarea name="remarks" id="" rows="4" minlength="0" class="form-control" placeholder="Remarks"
                                            maxlength="300" style="resize:none;" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-danger">Cancelled Transaction</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="updateTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="updateTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 75%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <label class="fw-bold h4 text-uppercase mb-2">Update Transaction</label>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('TransactionUpdate') }}" method="POST" id="updateTransactionValidateForm">
                    @csrf
                    <input type="hidden" name="atm_id" id="update_atm_id">
                    <input type="hidden" name="transanction_id" id="update_transaction_id">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Transaction Number : <span class="text-primary" id="update_transaction_number"></span>
                                </div>
                                <div class="fw-bold">
                                    Transaction : <span class="text-primary" id="update_transaction_action"></span>
                                </div>
                                <div class="fw-bold">
                                    Date Requested : <span class="text-primary" id="update_created_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">
                                    Name : <span class="text-primary" id="update_fullname"></span>
                                </div>
                                <div class="fw-bold">
                                    Pension Number : <span class="text-primary" id="update_pension_number_display"></span> /
                                    <span id="update_pension_account_type_display" class="fw-bold h6"></span> /
                                    <span id="update_pension_type_display" class="fw-bold h6"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Location</label>
                                <select name="location" id="update_location" class="form-select">
                                        <option value="Head Office">Head Office</option>
                                        <option value="Branch">Branch</option>
                                        <option value="Released">Released</option>
                                        <option value="Safekeep">Safekeep</option>
                                    <!-- <option value="6">Safekeep</option> -->
                                </select>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">ATM / Passbook Status</label>
                                <select name="bank_status" id="update_bank_status" class="form-select">
                                        <option value="1">Active ATM</option>
                                        <option value="0">Release to Client</option>
                                        <option value="3">Release to Client - Become Return Client</option>
                                        <option value="5">Return Client / Balik Loob</option>
                                        <option value="6">Safekeep</option>
                                        <option value="27">Old ATM Did Not Return By Bank</option>
                                </select>
                            </div>
                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Bank Account No</label>
                                <input type="text" name="update_atm_bank_no" id="update_atm_bank_account_no" class="atm_card_input_mask form-control" required>
                            </div>

                            <hr>
                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Transaction Bank Account No</label>
                                <input type="text" name="update_transaction_bank_no" id="update_transaction_bank_account_no" class="atm_card_input_mask form-control" required>
                            </div>

                            <div class="form-group col-2 mb-3">
                                <label class="fw-bold h6">Transaction Status</label>
                                <select name="transaction_status" id="update_transaction_status" class="form-select">
                                    <option value="ON GOING">ON GOING</option>
                                    <option value="COMPLETED">COMPLETED</option>
                                    <option value="CANCELLED">CANCELLED</option>
                                </select>
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Reason</label>
                                <input type="text" name="reason" id="update_reason" class="form-control">
                            </div>

                            <div class="form-group col-3 mb-3">
                                <label class="fw-bold h6">Reason Remarks</label>
                                <input type="text" name="reason_remarks" id="update_reason_remarks" class="form-control">
                            </div>
                            <hr>
                        {{-- update_transaction_number --}}

                        <div class="table-responsive table_scrollable mt-3">
                            <table class="table table-border dt-responsive wrap table-design">
                                <thead class="table-light">
                                <thead>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 18%;">Employee ID</th>
                                    <th style="width: 18%;">Position</th>
                                    <th style="width: 5%;">Sequence</th>
                                    <th style="width: 10%;">Balance</th>
                                    <th style="width: 15%;">Remarks</th>
                                    <th style="width: 13%;">Status</th>
                                    <th style="width: 11%;">Date Received</th>
                                    <th style="width: 5%;">Image</th>
                                </thead>
                                <tbody id="UpdateTransactionApprovalBody">

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>

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
                        let branchLocation = '';
                        let clientName = `${row.full_name}`;

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
                    data: 'pension_details',
                    name: 'pension_details',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
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
                        if (row.atm_client_banks && row.atm_client_banks.bank_name) {
                            BankName = row.atm_client_banks.bank_name;
                        }
                        // Check if replacement_count is greater than 0
                        if (row.replacement_count > 0) {
                        replacementCountDisplay = `<span class="text-danger fw-bold h6"> / ${row.replacement_count}</span>`;
                        }

                        return `<span class="fw-bold h6" style="color: #5AAD5D;">${row.bank_account_no}</span>
                                ${replacementCountDisplay}<br>
                                <span>${BankName}</span>`;

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

                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        let badgeClass = '';
                        let statusClass = '';

                        // Determine the badge class and status label based on status value
                        switch (row.status) {
                            case 'ON GOING':
                                badgeClass = 'pt-1 pb-1 ps-2 ps-2 pe-2 badge bg-warning';
                                statusClass = 'On Going';
                                break;
                            case 'CANCELLED':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-danger';
                                statusClass = 'Cancelled';
                                break;
                            case 'COMPLETED':
                                badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-success';
                                statusClass = 'Completed';
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
                var branchId = @json($branch_id);
                var userHasBranchId = {!! Auth::user()->branch_id ? 'true' : 'false' !!};

                if (userHasBranchId) {
                    $('#branch_id_select').val(branchId).prop('disabled', true);
                }

                $('#filterForm').submit(function(e) {
                    e.preventDefault();

                    // Get selected filter values
                    var selectedBranch = $('#branch_id_select').val();
                    var selectedTransaction = $('#transaction_id_select').val();
                    var selectedStatus = $('#status_select').val();

                    // Construct the URL with required query parameters
                    var targetUrl = '{!! route('TransactionData') !!}';
                    targetUrl += '?transaction_actions_id=' + selectedTransaction + '&status=' + selectedStatus;

                    // If the user does not have a branch ID, add the branch_id parameter
                    if (!userHasBranchId && selectedBranch) {
                        targetUrl += '&branch_id=' + selectedBranch;
                    }

                    // Update the DataTable with the filtered data
                    dataTable.table.ajax.url(targetUrl).load();
                });
            // Filtering of Transaction

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
                        $('#view_fullname').text(data.atm_client_banks.client_information.last_name +', '
                                                    + data.atm_client_banks.client_information.first_name +' '
                                                    +(data.atm_client_banks.client_information.middle_name ?? '') +' '
                                                    + (data.atm_client_banks.client_information.suffix ?? ''));

                        $('#view_pension_number_display').text(data.atm_client_banks.client_information.pension_number ?? '');
                        $('#view_pension_number_display').inputmask("99-9999999-99");
                        $('#view_pension_type_display').text(data.atm_client_banks.client_information.pension_type);
                        $('#view_pension_account_type_display').text(data.atm_client_banks.client_information.pension_account_type);

                        let formattedCreatedDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#view_transaction_number').text(data.transaction_number);
                        $('#view_created_date').text(formattedCreatedDate);
                        $('#view_transaction_action').text(data.data_transaction_action.name);


                        $('#TransactionApprovalBody').empty();

                        data.atm_banks_transaction_approval.forEach(function (rows) {
                            var employee_id = rows.employee_id !== null ? rows.employee_id : '';
                            var employee_name = rows.employee && rows.employee.name ? rows.employee.name : '';

                            // Load Inputmask if not already included in your environment
                            if (typeof Inputmask !== 'undefined') {
                                var balance = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.balance !== null
                                    ? rows.atm_transaction_approvals_balance_logs.balance
                                    : '';

                                // Apply Inputmask to format the balance if it's not empty
                                if (balance !== '') {
                                    balance = Inputmask.format(balance, {
                                        alias: 'numeric',
                                        prefix: '₱ ',
                                        groupSeparator: ',',
                                        autoGroup: true,
                                        digits: 2,
                                        digitsOptional: false,
                                        placeholder: '0'
                                    });
                                }
                            }

                            var remarks = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.remarks !== null
                                        ? rows.atm_transaction_approvals_balance_logs.remarks
                                        : '';

                            var badgeClass = '';
                            var StatusName = '';

                            switch (rows.status) {
                                case 'Completed':
                                case 'Others Account':
                                    badgeClass = 'badge bg-success';
                                    StatusName = 'Completed';
                                    break;
                                case 'Pending':
                                    badgeClass = 'badge bg-warning';
                                    StatusName = 'Pending';
                                    break;
                                case 'Stand By':
                                    badgeClass = 'badge bg-primary';
                                    StatusName = 'Stand By';
                                    break;
                                case 'Cancelled':
                                    badgeClass = 'badge bg-danger';
                                    StatusName = 'Cancelled';
                                    break;
                                default:
                                    badgeClass = 'badge bg-secondary'; // Default badge in case of unexpected status
                                    StatusName = status;
                                    break;
                            }

                            // Format the date_approved if it's not null
                            var dateApproved = rows.date_approved ? new Date(rows.date_approved).toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            }) : ''; // Leave blank if null

                            // Construct the new row with the status badge
                            var newRow = '<tr>' +
                                '<td>' + rows.id + '</td>' +
                                '<td>' + employee_id + '</td>' +
                                '<td>' + employee_name + '</td>' +
                                '<td>' + rows.data_user_group.group_name + '</td>' +
                                '<td>' + rows.sequence_no + '</td>' +
                                '<td>' + balance + '</td>' +
                                '<td>' + remarks + '</td>' +
                                '<td>' + '' + '</td>' +
                                '<td><span class="' + badgeClass + '">' + StatusName + '</span></td>' + // Display status with badge
                                '<td>' + dateApproved + '</td>' +
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

            // Edit Information
                $('#EditClientInformationModal').on('shown.bs.modal', function () {
                    $('#edit_pension_account_type_fetch').select2({ dropdownParent: $('#EditClientInformationModal'), });
                    $('#edit_bank_name').select2({ dropdownParent: $('#EditClientInformationModal'), });
                });

                $('#edit_pension_type').on('change', function() {
                    var selected_pension_types = $(this).val();

                    setTimeout(function() {
                        // Ensure we have the previous value for Pension Account Type
                        var PreviousPensionAccountTypeValue = $('#edit_pension_account_type_value').val();

                        console.log(PreviousPensionAccountTypeValue);

                        $.ajax({
                            url: '/pension/types/fetch',
                            type: 'GET',
                            data: {
                                selected_pension_types: selected_pension_types
                            },
                            success: function(response) {
                                // Start with the default option
                                var options = '<option value="">Select Pension Account Type</option>';

                                // Populate options with the response data
                                $.each(response, function(index, item) {
                                    // Mark as selected if it matches the previous value
                                    var selected = (item.pension_name === PreviousPensionAccountTypeValue) ? 'selected' : '';
                                    options += `<option value="${item.pension_name}" ${selected}>${item.pension_name}</option>`;
                                });

                                // Update the dropdown and trigger change to show selected
                                $('#edit_pension_account_type_fetch').html(options).trigger('change');
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', status, error);
                            }
                        });

                    }, 100);
                });

                $('#FetchingDatatable').on('click', '.editClientTransaction', function(e) {
                    e.preventDefault();
                    var transaction_id = $(this).data('id');

                    $.ajax({
                        url: "/TransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            $('#edit_transaction_id').val(data.id);
                            $('#edit_fullname').text(data.atm_client_banks.client_information.last_name +', '
                                                        + data.atm_client_banks.client_information.first_name +' '
                                                        +(data.atm_client_banks.client_information.middle_name ?? '') +' '
                                                        + (data.atm_client_banks.client_information.suffix ?? ''));

                            $('#edit_pension_number_display').text(data.atm_client_banks.client_information.pension_number ?? '');
                            $('#edit_pension_number_display').inputmask("99-9999999-99");
                            $('#edit_atm_id').val(data.atm_client_banks.id);

                            $('#edit_collection_date').val(data.atm_client_banks.collection_date ?? '').trigger('change');
                            $('#edit_atm_type').val(data.atm_client_banks.atm_type ?? '').trigger('change');
                            $('#edit_bank_name').val(data.atm_client_banks.bank_name ?? '').trigger('change');
                            $('#edit_atm_status').val(data.atm_client_banks.atm_status ?? '').trigger('change');
                            $('#edit_bank_account_no').val(data.atm_client_banks.bank_account_no ?? '');
                            $('#edit_pin_no').val(data.atm_client_banks.pin_no ?? '');

                            $('#edit_expiration_date').val(formatExpirationDate(data.atm_client_banks.expiration_date));
                            function formatExpirationDate(expirationDate) {
                                // If the expiration date exists and is in a valid format (YYYY-MM-DD)
                                if (expirationDate) {
                                    var date = new Date(expirationDate);
                                    var month = date.getMonth() + 1; // Get the month (0-11)
                                    var year = date.getFullYear(); // Get the year
                                    // Format as YYYY-MM
                                    return year + '-' + (month < 10 ? '0' + month : month);
                                }
                                return ''; // Return an empty string if no expiration date
                            }

                            $('#edit_transaction_number').val(data.transaction_number ?? '');
                            $('#edit_pension_type').val(data.atm_client_banks.client_information.pension_type).trigger('change');
                            $('#edit_pension_account_type_fetch').val(data.atm_client_banks.client_information.pension_account_type).trigger('change');
                            $('#edit_first_name').val(data.atm_client_banks.client_information.first_name ?? '');
                            $('#edit_middle_name').val(data.atm_client_banks.client_information.middle_name ?? '');
                            $('#edit_last_name').val(data.atm_client_banks.client_information.last_name ?? '');
                            $('#edit_suffix').val(data.atm_client_banks.client_information.suffix ?? '').trigger('change');
                            $('#edit_birth_date').val(data.atm_client_banks.client_information.birth_date ?? '');

                            $('#edit_pension_type_display').text(data.atm_client_banks.client_information.pension_type);
                            $('#edit_pension_account_type_display').text(data.atm_client_banks.client_information.pension_account_type);
                            $('#edit_pension_account_type_value').val(data.atm_client_banks.client_information.pension_account_type);

                            $('#EditClientInformationModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });
            // Edit Information

            $('#FetchingDatatable').on('click', '.cancelledTransaction', function(e) {
                e.preventDefault();
                var transaction_id = $(this).data('id');

                $.ajax({
                    url: "/TransactionGet",
                    type: "GET",
                    data: { transaction_id : transaction_id },
                    success: function(data) {
                        let formattedBirthDate = data.atm_client_banks.client_information.birth_date ? new Date(data.atm_client_banks.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#cancelled_fullname').text(data.atm_client_banks.client_information.last_name +', '
                                                    + data.atm_client_banks.client_information.first_name +' '
                                                    +(data.atm_client_banks.client_information.middle_name ?? '') +' '
                                                    + (data.atm_client_banks.client_information.suffix ?? ''));

                        $('#cancelled_branch_id').val(data.branch_id ?? '').trigger('change');

                        $('#cancelled_pension_number_display').text(data.atm_client_banks.client_information.pension_number ?? '');
                        $('#cancelled_pension_number_display').inputmask("99-9999999-99");

                        $('#cancelled_pension_number').val(data.atm_client_banks.client_information.pension_number);
                        $('#cancelled_pension_account_type').text(data.atm_client_banks.client_information.pension_account_type);
                        $('#cancelled_pension_type').val(data.atm_client_banks.client_information.pension_type);
                        $('#cancelled_birth_date').val(formattedBirthDate);
                        $('#cancelled_atm_id').val(data.atm_client_banks.id);
                        $('#cancelled_bank_account_no').val(data.atm_client_banks.bank_account_no ?? '');
                        $('#cancelled_collection_date').val(data.atm_client_banks.collection_date ?? '').trigger('change');
                        $('#cancelled_atm_type').val(data.atm_client_banks.atm_type ?? '');
                        $('#cancelled_bank_name').val(data.atm_client_banks.bank_name ?? '');
                        $('#cancelled_transaction_number').val(data.atm_client_banks.transaction_number ?? '');

                        let expirationDate = '';
                        if (data.atm_client_banks.expiration_date && data.atm_client_banks.expiration_date !== '0000-00-00') {
                            expirationDate = new Date(data.atm_client_banks.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                        }
                        $('#cancelled_expiration_date').val((expirationDate || ''));

                        $('#cancelledTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            // Edit Transaction
                $('#FetchingDatatable').on('click', '.editAdminTransaction', function(e) {
                    e.preventDefault();
                    var transaction_id = $(this).data('id');

                    $.ajax({
                        url: "/TransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            $('#update_transaction_id').val(data.id);
                            $('#update_atm_id').val(data.atm_client_banks.id);
                            $('#update_fullname').text(data.atm_client_banks.client_information.last_name +', '
                                                        + data.atm_client_banks.client_information.first_name +' '
                                                        +(data.atm_client_banks.client_information.middle_name ?? '') +' '
                                                        + (data.atm_client_banks.client_information.suffix ?? ''));

                            $('#update_pension_number_display').text(data.atm_client_banks.client_information.pension_number ?? '');
                            $('#update_pension_number_display').inputmask("99-9999999-99");
                            $('#update_pension_type_display').text(data.atm_client_banks.client_information.pension_type);
                            $('#update_pension_account_type_display').text(data.atm_client_banks.client_information.pension_account_type);

                            let formattedCreatedDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#update_transaction_number').text(data.transaction_number);
                            $('#update_created_date').text(formattedCreatedDate);
                            $('#update_transaction_action').text(data.data_transaction_action.name);

                            $('#update_transaction_status').val(data.status).trigger('change');
                            $('#update_transaction_bank_account_no').val(data.bank_account_no);
                            $('#update_reason').val(data.reason);
                            $('#update_reason_remarks').val(data.reason_remarks);

                            // ATM Information
                            $('#update_atm_id').val(data.atm_client_banks.id);
                            $('#update_location').val(data.atm_client_banks.location).trigger('change');
                            $('#update_bank_status').val(data.atm_client_banks.status).trigger('change');
                            $('#update_atm_bank_account_no').val(data.atm_client_banks.bank_account_no);

                            $.ajax({
                                url: '/UserSelect',
                                method: 'GET',
                                success: function(Users) {
                                    var hasUsers = Users && Users.length > 0;

                                $('#UpdateTransactionApprovalBody').empty();

                                    data.atm_banks_transaction_approval.forEach(function (rows) {
                                        var employee_id = rows.employee_id !== null ? rows.employee_id : '';
                                        var employee_name = rows.employee && rows.employee.name ? rows.employee.name : '';

                                        var employeeSelect = '<select name="employee_id[]" class="employee_select form-select select2">';
                                        employeeSelect += '<option value="">Select Employee</option>';

                                        if (hasUsers) {
                                            $.each(Users, function(index, user) {
                                                // Check if rows.employee_id matches user.employee_code; if null/empty, default to "Select Employee"
                                                var selected = (rows.employee_id == user.employee_id) ? 'selected' : '';
                                                employeeSelect += '<option value="' + user.employee_id + '" ' + selected + '>' + user.employee_id + ' - ' + user.name + '</option>';
                                            });
                                        } else {
                                            // Show "Select Employee" if no employees are available
                                            employeeSelect += '<option value="" disabled>No employees available</option>';
                                        }

                                        // Load Inputmask if not already included in your environment
                                        if (typeof Inputmask !== 'undefined') {
                                            var balance = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.balance !== null
                                                ? rows.atm_transaction_approvals_balance_logs.balance
                                                : '';

                                            // Apply Inputmask to format the balance if it's not empty
                                            if (balance !== '') {
                                                balance = Inputmask.format(balance, {
                                                    alias: 'numeric',
                                                    prefix: '₱ ',
                                                    groupSeparator: ',',
                                                    autoGroup: true,
                                                    digits: 2,
                                                    digitsOptional: false,
                                                    placeholder: '0'
                                                });
                                            }
                                        }

                                        var remarks = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.remarks !== null
                                                    ? rows.atm_transaction_approvals_balance_logs.remarks
                                                    : '';

                                        var statusOptions = {
                                            'Completed': 'Completed',
                                            'Pending': 'Pending',
                                            'Stand By': 'Stand By',
                                            'Cancelled': 'Cancelled',
                                            'Open to Others': 'Open to Others'
                                        };

                                        var selectedStatus = rows.status || ''; // Default to an empty string if rows.status is undefined
                                        var statusSelect = '<select class="form-control" name="status[]" class="form-select">';
                                        $.each(statusOptions, function(value, text) {
                                            statusSelect += '<option value="' + value + '" ' + (value === selectedStatus ? 'selected' : '') + '>' + text + '</option>';
                                        });
                                        statusSelect += '</select>';

                                        var dateApprovedFormatted = rows.date_approved ? rows.date_approved.replace(' ', 'T').slice(0, 16) : '';
                                        // Construct the new row with the status badge
                                        var newRow = '<tr>' +
                                            '<td>'
                                                + rows.id +
                                                '<input type="hidden" name="approval_id[]" value="'+ rows.id +'">' +
                                            '</td>' +
                                            '<td>' +
                                                '<div class="form-group">' + employeeSelect + '</div>' +
                                            '</td>' +
                                            '<td>'
                                                + '<span class="fw-bold h6 text-primary">' + employee_name + '</span><br>'
                                                + rows.data_user_group.group_name +
                                            '</td>' +
                                            '<td>' + rows.sequence_no + '</td>' +
                                            '<td>' + balance + '</td>' +
                                            '<td>' + remarks + '</td>' +
                                            '<td>' + statusSelect + '</td>' +
                                            '<td>' + '<input type="datetime-local" class="form-control" name="date_approved[]" value="'+ dateApprovedFormatted +'">' + '</td>' +
                                            '<td>' + '' + '</td>' +
                                            '</tr>';

                                        $('#UpdateTransactionApprovalBody').append(newRow);

                                        $('.employee_select').select2({
                                            dropdownParent: $('#updateTransactionModal')
                                        });
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error fetching elog users:", error);
                                }
                            });

                            $('#updateTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function UpdateTransactionModal() {
                    $('#updateTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

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
                                                    text: 'Transaction is successfully Update!',
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





        });

    </script>

@endsection
