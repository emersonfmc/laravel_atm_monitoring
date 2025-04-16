@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Passbook Transaction @endslot
        @slot('title') Passbook For Collection Setup @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Passbook For Collection Setup</h4>
                            <p class="card-title-desc">
                                Where branches select Passbook for scheduled to collections.
                            </p>
                        </div>
                        <div class="col-md-4"></div>
                        {{-- <div class="col-md-4 text-end" id="passbookForCollection" style="display: none;">
                            <a href="#" class="btn btn-primary" id="ForCollectionButton"><i class="fas fa-plus-circle me-1"></i>
                                Passbook For Collection
                            </a>
                        </div> --}}

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

                                <div class="col-md-3 align-items-end" style="margin-top:25px;">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <span id="passbookForCollection" style="display: none;">
                                        <a href="#" class="btn btn-primary" id="ForCollectionButton">
                                            <i class="fas fa-plus-circle me-1"></i> Passbook For Collection
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </form>
                        <hr>
                    @endif



                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th class="pe-4 ps-4">
                                        <input type="checkbox" id="selectAll" title="Select All">
                                    </th>
                                    <th style="width: 15%;">Pending By</th>
                                    <th>Transaction No</th>
                                    <th>Client</th>
                                    <th>Branch</th>
                                    <th>Pension No. / Type</th>
                                    <th>Created Date</th>
                                    <th>Birthdate</th>
                                    <th>Passbook</th>
                                    <th>Type / Status</th>
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

    <script>
        $(document).ready(function () {
            // Displaying of Data
                var FetchingDatatableBody = $('#FetchingDatatable tbody');

                const dataTable = new ServerSideDataTable('#FetchingDatatable');
                var url = '{!! route('PassbookCollectionData') !!}';
                const columns = [
                    {
                        data: 'action',
                        render: function(data, type, row, meta) {
                            return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'pending_to',
                        render: function(data, type, row, meta) {
                            return '<span class="fw-bold text-primary">' + data + '</span>';
                        },
                        orderable: true,
                        searchable: true,
                    },
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
                            return `<span>${row.pension_details ?? ''}</span>`;
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
                            return `<span>${row.bank_details ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'pb_status',
                        render: function(data, type, row, meta) {
                            return `<span>${row.pb_status ?? ''}</span>`;
                        },
                        orderable: true,
                        searchable: true,
                    },
                ];
                dataTable.initialize(url, columns, { pageLength: 200, lengthMenu: [200,250,300,350,400,500], });

                // Filtering of Transaction
                    var branchId = @json($branch_id);
                    var userHasBranchId = {!! Auth::user()->branch_id ? 'true' : 'false' !!};

                    if (userHasBranchId) {
                        $('#branch_id_select').val(branchId).prop('disabled', true);
                    }

                    $('#filterForm').submit(function(e) {
                        e.preventDefault();
                        var selectedBranch = $('#branch_id_select').val();
                        var targetUrl = '{!! route('PassbookCollectionData') !!}';

                        if (!userHasBranchId && selectedBranch) {
                            targetUrl += '?branch_id=' + selectedBranch;
                        }
                        dataTable.table.ajax.url(targetUrl).load();
                    });
                // Filtering of Transaction
            // Displaying of Data

            $('#selectAll').on('change', function () {
                const isChecked = $(this).is(':checked');
                $('.check-item').prop('checked', isChecked);
                $('.check-item').trigger('change');
            });

            // Individual checkbox logic
            $(document).on('change', '.check-item', function () {
                const totalCheckboxes = $('.check-item').length;
                const checkedCheckboxes = $('.check-item:checked').length;

                $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);

                if (checkedCheckboxes > 0) {
                    $('#passbookForCollection').show();
                } else {
                    $('#passbookForCollection').hide();
                }
            });

            // Handle button click to collect checked item IDs and show confirmation
            $('#ForCollectionButton').on('click', function(e) {
                e.preventDefault(); // Prevent default link behavior

                // Collect all checked item IDs
                var checkedItemsData = [];
                $('.check-item:checked').each(function() {
                    var itemId = $(this).data('id');
                    checkedItemsData.push(itemId);
                });

                const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token
                const today = new Date().toISOString().slice(0, 16);

                // Confirm the action with SweetAlert
                Swal.fire({
                    title: 'Passbook For Collection Approval',
                    text: 'Are you sure you want to proceed with these to Passbook for Collection?',
                    html: `
                        <p>Publish this selected clients?</p>
                        <span><hr></span>
                        <div class="form-group col-md-12">
                            <label for="publishDatetime" class="fw-bold">Select Pickup Date and Time</label>
                            <input type="datetime-local" id="publishDatetime" class="swal2-input form-control fs-5" min="${today}" name="publishDatetime">
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, proceed!",
                    preConfirm: () => {
                        const publishDatetime = Swal.getPopup().querySelector('#publishDatetime').value;
                        if (!publishDatetime) {
                            Swal.showValidationMessage(`Please select pick up date`);
                        }
                        return { publishDatetime: publishDatetime };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        const currentPage = dataTable.table.page();
                        const publishDatetime = result.value.publishDatetime;

                        $.ajax({
                            url: '{!! route('PassbookForCollectionCreate') !!}',
                            type: 'POST',
                            data: {
                                items: checkedItemsData,
                                publishDatetime: publishDatetime,
                                _token: csrfToken // Include CSRF token in the request
                            },
                            success: function(response) {
                                if (typeof response === 'string') {
                                    var res = JSON.parse(response);
                                } else {
                                    var res = response; // If it's already an object
                                }

                                if (res.status === 'success'){
                                    createDatatable();
                                    Swal.fire({
                                        title: 'Successfully Create!',
                                        text: 'Passbook For Collection is successfully Created!',
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
                                                            $('#passbookForCollection').hide();
                                                            dataTable.table.page(currentPage).draw( false );
                                                        },
                                                        false );
                                                    }
                                                })
                                            });
                                        }
                                    });
                                } else if (res.status === 'error'){
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
                });
            });

            function createDatatable() {
                $('#FetchingDatatable tbody').empty();
            }
        });
    </script>

@endsection
