@extends('layouts.settings_monitoring.settings_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('settings')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') System Logs @endslot
    @endcomponent

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
                            <button type="button" class="btn btn-success">Generate Reports</button>
                        </div>
                    </div>
                    <hr>


                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
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
            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('system.logs.data') !!}';
            const columns = [
                {
                    data: 'action',
                    name: 'action',
                    render: function(data, type, row, meta) {
                        let spanClass = '';
                        switch (row.action) {
                            case 'Create':
                                spanClass = 'bg-primary';
                                break;
                            case 'Update':
                                spanClass = 'bg-warning';
                                break;
                            case 'Delete':
                                spanClass = 'bg-danger';
                                break;
                            default:
                                spanClass = 'bg-secondary';
                        }
                        return `<span class="badge ${spanClass}">${row.action}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        // Set the maximum length for the description
                        const maxLength = 50; // Example: limit to 50 characters

                        // Truncate the description if it exceeds the maximum length
                        let truncatedDescription = row.description;
                        if (row.description.length > maxLength) {
                            truncatedDescription = row.description.substring(0, maxLength) + '...';
                        }

                        return `<span class="fw-bold text-primary h6">${row.title}</span><br>
                                <span class="text-muted">${truncatedDescription}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'employee_id',
                    name: 'employee.name',
                    render: function(data, type, row, meta) {
                        return row.employee ? '<span>' + row.employee.name + '</span>' : '';
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'differForHumans',
                    name: 'differForHumans',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
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
                    render: function(data, type, row) {
                        return new Date(row.created_at).toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                    },
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'ip_address',
                    name: 'ip_address',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
            ];

            // Initialize DataTable
            dataTable.initialize(url, columns);

            // // Initialize DataTable with explicit cache control in the AJAX request
            // const dt = $('#FetchingDatatable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: url,
            //         type: 'GET',
            //         cache: false, // Prevent browser from caching the response
            //     },
            //     columns: columns,
            // });


            // Add polling to reload data every 10 seconds
            // setInterval(function () {
            //     $('#FetchingDatatable').DataTable().ajax.reload(null, false); // Reload data without resetting pagination
            // }, 5000); // 10000 milliseconds = 10 seconds
        });
    </script>






@endsection
@section('script')

@endsection
