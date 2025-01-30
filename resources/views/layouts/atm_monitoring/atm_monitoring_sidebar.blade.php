<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('elog_monitoring_dashboard') }}" class="waves-effect">
                        <i class="fas fa-desktop text-info"></i>
                        <span key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-print text-success" aria-hidden="true"></i>
                        <span key="document">Document Transaction</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="#" key="document_creating_transaction">
                                <i class="fas fa-map-marked fs-6" style="color: #68FFFF;"></i>Creating Transaction</a>
                        </li>
                        <li>
                            <a href="#" key="document_on_going_transaction">
                                <i class="fas fa-map-marker-alt fs-6 text-info"></i>On Going <br>Transaction
                            </a>
                        </li>
                        <li>
                            <a href="#" key="document_completed_transaction">
                                <i class="fas fa-code-branch fs-6" style="color: #E684FF;"></i>Completed <br>Transaction
                            </a>
                        </li>
                        <li>
                            <a href="#" key="document_pending_transaction">
                                <i class="fas fa-users fs-6" style="color: #808000;"></i>Pending Transaction</a>
                        </li>
                        <li>
                            <a href="#" key="document_received">
                                <i class="fas fa-print fs-6 text-success fs-6"></i>Received Document</a>
                        </li>
                        <li>
                            <a href="#" key="document_received_completed">
                                <i class="fas fa-print fs-6 text-success fs-6"></i>Received <br> Completed Document</a>
                        </li>
                        <li>
                            <a href="#" key="document_return_to_ho">
                                <i class="fas fa-print fs-6 text-success fs-6"></i>Return Document <br> From HO</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-folder-open text-success"></i>
                        <span key="atm_reports">ATM Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="#" key="atm_reports_daily_report">
                                <i class="fas fa-print fs-6 text-success"></i>Daily Report
                            </a>
                        </li>
                        <li>
                            <a href="#" key="atm_reports_released_report">
                                <i class="fas fa-print fs-6 text-success"></i>Released Report
                            </a>
                        </li>
                        <li>
                            <a href="#" key="atm_reports_cancelled_transaction_logs">
                                <i class="fas fa-print fs-6 text-success"></i>Cancelled <br> Transaction Logs</a>
                        </li>
                        <li>
                            <a href="#" key="atm_reports_unreturn_yellow_paper">
                                <i class="fas fa-print fs-6 text-success"></i>Unreturn Yellow <br> Paper</a>
                        </li>
                        <li>
                            <a href="#" key="atm_reports_aprb_report">
                                <i class="fas fa-print fs-6 text-success"></i>APRB Report</a>
                        </li>

                        <li>
                            <a href="#" key="atm_reports_cash_box_report">
                                <i class="fas fa-box fs-6 text-warning"></i>Cash Box No. Report
                            </a>
                        </li>



                    </ul>
                </li>


                <li class="mm-active">
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="far fa-credit-card" style="color: #3787FD;"></i>
                        <span key="atm_monitoring">ATM Monitoring</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('clients.page') }}" key="atm_clients">
                                <span class="badge rounded-pill bg-success float-start me-2 fw-bold text-dark" id="ClientInformationCount"></span>
                                Clients
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('HeadOfficePage') }}" key="atm_ho_lists">
                                <span class="badge rounded-pill bg-success float-start me-2 fw-bold text-dark" id="HeadOfficeCount"></span>
                                H.O. ATM Lists
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('BranchOfficePage') }}" key="atm_branch_lists">
                                <span class="badge rounded-pill bg-primary float-start me-2" id="BranchOfficeCount"></span>
                                Branch ATM Lists
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('TransactionReceivingPage') }}" key="atm_receiving_transaction">
                                <span class="badge rounded-pill bg-info float-start me-2" id="PendingReceivingTransactionCount"></span>
                                Receiving of Transaction
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('TransactionReleasingPage') }}" key="atm_releasing_transaction">
                                <span class="badge rounded-pill bg-danger float-start me-2" id="PendingReleasingTransactionCount"></span>
                                Releasing of Transaction
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('TransactionPage') }}" key="atm_branch_transaction">
                                <span class="badge rounded-pill float-start me-2 text-white"  style="background: #000000;" id="OnGoingTransactionCount"></span>
                                Branch Transaction
                            </a>
                        </li>
                        <li>
                            <a href="{{  route('ReleasedPage') }}" key="atm_released_atm">
                                <span class="badge rounded-pill float-start me-2 text-dark" style="background: #00CC99;" id="ReleasedCount"></span>
                                Released ATM
                            </a>
                        </li>
                        <li>
                            <a href="{{  route('CancelledLoanPage') }}" key="atm_released_atm">
                                <span class="badge rounded-pill bg-danger float-start me-2">0</span>
                                Cancelled Loan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('SafekeepPage') }}" key="atm_safekeep_atm">
                                <span class="badge rounded-pill float-start me-2" style="background: #00008B;" id="SafekeepCount"></span>
                                Safekeep ATM
                            </a>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow"
                                key="t-sub-dropdown1"><i class="fas fa-chalkboard-teacher text-info fs-6"></i> Users Transaction</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="#" key="all_users_transaction">
                                        <i class="fas fa-chalkboard-teacher text-info fs-6"></i>All Users Transaction
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="all_on_process_transaction">
                                        <i class="fas fa-spinner text-success fs-6"></i>All On Process Transaction
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="all_users_completed_transaction">
                                        <i class="fas fa-check text-success fs-6"></i> All Users Completed Transaction</a>
                                </li>
                            </ul>
                        </li>

                        <li class="mm-active">
                            <a href="javascript: void(0);" class="has-arrow"
                                key="pb_for_collection"><i class="fas fa-book" style="color: #cc2424;"></i>PB For Collection</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="{{ route('PassbookCollectionSetUpPage') }}" key="pb_setup">
                                        <i class="fas fa-plus-circle fs-6 text-primary"></i>SETUP
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('PassbookCollectionReceivingPage') }}" key="pb_for_receiving">
                                        <i class="fas fa-undo fs-6 text-success"></i>
                                        For Receiving
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('PassbookCollectionReleasingPage') }}" key="pb_for_releasing">
                                        <i class="fas fa-redo fs-6 text-danger"></i>
                                        For Releasing
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('PassbookCollectionReturningPage') }}" key="pb_for_returning">
                                        <i class="fas fa-sync fs-6 text-warning"></i>
                                        For Returning
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('PassbookCollectionTransactionPage') }}" key="pb_transaction">
                                        <i class="fas fa-desktop fs-6 text-success"></i>
                                        PB Transaction
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('PassbookCollectionAllTransactionPage') }}" key="pb_all_transaction">
                                        <i class="fas fa-desktop fs-6 text-info"></i>
                                        PB All Transaction
                                    </a>
                                </li>


                            </ul>
                        </li>


                        <li>
                            <a href="javascript: void(0);" class="has-arrow"
                                key="outside_for_collection">
                                <i class="fas fa-user-cog text-info"></i> Outside For Collection</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li>
                                    <a href="#" key="pb_setup">
                                        <i class="fas fa-plus-circle fs-6 text-primary"></i>SETUP
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="outside_for_receiving">
                                        <i class="fas fa-undo fs-6 text-success"></i>
                                        For Receiving
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="outside_for_releasing">
                                        <i class="fas fa-redo fs-6 text-danger"></i>
                                        For Releasing
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="outside_for_returning">
                                        <i class="fas fa-sync fs-6 text-warning"></i>
                                        For Returning
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="outside_transaction">
                                        <i class="fas fa-desktop fs-6 text-success"></i>
                                        PB Transaction
                                    </a>
                                </li>
                                <li>
                                    <a href="#" key="outside_all_transaction">
                                        <i class="fas fa-desktop fs-6 text-info"></i>
                                        PB All Transaction
                                    </a>
                                </li>


                            </ul>
                        </li>

                        <li>
                            <a href="#" key="atm_released_atm">
                                <i class="fas fa-undo-alt fs-6 text-warning"></i>
                                Going to Head <br>Office ( ELOG )
                            </a>
                        </li>

                        <li>
                            <a href="#" key="atm_released_atm">
                                <i class="fas fa-redo fs-6 text-info"></i>
                                Going Back to <br>Branch ( ELOG )
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('elog_monitoring_dashboard') }}" class="waves-effect">
                        <i class="far fa-question-circle text-warning"></i>
                        <span key="how_it_works">How it Works ?</span>
                    </a>
                </li>

                {{-- Icons Used
                Box Icons
                Materail Icons
                Drip Icons
                Font Awesome --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

<script>
    $(document).ready(function () {
        const fetchCounts = () => {
            $.ajax({
                url: "/SidebarCount",  // Your route to get the counts
                type: "GET",
                cache: false,  // Disable AJAX caching
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',  // Prevent caching
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                success: function(response) {
                    // Format the numbers with comma as thousand separator
                    const formatNumber = (number) => {
                        return number.toLocaleString(); // Formats the number with commas (e.g., 1,000)
                    };
                    $('#ClientInformationCount').text(formatNumber(response.ClientInformationCount));
                    $('#HeadOfficeCount').text(formatNumber(response.HeadOfficeCounts));
                    $('#BranchOfficeCount').text(formatNumber(response.BranchOfficeCounts));
                    $('#ReleasedCount').text(formatNumber(response.ReleasedCounts));
                    $('#SafekeepCount').text(formatNumber(response.SafekeepCounts));
                    $('#OnGoingTransactionCount').text(formatNumber(response.OnGoingTransaction));
                    $('#PendingReceivingTransactionCount').text(formatNumber(response.PendingReceivingTransaction));
                    $('#PendingReleasingTransactionCount').text(formatNumber(response.PendingReleasingTransaction));
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching counts:", error);
                }
            });
        };

        // Initial fetch
        fetchCounts();

        // Set interval to refresh every 5 seconds
        setInterval(fetchCounts, 5000); // 5000 milliseconds = 5 seconds
    });



</script>
<!-- Left Sidebar End -->
