@extends('layouts.settings_monitoring.settings_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('settings')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-4">
            <div class="card shadow-lg overflow-hidden">
                <div class="bg-primary bg-soft p-2">
                    <div class="row align-items-center">
                        <!-- Text Section -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-5">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">Welcome Back!</h5>
                                <p class="mb-0">Settings Dashboard</p>
                            </div>
                        </div>

                        <!-- Image Section -->
                        <div class="col-12 col-sm-6 col-md-6 col-lg-7 col-xl-7 text-start">
                            <img
                                src="{{ URL::asset('/images/EverfirstLogo.png') }}"
                                class="img-fluid mx-md-4 mx-2"
                                style="max-height: 30px;"
                                alt="Everfirst Logo"
                            >
                        </div>
                    </div>
                </div>


                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="avatar-md profile-user-wid mb-3">
                                <img src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('images/no_image.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                            </div>
                            <h5 class="font-size-15 text-truncate">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                            <p class="mb-0 text-truncate text-danger ms-2">{{ Auth::user()->UserGroup->group_name }}</p>
                        </div>

                        <div class="col-sm-6">
                            <div class="pt-4">

                                <div class="row">
                                    {{-- <div class="col-6">
                                        <h5 class="font-size-15 fw-bold">125</h5>
                                        <p class="text-muted mb-0">Pending Transactions</p>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow border-left-primary mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium fw-bold">Users</p>
                                    <h4 class="mb-0" id="UserCount"></h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <i class="fas fa-user-edit fs-1" style="color: #3633ff;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-left-info mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium fw-bold">Districts</p>
                                    <h4 class="mb-0" id="DistrictCount"></h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <i class="fas fa-map-marked fs-1" style="color: #68FFFF;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-left-area mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium fw-bold">Area</p>
                                    <h4 class="mb-0" id="AreaCount"></h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <i class="fas fa-map-marked fs-1" style="color: #000066;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-left-branches mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium fw-bold">Branches</p>
                                    <h4 class="mb-0" id="BranchCount"></h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <i class="fas fa-code-branch fs-1" style="color: #EB9CFF;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-left-user-group mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium fw-bold">User Group</p>
                                    <h4 class="mb-0" id="UserGroupCount"></h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <i class="fas fa-users fs-1" style="color: #808000;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-left-warning mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium fw-bold">Banks</p>
                                    <h4 class="mb-0" id="BanksCount"></h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <i class="fas fa-university fs-1 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 text-start"> <h4 class="card-title">System Logs</h4>
                            <p class="card-title-desc">
                                System logs provide a record of events and activities within a computer
                                system, helping to monitor, troubleshoot, and secure the system's operations.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Action</th>
                                    <th>Title & Description</th>
                                    <th>User</th>
                                    <th>Time Elapsed</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>IP Address</th>
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

    <script>
        $(document).ready(function () {
            const fetchCounts = () => {
                $.ajax({
                    url: "{{ route('settings.monitoring.dashboard.data') }}",  // Your route to get the counts
                    type: "GET",
                    cache: false,  // Disable AJAX caching
                    headers: {
                        'Cache-Control': 'no-cache, no-store, must-revalidate',  // Prevent caching
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    },
                    success: function(response) {
                        console.log(response);
                        // Format the numbers with comma as thousand separator
                        const formatNumber = (number) => {
                            return number.toLocaleString(); // Formats the number with commas (e.g., 1,000)
                        };

                        $('#UserCount').text(formatNumber(response.UserCount ?? 0));
                        $('#AreaCount').text(formatNumber(response.AreaCount ?? 0));
                        $('#DistrictCount').text(formatNumber(response.DistrictCount ?? 0));
                        $('#BranchCount').text(formatNumber(response.BranchCount ?? 0));
                        $('#UserGroupCount').text(formatNumber(response.UserGroupCount ?? 0));
                        $('#BanksCount').text(formatNumber(response.BanksCount ?? 0));
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching counts:", error);
                    }
                        });
                    };
            // Initial fetch
            fetchCounts();

            // Set interval to refresh every 20 seconds
            setInterval(fetchCounts, 30000); // 5000 milliseconds = 5 seconds
        });

        // $(document).ready(function () {
        //     const dataTable = new ServerSideDataTable('#FetchingDatatable');
        //     var url = '{!! route('settings.system.logs.data') !!}';

        //     const columns = [
        //         {
        //             data: 'id',
        //             name: 'id',
        //             render: function(data, type, row, meta) {
        //                 return `<span>${data}</span>`;
        //             },
        //             orderable: true,
        //             searchable: true,
        //         },
        //         {
        //             data: 'action',
        //             name: 'action',
        //             render: function(data, type, row, meta) {
        //                 let spanClass = {
        //                     'Create': 'bg-primary',
        //                     'Update': 'bg-warning',
        //                     'Delete': 'bg-danger'
        //                 }[row.action] || 'bg-secondary';

        //                 return `<span class="badge ${spanClass}">${row.action}</span>`;
        //             },
        //             orderable: true,
        //             searchable: true,
        //         },
        //         {
        //             data: null,
        //             render: function(data, type, row, meta) {
        //                 const maxLength = 50; // Limit description to 50 characters
        //                 let truncatedDescription = row.description.length > maxLength
        //                     ? row.description.substring(0, maxLength) + '...'
        //                     : row.description;

        //                 return `<span class="fw-bold text-primary h6">${row.title}</span><br>
        //                         <span class="text-muted">${truncatedDescription}</span>`;
        //             },
        //             orderable: true,
        //             searchable: true,
        //         },
        //         {
        //             data: 'employee_id',
        //             name: 'employee.name',
        //             render: function(data, type, row, meta) {
        //                 return row.employee ? `<span>${row.employee.name}</span>` : '';
        //             },
        //             orderable: true,
        //             searchable: true,
        //         },
        //         {
        //             data: 'differForHumans',
        //             name: 'differForHumans',
        //             render: function(data, type, row, meta) {
        //                 return `<span>${data}</span>`;
        //             },
        //             orderable: true,
        //             searchable: true,
        //         },
        //         {
        //             data: 'created_at',
        //             name: 'created_at',
        //             render: function(data, type, row) {
        //                 return new Date(data).toLocaleDateString('en-US', {
        //                     month: 'long',
        //                     day: 'numeric',
        //                     year: 'numeric'
        //                 });
        //             }
        //         },
        //         {
        //             data: null,
        //             render: function(data, type, row) {
        //                 return new Date(row.created_at).toLocaleTimeString('en-US', {
        //                     hour: 'numeric',
        //                     minute: '2-digit',
        //                     hour12: true
        //                 });
        //             },
        //             orderable: true,
        //             searchable: true
        //         },
        //         {
        //             data: 'ip_address',
        //             name: 'ip_address',
        //             render: function(data, type, row, meta) {
        //                 return `<span>${data}</span>`;
        //             },
        //             orderable: true,
        //             searchable: true,
        //         },
        //     ];

        //     // Initialize DataTable with initial order by ID in descending order
        //     dataTable.initialize(url, columns, {
        //         order: [[0, 'desc']] // Assumes the ID column is the first column (index 0)
        //     });

        //     // Auto-refresh table every 10 seconds
        //     setInterval(function () {
        //         $('#FetchingDatatable').DataTable().ajax.reload(null, false); // Retains order
        //     }, 10000); // 10 seconds
        // });
    </script>


@endsection
@section('script')

@endsection
