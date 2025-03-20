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
                        <i class="far fa-question-circle text-warning"></i>
                        <span key="settings-dashboard">How It Works</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="waves-effect">
                        <i class="fas fa-desktop text-info"></i>
                        <span key="settings-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="mm-active">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="far fa-folder-open text-success" aria-hidden="true"></i>
                        <span key="documents">Documents Monitoring</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="#" key="documents_transaction">
                                <i class="fas fa-file-invoice text-primary"></i> My Transaction
                            </a>
                        </li>
                        <li>
                            <a href="#" key="documents_for_receiving">
                                <i class="fas fa-file-upload text-warning"></i> For Receiving
                            </a>
                        </li>
                        <li>
                            <a href="#" key="documents_already_received">
                                <i class="fas fa-file-signature text-success"></i> My Received
                            </a>
                        </li>
                        <li>
                            <a href="#" key="documents_already_received">
                                <i class="far fa-file-alt text-info"></i> All Transaction
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="waves-effect">
                        <i class="fas fa-file-alt"></i>
                        <span key="sub-districts">List of All Documents</span>
                    </a>
                </li>
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
