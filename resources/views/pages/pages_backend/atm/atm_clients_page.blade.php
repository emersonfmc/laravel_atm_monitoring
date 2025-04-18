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

                    <form action="{{ route('clients.data') }}" method="GET" id="validatePensionNumber" class="d-flex">
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
                                    <th>Branch</th>
                                    <th>Pension No. / Type</th>
                                    <th>Transaction Number</th>
                                    <th>Card No.</th>
                                    <th>PIN Code</th>
                                    <th>Type</th>
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
                                    <label class="fw-bold h6">Account Type</label>
                                    <select name="account_type" id="pension_type" class="form-select" required>
                                        <option value="" selected disabled>Account Type</option>
                                        <option value="SSS">SSS</option>
                                        <option value="GSIS">GSIS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Pension Types</label>
                                    <select name="pension_type" id="pension_account_type" class="form-select select2" required disabled>
                                        <option value="" selected disabled>Pension Type</option>
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
                                    <label class="fw-bold h6">Lastname</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                            placeholder="Enter Lastname" minlength="0" maxlength="50" required>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Firstname</label>
                                    <input type="text" name="first_name" id="first_name"
                                            class="form-control" placeholder="Enter Firstname" minlength="0" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Middle Initial</label>
                                    <input type="text" name="middle_name" id="middle_name"
                                            class="form-control" placeholder="Enter Middle Initial" minlength="0" maxlength="3">
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
                                            <label class="col-form-label col-sm-4 fw-bold">Card No.</label>
                                            <div class="col-8">
                                                <input type="text" name="atm_number[]" class="atm_card_input_mask form-control" placeholder="Card No." required>
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
                                        <div class="form-group mb-2 row align-items-center">
                                            <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                            <div class="col-8">
                                                <input type="number" name="pin_code[]" class="form-control" min="0" placeholder="PIN Code">
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
                                                <input type="text" name="atm_balance[]" class="balanceCurrency form-control" value="0" placeholder="Balance" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2 row align-items-center">
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

    <div class="modal fade" id="addAtmInformationModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
        <div class="modal-dialog" style="max-width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="exampleModalLabel">Add ATM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('add.more.atm') }}" method="POST" id="addAtmValidationForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="atm_id" id="add_atm_item_id">

                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="form-group mb-3">
                                    <div class="fw-bold h4" id="add_more_fullname"></div>
                                    <span class="fw-bold h5 text-primary ms-4" id="add_more_pension_number"></span> /
                                    <span class="fw-bold h5" id="add_more_pension_type"> /
                                    <span class="fw-bold h5" id="add_more_account_type">
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date Created</label>
                                    <div class="fw-bold h6 text-primary" id="add_more_created_at"></div>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group mb-3 col-3">
                                <label class="fw-bold h6">Birth Date</label>
                                <input type="text" class="form-control" id="add_more_birth_date" readonly>
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label class="fw-bold h6">Branch</label>
                                <input type="text" class="form-control" id="add_more_branch_location" readonly>
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

                        <div class="row atm-details mt-2">
                            <hr>
                            <label class="fw-bold h6 text-center mb-3 text-primary">
                                ATM / Passsbook / Simcard Details
                            </label>

                            <hr>
                            <div class="col-md-6">
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Type</label>
                                    <div class="col-sm-5">
                                        <select name="atm_type" id="atm_type_add_atm" class="form-select" required>
                                        <option value="" selected disabled>Type</option>
                                        <option value="ATM">ATM</option>
                                        <option value="Passbook">Passbook</option>
                                        <option value="Sim Card">Sim Card</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="atm_status" id="atm_status_add_atm" class="form-select" required>
                                        <option value="">ATM Status</option>
                                        <option value="new" selected>New</option>
                                        <option value="old">Old</option>
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
                                            <select name="bank_name" id="add_atm_bank_name" class="form-select select2" required>
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
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-md-4 fw-bold">Pin Code</label>
                                    <div class="col-md-5">
                                        <input type="number" name="pin_code" class="form-control" placeholder="PIN Code">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="cash_box_no" class="form-control" placeholder="Cash Box" min="0" max="100">
                                    </div>
                                </div>
                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-md-form-label col-md-4 fw-bold">Expiration Date</label>
                                    <div class="col-md-8">
                                        <input type="month" name="expiration_date" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-md-form-label col-md-4 fw-bold">Collection Date</label>
                                    <div class="col-md-8">
                                        <select name="collection_date" id="add_more_collection_date" class="form-select select2" required>
                                            <option value="" selected disabled>Collection Date</option>
                                            @foreach ($DataCollectionDates as $DataCollectionDate)
                                                <option value="{{ $DataCollectionDate->collection_date }}">{{ $DataCollectionDate->collection_date }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-success">Add ATM / PB</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('clients.data') !!}';

            const columns = [
                {
                    data: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="#" class="btn btn-info view_btn" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="View ">
                                <i class="fas fa-eye"></i>
                            </a>

                            <span>${row.action ?? ''}</span>`;
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'full_name',
                    render: function(data, type, row) {
                        return `<span>${row.full_name ?? ''}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'branch_location',
                    render: function(data, type, row) {
                        return `<span>${row.branch_location ?? ''}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'pension_details', // Correct field name
                    render: function(data, type, row, meta) {
                        return `<span>${row.pension_details ?? ''}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'transaction_number', // Correct field name
                    render: function(data, type, row, meta) {
                        return `<span>${row.transaction_number}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'bank_details', // Correct field name
                    render: function(data, type, row, meta) {
                        return `<span>${row.bank_details ?? ''}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'pin_code_details', // Correct field name
                    render: function(data, type, row, meta) {
                        return `<span>${row.pin_code_details}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'bank_status', // Correct field name
                    render: function(data, type, row, meta) {
                        return `<span>${row.bank_status ?? ''}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
            ];
            dataTable.initialize(url, columns);

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
                                        $('#AddClientButton').show();
                                        $('#pension_number_get').val($('#pension_number').val());
                                        $('#createClientModal').modal('show');
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

            $('#FetchingDatatable').on('click', '.view_btn', function(e) {
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

            // Add More ATM / PB Transaction
                $('#addAtmInformationModal').on('shown.bs.modal', function () {
                    $('#add_atm_bank_name').select2({ dropdownParent: $('#addAtmInformationModal'), });
                    $('#add_more_collection_date').select2({ dropdownParent: $('#addAtmInformationModal'), });
                    $('#add_atm_pension_account_type_dropdown').select2({  dropdownParent: $('#addAtmInformationModal') });
                });

                $('#FetchingDatatable').on('click', '.add_more_atm', function(e) {
                    e.preventDefault();
                    var itemID = $(this).data('id');

                    var url = "/clients/get/banks/" + itemID;

                    $.get(url, function(data) {
                        $('#add_atm_item_id').val(data.id);

                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';
                        let formattedCreatedAt = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        // Display formatted dates or blank if not valid
                        $('#add_more_birth_date').val(formattedBirthDate);
                        $('#add_more_created_at').text(formattedCreatedAt);
                        $('#add_more_fullname').text(`${data.client_information.last_name} ${data.client_information.first_name} ${data.client_information.middle_name ?? ''} ${data.client_information.suffix ?? ''}`.trim());
                        $('#add_more_pension_number').text(data.pension_number ?? '');
                        $('#add_more_pension_number').inputmask("99-9999999-99");
                        $('#add_atm_pension_number_value').val(data.pension_number ?? '');

                        $('#add_atm_pension_type_value').val(data.pension_type ?? '');
                        $('#add_atm_account_type').val(data.account_type ?? '').trigger('change');

                        $('#add_more_pension_type').text(data.pension_type ?? '');
                        $('#add_more_account_type').text(data.account_type ?? '');
                        $('#add_more_branch_location').val(data.branch.branch_location ?? '');

                        $('#addAtmInformationModal').modal('show');
                    });
                });

                $('#addAtmValidationForm').validate({
                    rules: {
                        atm_type: {
                            required: true
                        },
                        pin_code: {
                            required: function (element) {
                                return $('#atm_type_add_atm').val() === 'ATM'; // Pin code required only if ATM type is 'ATM'
                            }
                        }
                    },
                    messages: {
                        atm_type: {
                            required: "ATM Type is required"  // Custom error message for existing ATM numbers
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

                                            if (res.status === 'success') {
                                                CloseAtmInformationModal();

                                                Swal.fire({
                                                    title: 'Successfully Added!',
                                                    text: 'ATM / PB is successfully added!',
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

                function CloseAtmInformationModal() {
                    $('#AddAtmInformationModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

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
            // Add More ATM / PB Transaction

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

            // Create Client
                $('#createClientModal').on('shown.bs.modal', function () {
                    $('#branch_id').select2({ dropdownParent: $('#createClientModal'), });
                    $('#pension_account_type').select2({  dropdownParent: $('#createClientModal') });
                    $('#bank_id').select2({  dropdownParent: $('#createClientModal') });
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

                                            if (res.status === 'success') {
                                                closeCreateClientModal();
                                                Swal.fire({
                                                    title: 'Successfully Added!',
                                                    text: 'Client is successfully added!',
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

                function closeCreateClientModal() {
                    $('#createClientModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }
            // Create Client
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

                                <div class="form-group mb-2 row align-items-center">
                                    <label class="col-form-label col-4 fw-bold">Pin Code</label>
                                    <div class="col-8">
                                        <input type="number" name="pin_code[]" class="form-control" placeholder="PIN Code">
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
                                        <input type="text" name="atm_balance[]" class="balanceCurrency form-control" value="0" placeholder="Balance" required>
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
