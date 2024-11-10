@extends('layouts.master')

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
                                                <option value="">Select Branches</option>
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
                                    <th>Bank Account No</th>
                                    {{-- <th>ATM / Passbook / Simcard No & Bank</th> --}}
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
                    <form action="{{  route('TransactionCreate') }}" method="POST" id="TransactionCreateValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="create_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="create_fullname" class="fw-bold h4"></div>
                                    <span id="create_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="create_pension_account_type" class="fw-bold h5"></span>
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
                                        <label class="fw-bold h6">Bank Account Number</label>
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
                                        @foreach ($AtmTransactionAction as $item)
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
                                            <option value="For Requiremtns">For Requiremtns</option>
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
                                            <th>Bank Account No</th>
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
                    <h5 class="modal-title fw-bold text-uppercase">Add ATM Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="add_atm_atm_id">
                            <div class="col-12">
                                <div class="form-group">
                                    <div id="add_atm_fullname" class="fw-bold h4"></div>
                                    <span id="add_atm_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="add_atm_pension_account_type" class="fw-bold h5"></span>
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
                                        <label class="fw-bold h6">Bank Account Number</label>
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

                                    <div class="form-group col-3">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <select name="collection_date" id="add_atm_collection_date" class="form-select">
                                            @foreach ($DataCollectionDate as $collection)
                                                <option value="{{ $collection->collection_date }}">{{ $collection->collection_date }}</option>
                                            @endforeach
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
                                        <label class="col-form-label col-4 fw-bold">ATM / Passbook / Sim No.</label>
                                        <div class="col-8">
                                          <input type="text" name="atm_number" class="atm_card_input_mask form-control" placeholder="ATM / Passbook / Sim No." required>
                                        </div>
                                      </div>
                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Balance</label>
                                        <div class="col-8">
                                            <input type="text" name="atm_balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                        </div>
                                      </div>
                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="font-size col-form-label col-4 fw-bold">Banks</label>
                                        <div class="col-8">
                                          <div class="form-group">
                                              <select name="bank_name" id="bank_name" class="form-select" required>
                                                @foreach ($DataBankLists as $bank)
                                                    <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                                @endforeach
                                              </select>
                                          </div>
                                      </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                        <div class="col-8">
                                          <input type="number" name="pin_code" class="form-control" placeholder="PIN Code">
                                        </div>
                                      </div>
                                      <div class="form-group mb-2 row align-items-center">
                                        <label class="col-form-label col-4 fw-bold">Expiration Date</label>
                                        <div class="col-8">
                                          <input type="month" name="expiration_date" class="form-control">
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
                    <form action="#" method="POST" id="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="transfer_atm_id">
                            <div class="col-12">
                                <div class="form-group">
                                    <div id="transfer_fullname" class="fw-bold h4"></div>
                                    <span id="transfer_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="transfer_pension_account_type" class="fw-bold h5"></span>
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
                                        <label class="fw-bold h6">Bank Account Number</label>
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

    <div class="modal fade" id="EditInformationTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Edit Client / ATM Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <input type="hidden" name="atm_id" id="edit_atm_id">
                        <div class="form-group">
                            <div id="edit_fullname" class="fw-bold h4"></div>
                            <span id="edit_pension_number_display" class="ms-3 pension_number_mask text-primary fw-bold h5"></span> / <span id="edit_pension_account_type" class="fw-bold h5"></span>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Transaction Number</label>
                                <input type="text" name="transaction_number" id="edit_transaction_number" class="form-control" readonly>
                            </div>
                            <div class="col-3 form-group mb-3">
                                <label class="fw-bold h6">Branch</label>
                                <select name="branch_id" id="edit_branch_id" class="form-select select2">
                                    @foreach ($Branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                                    @endforeach
                                </select>
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

                                        <div class="form-group mb-2 row">
                                            <div class="col-auto">
                                                <input type="checkbox" name="replacement_atm" id="hide_atm_details" value="replacement_atm" class="form-check-input ms-1 me-1">
                                            </div>
                                            <div class="col">
                                                <label class="fw-bold h6">
                                                    Is this a Replacement ATM with the same Bank Number? Check if Yes Select Checkbox.
                                                </label>
                                            </div>
                                        </div>
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

                                      <div class="form-group mb-3 row align-items-center">
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

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('HeadOfficeData') !!}';
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
                        return row.action + ' ' + row.passbook_for_collection; // Concatenate action and passbook_for_collection
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

                        $('#create_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#create_pension_number_display').inputmask("99-9999999-99");

                        $('#create_pension_number').val(data.client_information.pension_number);
                        $('#create_pension_account_type').text(data.client_information.pension_account_type);
                        $('#create_pension_type').val(data.client_information.pension_type);
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

                            // Check if the client information and ATM bank data exist
                            if (data.client_information && data.client_information.atm_client_banks) {
                                $.each(data.client_information.atm_client_banks, function(index, atms) {
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
                        // End Used Only for Releasing and Releasing with Balance Transaction

                        $('#createTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            // Validation for Creation of Transaction
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

                        $('#add_atm_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#add_atm_pension_number_display').inputmask("99-9999999-99");

                        $('#add_atm_pension_number').val(data.client_information.pension_number);
                        $('#add_atm_pension_account_type').text(data.client_information.pension_account_type);
                        $('#add_atm_pension_type').val(data.client_information.pension_type);
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

                        $('#transfer_pension_number_display').text(data.client_information.pension_number ?? '');
                        $('#transfer_pension_number_display').inputmask("99-9999999-99");

                        $('#transfer_pension_number').val(data.client_information.pension_number);
                        $('#transfer_pension_account_type').text(data.client_information.pension_account_type);
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


            // Edit Transaction
                $('#EditInformationTransactionModal').on('shown.bs.modal', function () {
                    $('#edit_branch_id').select2({ dropdownParent: $('#EditInformationTransactionModal'), });
                    $('#edit_pension_account_type_fetch').select2({ dropdownParent: $('#EditInformationTransactionModal'), });
                    $('#edit_bank_name').select2({ dropdownParent: $('#EditInformationTransactionModal'), });
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

                $("#hide_atm_details").change(function() {
                    if ($(this).is(':checked')){
                        $(".replaceBankAccountNo").hide();
                        $(".replaceBankName").hide();
                    } else {
                        $(".replaceBankAccountNo").show();
                        $(".replaceBankName").show();
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

                            $('#edit_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#edit_pension_number_display').text(data.client_information.pension_number ?? '');
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

                            $('#edit_pension_type').val(data.client_information.pension_type).trigger('change');
                            $('#edit_pension_account_type_value').val(data.client_information.pension_account_type);
                            $('#edit_pension_account_type_fetch').val(data.client_information.pension_account_type).trigger('change');
                            $('#edit_first_name').val(data.client_information.first_name ?? '');
                            $('#edit_middle_name').val(data.client_information.middle_name ?? '');
                            $('#edit_last_name').val(data.client_information.last_name ?? '');
                            $('#edit_suffix').val(data.client_information.suffix ?? '').trigger('change');
                            $('#edit_birth_date').val(data.client_information.birth_date ?? '');

                            $('#EditInformationTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });
            // Edit Transaction

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


            function closeTransactionModal() {
                $('#createTransactionModal').modal('hide');
                // $('#FetchingDatatable tbody').empty();
            }
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
