<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('settings.dashboard.page') }}" class="waves-effect">
                        <i class="fas fa-desktop text-info"></i>
                        <span key="settings-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.company.page') }}" class="waves-effect">
                        <i class="fas fa-globe-americas text-success"></i>
                        <span key="settings-company">Company</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.page') }}" class="waves-effect">
                        <i class="fas fa-users text-info"></i>
                        <span key="settings-user">Users</span>
                    </a>
                </li>



                <li class="mm-active">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-cog text-primary" aria-hidden="true"></i>
                        <span key="settings">ATM Settings</span>
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
                    </ul>
                </li>


                <li>
                    <a href="{{ route('settings.departments.page') }}" class="waves-effect">
                        <i class="fas fa-building text-primary"></i>
                        <span key="sub-districts">Departments</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.district.page') }}" class="waves-effect">
                        <i class="fas fa-map-marked fs-5" style="color: #68FFFF;"></i>
                        <span key="sub-districts">Districts</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.area.page') }}" class="waves-effect">
                        <i class="fas fa-map-marker-alt fs-5 text-info"></i>
                        <span key="sub-area">Areas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.branch.page') }}" class="waves-effect">
                        <i class="fas fa-code-branch fs-5" style="color: #E684FF;"></i>
                        <span key="sub-branches">Branches</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.users.group.page') }}" class="waves-effect">
                        <i class="fas fa-users fs-5" style="color: #808000;"></i>
                        <span key="sub-user-group">User Group</span>
                    </a>
                </li>

                <li>
                    <a href="#}" class="waves-effect">
                        <i class="fas fa-print fs-5 text-success"></i>
                        <span key="sub-documents">Documents</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.maintenance.page') }}" class="waves-effect">
                        <i class="fas fa-tools fs-5 text-danger "></i>
                        <span key="sub-maintenance">Maintenance</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('system.announcement.pages') }}" class="waves-effect">
                        <i class="fas fa-bullhorn text-primary"></i>
                        <span key="sub-maintenance">Announcement</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('system.logs.pages') }}" class="waves-effect">
                        <i class="fa fa-history text-info"></i>
                        <span key="sub-maintenance">System Logs</span>
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
