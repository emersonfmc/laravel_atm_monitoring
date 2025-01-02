@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Receiving of Transaction @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <hr>
                    <form id="filterForm">
                        @csrf
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-2">
                                <div class="form-group">
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
                            <div class="col-2">
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
                                    <th>
                                        <i class="far fa-check-square"></i>
                                    </th>
                                    <th>Action</th>
                                    <th>Transaction / Date Requested</th>
                                    <th>Reference No</th>
                                    <th>No</th>
                                    <th>APRB No.</th>
                                    <th>Client</th>
                                    <th>Pension No</th>
                                    <th>Card No. & Bank</th>
                                    <th>Type</th>
                                    <th>PIN Code</th>
                                    <th>Collection Date</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelledTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 30%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Cancelled Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('TransactionCancelled') }}" method="POST" id="cancelledTransactionForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="cancelled_atm_id">
                            <input type="hidden" name="transaction_id" id="cancelled_transaction_id">

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
                                            maxlength="250" style="resize:none;" required></textarea>
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

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('TransactionReceivingData') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];
            const columns = [
                {
                    data: 'checkbox',
                    name: 'checkbox', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span>' + row.checkbox + '</span>';
                    }
                },
                {
                    data: 'action',
                    name: 'action', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span>' + row.action + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '',
                    render: function(data, type, row, meta) {
                        let transactionName = '';
                        let createdAt = '';

                        // Check if `data_transaction_action` exists and has a `name`
                        if (row.data_transaction_action) {
                            transactionName = row.data_transaction_action.name || '';
                        }

                        // Check if `atm_banks_transaction` exists and has `created_at`
                        if (row.atm_banks_transaction && row.atm_banks_transaction.created_at) {
                            // Convert `created_at` to desired format
                            const date = new Date(row.atm_banks_transaction.created_at);
                            createdAt = date.toLocaleString('en-US', {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                        }

                        // Return formatted HTML
                        return `
                            <span class="fw-bold text-primary">${transactionName}</span><br>
                            <span style="font-size: 12px;">${createdAt}</span>
                        `;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: 'atm_banks_transaction.transaction_number',
                    render: function(data, type, row, meta) {
                        return row.atm_banks_transaction ? '<span>' + row.atm_banks_transaction.transaction_number + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return row.atm_banks_transaction.branch ? '<span>' + row.atm_banks_transaction.branch.branch_location + '</span>' : ''; // Check if company exists
                //     },
                //     orderable: true,
                //     searchable: true,
                // },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: 'atm_banks_transaction.aprb_no',
                    render: function(data, type, row, meta) {
                        return row.atm_banks_transaction.aprb_no ? '<span>' + row.atm_banks_transaction.aprb_no + '</span>' : ''; // Check if company exists
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<span>' + row.full_name + '</span>';
                    },
                    orderable: true,
                    searchable: true
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<span>' + row.pension_details + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                            if (row.atm_banks_transaction) {
                                if(row.atm_banks_transaction.atm_client_banks){
                                    const bankAccountNo = row.atm_banks_transaction.atm_client_banks.bank_account_no;
                                    const bankname = row.atm_banks_transaction.atm_client_banks.bank_name;

                                    return `<span class="fw-bold h6 text-success">${bankAccountNo}</span><br>
                                            <span>${bankname}</span>`;
                                }
                            }
                            return '';

                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        if (row.atm_banks_transaction && row.atm_banks_transaction.atm_client_banks) {
                            const atmType = row.atm_banks_transaction.atm_client_banks.atm_type;
                            const atmStatus = row.atm_banks_transaction.atm_client_banks.atm_status;

                            // Determine class based on atmType
                            let atmTypeClass = '';
                            if (atmType === 'ATM') {
                                atmTypeClass = 'text-primary';
                            } else if (atmType === 'Passbook') {
                                atmTypeClass = 'text-danger';
                            } else if (atmType === 'Sim Card') {
                                atmTypeClass = 'text-info';
                            }

                            // Return formatted HTML with dynamic class
                            return `
                                <span class="${atmTypeClass}">${atmType}</span><br>
                                <span>${atmStatus}</span>
                            `;
                        }
                        return ''; // Fallback if data is missing
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '',
                    render: function(data, type, row) {
                        if (row.atm_banks_transaction && row.atm_banks_transaction.atm_client_banks) {
                            const clientBank = row.atm_banks_transaction.atm_client_banks;

                            // Extract data
                            const bankAccountNo = clientBank.bank_account_no || '';
                            const bankPinCode = clientBank.pin_no;
                            const bankType = clientBank.atm_type;

                            // Check if atm_type is "ATM"
                            if (bankType === 'ATM') {
                                // Check if pin_code is null or empty
                                if (!bankPinCode) {
                                    return `<span class="text-danger">No Pin Code</span>`;
                                }

                                // Display view pin code link if pin_code is not empty
                                return `
                                    <a href="#" class="text-info fs-4 view_pin_code"
                                        data-pin="${bankPinCode}"
                                        data-bank_account_no="${bankAccountNo}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                `;
                            }
                        }

                        // Default return if condition not met
                        return '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'banks_transactions_id',
                    name: '', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                            if (row.atm_banks_transaction) {
                                if(row.atm_banks_transaction.atm_client_banks){
                                    const CollectionDate = row.atm_banks_transaction.atm_client_banks.collection_date;

                                    return `<span>${CollectionDate}</span>`;
                                }
                            }
                            return '';

                    },
                    orderable: true,
                    searchable: true,
                },


            ];
            dataTable.initialize(url, columns);

            // Cancelled Transanction
                $('#FetchingDatatable').on('click', '.cancelledTransaction', function(e) {
                    e.preventDefault();
                    var approval_id = $(this).data('id');
                    var transaction_id = $(this).data('transaction_id');
                    var transaction_number = $(this).data('transaction_number');

                    $('#cancelled_approval_id').val(approval_id);
                    $('#cancelled_transaction_id').val(transaction_id);
                    $('#cancelled_transaction_number').text(transaction_number);

                    $.ajax({
                        url: "/TransactionGet",
                        type: "GET",
                        data: { transaction_id : transaction_id },
                        success: function(data) {
                            console.log(data);
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

                function CancelledTransactionModal() {
                    $('#cancelledTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#cancelledTransactionForm').validate({
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
                                text: 'Are you sure you want to Cancelled this?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: "#EB6666",
                                cancelButtonColor: "#6C757D",
                                confirmButtonText: "Yes, Cancelled it!"
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
                                                CancelledTransactionModal();
                                                Swal.fire({
                                                    title: 'Successfully Cancelled!',
                                                    text: 'Transaction is successfully Cancelled!',
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
            // Cancelled Transanction
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

@endsection
