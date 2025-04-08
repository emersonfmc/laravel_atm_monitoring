@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM Monitoring @endslot
        @slot('title') Branch Office ATM Lists @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Branch Office ATM Lists</h4>
                            <p class="card-title-desc">
                                A Centralized Record of all ATMs managed by the branch office
                            </p>
                        </div>
                        <div class="col-md-4 text-end">

                        </div>
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
                                    <th>Pension No</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    {{-- <th>Box</th> --}}
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

    <div class="modal fade" id="ReleasingTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="ReleasingTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Releasing Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('TransactionReleaseCreate') }}"
                          method="POST"
                          id="releasingValidateForm"
                          enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="release_atm_id">
                            <input type="hidden" name="action_name" value="Released">
                            <input type="hidden" name="transaction_action_id" value="3">
                            <input type="hidden" name="aprb_no" id="release_aprb_no">

                            <div class="col-6">
                                <div class="form-group">
                                    <div id="release_fullname" class="fw-bold h4"></div>
                                    <span id="release_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="release_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="release_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="release_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card Number</label>
                                        <input type="text" class="form-control" id="release_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="release_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="release_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="release_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="release_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="fw-bold h6">Select Reason For Pull Out</label>
                                    <select name="reason_for_pull_out" id="releasing_reason_select" class="form-select" required>
                                        <option value="" selected disabled>Select Reason</option>
                                        <option value="6">For Safekeeping</option>
                                        <option value="8">Send Copy of Yellow Paper </option>
                                        <option value="7">For Renewal</option>
                                    </select>
                                </div>



                                <div class="text-center" id="releaseImagePreview" style="display: none;">
                                    <hr>
                                    <div class="fw-bold h6 text-primary text-uppercase">Image Preview</div>
                                    <img id="image_release_preview" src="{{ asset('images/no_image.jpg') }}" class="img-fluid" style="height: 150px; width:150px;">
                                    <hr>
                                </div>

                                <span id="ReleasingReason" style="display: none;">
                                    <div class="col-6 form-group mb-3">
                                        <label class="fw-bold h6">Balance</label>
                                        <input type="text" name="balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Remarks</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                    </div>
                                </span>

                                <span id="ReleasingImage" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Yellow Copy and Client Image</label> <span class="text-danger">( Proof of Release to Client )</span>
                                        <input type="file" name="upload_file" id="imageReleaseUpload" class="form-control" accept=".jpg, .jpeg, .png" required>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ReleasingWithBalanceTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="ReleasingWithBalanceTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Releasing w/ Balance Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('TransactionReleaseCreate') }}" method="POST"
                          id="releasingBalanceValidateForm"
                          enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="release_balance_atm_id">
                            <input type="hidden" name="reason_for_pull_out" value="8">
                            <input type="hidden" name="action_name" value="Released With Balance">
                            <input type="hidden" name="transaction_action_id" value="3">
                            <input type="hidden" name="aprb_no" id="release_balance_aprb_no">

                            <div class="col-6">
                                <div class="form-group">
                                    <div id="release_balance_fullname" class="fw-bold h4"></div>
                                    <span id="release_balance_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="release_balance_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="release_balance_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="release_balance_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card Number</label>
                                        <input type="text" class="form-control" id="release_balance_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="release_balance_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="release_balance_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="release_balance_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="release_balance_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3 text-center">
                                    <span class="fw-bold h6 text-primary">Release to Client ( Sending of Yellow Copy )</span>
                                </div>
                                <hr>

                                <div class="text-center">
                                    <div class="fw-bold h6 text-primary text-uppercase">Image Preview</div>
                                    <img id="image_release_balance_preview" src="{{ asset('images/no_image.jpg') }}" class="img-fluid" style="height: 150px; width:150px;">
                                </div>

                                <hr>

                                <div class="col-6 form-group mb-3">
                                    <label class="fw-bold h6">Balance</label>
                                    <input type="text" name="balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Remarks</label>
                                    <input type="text" name="remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Yellow Copy and Client Image</label> <span class="text-danger">( Proof of Release to Client )</span>
                                    <input type="file" name="upload_file" id="imageReleaseBalanceUpload" class="form-control" accept=".jpg, .jpeg, .png" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="BorrowTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="BorrowTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Returning of Borrowed Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('transaction.return.borrow.create') }}" method="POST" id="borrowValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="borrow_atm_id">
                            <input type="hidden" name="reason_for_pull_out" value="4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div id="borrow_fullname" class="fw-bold h4"></div>
                                    <span id="borrow_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="borrow_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="borrow_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="borrow_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card Number</label>
                                        <input type="text" class="form-control" id="borrow_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="borrow_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="borrow_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="borrow_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="borrow_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center mb-2">
                                    <div class="col-auto">
                                        <input type="checkbox" name="replacement_status" id="replacement_status_display" value="no" class="form-check-input ms-1 me-1">
                                        <input type="hidden" name="replacement_status_value" value="no" class="replacement_status_value">
                                    </div>
                                    <div class="col">
                                        <label class="fw-bold h6 mb-0 text-primary">
                                            Has this become a replacement ATM / PB? If yes, please select the checkbox
                                        </label> <br>
                                        <span class="text-danger">( Napalitan ba ang ATM / PB na ito? kung oo paki check lamang ang checkbox )</span>
                                    </div>
                                </div>
                                <hr>

                                <span id="borrowTransaction" style="display: block;">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Balance</label>
                                        <input type="text" name="balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Remarks</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                    </div>
                                </span>

                                <span id="replacementTransaction" style="display: none;">
                                    <div class="row">
                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6">Reason For Replacement</label>
                                            <select name="replacement_type_action" id="borrow_replacement_reason_for_pull_out" class="form-select" required>
                                                <option value="" selected disabled>Select Reason For Replacement</option>
                                                <option value="4">Old Did not Return by Bank w/ Replacement</option>
                                                <option value="12">Old Has Returned w/ Replacement</option>
                                            </select>
                                        </div>
                                        <hr>
                                        <div class="form-group row align-items-center mb-2">
                                            <div class="col-auto">
                                                <input type="checkbox" name="replacement_same_atm" id="borrow_replacement_same_atm" value="replacement_same_atm" class="form-check-input ms-1 me-1">
                                            </div>
                                            <div class="col">
                                                <label class="fw-bold h6 mb-0">
                                                    Is this a replacement with the same card no.? <br>check if yes select checkbox.
                                                </label>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">ATM Type</label>
                                            <select name="new_atm_type" id="borrow_atm_type_fetch" class="form-select" required>
                                                <option value="ATM">ATM</option>
                                                <option value="Passbook">Passbook</option>
                                                <option value="Sim Card">Sim Card</option>
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Collection Date</label>
                                            <select name="new_collection_date" id="borrow_collection_date_fetch" class="form-select" required>
                                                <option value="">Select Collection Date</option>
                                                @foreach ($DataCollectionDate as $collection_date)
                                                    <option value="{{ $collection_date->collection_date }}">{{ $collection_date->collection_date }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3 atm_number_field">
                                            <label class="fw-bold h6">Card Number</label>
                                            <input type="text" class="atm_card_input_mask form-control" name="new_atm_number" placeholder="Card Number" required>
                                        </div>

                                        <div class="col-6 form-group mb-3 atm_bank_list">
                                            <label class="fw-bold h6">Bank Name</label>
                                            <select name="new_bank_name" id="borrow_bank_name_fetch" class="form-select select2" required>
                                                <option value="">Select Banks</option>
                                                @foreach ($DataBankLists as $banks)
                                                    <option value="{{ $banks->bank_name }}">{{ $banks->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">PIN Code</label>
                                            <input type="text" name="new_pin_code" class="form-control" placeholder="PIN Code">
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Status</label>
                                            <select name="new_atm_status" id="new_atm_status_borrow" class="form-select" required>
                                                <option value="new" selected>New</option>
                                                <option value="old">Old</option>
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Expiration Date</label>
                                            <input type="month" name="new_expiration_date" class="form-control">
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Balance</label>
                                            <input type="text" name="new_balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6">Remarks</label>
                                            <input type="text" name="new_remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ReplacementTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="ReplacementTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl"role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Replacement of ATM / Passbook / Simcard Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('TransactionReplacementCreate') }}" method="POST" id="replacementValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="replacement_atm_id">
                            <div class="col-6">
                                <div class="form-group">
                                    <div id="replacement_fullname" class="fw-bold h4"></div>
                                    <span id="replacement_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> /
                                    <span id="replacement_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="replacement_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="replacement_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card Number</label>
                                        <input type="text" class="form-control" id="replacement_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="replacement_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="replacement_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="replacement_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="replacement_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Reason For Replacement</label>
                                    <select name="reason_for_pull_out" id="replacement_reason_for_pull_out" class="form-select" required>
                                        <option value="" selected disabled>Select Reason For Replacement</option>
                                        <option value="4">Old Did not Return by Bank w/ Replacement</option>
                                        <option value="12">Old Has Returned w/ Replacement</option>
                                        <option value="21">Did Not Replaced by Bank</option>
                                    </select>
                                </div>

                                <span id="withReplacement" style="display: none;">
                                    <div class="row">
                                        <hr>
                                        <div class="form-group row align-items-center mb-2">
                                            <div class="col-auto">
                                                <input type="checkbox" name="replacement_same_atm" id="replacement_same_atm" value="replacement_same_atm" class="form-check-input ms-1 me-1">
                                            </div>
                                            <div class="col">
                                                <label class="fw-bold h6 mb-0">
                                                    Is this a replacement ATM / PB with the same card no.? <br>check if yes select checkbox.
                                                </label>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">ATM Type</label>
                                            <select name="new_atm_type" id="replacement_atm_type_fetch" class="form-select" required>
                                                <option value="ATM">ATM</option>
                                                <option value="Passbook">Passbook</option>
                                                <option value="Sim Card">Sim Card</option>
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Collection Date</label>
                                            <select name="new_collection_date" id="replacement_collection_date_fetch" class="form-select" required>
                                                <option value="">Select Collection Date</option>
                                                @foreach ($DataCollectionDate as $collection_date)
                                                    <option value="{{ $collection_date->collection_date }}">{{ $collection_date->collection_date }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3 atm_number_field">
                                            <label class="fw-bold h6">Card No.</label>
                                            <input type="text" class="atm_card_input_mask form-control" name="new_atm_number" placeholder="Card Number" required>
                                        </div>

                                        <div class="col-6 form-group mb-3 atm_bank_list">
                                            <label class="fw-bold h6">Bank Name</label>
                                            <select name="new_bank_name" id="replacement_bank_name_fetch" class="form-select select2" required>
                                                <option value="">Select Banks</option>
                                                @foreach ($DataBankLists as $banks)
                                                    <option value="{{ $banks->bank_name }}">{{ $banks->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">PIN Code</label>
                                            <input type="text" name="new_pin_code" class="form-control" placeholder="PIN Code">
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Status</label>
                                            <select name="new_atm_status" id="new_atm_status_replacement" class="form-select" required>
                                                <option value="new" selected>New</option>
                                                <option value="old">Old</option>
                                            </select>
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Expiration Date</label>
                                            <input type="month" name="new_expiration_date" class="form-control">
                                        </div>

                                        <div class="col-6 form-group mb-3">
                                            <label class="fw-bold h6">Balance</label>
                                            <input type="text" name="new_balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="fw-bold h6">Remarks</label>
                                            <input type="text" name="new_remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                        </div>
                                    </div>
                                </span>

                                <span id="NotReplaced" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Balance</label>
                                        <input type="text" name="balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-bold h6">Remarks</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                    </div>
                                </span>
                            </div>


                            {{-- borrow_atm_type_fetch --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CancelledLoanTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="CancelledLoanTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Cancelled Loan Transaction</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('TransactionReleaseCreate') }}" method="POST" id="cancelledLoanValidateForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="reason_for_pull_out" value="15">
                            <input type="hidden" name="action_name" value="Cancelled Loan">
                            <input type="hidden" name="transaction_action_id" value="13">

                            <div class="col-md-6">
                                <input type="hidden" name="atm_id" id="cancelled_loan_atm_id">
                                <div class="form-group">
                                    <div id="cancelled_loan_fullname" class="fw-bold h4"></div>
                                    <span id="cancelled_loan_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="cancelled_loan_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="cancelled_loan_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="cancelled_loan_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card Number</label>
                                        <input type="text" class="form-control" id="cancelled_loan_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="cancelled_loan_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="cancelled_loan_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="cancelled_loan_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="cancelled_loan_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3 text-center">
                                    <span class="fw-bold h6 text-primary">Proof of Release to Client</span>
                                </div>
                                <hr>

                                <div class="text-center">
                                    <div class="fw-bold h6 text-primary text-uppercase">Image Preview</div>
                                    <img id="image_release_cancelled_loan_preview" src="{{ asset('images/no_image.jpg') }}" class="img-fluid" style="height: 150px; width:150px;">
                                </div>

                                <hr>

                                <div class="col-6 form-group mb-3">
                                    <label class="fw-bold h6">Balance</label>
                                    <input type="text" name="balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Remarks</label>
                                    <input type="text" name="remarks" class="form-control" placeholder="Remarks" minlength="0" maxlength="50">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Upload Cancelled Form </label> <span class="text-danger">( Proof of Release to Client )</span>
                                    <input type="file" name="upload_file" id="ProofReleaseCancelledLoan" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ChooseSlotQRCodeModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop='static' aria-hidden="true" data-keyboard="false">
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

    <div class="modal fade" id="addAtmTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Add ATM Transaction</h5>
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
                                    <span id="add_atm_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> / <span id="add_atm_pension_type" class="fw-bold h5"></span>
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
                                        <label class="fw-bold h6">Card Number</label>
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
                                            <label class="col-form-label col-4 fw-bold">Balance</label>
                                            <div class="col-8">
                                                <input type="text" name="atm_balance" class="balance_input_mask form-control" placeholder="Balance" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="font-size col-form-label col-4 fw-bold">Banks</label>
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <select name="bank_name" id="add_atm_bank_names" class="form-select select2" required>
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
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Collection Date</label>
                                            <div class="col-8">
                                                <select name="collection_date" id="add_atm_collection_date" class="form-select select2">
                                                    @foreach ($DataCollectionDate as $collection)
                                                        <option value="{{ $collection->collection_date }}">{{ $collection->collection_date }}</option>
                                                    @endforeach
                                                </select>
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

    <script>
        $(document).ready(function () {
            // Displaying of Transaction
                var FetchingDatatableBody = $('#FetchingDatatable tbody');

                const dataTable = new ServerSideDataTable('#FetchingDatatable');
                var url = '{!! route('BranchOfficeData') !!}';
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
                            return '<span class="fw-bold h6 text-primary">' + row.pending_to + '</span>';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    // Transaction No
                    {
                        data: 'transaction_number',
                        render: function(data, type, row, meta) {
                            return '<span class="fw-bold h6">' + data + '</span>';
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'full_name',
                        render: function(data, type, row, meta) {
                            return '<span>' + row.full_name + '</span>'; // Check if company exists
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
                            return '<span>' + row.pension_details + '</span>'; // Check if company exists
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'created_at',
                        render: function(data, type, row, meta) {
                            const createdAt = row.created_at ? new Date(row.created_at) : null;
                            const formattedDate = createdAt ? createdAt.toLocaleDateString('en-US',
                                {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                })
                                : '';

                            return `<span class="text-muted">${formattedDate}</span>`;

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
                        render: function(data, type, row, meta) {
                            return `<span>${row.bank_details}</span>`;

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
                        render: function(data, type, row, meta) {
                            return data ? `<span>${data}</span>` : '';
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
                    {
                        data: 'qr_code',
                        render: function(data, type, row, meta) {
                            return '<span>' + data + '</span>';
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
                        var targetUrl = '{!! route('BranchOfficeData') !!}';

                        // Add branch_id as a query parameter if user doesn't have a fixed branch and has selected a branch
                        if (!userHasBranchId && selectedBranch) {
                            targetUrl += '?branch_id=' + selectedBranch;
                        }

                        // Update the DataTable with the filtered data
                        dataTable.table.ajax.url(targetUrl).load();
                    });
                // Filtering of Transaction
            // Displaying of Transaction

            // Releasing Transaction
                $('#FetchingDatatable').on('click', '.release_transaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');
                    var release_aprb_no = $(this).data('aprb_no');

                    $('#release_aprb_no').val(release_aprb_no ?? '');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#release_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#release_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#release_pension_number_display').text(data.pension_number ?? '');
                            $('#release_pension_number_display').inputmask("99-9999999-99");

                            $('#release_pension_number').val(data.pension_number ?? '');
                            $('#release_pension_type').text(data.pension_type ?? '');
                            $('#release_birth_date').val(formattedBirthDate);
                            $('#release_branch_location').val(data.branch.branch_location ? data.branch.branch_location : '');

                            $('#release_atm_id').val(data.id);
                            $('#release_bank_account_no').val(data.bank_account_no ?? '');
                            $('#release_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#release_atm_type').val(data.atm_type ?? '');
                            $('#release_bank_name').val(data.bank_name ?? '');
                            $('#release_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#release_expiration_date').val((expirationDate || ''));

                            $('#ReleasingTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                    function closeReleasingTransactionModal() {
                        $('#ReleasingTransactionModal').modal('hide');
                        $('#FetchingDatatable tbody').empty();
                    }

                    $("#releasing_reason_select").on("change", function() {
                        var SelectedReleasingReason = $(this).val(); // Get the selected value

                        if(SelectedReleasingReason == 6 || SelectedReleasingReason == 7) {
                            $('#ReleasingReason').show();
                            $('#ReleasingImage').hide();
                            $('#releaseImagePreview').hide();
                        } else {
                            $('#ReleasingReason').show();
                            $('#ReleasingImage').show();
                            $('#releaseImagePreview').show();
                        }


                    });

                    // Validate and Compress Image
                    document.getElementById('imageReleaseUpload').addEventListener('change', async function (event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('image_release_preview');

                        // Reset preview if no file is selected
                        if (!file) {
                            preview.src = "{{ asset('images/no_image.jpg') }}";
                            return;
                        }

                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid file type',
                                text: 'Only JPG, JPEG, and PNG files are allowed.'
                            });
                            event.target.value = ''; // Clear the input
                            preview.src = "{{ asset('images/no_image.jpg') }}"; // Reset preview
                            return;
                        }

                        // Show compression loading indicator
                        Swal.fire({
                            title: 'Compressing image...',
                            text: 'Please wait while the image is being optimized.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            const options = {
                                maxSizeMB: 1, // Higher limit to allow initial compression
                                maxWidthOrHeight: 1920, // Maintain aspect ratio
                                useWebWorker: true,
                                maxIteration: 10,
                                initialQuality: 0.8
                            };

                            let compressedFile = await imageCompression(file, options);

                            // Further compress if the file size is still above 200KB
                            while (compressedFile.size > 200 * 1024) {
                                compressedFile = await imageCompression(compressedFile, {
                                    maxSizeMB: compressedFile.size / 1024 / 1024 / 2, // Reduce size further
                                    initialQuality: 0.7 // Reduce quality slightly
                                });
                            }

                            console.log('Final compressed file size:', (compressedFile.size / 1024).toFixed(2), 'KB');

                            // Close the loading indicator
                            Swal.close();

                            // Display preview
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                preview.src = e.target.result;
                            };
                            reader.readAsDataURL(compressedFile);

                            // Create a new File object to replace the original input
                            const newFile = new File([compressedFile], file.name, { type: file.type });

                            // Create a DataTransfer object to update the input field
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            document.getElementById('imageReleaseUpload').files = dataTransfer.files;

                        } catch (error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Compression error',
                                text: 'An error occurred during image compression. Please try again.'
                            });
                        }
                    });

                    $('#releasingValidateForm').validate({
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
                                                    closeReleasingTransactionModal();
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
                                                                            // window.location.href = '{{ route("ReleasedPage") }}';
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
            // Releasing Transaction

            // Releasing w/ Balance Transaction
                $('#FetchingDatatable').on('click', '.release_balance_transaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');
                    var release_aprb_no = $(this).data('aprb_no');

                    $('#release_balance_aprb_no').val(release_aprb_no ?? '');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#release_balance_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#release_balance_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#release_balance_pension_number_display').text(data.pension_number ?? '');
                            $('#release_balance_pension_number_display').inputmask("99-9999999-99");

                            $('#release_balance_pension_number').val(data.pension_number ?? '');
                            $('#release_balance_pension_type').text(data.pension_type ?? '');
                            $('#release_balance_birth_date').val(formattedBirthDate);
                            $('#release_balance_branch_location').val(data.branch.branch_location ?? '');

                            $('#release_balance_atm_id').val(data.id);
                            $('#release_balance_bank_account_no').val(data.bank_account_no ?? '');
                            $('#release_balance_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#release_balance_atm_type').val(data.atm_type ?? '');
                            $('#release_balance_bank_name').val(data.bank_name ?? '');
                            $('#release_balance_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#release_balance_expiration_date').val((expirationDate || ''));

                            $('#ReleasingWithBalanceTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                    function closeReleasingWithBalanceTransactionModal() {
                        $('#ReleasingWithBalanceTransactionModal').modal('hide');
                        $('#FetchingDatatable tbody').empty();
                    }

                    document.getElementById('imageReleaseBalanceUpload').addEventListener('change', async function (event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('image_release_balance_preview');

                        // Reset preview if no file is selected
                        if (!file) {
                            preview.src = "{{ asset('images/no_image.jpg') }}";
                            return;
                        }

                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid file type',
                                text: 'Only JPG, JPEG, and PNG files are allowed.'
                            });
                            event.target.value = ''; // Clear the input
                            preview.src = "{{ asset('images/no_image.jpg') }}"; // Reset preview
                            return;
                        }

                        // Show compression loading indicator
                        Swal.fire({
                            title: 'Compressing image...',
                            text: 'Please wait while the image is being optimized.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            const options = {
                                maxSizeMB: 1, // Higher limit to allow initial compression
                                maxWidthOrHeight: 1920, // Maintain aspect ratio
                                useWebWorker: true,
                                maxIteration: 10,
                                initialQuality: 0.8
                            };

                            let compressedFile = await imageCompression(file, options);

                            // Further compress if the file size is still above 200KB
                            while (compressedFile.size > 200 * 1024) {
                                compressedFile = await imageCompression(compressedFile, {
                                    maxSizeMB: compressedFile.size / 1024 / 1024 / 2, // Reduce size further
                                    initialQuality: 0.7 // Reduce quality slightly
                                });
                            }

                            console.log('Final compressed file size:', (compressedFile.size / 1024).toFixed(2), 'KB');

                            // Close the loading indicator
                            Swal.close();

                            // Display preview
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                preview.src = e.target.result;
                            };
                            reader.readAsDataURL(compressedFile);

                            // Create a new File object to replace the original input
                            const newFile = new File([compressedFile], file.name, { type: file.type });

                            // Create a DataTransfer object to update the input field
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            document.getElementById('imageReleaseBalanceUpload').files = dataTransfer.files;

                        } catch (error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Compression error',
                                text: 'An error occurred during image compression. Please try again.'
                            });
                        }
                    });

                    $('#releasingBalanceValidateForm').validate({
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
                                                    closeReleasingWithBalanceTransactionModal();
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
            // Releasing w/ Balance Transaction

            // Borrow Transaction
                $('#BorrowTransactionModal').on('shown.bs.modal', function () {
                    $('#borrow_bank_name_fetch').select2({ dropdownParent: $('#BorrowTransactionModal'), });
                });

                $('#FetchingDatatable').on('click', '.borrow_transaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#borrow_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#borrow_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#borrow_pension_number_display').text(data.pension_number ?? '');
                            $('#borrow_pension_number_display').inputmask("99-9999999-99");

                            $('#borrow_pension_number').val(data.pension_number ?? '');
                            $('#borrow_pension_account_type').text(data.account_type ?? '');
                            $('#borrow_pension_type').text(data.pension_type ?? '');
                            $('#borrow_birth_date').val(formattedBirthDate ?? '');
                            $('#borrow_branch_location').val(data.branch.branch_location ?? '');

                            $('#borrow_atm_id').val(data.id);
                            $('#borrow_bank_account_no').val(data.bank_account_no ?? '');
                            $('#borrow_collection_date').val(data.collection_date ?? '');
                            $('#borrow_atm_type').val(data.atm_type ?? '');

                            $('#borrow_bank_name').val(data.bank_name ?? '');
                            $('#borrow_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#borrow_expiration_date').val((expirationDate || ''));

                            $('#borrow_atm_type_fetch').val(data.atm_type ?? '').trigger('change');
                            $('#borrow_collection_date_fetch').val(data.collection_date ?? '').trigger('change');
                            $('#borrow_bank_name_fetch').val(data.bank_name ?? '').trigger('change');

                            $('#BorrowTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                    function closeBorrowTransactionModal() {
                        $('#BorrowTransactionModal').modal('hide');
                        $('#FetchingDatatable tbody').empty();
                    }

                    $("#borrow_replacement_reason_for_pull_out").on("change", function() {
                        const reason_for_replacement = $("#borrow_replacement_reason_for_pull_out").val();

                        if(reason_for_replacement == 4 || reason_for_replacement == 12) {
                            $("#withReplacement").show();
                            $("#NotReplaced").hide();
                        } else {
                            $("#withReplacement").hide();
                            $("#NotReplaced").show();
                        }
                    });

                    $('#replacement_status_display').change(function() {
                        if ($(this).is(':checked')) {
                            $('#borrowTransaction').hide();
                            $('#replacementTransaction').show();
                            $('.replacement_status_value').val('for_replacement');
                            // $('#checkBoxReplacementTransaction').show();

                        } else {
                            $('#borrowTransaction').show();
                            $('#replacementTransaction').hide();

                            $('.replacement_status_value').val('no');
                            // $('#checkBoxReplacementTransaction').hide();

                            $('#reason_replacement').val('');    // Reset dropdown selection
                        }
                    });

                    $("#borrow_replacement_same_atm").on("change", function() {
                        if ($(this).is(":checked")) {
                            $(".atm_number_field").hide(); // Hide the new ATM number field
                            $(".atm_bank_list").hide();
                        } else {
                            $(".atm_number_field").show(); // Show the new ATM number field
                            $(".atm_bank_list").show();
                        }
                    });

                    $('#borrowValidateForm').validate({
                        rules: {
                            remarks: { required: true },
                            new_pin_code: { // Initial validation for PIN Code
                                required: function() {
                                    return $('#borrow_atm_type_fetch').val() === 'ATM'; // Make required only if ATM is selected
                                }
                            }
                        },
                        messages: {
                            new_pin_code: {
                                required: "PIN Code is required for ATM type."
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
                                                    closeBorrowTransactionModal();
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
            // Borrow Transaction

            // Replacement Transaction
                $('#FetchingDatatable').on('click', '.replacement_atm_transaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#replacement_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#replacement_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#replacement_pension_number_display').text(data.pension_number ?? '');
                            $('#replacement_pension_number_display').inputmask("99-9999999-99");

                            $('#replacement_pension_number').val(data.pension_number ?? '');
                            $('#replacement_pension_account_type').text(data.account_type ?? '');
                            $('#replacement_pension_type').text(data.pension_type ?? '');
                            $('#replacement_birth_date').val(formattedBirthDate);
                            $('#replacement_branch_location').val(data.branch.branch_location ?? '');

                            $('#replacement_atm_id').val(data.id);
                            $('#replacement_bank_account_no').val(data.bank_account_no ?? '');
                            $('#replacement_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#replacement_atm_type').val(data.atm_type ?? '');
                            $('#replacement_bank_name').val(data.bank_name ?? '');
                            $('#replacement_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#replacement_expiration_date').val((expirationDate || ''));

                            $('#replacement_atm_type_fetch').val(data.atm_type ?? '').trigger('change');
                            $('#replacement_collection_date_fetch').val(data.collection_date ?? '').trigger('change');
                            $('#replacement_bank_name_fetch').val(data.bank_name ?? '').trigger('change');

                            $('#ReplacementTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function closeReplacementTransactionModal() {
                    $('#ReplacementTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                    $('#ReplacementTransactionModal').on('shown.bs.modal', function () {
                        $('#replacement_bank_name_fetch').select2({ dropdownParent: $('#ReplacementTransactionModal'), });
                    });

                    $("#replacement_reason_for_pull_out").on("change", function() {
                        const reason_for_replacement = $("#replacement_reason_for_pull_out").val();

                        if(reason_for_replacement == 4 || reason_for_replacement == 12) {
                            $("#withReplacement").show();
                            $("#NotReplaced").hide();
                        } else {
                            $("#withReplacement").hide();
                            $("#NotReplaced").show();
                        }
                    });

                    $("#replacement_same_atm").on("change", function() {
                        if ($(this).is(":checked")) {
                            $(".atm_number_field").hide(); // Hide the new ATM number field
                            $(".atm_bank_list").hide();
                        } else {
                            $(".atm_number_field").show(); // Show the new ATM number field
                            $(".atm_bank_list").show();
                        }
                    });

                    $('#replacementValidateForm').validate({
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
                                                    closeReplacementTransactionModal();
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
            // Replacement Transaction

            // Cancelled Loan Transaction
                $('#FetchingDatatable').on('click', '.cancelled_loan_transaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#cancelled_loan_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#cancelled_loan_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#cancelled_loan_pension_number_display').text(data.pension_number ?? '');
                            $('#cancelled_loan_pension_number_display').inputmask("99-9999999-99");

                            $('#cancelled_loan_pension_number').val(data.pension_number);
                            $('#cancelled_loan_account_type').text(data.account_type ?? '');
                            $('#cancelled_loan_pension_type').text(data.pension_type ?? '');
                            $('#cancelled_loan_birth_date').val(formattedBirthDate);
                            $('#cancelled_loan_branch_location').val(data.branch.branch_location);

                            $('#cancelled_loan_atm_id').val(data.id);
                            $('#cancelled_loan_bank_account_no').val(data.bank_account_no ?? '');
                            $('#cancelled_loan_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#cancelled_loan_atm_type').val(data.atm_type ?? '');
                            $('#cancelled_loan_bank_name').val(data.bank_name ?? '');
                            $('#cancelled_loan_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#cancelled_loan_expiration_date').val((expirationDate || ''));

                            $('#CancelledLoanTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                    function closeCancelledLoanTransactionModal() {
                        $('#CancelledLoanTransactionModal').modal('hide');
                        $('#FetchingDatatable tbody').empty();
                    }

                    // Validate and Compress Image
                    document.getElementById('ProofReleaseCancelledLoan').addEventListener('change', async function (event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('image_release_cancelled_loan_preview');

                        // Reset preview if no file is selected
                        if (!file) {
                            preview.src = "{{ asset('images/no_image.jpg') }}";
                            return;
                        }

                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid file type',
                                text: 'Only JPG, JPEG, and PNG files are allowed.'
                            });
                            event.target.value = ''; // Clear the input
                            preview.src = "{{ asset('images/no_image.jpg') }}"; // Reset preview
                            return;
                        }

                        // Show compression loading indicator
                        Swal.fire({
                            title: 'Compressing image...',
                            text: 'Please wait while the image is being optimized.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            const options = {
                                maxSizeMB: 1, // Higher limit to allow initial compression
                                maxWidthOrHeight: 1920, // Maintain aspect ratio
                                useWebWorker: true,
                                maxIteration: 10,
                                initialQuality: 0.8
                            };

                            let compressedFile = await imageCompression(file, options);

                            // Further compress if the file size is still above 200KB
                            while (compressedFile.size > 200 * 1024) {
                                compressedFile = await imageCompression(compressedFile, {
                                    maxSizeMB: compressedFile.size / 1024 / 1024 / 2, // Reduce size further
                                    initialQuality: 0.7 // Reduce quality slightly
                                });
                            }

                            console.log('Final compressed file size:', (compressedFile.size / 1024).toFixed(2), 'KB');

                            // Close the loading indicator
                            Swal.close();

                            // Display preview
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                preview.src = e.target.result;
                            };
                            reader.readAsDataURL(compressedFile);

                            // Create a new File object to replace the original input
                            const newFile = new File([compressedFile], file.name, { type: file.type });

                            // Create a DataTransfer object to update the input field
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            document.getElementById('ProofReleaseCancelledLoan').files = dataTransfer.files;

                        } catch (error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Compression error',
                                text: 'An error occurred during image compression. Please try again.'
                            });
                        }
                    });

                    $('#cancelledLoanValidateForm').validate({
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
                                                    closeCancelledLoanTransactionModal();
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
            // Cancelled Loan Transaction

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
                            $('#ChooseSlotQRCodeModal').modal('show');
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

            // Add ATM Transaction
                $('#addAtmTransactionModal').on('shown.bs.modal', function () {
                    $('#add_atm_pension_account_type_dropdown').select2({  dropdownParent: $('#addAtmTransactionModal') });
                    $('#add_atm_bank_names').select2({ dropdownParent: $('#addAtmTransactionModal'), });
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
                            $('#add_atm_pension_number').val(data.pension_number ?? '');
                            $('#add_atm_pension_account_type').text(data.account_type ?? '');
                            $('#add_atm_pension_type').text(data.pension_type ?? '');
                            $('#add_atm_account_type').val(data.account_type ?? '').trigger('change');
                            $('#add_atm_birth_date').val(formattedBirthDate);
                            $('#add_atm_pension_type_value').val(data.pension_type ?? '');
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

                                            if (res.status === 'success')
                                            {
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
                                                                    // window.location.href = '{{ route("BranchOfficePage") }}';
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
            // Add ATM Transaction
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
                            location: 'Branch',
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
