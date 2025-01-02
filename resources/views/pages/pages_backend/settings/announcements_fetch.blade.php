@extends('layouts.main_dashboard_master')
@section('main_dashboard')

@section('css')
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection


    <div class="container">
        @component('components.breadcrumb')
            @slot('li_1') Settings @endslot
            @slot('title') System Announcements @endslot
        @endcomponent

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-8 text-start">
                                <h4 class="card-title">System Announcements</h4>
                                <p class="card-title-desc">
                                    System announcements serve as important notifications that inform users about updates,
                                    changes, or critical information related to the system's functionality and operations.
                                </p>
                            </div>
                            {{-- <div class="col-md-4 text-end">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementsModal"><i
                                    class="fas fa-plus-circle me-1"></i> Create Announcement</button>
                            </div> --}}
                        </div>
                        <hr>


                        <div id="displayAllAnnouncements">
                        </div>

                        <script>
                            var url = "/system/announcement/fetch/data";

                            $.get(url, function(response) {
                                // Assuming response is an array of announcements
                                var container = $('#displayAllAnnouncements');
                                container.empty(); // Clear any existing content

                                response.forEach(function(announcement) {
                                    var iconClass;
                                    switch(announcement.type) {
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
                                            iconClass = 'fas fa-info-circle';
                                    }

                                    var dateOptions = { year: 'numeric', month: 'short', day: 'numeric' };
                                    var formattedStartDate = announcement.date_start ? new Date(announcement.date_start).toLocaleDateString('en-US', dateOptions) : 'N/A';
                                    var formattedEndDate = announcement.date_end ? new Date(announcement.date_end).toLocaleDateString('en-US', dateOptions) : 'N/A';

                                    var dateDisplay = (announcement.date_start === announcement.date_end) ?
                                        `<i class="mdi mdi-clock-outline text-danger"></i> <span>${formattedStartDate}</span>` :
                                        `<i class="mdi mdi-clock-outline"></i> <span>${formattedStartDate}</span> - <i class="mdi mdi-clock-outline text-danger"></i> <span>${formattedEndDate}</span>`;

                                    var announcementHTML = `
                                        <div class="announcement-item mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center">
                                                    <div class="me-2 mb-2">
                                                        <i class="${iconClass}" style="font-size:100px;"></i>
                                                    </div>
                                                    <div class="h5 fw-bold text-uppercase">${announcement.type}</div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="fw-bold h6 mb-2">${announcement.announcement_id}</div>
                                                    <h6 class="fw-bold text-primary mb-2">${announcement.title}</h6>
                                                    <div class="ms-2 mb-2">${announcement.description}</div>
                                                    <div class="text-muted">
                                                        - ${announcement.employee.name}
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="text-muted">${dateDisplay}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><hr>`;

                                    container.append(announcementHTML);
                                });
                            });
                        </script>



                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>

    <div class="modal fade" id="createAnnouncementsModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="createAnnouncementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Create Announcements</h5>
                    <button type="button" class="btn-close closeCreateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('system.announcement.create') }}" id="createValidateForm">
                        @csrf
                        <div class="col-md-6 form-group mb-3">
                            <label class="fw-bold h6">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="" selected disabled>Select Types</option>
                                <option value="New Features">New Features</option>
                                <option value="Enhancements">Enhancements</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Notification">Notification</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Title</label>
                            <input type="text" name="title" class="form-control" minlength="0" maxlength="100" placeholder="Title" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Description</label>
                            <textarea name="description" class="form-control" minlength="0" maxlength="800"
                                      rows="9" placeholder="Enter Description" style="resize: none;" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date Start</label>
                                    <input type="date" id="date_start_create" name="date_start" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date End</label>
                                    <input type="date" id="date_end_create" name="date_end" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary closeCreateModal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateAnnouncementsModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="updateAnnouncementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">Update Announcements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('system.announcement.update') }}" id="updateValidateForm">
                        @csrf
                        <input type="hidden" name="item_id" id="update_item_id">
                        <div class="col-md-6 form-group mb-3">
                            <label class="fw-bold h6">Type</label>
                            <select name="type" class="form-select" id="update_type" required>
                                <option value="" selected disabled>Select Types</option>
                                <option value="New Features">New Features</option>
                                <option value="Enhancements">Enhancements</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Notification">Notification</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Title</label>
                            <input type="text" name="title" id="update_title" class="form-control" minlength="0" maxlength="100" placeholder="Title" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold h6">Description</label>
                            <textarea name="description" class="form-control" minlength="0" maxlength="800" id="update_description"
                                      rows="9" placeholder="Enter Description" style="resize: none;" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date Start</label>
                                    <input type="date" id="update_date_start" name="date_start" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold h6">Date End</label>
                                    <input type="date" id="update_date_end" name="date_end" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewAnnouncementsModal" data-bs-backdrop="static" tabindex="-1" role="dialog"district_id
        aria-labelledby="viewAnnouncementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase">View Announcements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="view_item_id">
                    <div class="col-md-6 form-group mb-3">
                        <label class="fw-bold h6">Type</label>
                        <select name="type" class="form-select" id="view_type" disabled>
                            <option value="New Features">New Features</option>
                            <option value="Enhancements">Enhancements</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Notification">Notification</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold h6">Title</label>
                        <input type="text" name="title" id="view_title" class="form-control" minlength="0" maxlength="100" placeholder="Title" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold h6">Description</label>
                        <div id="view_description"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold h6">Date Start</label>
                                <input type="text" id="view_date_start" name="date_start" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold h6">Date End</label>
                                <input type="text" id="view_date_end" name="date_end" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format

            $('#date_start_create').attr('min', today); // Set the minimum date to today for date_start
            $('#date_end_create').attr('min', today); // Set the minimum date to today for date_end

            $('#date_start_create').on('change', function() {
                var startDate = $(this).val();
                $('#date_end_create').attr('min', startDate).val(''); // Set min date for date_end and clear its value
            });

            $('#date_end_create').on('change', function() {
                var endDate = $(this).val();
                var startDate = $('#date_start_create').val();
                if (startDate && endDate < startDate) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "End date must be the same or after the start date!"
                    });
                    $(this).val(''); // Clear the end date input if invalid
                }
            });
        });
    </script>






@endsection
@section('script')

@endsection
