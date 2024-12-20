@extends('layouts.settings.settings_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('settings')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Collection Date @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Collection</h4>
                            <p class="card-title-desc">
                                The Collection Date refers to the scheduled date when funds or payments are collected.
                                This date is essential for ensuring timely transactions, It allows recipients and institutions to
                                manage finances accurately and maintain records efficiently.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCollectionDateModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Collection Date</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Bank Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


    <div class="modal fade" id="createCollectionDateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="createBankLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="createBankLabel">Create Collection Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.collection.date.create') }}" id="createCollectionDateForm">
                        @csrf

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">Collection Date</label>
                            <input type="text" name="collection_date" id="collection_date" class="form-control" placeholder="Collection Date" minlength="0" maxlength="50" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCollectionDateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="createBankLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Collection Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('settings.collection.date.update')  }}" id="updateCollectionDateForm">
                        @csrf
                        <input type="hidden" id="item_id" name="item_id">

                        <div class="form-group mb-2">
                            <label class="fw-bold h6">Collection Date</label>
                            <input type="text" name="collection_date" id="collection_date_update" class="form-control" placeholder="Collection Date" minlength="0" maxlength="50" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Status</label>
                            <select name="status" id="status_update" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var SettingsBanksTableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('settings.collection.date.data') !!}';
            const buttons = [{
                text: 'Delete',
                action: function(e, dt, node, config) {
                    // Add your custom button action here
                    alert('Custom button clicked!');
                }
            }];
            const columns = [
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span">' + data + '</span>'; // Display user's ID
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'collection_date',
                    name: 'collection_date',
                    render: function(data, type, row, meta) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        if (data.toLowerCase() === 'active') {
                            return '<span class="badge bg-primary">Active</span>';
                        } else if (data.toLowerCase() === 'inactive') {
                            return '<span class="badge bg-danger">Inactive</span>';
                        } else {
                            return '<span>No Status</span>';
                        }
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });
                    }

                },
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="#" class="text-warning editBtn me-2" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit ">
                                <i class="fas fa-pencil-alt me-2"></i>
                            </a>

                            <a href="#" class="text-danger deleteBtn me-2" data-id="${row.id}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete ">
                                <i class="fas fa-trash-alt me-2"></i>
                            </a>`;
                    },
                    orderable: false,
                    searchable: false,
                }
            ];
            dataTable.initialize(url, columns);

            function closeCreateModal() {
                $('#createCollectionDateModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
            }

            function closeUpdateModal() {
                $('#updateCollectionDateModal').modal('hide');
                $('#FetchingDatatable tbody').empty();
            }

            $('#createCollectionDateForm').validate({
                rules: {
                    collection_data: { required: true },
                },
                messages: {
                    collection_data: { required: 'Please Enter Collection Date' },
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
                    var hasRows = SettingsBanksTableBody.children('tr').length > 0;
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
                                        closeCreateModal();
                                        Swal.fire({
                                            title: 'Successfully Added!',
                                            text: 'Collection Date is successfully Updated!',
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

            $('#FetchingDatatable').on('click', '.editBtn', function(e) {
                e.preventDefault();
                var itemID = $(this).data('id');

                var url = "/settings/collection/date/get/" + itemID;

                $.get(url, function(data) {
                    $('#item_id').val(data.id);
                    $('#status_update').val(data.status).trigger('change');
                    $('#collection_date_update').val(data.collection_date);

                    $('#updateCollectionDateModal').modal('show');
                });
            });

            $('#updateCollectionDateForm').validate({
                rules: {
                    collection_date: { required: true },
                },
                messages: {
                    collection_date: { required: 'Please Enter Collection Date' },
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
                    var hasRows = SettingsBanksTableBody.children('tr').length > 0;
                    if (hasRows) {
                        Swal.fire({
                            title: 'Confirmation',
                            text: 'Are you sure you want to save this?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: "#28A745",
                            cancelButtonColor: "#6C757D",
                            confirmButtonText: "Yes, Update it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const currentPage = dataTable.table.page();
                                $.ajax({
                                    url: form.action,
                                    type: form.method,
                                    data: $(form).serialize(),
                                    success: function(response) {
                                        closeUpdateModal();
                                        Swal.fire({
                                            title: 'Successfully Updated!',
                                            text: 'Collection Date is successfully Updated!',
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

        });
    </script>






@endsection
@section('script')

@endsection
