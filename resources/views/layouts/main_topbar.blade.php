<header id="page-topbar">
    <div class="navbar-header ms-5 me-5">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box text-start">
                <a href="{{ route('main_dashboard') }}" class="logo logo-dark text-start">
                    <span>
                        <img src="{{ URL::asset('/assets/images/favicon.ico') }}" alt="" height="40" class="me-2">
                        <span class="fw-bold h5 text-dark">EVERFIRST </span>
                    </span>
                </a>

                <a href="{{ route('main_dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/favicon.ico') }}" alt="" height="25" width="25">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/assets/images/favicon.ico') }}" alt="" height="35" width="35" class="me-2">
                        <span class="fw-bold h5 text-white">ATM MONITORING</span>
                    </span>
                </a>
            </div>


        </div>

    <div class="d-flex">

        <div class="dropdown d-inline-block d-lg-none ms-2">
            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mdi mdi-magnify"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-search-dropdown">

                <form class="p-3">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="@lang('translation.Search')" aria-label="Search input">

                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>s
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="dropdown d-none d-lg-inline-block ms-1">
            <button id="fullscreenBtn" type="button" class="btn header-item noti-icon waves-effect">
                <i class="bx bx-fullscreen"></i>
            </button>
        </div>

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bullhorn"></i>
                <span class="badge bg-danger rounded-pill" id="annoucementsCounts"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-notifications-dropdown">
                <div class="p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0" key="t-notifications"> Announcements </h6>
                        </div>
                    </div>
                </div>
                <div data-simplebar style="max-height: 230px;">
                    <div class="notification-items ms-2">

                    </div>
                </div>
                <div class="p-2 border-top d-grid">
                    <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('system.announcement.fetch') }}">
                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View_More</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('images/no_image.jpg') }}"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ucfirst(Auth::user()->name)}}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->

                <a class="dropdown-item" href="{{ route('users.profile', Auth::user()->employee_id ) }}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">@lang('translation.Profile')</span></a>
                {{-- <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">@lang('translation.My_Wallet')</span></a>
                <a class="dropdown-item d-block" href="#" data-bs-toggle="modal" data-bs-target=".change-password"><span class="badge bg-success float-end">11</span><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">@lang('translation.Settings')</span></a> --}}
                {{-- <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">@lang('translation.Lock_screen')</span></a> --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">@lang('translation.Logout')</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
</header>

    {{-- Announcements --}}
    <script>
        $(document).ready(function () {
            function fetchAnnouncementDisplay() {
                $.ajax({
                    url: "/system/announcement/display",  // Append a unique timestamp to the URL
                    type: "GET",
                    cache: false,  // Disable caching to avoid stale data
                    success: function(response) {
                        $('.notification-items').empty();

                        // Example response handling for multiple rows:
                        response.forEach(function(row) {
                            // Check if row and its properties exist
                            if (!row || !row.description) {
                                console.error("Error: Missing expected data in the response.");
                                return;
                            }

                            var iconClass;
                            switch(row.type) {
                                case 'New Features':
                                    iconClass = 'fas fa-plus-square text-success';
                                    break;
                                case 'Notification':
                                    iconClass = 'far fa-bell text-info';
                                    break;
                                case 'Enhancements':
                                    iconClass = 'fas fa-edit text-warning';
                                    break;
                                case 'Maintenance':
                                    iconClass = 'fas fa-tools text-danger';
                                    break;
                                default:
                                    iconClass = 'fas fa-info-circle'; // Default icon
                            }

                            // Truncate description to max length
                            const maxLength = 30; // Example: limit to 30 characters
                            const truncatedDescription = row.description.length > maxLength ? row.description.substring(0, maxLength) + '...' : row.description;

                            // Format the dates
                            const dateOptions = { year: 'numeric', month: 'short', day: 'numeric' };
                            const formattedStartDate = row.date_start ? new Date(row.date_start).toLocaleDateString('en-US', dateOptions) : 'N/A';
                            const formattedEndDate = row.date_end ? new Date(row.date_end).toLocaleDateString('en-US', dateOptions) : 'N/A';

                            // Use the differForHumans value
                            const timeAgo = row.differForHumans;
                            const annoucementURL = `/system/announcement/specific/${row.id}`

                            // Create new notification item with conditional display of date_end
                            var notificationItem;
                            if (row.date_start === row.date_end) {
                                notificationItem = `
                                    <a href="${annoucementURL}" class="notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3 d-flex align-items-center justify-content-center">
                                                <i class="${iconClass} fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mt-0 mb-1">${row.title}</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">${truncatedDescription}</p>
                                                    <i class="mdi mdi-clock-outline text-danger"></i> <span>${formattedStartDate}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            } else {
                                notificationItem = `
                                    <a href="${annoucementURL}" class="notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3 d-flex align-items-center justify-content-center">
                                                <i class="${iconClass} fs-1"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mt-0 mb-1">${row.title}</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">${truncatedDescription}</p>
                                                    <i class="mdi mdi-clock-outline"></i> <span>${formattedStartDate}</span> -
                                                    <i class="mdi mdi-clock-outline text-danger"></i> <span>${formattedEndDate}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            }

                            // Append the new notification item to the list
                            $('.notification-items').append(notificationItem);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching counts:", error);
                    }
                });
            }

            fetchAnnouncementDisplay();
            // Reload the count every 5 seconds (5000 milliseconds)
            setInterval(fetchAnnouncementDisplay, 5000);
        });

        $(document).ready(function () {
            // Function to fetch the announcement count
            function fetchAnnouncementCount() {
                $.ajax({
                    url: "/system/announcement/counts",  // Your route to get the counts
                    type: "GET",
                    cache: false,  // Disable caching to avoid stale data
                    success: function(response) {
                        $('#annoucementsCounts').text(response ?? '');  // Display the count directly
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching counts:", error);
                    }
                });
            }

            // Initial count fetch
            fetchAnnouncementCount();

            // Reload the count every 5 seconds (5000 milliseconds)
            setInterval(fetchAnnouncementCount, 5000);
        });
    </script>


    {{-- Full Screen and Minimize Full Screen size --}}
    <script>
        document.getElementById('fullscreenBtn').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                // Trigger full screen (equivalent to F11)
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) { // Firefox
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
                    document.documentElement.webkitRequestFullscreen();
                } else if (document.documentElement.msRequestFullscreen) { // IE/Edge
                    document.documentElement.msRequestFullscreen();
                }
            } else {
                // Exit full screen (and trigger F12 if needed)
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) { // Firefox
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { // IE/Edge
                    document.msExitFullscreen();
                }

                // Simulate F12 key press
                var f12Event = new KeyboardEvent('keydown', {
                    key: 'F12',
                    code: 'F12',
                    keyCode: 123,
                    which: 123,
                    bubbles: true,
                    cancelable: true
                });
                document.dispatchEvent(f12Event);
            }
        });
    </script>
