<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="#" class="waves-effect">
                        <i class="fas fa-desktop text-info"></i>
                        <span key="settings-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.page') }}" class="waves-effect">
                        <i class="fas fa-users text-info"></i>
                        <span key="settings-user">Users</span>
                    </a>
                </li>

                <li class="menu-title" key="t-ef-main">System Management</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="far fa-credit-card text-success me-1" aria-hidden="true"></i>
                        <span key="settings_atm">ELOG Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('settings.bank.page') }}" key="settings_atm_bank_lists">
                                <i class="fas fa-university fs-6 text-primary"></i> Banks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.pension.types.page') }}" key="settings_atm_pension_types">
                                <i class="fas fa-stream fs-6 text-success"></i> Pension Types</a>
                        </li>
                        <li>
                            <a href="{{ route('settings.release.reason.page') }}" key="settings_atm_release_reason">
                                <i class="fas fa-stream fs-6 text-success"></i>
                                Release Reason
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.borrow.reason.page') }}" key="settings_atm_borrow_reason">
                                <i class="fas fa-stream fs-6 text-success"></i>
                                Borrow Reason
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.transaction.action.page') }}" key="settings_atm_transaction_action">
                                <i class="fas fa-location-arrow fs-6 text-primary"></i>
                                Transaction Action
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.collection.date.page') }}" key="settings_atm_collection_date">
                                <i class="fas fa-hands fs-6 text-info"></i>
                                Collection Date
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.documents.action.page') }}" key="documents_settings_action">
                                <i class="fas fa-file-alt fs-6 text-primary"></i> Documents Action
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li>
                    <a href="{{ route('settings.system.logs.page') }}" class="waves-effect">
                        <i class="fa fa-history text-info"></i>
                        <span key="sub-maintenance">System Logs</span>
                    </a>
                </li> --}}







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
        $.ajax({
            url: "/SidebarCount",  // Your route to get the counts
            type: "GET",
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
    });

</script>
<!-- Left Sidebar End -->
