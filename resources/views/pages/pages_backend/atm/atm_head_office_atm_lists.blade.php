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
                                    <th>Box</th>
                                    <th>ATM / Passbook / Simcard No & Bank</th>
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
                            <input type="text" name="atm_id" id="create_atm_id">
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

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-border dt-responsive wrap table-design">
                                        <thead>
                                            <th>Checkbox</th>
                                            <th>Transaction Number</th>
                                            <th>Bank Account No</th>
                                            <th>Type / Status</th>
                                            <th>Collection Date</th>
                                            <th>Expiration Date</th>
                                        </thead>

                                        <tbody id="displayClientInformation">

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
                        return row.action; // Use the action rendered from the server
                    },
                    orderable: false,
                    searchable: false,
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
                        return data ? `<span>${data}</span>` : '';
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

            $('#FetchingDatatable').on('click', '.createTransaction', function(e) {
                e.preventDefault();
                var new_atm_id = $(this).data('id');

                $.ajax({
                    url: "/AtmClientFetch",
                    type: "GET",
                    data: { new_atm_id : new_atm_id },
                    success: function(data) {
                        console.log(data);
                        let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                        $('#create_fullname').text(data.client_information.last_name +', '+ data.client_information.first_name +' '+ data.client_information.middle_name +' '+ data.client_information.suffix ?? '');

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


                        $('#createTransactionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                });
            });

            $('#TransactionCreateValidateForm').validate({
                rules: {
                    reason_for_pull_out: { required: true }
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

            function closeTransactionModal() {
                $('#createTransactionModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
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
