@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM Monitoring @endslot
        @slot('title') Head Office ATM Lists @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Head Office ATM Lists</h4>
                            <p class="card-title-desc">
                                A Centralized Record of all ATMs managed by the head office
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddOldNewClientModal">
                                <i class="fas fa-plus-circle me-1"></i> Add Old / New Client</button>
                        </div>
                    </div>
                    <hr>
                        @if(in_array($userGroup, ['Developer', 'Admin', 'Everfirst Admin','Branch Head']))
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
                                    <th>Reference No</th>
                                    <th>Client</th>
                                    <th>Branch</th>
                                    {{-- <th>Pension No. / Type</th> --}}
                                    <th>Pension No</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    <th>Box</th>
                                    <th>Card No. / Bank</th>
                                    <th>Coll Date</th>
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

    <div class="modal fade" id="createTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('transaction.pullout.create') }}" method="POST" id="TransactionCreateValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="create_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="create_fullname" class="fw-bold h4"></div>
                                    <span id="create_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="create_pension_type_display" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="create_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="create_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card No.</label>
                                        <input type="text" class="form-control" id="create_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="create_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="create_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="create_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="create_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Select Reason For Pullout</label>
                                    <select name="reason_for_pull_out" id="reason_for_pull_out" class="form-select" required>
                                        <option value="" selected disabled>Reason for Pullout</option>
                                        @foreach ($DataTransactionAction as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <span id="BorrowTransaction" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Select Reason</label>
                                        <select name="borrow_reason" id="borrow_reason" class="form-select" required>
                                            <option value="" selected disabled>Select Reason</option>
                                            <option value="For SSS/GSIS Report">For SSS/GSIS Report</option>
                                            <option value="For Emegency Loan">For Emegency Loan</option>
                                            <option value="For Bank Report">For Bank Report</option>
                                            <option value="For Requirements">For Requirements</option>
                                        </select>
                                    </div>
                                </span>

                                <span id="RemarksTransaction" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Remarks</label>
                                        <textarea name="remarks" id="remarks" minlength="0" maxlength="300" placeholder="Enter Remarks"
                                            class="form-control" rows="5" style="resize: none;" required></textarea>
                                    </div>
                                </span>

                                <span id="ReleasingTransaction" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Select Releasing Reason</label>
                                        <select name="release_reason" id="release_reason" class="form-select select2" required>
                                            <option value="" selected disabled>Releasing Reason</option>
                                            @foreach ($DataReleaseOption as $item)
                                            <option value="{{ $item->reason }}">{{ $item->reason }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">APRB Number</label>
                                        <input type="number" name="aprb_no" class="form-control"  placeholder="APRB Number" required>
                                    </div>
                                </span>
                            </div>

                            <div class="col-12" id="ReleasingTableSelect" style="display: none;">
                                <hr>
                                <div class="row mb-2 mt-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold h5 text-danger">Select ATM / Passbook / Simcard to Release</label>
                                    </div>
                                    <div class="col-md-6">
                                        <span id="SelectAtleastOneError"></span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-border dt-responsive wrap table-design">
                                        <thead>
                                            <th>Checkbox</th>
                                            <th>Transaction Number</th>
                                            <th>Card No.</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Collection Date</th>
                                            <th>Expiration Date</th>
                                        </thead>

                                        <tbody id="displayClientBanksInformation">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAtmTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Add ATM / PB / Simcard Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('TransactionAddAtm') }}" method="POST" id="TransactionAddAtmValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="add_atm_atm_id">
                            <input type="hidden" name="reason_for_pull_out" value="23">
                            <div class="col-12">
                                <div class="form-group">
                                    <div id="add_atm_fullname" class="fw-bold h4"></div>
                                    <span id="add_atm_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="add_atm_pension_account_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="add_atm_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="add_atm_birth_date" readonly>
                                    </div>

                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Card No.</label>
                                        <input type="text" class="form-control" id="add_atm_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="add_atm_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="add_atm_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="add_atm_expiration_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Pension Number</label>
                                        <span id="SamePensionNumberSelected" style="display: none;">
                                            <input type="text" class="form-control pension_number_mask"
                                                name="pension_number"
                                                id="add_atm_pension_number_value"
                                                placeholder="Pension Number">
                                        </span>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" id="same_pension_number" checked>
                                            <label class="text-danger" for="same_pension_number" style="font-size: 10px;">
                                                check if same pension no. used
                                            </label>
                                        </div>
                                        <input type="hidden" name="pension_no_select" id="pension_no_select" value="yes">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Account Type</label>
                                        <select name="account_type" id="add_atm_account_type" class="form-select" required>
                                            <option value="SSS">SSS</option>
                                            <option value="GSIS">GSIS</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="add_atm_pension_type_value">

                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Pension Type</label>
                                        <select name="pension_type" id="add_atm_pension_account_type_dropdown" class="form-select select2" required>
                                            <option value="">Pension Account Type</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row mt-2">
                                    <hr>
                                    <label class="fw-bold h6 text-center mb-3 text-primary">
                                      ATM / Passsbook / Simcard Details
                                    </label>

                                    <hr>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-sm-4 fw-bold">Type</label>
                                            <div class="col-sm-5">
                                            <select name="atm_type" id="atm_type_add_atm" class="form-select" required>
                                                <option value="" selected disabled>Type</option>
                                                <option value="ATM">ATM</option>
                                                <option value="Passbook">Passbook</option>
                                                <option value="Sim Card">Sim Card</option>
                                            </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="atm_status" id="atm_status" class="form-select" required>
                                                <option value="">ATM Status</option>
                                                <option value="New" selected>New</option>
                                                <option value="Old">Old</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Card No.</label>
                                            <div class="col-8">
                                                <input type="text" name="atm_number" class="atm_card_input_mask form-control" placeholder="Card No." required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="font-size col-form-label col-4 fw-bold">Banks</label>
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <select name="bank_name" id="add_atm_bank_names" class="form-select select2" required>
                                                        <option value="" selected>Select Bank</option>
                                                        @foreach ($DataBankLists as $bank)
                                                            <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                            <div class="col-8">
                                                <input type="number" name="pin_code" class="form-control" placeholder="PIN Code">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Collection Date</label>
                                            <div class="col-8">
                                                <select name="collection_date" id="add_atm_collection_date" class="form-select select2">
                                                    <option value="">Collection Date</option>
                                                    @foreach ($DataCollectionDate as $collection)
                                                        <option value="{{ $collection->collection_date }}">{{ $collection->collection_date }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                            <div class="col-8">
                                                <input type="month" name="expiration_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Balance</label>
                                            <div class="col-8">
                                                <input type="text" name="atm_balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Remarks</label>
                                            <div class="col-8">
                                                <input type="text" name="remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="100">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Add ATM</button>
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
                                    <span id="transfer_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="transfer_pension_account_type" class="fw-bold h5"></span>
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

    <div class="modal fade" id="editInformationTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Edit Client / ATM Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('TransactionEditClient') }}" method="POST" id="TransactionEditClientValidateForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="atm_id" id="edit_atm_id">
                        <div class="form-group">
                            <div id="edit_fullname" class="fw-bold h4"></div>
                            <span id="edit_pension_number_display" class="ms-3 text-primary fw-bold h5"></span>
                            /
                            <span id="edit_pension_type_display" class="fw-bold h5"></span>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 form-group mb-3">
                                <label class="fw-bold h6">Transaction Number</label>
                                <input type="text" name="transaction_number" id="edit_transaction_number" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="fw-bold h6">Branch</label>
                                <select name="branch_id" id="edit_branch_id" class="form-select select2">
                                    @foreach ($Branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>

                            <div class="col-md-3 form-group mb-3">
                                <label class="fw-bold h6">Lastname</label>
                                <input type="text" name="last_name" id="edit_last_name" class="form-control"
                                       minlength="0" maxlength="50" placeholder="Lastname" required>
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label class="fw-bold h6">Firstname</label>
                                <input type="text" name="first_name" id="edit_first_name" class="form-control"
                                       minlength="0" maxlength="50" placeholder="Firstname" required>
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label class="fw-bold h6">Middle Initial</label>
                                <input type="text" name="middle_name" id="edit_middle_name" class="form-control"
                                       minlength="0" maxlength="3" placeholder="Middle Initial" required>
                            </div>

                            <div class="col-md-3 form-group mb-3">
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

                            <div class="col-md-3 form-group mb-3">
                                <label class="fw-bold h6">Birthdate</label>
                                <input type="date" name="birth_date" id="edit_birth_date" class="form-control" required>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Pension Number</label>
                                        <span id="SamePensionNumberSelectedEdit" style="display: none;">
                                            <input type="text" class="form-control pension_number_mask"
                                                name="pension_number"
                                                id="edit_pension_number_value"
                                                placeholder="Pension Number">
                                        </span>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" id="same_pension_number_edit" checked>
                                            <label class="text-danger" style="font-size: 10px;">
                                                check if same pension no. used
                                            </label>
                                        </div>
                                        <input type="hidden" name="pension_no_select" id="pension_no_select_edit" value="yes">
                                    </div>
                                </div>
                                <div class="col-md-3 form-group mb-3">
                                    <label class="fw-bold h6">Account Type</label>
                                    <select name="account_type" id="edit_account_type" class="form-select">
                                        <option value="SSS">SSS</option>
                                        <option value="GSIS">GSIS</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-3">
                                    <input type="hidden" id="edit_pension_account_type_value">
                                    <label class="fw-bold h6">Pension Type</label>
                                    <select name="pension_type" id="edit_pension_account_type_fetch" class="form-select select2">
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <div class="row">
                                    <label class="fw-bold h6 text-center mb-3 text-primary">
                                      ATM / Passsbook / Simcard Details
                                    </label>
                                    <hr>

                                        <div class="form-group mb-2 row">
                                            <div class="col-auto">
                                                <input type="checkbox" name="replacement_atm" id="hide_atm_details" value="replacement_atm" class="form-check-input ms-1 me-1">
                                            </div>
                                            <div class="col">
                                                <label class="fw-bold h6">
                                                    Is this a Replacement ATM with the same Bank Number? Check if Yes Select Checkbox.
                                                </label>
                                            </div>
                                            <input type="hidden" name="replace_status" value="no" id="replaced_status">
                                        </div>
                                    <hr>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2 row align-items-center">
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

                                        <div class="row mb-2 replaceBankAccountNo align-items-center">
                                            <label class="col-4 fw-bold">Card No.</label>
                                            <div class="form-group col-8">
                                                <input type="text" name="atm_number" class="atm_card_input_mask form-control" id="edit_bank_account_no" placeholder="ATM / Passbook / Sim No." required>
                                            </div>
                                        </div>
                                        {{-- <div class="row mb-2" id="replaceBankName" style="display:block;"> --}}

                                        <div class="row mb-2 replaceBankName align-items-center">
                                            <label class="col-4 fw-bold">Banks</label>
                                            <div class="form-group col-8">
                                                <select name="bank_name" id="edit_bank_name" class="form-select select2" required>
                                                    @foreach ($DataBankLists as $bank)
                                                        <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                            <div class="col-8">
                                                <input type="number" name="pin_code" class="form-control" id="edit_pin_no" placeholder="PIN Code">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                        <div class="col-8">
                                          <input type="month" name="expiration_date" id="edit_expiration_date" class="form-control">
                                        </div>
                                      </div>

                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Collection Date</label>
                                        <div class="col-8">
                                            <select name="collection_date" id="edit_collection_date" class="form-select" required>
                                                @foreach ($DataCollectionDate as $collection_date)
                                                    <option value="{{ $collection_date->collection_date }}">{{ $collection_date->collection_date }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                      </div>

                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Cash Box No.</label>
                                        <div class="col-8">
                                          <input type="number" name="cash_box_no" id="edit_cash_box_no" class="form-control" placeholder="Cash Box No.">
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

    <div class="modal fade" id="choose_slot_print_qr_modal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop='static' aria-hidden="true" data-keyboard="false">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">

              <h5 class="modal-title fw-bold text-uppercase" id="exampleModalLabel">Select Printing Area
                <input type="hidden" id="reference_number_slot">
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <div class="container">
                <div class="row justify-content-center">
                    <?php
                    for ($i = 1; $i <= 77; $i++) {
                    ?>
                        <div class="col-auto mb-2">
                            <div class="slot_number text-center" style="width:75px; height:75px; padding:5px;">
                                <button type="button" class="btn btn-primary w-100" id='print_number_receiving' value="<?= $i ?>">Slot <br><?= $i ?></button>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
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
            // Display Data
                var FetchingDatatableBody = $('#FetchingDatatable tbody');

                const dataTable = new ServerSideDataTable('#FetchingDatatable');
                var url = '{!! route('HeadOfficeData') !!}';
                const columns = [
                    {
                        data: null,
                        name: 'action', // This matches the name you used in your server-side code
                        render: function(data, type, row) {
                            return row.action + ' ' + row.passbook_for_collection; // Concatenate action and passbook_for_collection
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
                        data: 'full_name',
                        render: function(data, type, row, meta) {
                            return `<span>${row.full_name ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'branch_location',
                        render: function(data, type, row, meta) {
                            return `<span>${row.branch_location ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'pension_details',
                        render: function(data, type, row, meta) {
                            return '<span>' + row.pension_details + '</span>'; // Check if company exists
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: null,
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
                        data: 'cash_box_no',
                        name: 'cash_box_no',
                        render: function(data, type, row, meta) {
                            return data ? `<span>${data}</span>` : '';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'bank_details',
                        render: function(data, type, row, meta) {
                            return `<span>${row.bank_details ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'collection_date',
                        render: function(data, type, row, meta) {
                            return `<span>${row.collection_date ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'pin_code_details',
                        render: function(data, type, row, meta) {
                            return data ? `<span>${data}</span>` : '';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'atm_status',
                        render: function(data, type, row, meta) {
                            return data ? `<span>${data}</span>` : '';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'qr_code',
                        name: 'qr_code',
                        render: function(data, type, row, meta) {
                            return '<span>' + data + '</span>';
                        },
                        orderable: false,
                        searchable: false,
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
                        var targetUrl = '{!! route('HeadOfficeData') !!}';

                        // Add branch_id as a query parameter if user doesn't have a fixed branch and has selected a branch
                        if (!userHasBranchId && selectedBranch) {
                            targetUrl += '?branch_id=' + selectedBranch;
                        }

                        // Update the DataTable with the filtered data
                        dataTable.table.ajax.url(targetUrl).load();
                    });
                // End Filtering of Transaction
            // Display Data

            // Create of Transaction for Pullout
                $('#FetchingDatatable').on('click', '.createTransaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ?
                                new Date(data.client_information.birth_date).toLocaleDateString('en-US',
                                    { month: 'long',
                                        day: 'numeric',
                                        year: 'numeric'
                                    }) : '';

                            $('#create_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#create_pension_number_display').text(data.pension_number ?? '');
                            $('#create_pension_number_display').inputmask("99-9999999-99");
                            $('#create_pension_type_display').text(data.pension_type);
                            $('#create_birth_date').val(formattedBirthDate);
                            $('#create_branch_location').val(data.branch.branch_location);

                            $('#create_atm_id').val(data.id);
                            $('#create_bank_account_no').val(data.bank_account_no ?? '');
                            $('#create_collection_date').val(data.collection_date ?? '');
                            $('#create_atm_type').val(data.atm_type ?? '');
                            $('#create_bank_name').val(data.bank_name ?? '');
                            $('#create_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#create_expiration_date').val((expirationDate || ''));

                            // Start Used Only for Releasing and Releasing with Balance Transaction
                                $('#displayClientBanksInformation').empty();

                                $.ajax({
                                    url: "/AtmClientBanksFetch",
                                    type: "GET",
                                    data: { client_id : data.client_information.id },
                                    success: function(response) {
                                        if (response.atm_client_banks && response.atm_client_banks) {
                                            $.each(response.atm_client_banks, function(index, atms) {
                                                // Only proceed if the status is equal to 1
                                                if (atms.status == 1) {
                                                    let AtmsExpirationDate = '';
                                                    if (atms.expiration_date && atms.expiration_date !== '0000-00-00') {
                                                        AtmsExpirationDate = new Date(atms.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                                                    }

                                                    let atmTypeClass = '';
                                                    if (atms.atm_type === 'ATM') {
                                                        atmTypeClass = 'text-primary';
                                                    } else if (atms.atm_type === 'Passbook') {
                                                        atmTypeClass = 'text-danger';
                                                    } else if (atms.atm_type === 'Sim Card') {
                                                        atmTypeClass = 'text-info';
                                                    }

                                                    const row = `
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="atm_checkboxes[]" id="atm_checkbox" value="${atms.id}">
                                                                </div>
                                                            </td>
                                                            <td>${atms.transaction_number}</td>
                                                            <td>
                                                                <span class="fw-bold h6 text-success">${atms.bank_account_no}</span><br>
                                                                ${atms.bank_name}
                                                            </td>
                                                            <td class="${atmTypeClass}">${atms.atm_type}</td>
                                                            <td>${atms.atm_status}</td>
                                                            <td>${atms.collection_date}</td>
                                                            <td>${AtmsExpirationDate}</td>
                                                        </tr>
                                                    `;

                                                    // Append the row to the display element
                                                    $('#displayClientBanksInformation').append(row);
                                                }
                                            });
                                        }
                                    }
                                });

                            $('#createTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                // Validation for Creation of Transaction
                    function closeTransactionModal() {
                        $('#createTransactionModal').modal('hide');
                        $('#FetchingDatatable tbody').empty();
                    }

                    $.validator.addMethod("checkAtLeastOne", function(value, element) {
                        return $('input[name="atm_checkboxes[]"]:checked').length > 0;
                    }, "Please select at least one ATM / Passbook / Simcard for this transaction.");

                    $('#TransactionCreateValidateForm').validate({
                        rules: {
                            reason_for_pull_out: { required: true },
                            "atm_checkboxes[]": {
                            required: function(element) {
                                const reason_for_pullout = $("#reason_for_pull_out").val();
                                return reason_for_pullout == '3' || reason_for_pullout == '16'; // Apply validation when reason_for_pullout is 3 or 16
                            },
                            checkAtLeastOne: true // Apply custom validation rule
                        }
                        },
                        messages: {
                            "atm_checkboxes[]": {
                                required: "Please select at least one ATM / Passbook / Simcard for Releasing."
                            }
                        },
                        errorElement: 'span',
                        errorPlacement: function(error, element) {
                            if (element.attr("name") == "atm_checkboxes[]") {
                                // Place the error message inside the #SelectAtleastOneError span
                                $("#SelectAtleastOneError").html(error).addClass('text-danger');
                            } else {
                                error.addClass('invalid-feedback');
                                element.closest('.form-group').append(error);
                            }
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
                // Validation for Creation of Transaction
            // Create of Transaction for Pullout

            // Add ATM Transaction
                $('#addAtmTransactionModal').on('shown.bs.modal', function () {
                    $('#add_atm_bank_names').select2({ dropdownParent: $('#addAtmTransactionModal'), });
                    $('#add_atm_pension_account_type_dropdown').select2({  dropdownParent: $('#addAtmTransactionModal') });
                    $('#add_atm_collection_date').select2({ dropdownParent: $('#addAtmTransactionModal'), });
                });

                $('#FetchingDatatable').on('click', '.addAtmTransaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#add_atm_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#add_atm_pension_number_display').text(data.pension_number ?? '');
                            $('#add_atm_pension_number_display').inputmask("99-9999999-99");
                            $('#add_atm_pension_number_value').val(data.pension_number ?? '');
                            $('#add_atm_pension_account_type').text(data.pension_type ?? '');

                            $('#add_atm_account_type').val(data.account_type ?? '').trigger('change');
                            $('#add_atm_pension_type_value').val(data.pension_type ?? '');

                            $('#add_atm_birth_date').val(formattedBirthDate);
                            $('#add_atm_branch_location').val(data.branch.branch_location);

                            $('#add_atm_atm_id').val(data.id);
                            $('#add_atm_bank_account_no').val(data.bank_account_no ?? '');
                            $('#add_atm_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#add_atm_atm_type').val(data.atm_type ?? '');
                            $('#add_atm_bank_name').val(data.bank_name ?? '');
                            $('#add_atm_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#add_atm_expiration_date').val((expirationDate || ''));

                            $('#addAtmTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                $('#add_atm_account_type').on('change', function() {
                    var selected_pension_types = $(this).val();

                    setTimeout(function() {
                        var PreviousPensionType = $('#add_atm_pension_type_value').val(); // Get the latest area ID value after a brief delay

                        // Make the AJAX GET request for areas
                        $.ajax({
                            url: '/pension/types/fetch',
                            type: 'GET',
                            data: {
                                selected_pension_types: selected_pension_types
                            },
                            success: function(response) {
                                var options = '<option value="" selected disabled>Pension Types</option>';

                                // Build options for each area
                                $.each(response, function(index, item) {
                                    // Check if this area matches the previous one and mark it as selected
                                    var selected = (item.pension_name == PreviousPensionType) ? 'selected' : '';
                                    options += `<option value="${item.pension_name}" ${selected}>${item.pension_name}</option>`;
                                });

                                $('#add_atm_pension_account_type_dropdown').html(options); // Update the dropdown with the new options

                                // Automatically trigger the area change to load branches
                                if (PreviousPensionType) {
                                    $('#add_atm_pension_account_type_dropdown').val(PreviousPensionType).trigger('change'); // Set previous area as selected and trigger change event
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', status, error);
                            }
                        });
                    }, 100); // Small delay to ensure area ID is updated
                });

                $('#same_pension_number').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#pension_no_select').val('yes'); // Hide the entire span (label + input)
                        $('#SamePensionNumberSelected').hide(); // Hide the entire span (label + input)
                    } else {
                        $('#SamePensionNumberSelected').show(); // Show the span back
                        $('#pension_no_select').val('no');
                    }
                });

                function AddAtmTransactionModal() {
                    $('#addAtmTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#TransactionAddAtmValidateForm').validate({
                    rules: {
                        remarks: {
                            required: true
                        },
                        pin_code: {
                            required: function (element) {
                                return $('#atm_type_add_atm').val() === 'ATM'; // Pin code required only if ATM type is 'ATM'
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
                                                AddAtmTransactionModal();
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
                                                                    window.location.href = '{{ route("BranchOfficePage") }}';
                                                                    // dataTable.table.ajax.reload( () =>
                                                                    // {
                                                                    //     Swal.close();
                                                                    //     $(form)[0].reset();
                                                                    //     // dataTable.table.page(currentPage).draw( false );

                                                                    //     window.location.href = '{{ route("BranchOfficePage") }}';
                                                                    // },
                                                                    // false );
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
            // Add ATM Transaction

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

                            $('#transfer_pension_number').val(data.pension_number);
                            $('#transfer_pension_account_type').text(data.account_type);
                            $('#transfer_pension_type').val(data.client_information.pension_type);
                            $('#transfer_birth_date').val(formattedBirthDate);
                            $('#transfer_branch_location').val(data.branch.branch_location);

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

                                            if (res.status === 'success')
                                            {
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
            // Transfer to Other Branch

            // Edit Transaction
                $('#editInformationTransactionModal').on('shown.bs.modal', function () {
                    $('#edit_branch_id').select2({ dropdownParent: $('#editInformationTransactionModal'), });
                    $('#edit_pension_account_type_fetch').select2({ dropdownParent: $('#editInformationTransactionModal'), });
                    $('#edit_bank_name').select2({ dropdownParent: $('#editInformationTransactionModal'), });
                });

                $('#same_pension_number_edit').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#pension_no_select_edit').val('yes'); // Hide the entire span (label + input)
                        $('#SamePensionNumberSelectedEdit').hide(); // Hide the entire span (label + input)
                    } else {
                        $('#SamePensionNumberSelectedEdit').show(); // Show the span back
                        $('#pension_no_select_edit').val('no');
                    }
                });

                $('#edit_account_type').on('change', function() {
                    var selected_pension_types = $(this).val();

                    setTimeout(function() {
                        // Ensure we have the previous value for Pension Account Type
                        var PreviousPensionAccountTypeValue = $('#edit_pension_account_type_value').val();

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

                $("#hide_atm_details").change(function() {
                    if ($(this).is(':checked')){
                        $(".replaceBankAccountNo").hide();
                        $(".replaceBankName").hide();
                        $("#replaced_status").val('yes');
                    } else {
                        $(".replaceBankAccountNo").show();
                        $(".replaceBankName").show();
                        $("#replaced_status").val('no');
                    }
                });

                $('#FetchingDatatable').on('click', '.EditInformationTransaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#edit_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#edit_pension_type_display').text(data.pension_type ?? '');
                            $('#edit_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#edit_pension_number_display').text(data.pension_number ?? '');
                            $('#edit_pension_number_value').val(data.pension_number ?? '');
                            $('#edit_pension_number_display').inputmask("99-9999999-99");
                            $('#edit_atm_id').val(data.id);

                            $('#edit_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#edit_branch_id').val(data.branch_id ?? '').trigger('change');
                            $('#edit_atm_type').val(data.atm_type ?? '').trigger('change');
                            $('#edit_bank_name').val(data.bank_name ?? '').trigger('change');
                            $('#edit_atm_status').val(data.atm_status ?? '').trigger('change');
                            $('#edit_bank_account_no').val(data.bank_account_no ?? '');
                            $('#edit_pin_no').val(data.pin_no ?? '');
                            $('#edit_cash_box_no').val(data.cash_box_no ?? '');

                            $('#edit_expiration_date').val(formatExpirationDate(data.expiration_date));
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

                            $('#edit_account_type').val(data.account_type).trigger('change');
                            $('#edit_pension_account_type_value').val(data.pension_type);
                            $('#edit_pension_account_type_fetch').val(data.pension_type).trigger('change');
                            $('#edit_first_name').val(data.client_information.first_name ?? '');
                            $('#edit_middle_name').val(data.client_information.middle_name ?? '');
                            $('#edit_last_name').val(data.client_information.last_name ?? '');
                            $('#edit_suffix').val(data.client_information.suffix ?? '').trigger('change');
                            $('#edit_birth_date').val(data.client_information.birth_date ?? '');

                            $('#editInformationTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function EditClientTransactionModal() {
                    $('#editInformationTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#TransactionEditClientValidateForm').validate({
                    rules: {
                        pin_code: {
                            required: function (element) {
                                return $('#edit_atm_type').val() === 'ATM'; // Pin code required only if ATM type is 'ATM'
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
                                                EditClientTransactionModal();
                                                Swal.fire({
                                                    title: 'Successfully Edited!',
                                                    text: 'Transaction is successfully Edited!',
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

            // Generating of QR Code
                $(document).on('click', '.generate_qr_code', function (e) {
                    e.preventDefault();

                    const transaction_number = $(this).data('transaction_number');

                    // SweetAlert confirmation
                    Swal.fire({
                        icon: "question",
                        title: 'Do you want to Generate QR Code?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Open the QR code in a new tab
                            console.log(transaction_number);
                            $("#reference_number_slot").val(transaction_number);
                            $('#choose_slot_print_qr_modal').modal('show');
                        }
                    });
                });

                $(document).on("click", "#print_number_receiving", function () {
                    const print_area_number = $(this).val(); // Value of print number
                    const transaction_number = $("#reference_number_slot").val(); // Reference number

                    // Open a new window to generate and display the QR Code
                    const printWindow = window.open(`/GenerateQRCode/${print_area_number}/${transaction_number}`,'','width=500,height=500,top=200,left=600');

                    // Trigger the print action after the PDF is loaded
                    printWindow.onload = function () {
                        printWindow.print();
                    };
                });
            // Generating of QR Code

            $(document).on('click', '.passbookForCollection', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                const atm_id = $(this).data('id'); // Get the ATM ID from the data attribute
                const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

                // SweetAlert confirmation
                Swal.fire({
                    icon: "question",
                    title: 'Do you want to Add to Setup for Passbook for Collection?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('PassbookForCollectionSetup') }}",
                            type: "POST",
                            data: {
                                atm_id: atm_id,
                                _token: csrfToken // Include CSRF token in the request
                            },
                            success: function(response)
                            {
                                if (typeof response === 'string') {
                                    var res = JSON.parse(response);
                                } else {
                                    var res = response; // If it's already an object
                                }

                                if (res.status === 'success')
                                {
                                    closeTransactionModal();
                                    Swal.fire({
                                        title: 'Successfully Updated!',
                                        text:  res.message,
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
                            error: function(xhr, status, error)
                            {
                                var errorMessage ='An error occurred. Please try again later.';
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
                        });
                    }

                });
            });
        });

        $(document).on('click', '.view_pin_code', function(e) {
            e.preventDefault(); // Prevent the default anchor behavior

            const pinCode = $(this).data('pin'); // Get the PIN code from the data attribute
            const bankAccountNo = $(this).data('bank_account_no'); // Get the Card No.

            // SweetAlert confirmation
            Swal.fire({
                icon: "question",
                title: 'Do you want to view the PIN code?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, show another SweetAlert with the PIN code and Card No.
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

        $(document).ready(function () {
            $('#createTransactionModal').on('shown.bs.modal', function () {
                $('#release_reason').select2({ dropdownParent: $('#createTransactionModal'), });
            });

            $('#reason_for_pull_out').on('change', function() {
                var ReasonToPullout = $(this).val();

                if(ReasonToPullout == 1 )
                {
                    $('#BorrowTransaction').show();
                    $('#RemarksTransaction').hide();
                    $('#ReleasingTransaction').hide();
                    $('#ReleasingTableSelect').hide();
                }
                else if(ReasonToPullout == 11 || ReasonToPullout == 13)
                {
                    $('#BorrowTransaction').hide();
                    $('#RemarksTransaction').show();
                    $('#ReleasingTransaction').hide();
                    $('#ReleasingTableSelect').hide();
                }
                else if(ReasonToPullout == 3 || ReasonToPullout == 16)
                {
                    $('#BorrowTransaction').hide();
                    $('#ReleasingTransaction').show();
                    $('#RemarksTransaction').show();
                    $('#ReleasingTableSelect').show();
                }
                else
                {
                    $('#BorrowTransaction').hide();
                    $('#RemarksTransaction').show();
                    $('#ReleasingTransaction').hide();
                    $('#ReleasingTableSelect').hide();
                }
                // else if(selectedUserType === 'Area')
                // {
                //     $('#HeadOfficeDisplay').hide();
                //     $('#DistrictDisplay').hide();
                //     $('#AreaDisplay').show();
                //     $('#BranchDisplay').hide();
                // }
                // else if(selectedUserType === 'Branch')
                // {
                //     $('#HeadOfficeDisplay').hide();
                //     $('#DistrictDisplay').hide();
                //     $('#AreaDisplay').hide();
                //     $('#BranchDisplay').show();
                // }
                // else
                // {
                //     $('#HeadOfficeDisplay').hide();
                //     $('#DistrictDisplay').hide();
                //     $('#AreaDisplay').hide();
                //     $('#BranchDisplay').hide();
                // }


            });
        });

    </script>

@endsection
