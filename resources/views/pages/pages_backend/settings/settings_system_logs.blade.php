@extends('layouts.settings_monitoring.settings_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        td.text-left {
            text-align: left !important;
        }
    </style>
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
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>Title</th>
                                    <th style="text-align: left !important; width: 20%;">Description New</th>
                                    <th style="text-align: left !important; width: 20%;">Description Old</th>
                                    <th>User</th>
                                    <th>Time Elapsed</th>
                                    <th>Date</th>
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
            var url = '{!! route('settings.system.logs.data') !!}';
            const columns = [
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return '<span>' + data + '</span>';
                    },
                    orderable: true,
                    searchable: true,
                },
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
                        return `<span>${row.module ?? ''}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<span class="fw-bold text-primary">${row.title}</span>`;
                    },
                    orderable: true,
                    searchable: true,
                },
                {
                    data: "description_logs",
                    className: "text-left",
                    render: function(data, type, row, meta) {
                        let details;

                        // Ensure JSON is parsed properly
                        try {
                            details = typeof data === "string" ? JSON.parse(data) : data;
                        } catch (e) {
                            console.error("Invalid JSON:", data);
                            return "<em>Invalid data</em>";
                        }

                        let newDetails = details?.new_details ?? {};

                        if (typeof newDetails === "object" && Object.keys(newDetails).length > 0) {
                            return Object.entries(newDetails).map(([key, value]) => {
                                // If value is an object with a single key "", extract the value
                                if (typeof value === "object" && value !== null) {
                                    let extractedValue = Object.values(value)[0] ?? "<em> </em>";
                                    return `<strong>${key.replace(/_/g, " ")} : </strong> ${extractedValue}`;
                                }
                                return `<strong>${key.replace(/_/g, " ")} : </strong> ${value ?? "<em> </em>"}`;
                            }).join("<br>");
                        }

                        return "<em>No old details</em>";
                    }
                },
                {
                    data: "description_logs",
                    className: "text-left",
                    render: function(data, type, row, meta) {
                        let details;

                        // Ensure JSON is parsed properly
                        try {
                            details = typeof data === "string" ? JSON.parse(data) : data;
                        } catch (e) {
                            console.error("Invalid JSON:", data);
                            return "<em>Invalid data</em>";
                        }

                        let oldDetails = details?.old_details ?? {};

                        if (typeof oldDetails === "object" && Object.keys(oldDetails).length > 0) {
                            return Object.entries(oldDetails).map(([key, value]) => {
                                // If value is an object with a single key "", extract the value
                                if (typeof value === "object" && value !== null) {
                                    let extractedValue = Object.values(value)[0] ?? "<em> </em>";
                                    return `<strong>${key.replace(/_/g, " ")} : </strong> ${extractedValue}`;
                                }
                                return `<strong>${key.replace(/_/g, " ")} : </strong> ${value ?? "<em> </em>"}`;
                            }).join("<br>");
                        }

                        return "";
                    }
                },

                {
                    data: 'user_logs',
                    render: function(data, type, row, meta) {
                        return `<span>${row.user_logs ?? ''}</span>`
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
                        let dateObj = new Date(data);
                        let datePart = dateObj.toLocaleDateString('en-US', {
                            month: 'short',  // "Sep"
                            day: 'numeric',  // "20"
                            year: 'numeric'  // "2024"
                        });

                        let timePart = dateObj.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });

                        return `${datePart} <br> ${timePart}`;
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
