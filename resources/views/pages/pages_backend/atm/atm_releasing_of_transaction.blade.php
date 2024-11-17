@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Releasing of Transaction @endslot
    @endcomponent


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Checkbox</th>
                                    <th>Action</th>
                                    <th>Reference No</th>
                                    <th>Branch</th>
                                    <th>Approval ID</th>
                                    <th>Transaction / Date Requested</th>
                                    <th>APRB No.</th>
                                    <th>Client</th>
                                    <th>Pension No</th>
                                    <th>Account Type</th>
                                    <th>Card No. & Bank</th>
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

    <script>
        $(document).ready(function () {
            var FetchingDatatableBody = $('#FetchingDatatable tbody');

            const dataTable = new ServerSideDataTable('#FetchingDatatable');
            var url = '{!! route('TransactionReleasingData') !!}';
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
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id', // This matches the name you used in your server-side code
                    render: function(data, type, row) {
                        return '<span class="fw-bold h6 text-primary">' + data + '</span>';
                    },
                    orderable: false,
                    searchable: false,
                },


            ];
            dataTable.initialize(url, columns);
        });
    </script>

@endsection

