@extends('layouts.atm_monitoring.atm_monitoring_master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboards @endslot
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
                            <p class="mb-0">E-LOG Dashboard</p>
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
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-3">
                            <img src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('images/no_image.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-truncate">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="mb-0 text-truncate text-danger ms-2">{{ Auth::user()->UserGroup->group_name }}</p>
                    </div>

                    <div class="col-sm-8">
                        <div class="pt-4">

                            <div class="row">
                                <div class="col-6">
                                    <h5 class="font-size-15 fw-bold">125</h5>
                                    <p class="text-muted mb-0">Pending Transactions</p>
                                </div>

                            </div>
                            <div class="mt-4">
                                <a href="{{ route('users.profile', Auth::user()->employee_id ) }}" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
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
        <!-- end row -->

        {{-- <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Email Sent</h4>
                    <div class="ms-auto">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Year</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div> --}}
    </div>
</div>
<!-- end row -->

<div class="row align-items-stretch">
    <!-- Left Column: Top Branch Most Clients -->
    <div class="col-xl-4 d-flex">
        <div class="card shadow flex-fill">
            <div class="card-body">
                <h4 class="card-title mb-4 mt-2">Top Branch Most Clients</h4>
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-code-branch display-4" style="color: #E684FF;"></i>
                    </div>
                    <!-- Placeholder for Top 1 Branch -->
                    <h3 id="TopBranchClientCount">0</h3>
                    <p id="TopBranchName">N/A</p>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table align-middle table-nowrap">
                        <tbody id="TopBranchesTableBody">
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Email Sent -->
    <div class="col-xl-8 d-flex">
        <div class="card shadow flex-fill">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4 mt-2">Clients Graphs</h4>
                    <div class="ms-auto">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <select id="yearDropdownClient" class="form-control border border-primary ps-4 pe-4">
                                    <option value="" disabled selected>Select Year</option>
                                </select>
                            </li>
                        </ul>
                    </div>

                </div>
                <div id="client_all_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 d-flex">
        <div class="card shadow flex-fill">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4 mt-2">ATM's, Passbooks, Simcards Graphs</h4>
                    <div class="ms-auto">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <select id="yearDropdownAtm" class="form-control border border-primary ps-4 pe-4">
                                    <option value="" disabled selected>Select Year</option>
                                </select>
                            </li>
                        </ul>
                    </div>

                </div>
                <div id="atms_all_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 d-flex">
        <div class="card shadow flex-fill">
            <div class="card-body">
                <h4 class="card-title mb-5 mt-2">Pending Transactions</h4>

                <ul class="verti-timeline list-unstyled" id="displayPendingTransaction">
                    <!-- Pending transactions will be appended here -->
                </ul>

                <div class="text-center mt-4">
                    <a href="{{ route('TransactionPage') }}" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">On Going Transaction</h4>
                <hr>
                <form id="filterForm">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="fw-bold h6">Branch</label>
                                <select name="branch_id" id="branch_id_select" class="form-select select2">
                                    <option value="">Select Branches</option>
                                    @foreach($Branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $branch_id ? 'selected' : '' }}>
                                            {{ $branch->branch_location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
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
                                <select name="status" id="status_select" class="form-select" disabled>
                                    <option value="">Select Status</option>
                                    <option value="ON GOING" selected>ON GOING</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="margin-top: 25px;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table id="FetchingDatatable" class="table table-border dt-responsive wrap table-design" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Transaction / Pending By</th>
                                <th>Transaction Number</th>
                                <th>Client</th>
                                <th>Pension No. / Type</th>
                                <th>Card No & Bank</th>
                                <th>Type</th>
                                <th>PIN Code</th>
                                <th>Collection Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!-- end table-responsive -->
            </div>
        </div>
    </div>
</div>
<!-- end row -->

    <!-- Transaction Modal -->
    <div class="modal fade" id="viewTransactionModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 75%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">View Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fw-bold">
                                Transaction Number : <span class="text-primary" id="view_transaction_number"></span>
                            </div>
                            <div class="fw-bold">
                                Transaction : <span class="text-primary" id="view_transaction_action"></span>
                            </div>
                            <div class="fw-bold">
                                Date Requested : <span class="text-primary" id="view_created_date"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fw-bold">
                                Name : <span class="text-primary" id="view_fullname"></span>
                            </div>
                            <div class="fw-bold">
                                Pension Number : <span class="text-primary" id="view_pension_number_display"></span> /
                                <span id="view_pension_account_type_display" class="fw-bold h6"></span> /
                                <span id="view_pension_type_display" class="fw-bold h6"></span>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive mt-3">
                        <table class="table table-design">
                            <thead>
                                <th>ID</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Sequence</th>
                                <th>Balance Amount</th>
                                <th>Remarks</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Date Received</th>
                            </thead>
                            <tbody id="TransactionApprovalBody">

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
    <!-- end modal -->

<script>
// Selection Year for ATMs Passbooks and Simcards
    $(document).ready(function () {
        var startYearAtm = 2000; // Starting year
        var endYearAtm = new Date().getFullYear(); // Current year

        // Populate the dropdown with years and set the current year as selected
        for (var year = startYearAtm; year <= endYearAtm; year++) {
            $('#yearDropdownAtm').append(`<option value="${year}" ${year === endYearAtm ? "selected" : ""}>${year}</option>`);
        }

        var chart;
        fetchDataForYearATM(null);

        $('#yearDropdownAtm').change(function () {
            var selectedYear = $(this).val(); // Get the selected year
            fetchDataForYearATM(selectedYear); // Fetch data for the selected year
        });

        // Function to make the AJAX request
        function fetchDataForYearATM(year) {
            $.ajax({
                url: "/elog_monitoring_dashboard_data", // Your route to handle the request
                type: "GET",
                data: year ? { yearAtm: year } : {}, // Send yearAtm if year is provided
                success: function (response) {
                    // Destroy the previous chart instance if it exists
                    if (chart) {
                        chart.destroy();
                    }

                    // ATM's Passbooks and Sim Cards Graph
                    var atm_year = response.AtmClientBanksCounts[0] ? response.AtmClientBanksCounts[0].year : ''; // Extract the year from the first item
                    var atm_months = response.AtmClientBanksCounts.map(function(item) {
                        // Format the month and year together (e.g., 'Jan 2024')
                        return new Date(atm_year, item.month - 1).toLocaleString('default', { month: 'short' }) + ' ' + atm_year;
                    });

                    var atm_counts = response.AtmClientBanksCounts.map(function(item) {
                        return {
                            atm_count: item.atm_count,
                            passbook_count: item.passbook_count,
                            sim_card_count: item.sim_card_count
                        };
                    });

                    var atm_all_options = {
                        chart: {
                            type: 'area', // Change this to 'bar' if you prefer a bar chart
                            height: 350
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        series: [{
                            name: 'ATMs',
                            data: atm_counts.map(function(item) { return item.atm_count; }), // Data for ATM count
                            color: '#4e73df' // Define the color for ATM count
                        },
                        {
                            name: 'Passbooks',
                            data: atm_counts.map(function(item) { return item.passbook_count; }), // Data for Passbook count
                            color: '#F32F53' // Define the color for Passbook count
                        },
                        {
                            name: 'Sim Cards',
                            data: atm_counts.map(function(item) { return item.sim_card_count; }), // Data for Sim Card count
                            color: '#50B9F6' // Define the color for Sim Card count
                        }],
                        xaxis: {
                            categories: atm_months, // Dynamic month-year labels (e.g., 'Jan 2024')
                            title: {
                                text: 'Month'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Number of Clients'
                            }
                        },
                        title: {
                            text: `ATMs, Passbooks, and Sim Cards - ${atm_year}`, // Display the year dynamically
                            align: 'center',
                            style: {
                                color: '#FFFFFF' // Set the title color to white
                            }
                        }
                    };

                    // Initialize the chart with updated data
                    chart = new ApexCharts(document.querySelector("#atms_all_chart"), atm_all_options);
                    chart.render();
                    // ATM's Passbooks and Sim Cards Graph
                },
                error: function (error) {
                    console.error("Error:", error);
                }
            });
        }
    });

    // Selection Year for Clients
    $(document).ready(function () {
        var startYear = 2000; // Starting year
        var endYear = new Date().getFullYear(); // Current year

        // Populate the dropdown with years and set the current year as selected
        for (var year = startYear; year <= endYear; year++) {
            $('#yearDropdownClient').append(`<option value="${year}" ${year === endYear ? "selected" : ""}>${year}</option>`);
        }

        var chart; // Declare a global variable to hold the chart instance

        // Fetch data for all years initially
        fetchDataForYear(null); // Pass `null` or omit `yearClient` for all years

        // Add event listener for year selection
        $('#yearDropdownClient').change(function () {
            var selectedYear = $(this).val(); // Get the selected year
            fetchDataForYear(selectedYear); // Fetch data for the selected year
        });

        // Function to make the AJAX request
        function fetchDataForYear(year) {
            $.ajax({
                url: "/elog_monitoring_dashboard_data", // Your route to handle the request
                type: "GET",
                data: year ? { yearClient: year } : {}, // Send yearClient if year is provided
                success: function (response) {
                    // Destroy the previous chart instance if it exists
                    if (chart) {
                        chart.destroy();
                    }

                    // Process response data
                    var months = response.ClientCounts.length
                        ? response.ClientCounts.map(function (item) {
                            // Format the month and year for each entry
                            return new Date(item.year, item.month - 1).toLocaleString('default', { month: 'short' }) + ' ' + item.year;
                        })
                        : ['No Data'];

                    var clientCounts = response.ClientCounts.length
                        ? response.ClientCounts.map(function (item) {
                            return item.client_monthly_counts;
                        })
                        : [0];

                    // Define chart options
                    var client_all_options = {
                        chart: {
                            type: 'area', // Change to 'bar' if preferred
                            height: 350
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        series: [{
                            name: 'Client Counts',
                            data: clientCounts, // Data for each month
                            color: '#1129DE' // Define the color here
                        }],
                        xaxis: {
                            categories: months, // Dynamic month names with the correct year for each entry
                            title: {
                                text: 'Month'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Number of Clients'
                            }
                        },
                        title: {
                            text: `MONTHLY CLIENTS`, // Display the year or "ALL YEARS"
                            align: 'center',
                            style: {
                                color: '#FFFFFF' // Set the title color to white
                            }
                        }
                    };

                    // Initialize the chart with updated data
                    chart = new ApexCharts(document.querySelector("#client_all_chart"), client_all_options);
                    chart.render();
                },
                error: function (error) {
                    console.error("Error:", error);
                }
            });
        }
    });

    $(document).ready(function () {
        $.ajax({
            url: "/elog_monitoring_dashboard_data",  // Your route to get the counts
            type: "GET",
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

                // Handle Top Branches Count
                    const topBranches = response.TopBranchesCount ?? [];
                        if (topBranches.length > 0) {
                            // Update the top branch card (Top 1)
                            $('#TopBranchName').text(topBranches[0]?.branch?.branch_location ?? "N/A");
                            $('#TopBranchClientCount').text(formatNumber(topBranches[0]?.client_count ?? 0));

                            // Update the table rows dynamically
                            const tableBody = $('#TopBranchesTableBody'); // Target the <tbody> element
                            tableBody.empty(); // Clear existing rows

                            topBranches.forEach((branch, index) => {
                                const progressBarColor = index === 0 ? 'bg-primary' :
                                                        index === 1 ? 'bg-success' :
                                                        index === 2 ? 'bg-warning' : 'bg-secondary';

                                const progressPercentage = topBranches[0]?.client_count ? (branch.client_count / topBranches[0].client_count) * 100 : 0;

                                // Add a row for each branch
                                tableBody.append(`
                                    <tr>
                                        <td style="width: 30%">
                                            <p class="mb-0">${branch.branch?.branch_location ?? "N/A"}</p>
                                        </td>
                                        <td style="width: 25%">
                                            <h5 class="mb-0">${formatNumber(branch.client_count)}</h5>
                                        </td>
                                        <td>
                                            <div class="progress bg-transparent progress-sm">
                                                <div class="progress-bar ${progressBarColor} rounded" role="progressbar"
                                                    style="width: ${progressPercentage}%"
                                                    aria-valuenow="${branch.client_count}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="${topBranches[0]?.client_count}">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            // Handle case where no branches are found
                            $('#TopBranchName').text("No Branches Found");
                            $('#TopBranchClientCount').text("0");

                            $('#TopBranchesTableBody').html(`
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                            `);
                        }
                // Handle Top Branches Count

                response.PendingReceivingTransaction.forEach(function (rows) {
                    // Create a date object from the created_at string
                    var dateRequest = new Date(rows.atm_banks_transaction.created_at);
                    // Format to "day, month"
                    var formattedDate = dateRequest.toLocaleDateString('en-US', {
                        day: '2-digit',
                        month: 'long'
                    });

                    var transactionAction = rows.atm_banks_transaction.data_transaction_action.name;
                    var branchLocation = rows.atm_banks_transaction.branch.branch_location;
                    var transactionNumber = rows.atm_banks_transaction.transaction_number;

                    // Construct the transaction list item HTML
                    var displayTransaction = `<li class="event-list active">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bxs-right-arrow-circle font-size-18 bx-fade-right"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">${formattedDate}
                                                            <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                                        </h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            <span class="fw-bold h6 text-danger">${transactionAction} </span> /
                                                            <span class="fw-bold h6">${transactionNumber }</span> /
                                                            <span class="text-primary">${branchLocation}</span></div>
                                                    </div>
                                                </div>
                                            </li>`;

                    // Append the transaction to the list
                    $('#displayPendingTransaction').append(displayTransaction);
                });


            },
            error: function(xhr, status, error) {
                console.error("Error fetching counts:", error);
            }
        });
    });

    $(document).ready(function () {
        var FetchingDatatableBody = $('#FetchingDatatable tbody');

        const dataTable = new ServerSideDataTable('#FetchingDatatable');
        var url = '{!! route('elog_monitoring_transaction_data') !!}';
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
                name: 'action',
                render: function(data, type, row, meta) {
                    return data;
                },
                orderable: true,
                searchable: true,
            },
            {
                data: 'pending_to',
                name: 'pending_to',
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
                data: 'client_banks_id',
                name: 'client_banks_id',
                render: function(data, type, row, meta) {
                    let branchLocation = '';
                    let clientName = `${row.full_name}`;

                    // Get branch location
                    if (row.branch && row.branch.branch_location) {
                        branchLocation = row.branch.branch_location;
                    }

                    // Return client name and branch location separated by <br>
                    return `<span>${clientName}</span><br><span class="text-primary">${branchLocation}</span>`;
                },
                orderable: true,
                searchable: true,
            },
            {
                data: 'pension_details',
                name: 'pension_details',
                render: function(data, type, row, meta) {
                    return '<span>' + data + '</span>';
                },
                orderable: true,
                searchable: true,
            },
            {
                data: 'bank_account_no',
                name: 'bank_account_no',
                render: function(data, type, row, meta) {
                    // Initialize the variable for replacement count
                    let replacementCountDisplay = '';
                    if (row.atm_client_banks && row.atm_client_banks.bank_name) {
                        BankName = row.atm_client_banks.bank_name;
                    }
                    // Check if replacement_count is greater than 0
                    if (row.replacement_count > 0) {
                    replacementCountDisplay = `<span class="text-danger fw-bold h6"> / ${row.replacement_count}</span>`;
                    }

                    return `<span class="fw-bold h6" style="color: #5AAD5D;">${row.bank_account_no}</span>
                            ${replacementCountDisplay}<br>
                            <span>${BankName}</span>`;

                },
                orderable: true,
                searchable: true,
            },

            {
                data: 'atm_type',
                name: 'atm_type',
                render: function(data, type, row, meta) {
                    let BankStatus = ''; // Define BankStatus outside the if block with a default value
                    let atmTypeClass = ''; // Variable to hold the class based on atm_type

                    // Determine the BankStatus if it exists
                    if (row.atm_client_banks && row.atm_client_banks.atm_status) {
                        BankStatus = row.atm_client_banks.atm_status;
                    }

                    // Determine the text color based on atm_type
                    switch (row.atm_type) {
                        case 'ATM':
                            atmTypeClass = 'text-primary';
                            break;
                        case 'Passbook':
                            atmTypeClass = 'text-danger';
                            break;
                        case 'Sim Card':
                            atmTypeClass = 'text-info';
                            break;
                        default:
                            atmTypeClass = 'text-secondary'; // Default color if none match
                    }

                    return `<span class="${atmTypeClass}">${row.atm_type}</span><br>
                            <span class="fw-bold h6">${BankStatus}</span>`;
                },
                orderable: true,
                searchable: true,
            },

            {
                data: 'client_banks_id',
                name: '',
                render: function(data, type, row) {
                    let PinCode = '';
                    let BankAccountNo = '';

                    // Check if atm_type is not "ATM" and pin_no exists
                    if (row.atm_client_banks && row.atm_client_banks.pin_no && row.atm_type == 'ATM') {
                        PinCode = row.atm_client_banks.pin_no;
                        BankAccountNo = row.atm_client_banks.bank_account_no;

                        // Return the eye icon with the data attributes
                        return `<a href="#" class="text-info fs-4 view_pin_code"
                                    data-pin="${PinCode}"
                                    data-bank_account_no="${BankAccountNo}">
                                    <i class="fas fa-eye"></i>
                                </a><br>`;
                    }

                    // If conditions are not met, return an empty string
                    return 'No Pin Code Detected';
                },
                orderable: true,
                searchable: true,
            },

            {
                data: 'client_banks_id',
                name: '',
                render: function(data, type, row, meta) {
                    let CollectionDate = ''; // Define CollectionDate outside the if block with a default value

                    if (row.atm_client_banks && row.atm_client_banks.collection_date) {
                        CollectionDate = row.atm_client_banks.collection_date;
                    }

                    return `<span>${CollectionDate}</span>`;
                },
                orderable: true,
                searchable: true,
            },

            {
                data: 'status',
                name: 'status',
                render: function(data, type, row, meta) {
                    let badgeClass = '';
                    let statusClass = '';

                    // Determine the badge class and status label based on status value
                    switch (row.status) {
                        case 'ON GOING':
                            badgeClass = 'pt-1 pb-1 ps-2 ps-2 pe-2 badge bg-warning fw-bold h6';
                            statusClass = 'On Going';
                            break;
                        case 'CANCELLED':
                            badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-danger fw-bold h6';
                            statusClass = 'Cancelled';
                            break;
                        case 'COMPLETED':
                            badgeClass = 'pt-1 pb-1 ps-2 pe-2 badge bg-success fw-bold h6';
                            statusClass = 'Completed';
                            break;
                        default:
                            badgeClass = 'badge bg-secondary fw-bold h6'; // Default badge class
                            statusClass = 'Unknown Status';
                    }

                    // Return the status wrapped in a span with the appropriate badge and status class
                    return `<span class="${badgeClass} fw-bold h6">${statusClass}</span>`;
                },
                orderable: true,
                searchable: true,
            },

        ];
        dataTable.initialize(url, columns);

        // Filtering of Transaction
            var branchId = @json($branch_id);
            var userHasBranchId = {!! Auth::user()->branch_id ? 'true' : 'false' !!};

            if (userHasBranchId) {
                $('#branch_id_select').val(branchId).prop('disabled', true);
            }

            $('#filterForm').submit(function(e) {
                e.preventDefault();

                // Get selected filter values
                var selectedBranch = $('#branch_id_select').val();
                var selectedTransaction = $('#transaction_id_select').val();

                // Construct the URL with required query parameters
                var targetUrl = '{!! route('elog_monitoring_transaction_data') !!}';
                targetUrl += '?transaction_actions_id=' + selectedTransaction;

                // If the user does not have a branch ID, add the branch_id parameter
                if (!userHasBranchId && selectedBranch) {
                    targetUrl += '&branch_id=' + selectedBranch;
                }

                // Update the DataTable with the filtered data
                dataTable.table.ajax.url(targetUrl).load();
            });
        // Filtering of Transaction

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

        $('#FetchingDatatable').on('click', '.viewTransaction', function(e) {
            e.preventDefault();
            var transaction_id = $(this).data('id');

            $.ajax({
                url: "/TransactionGet",
                type: "GET",
                data: { transaction_id : transaction_id },
                success: function(data) {
                    $('#view_fullname').text(data.atm_client_banks.client_information.last_name +', '
                                                + data.atm_client_banks.client_information.first_name +' '
                                                +(data.atm_client_banks.client_information.middle_name ?? '') +' '
                                                + (data.atm_client_banks.client_information.suffix ?? ''));

                    $('#view_pension_number_display').text(data.atm_client_banks.client_information.pension_number ?? '');
                    $('#view_pension_number_display').inputmask("99-9999999-99");
                    $('#view_pension_type_display').text(data.atm_client_banks.client_information.pension_type);
                    $('#view_pension_account_type_display').text(data.atm_client_banks.client_information.pension_account_type);

                    let formattedCreatedDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '';

                    $('#view_transaction_number').text(data.transaction_number);
                    $('#view_created_date').text(formattedCreatedDate);
                    $('#view_transaction_action').text(data.data_transaction_action.name);


                    $('#TransactionApprovalBody').empty();

                    data.atm_banks_transaction_approval.forEach(function (rows) {
                        var employee_id = rows.employee_id !== null ? rows.employee_id : '';
                        var employee_name = rows.employee && rows.employee.name ? rows.employee.name : '';

                        // Load Inputmask if not already included in your environment
                        if (typeof Inputmask !== 'undefined') {
                            var balance = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.balance !== null
                                ? rows.atm_transaction_approvals_balance_logs.balance
                                : '';

                            // Apply Inputmask to format the balance if it's not empty
                            if (balance !== '') {
                                balance = Inputmask.format(balance, {
                                    alias: 'numeric',
                                    prefix: '₱ ',
                                    groupSeparator: ',',
                                    autoGroup: true,
                                    digits: 2,
                                    digitsOptional: false,
                                    placeholder: '0'
                                });
                            }
                        }

                        var remarks = rows.atm_transaction_approvals_balance_logs && rows.atm_transaction_approvals_balance_logs.remarks !== null
                                    ? rows.atm_transaction_approvals_balance_logs.remarks
                                    : '';

                        var badgeClass = '';
                        var StatusName = '';

                        switch (rows.status) {
                            case 'Completed':
                            case 'Others Account':
                                badgeClass = 'badge bg-success';
                                StatusName = 'Completed';
                                break;
                            case 'Pending':
                                badgeClass = 'badge bg-warning';
                                StatusName = 'Pending';
                                break;
                            case 'Stand By':
                                badgeClass = 'badge bg-primary';
                                StatusName = 'Stand By';
                                break;
                            case 'Cancelled':
                                badgeClass = 'badge bg-danger';
                                StatusName = 'Cancelled';
                                break;
                            default:
                                badgeClass = 'badge bg-secondary'; // Default badge in case of unexpected status
                                StatusName = status;
                                break;
                        }

                        // Format the date_approved if it's not null
                        var dateApproved = rows.date_approved ? new Date(rows.date_approved).toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        }) : ''; // Leave blank if null

                        // Construct the new row with the status badge
                        var newRow = '<tr>' +
                            '<td>' + rows.id + '</td>' +
                            '<td>' + employee_id + '</td>' +
                            '<td>' + employee_name + '</td>' +
                            '<td>' + rows.data_user_group.group_name + '</td>' +
                            '<td>' + rows.sequence_no + '</td>' +
                            '<td>' + balance + '</td>' +
                            '<td>' + remarks + '</td>' +
                            '<td>' + '' + '</td>' +
                            '<td><span class="' + badgeClass + '">' + StatusName + '</span></td>' + // Display status with badge
                            '<td>' + dateApproved + '</td>' +
                            '</tr>';


                        $('#TransactionApprovalBody').append(newRow);
                    });

                    $('#viewTransactionModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + error);
                }
            });
        });
    });
</script>

@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
@endsection
