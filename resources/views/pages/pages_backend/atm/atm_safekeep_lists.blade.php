@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Safekeep Lists @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Safekeep ATM Lists</h4>
                            <p class="card-title-desc">
                                Safekeeping refers to the temporary holding of ATMs that are ready for client release but have not yet been
                                picked up at the branch. These ATMs are securely stored by the head office until the client is able to collect them.
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
                                    <th>Transaction No</th>
                                    <th>Client</th>
                                    <th>Branch</th>
                                    <th>Pension No. / Type</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    <th>Cash Box</th>
                                    <th>Card No & Bank</th>
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

    <div class="modal fade" id="pulloutBranchTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="pulloutBranchTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 30%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Pullout From Safekeep</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('TransactionCreate') }}" method="POST" id="TransactionPulloutValidateForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="atm_id" id="pullout_atm_id">
                            <input type="hidden" name="reason_for_pull_out" value="9">
                            <input type="hidden" name="aprb_no" id="pullout_aprb_no">
                            <div class="col-12">
                                <div class="form-group">
                                    <div id="pullout_fullname" class="fw-bold h4"></div>
                                    <span id="pullout_pension_number_display" class="ms-3 text-primary fw-bold h5"></span> /
                                    <span id="pullout_pension_type" class="fw-bold h5"></span>
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="form-group col-12 mb-3">
                                        <label class="fw-bold h6">Remarks</label>
                                        <textarea name="remarks" cols="30" rows="3" class="form-control" style="resize: none;"
                                                  placeholder="Remarks" minlength="0" maxlength="100" required></textarea>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Transaction Number</label>
                                        <input type="text" class="form-control" id="pullout_transaction_number" readonly>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Birthdate</label>
                                        <input type="text" class="form-control" id="pullout_birth_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Card No.</label>
                                        <input type="text" class="form-control" id="pullout_bank_account_no" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Bank Name</label>
                                        <input type="text" class="form-control" id="pullout_bank_name" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Type</label>
                                        <input type="text" class="form-control" id="pullout_atm_type" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Expiration Date</label>
                                        <input type="text" class="form-control" id="pullout_expiration_date" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-6">
                                        <label class="fw-bold h6">Collection Date</label>
                                        <input type="text" class="form-control" id="pullout_collection_date" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-success">Pullout From Safekeep</button>
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
                var url = '{!! route('SafekeepData') !!}';
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
                            return `<span>${row.action ?? ''}</span>`;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    // Transaction Type and Pending By
                    {
                        data: 'pending_to',
                        render: function(data, type, row, meta) {
                            return `<span class="fw-bold h6 text-primary">${row.pending_to ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    // Reference No
                    {
                        data: 'transaction_number',
                        name: 'transaction_number',
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
                        data: 'cash_box_no',
                        render: function(data, type, row, meta) {
                            return `<span>${row.cash_box_no ?? ''}</span>`;
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
                        data: 'pin_code_details',
                        render: function(data, type, row) {
                            return `<span>${row.pin_code_details ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'collection_date',
                        render: function(data, type, row, meta) {
                            return `<span>${row.collection_date ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'bank_status',
                        render: function(data, type, row, meta) {
                            return `<span>${row.bank_status ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                ];
                dataTable.initialize(url, columns);
            // Displaying of Data

            // Creation of Pullout
                $('#FetchingDatatable').on('click', '.pullOutTransaction', function(e) {
                    e.preventDefault();
                    var new_atm_id = $(this).data('id');
                    var new_aprb_no = $(this).data('aprb_no');

                    $('#pullout_aprb_no').val(new_aprb_no ?? '');

                    $.ajax({
                        url: "/AtmClientFetch",
                        type: "GET",
                        data: { new_atm_id : new_atm_id },
                        success: function(data) {
                            let formattedBirthDate = data.client_information.birth_date ? new Date(data.client_information.birth_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                            $('#pullout_fullname').text(data.client_information.last_name +', '
                                                        + data.client_information.first_name +' '
                                                        +(data.client_information.middle_name ?? '') +' '
                                                        + (data.client_information.suffix ?? ''));

                            $('#pullout_branch_id').val(data.branch_id ?? '').trigger('change');

                            $('#pullout_pension_number_display').text(data.pension_number ?? '');
                            $('#pullout_pension_number_display').inputmask("99-9999999-99");

                            $('#pullout_pension_number').val(data.pension_number ?? '');
                            $('#pullout_pension_type').text(data.pension_type ?? '');
                            $('#pullout_pension_account_type').val(data.pension_type ?? '');
                            $('#pullout_birth_date').val(formattedBirthDate ?? '');
                            $('#pullout_branch_location').val(data.branch.branch_location ?? '');

                            $('#pullout_atm_id').val(data.id ?? '');
                            $('#pullout_bank_account_no').val(data.bank_account_no ?? '');
                            $('#pullout_collection_date').val(data.collection_date ?? '').trigger('change');
                            $('#pullout_atm_type').val(data.atm_type ?? '');
                            $('#pullout_bank_name').val(data.bank_name ?? '');
                            $('#pullout_transaction_number').val(data.transaction_number ?? '');

                            let expirationDate = '';
                            if (data.expiration_date && data.expiration_date !== '0000-00-00') {
                                expirationDate = new Date(data.expiration_date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                            }
                            $('#pullout_expiration_date').val((expirationDate || ''));

                            $('#pulloutBranchTransactionModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                        }
                    });
                });

                function closeTransactionModal() {
                    $('#pulloutBranchTransactionModal').modal('hide');
                    $('#FetchingDatatable tbody').empty();
                }

                $('#TransactionPulloutValidateForm').validate({
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

                                            if (res.status === 'success') {
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
                                            if (xhr.responseJSON && xhr.responseJSON.error) {
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
            // Creation of Pullout
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
                            location: 'Safekeep',
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
