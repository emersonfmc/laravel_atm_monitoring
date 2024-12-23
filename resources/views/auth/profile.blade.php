@extends('layouts.main_dashboard_master')

@section('main_dashboard')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

    <div class="container">
        @component('components.breadcrumb')
            @slot('li_1') Settings @endslot
            @slot('title') Profile @endslot
        @endcomponent

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="text-start">
                            <h4 class="card-title">Profile</h4>
                            <p class="card-title-desc">
                                This section provides an overview of your profile, allowing you to view and manage your personal information.
                            </p>
                        </div>
                        <hr>

                        <form action="{{ route('users.profile.update',$user->employee_id) }}" method="POST" id="updateProfileForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $user->id }}">

                            <div class="text-center">
                                <img id="image_preview"
                                        src="{{ $user->avatar ? asset('upload/user_profile/' . basename($user->avatar)) : asset('images/no_image.jpg') }}"
                                        class="img-fluid"
                                        style="height: 200px; width: 210px;">
                                <br>
                                <label for="file_input" class="btn btn-primary mt-2 ps-5 pe-5">Select Image File</label>
                                <input type="file" id="file_input" name="image_file" style="display: none;" accept=".jpg, .png, .jpeg">
                                <hr>
                                <div class="mt-2">
                                    <span class="fw-bold h1 text-primary mt-2">{{ $user->user_types }}</span><br>
                                    <span class="fw-bold h6">User Types</span>
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="fw-bold h5 text-primary">Personal Information</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Fullname</label>
                                        <input type="text" name="name" id="update_name" value="{{ $user->name }}" class="form-control" placeholder="Fullname" minlength="0" maxlength="100" required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Contact No</label>
                                        <input type="text" name="contact_no" id="update_contact_no" value="{{ $user->contact_no }}" class="form-control contact_input_mask" placeholder="Contact No." required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Address</label>
                                        <input type="text" name="address" id="update_address" value="{{ $user->address }}" class="form-control" placeholder="Address" minlength="0" maxlength="100" required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Employee No</label>
                                        <input type="number" name="employee_id" id="update_employee_id" value="{{ $user->employee_id }}" min="0" class="form-control" placeholder="Employee No." readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="fw-bold h5 text-primary">User Account Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Username</label>
                                        <input type="text" name="username" id="update_username" value="{{ $user->username }}" class="form-control" placeholder="Username" minlength="0" maxlength="50" required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Password</label>
                                        <input type="password" name="password" id="update_create_password" class="form-control" placeholder="Password" minlength="0" maxlength="50">
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="update_confirm_password" class="form-control" placeholder="Confirm Password" minlength="0" maxlength="50">
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Email</label>
                                        <input type="email" name="email" id="update_email" value="{{ $user->email }}" class="form-control" placeholder="Email" minlength="0" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="fw-bold h5 text-primary">Branch Details</label>
                                    <hr>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">District Manager</label>
                                        <input type="text"
                                            value="{{ ($user->District->district_number ?? '') . (!empty($user->District->district_number) && !empty($user->District->district_name) ? ' - ' : '') . ($user->District->district_name ?? '') }}"
                                            class="form-control"
                                            placeholder="District"
                                            readonly>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Area</label>
                                        <input type="text"
                                            value="{{ ($user->Area->area_no ?? '') . (!empty($user->Area->area_supervisor) && !empty($user->Area->area_no) ? ' - ' : '') . ($user->Area->area_supervisor ?? '') }}"
                                            class="form-control"
                                            placeholder="Area"
                                            readonly>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Branch</label>
                                        <input type="text"
                                            value="{{ $user->Branch->branch_location ?? '' }}"
                                            class="form-control"
                                            placeholder="Branch"
                                            readonly>
                                    </div>


                                    <div class="form-group mb-2">
                                        <label class="fw-bold h6">Usergroup</label>
                                        <input type="text"
                                            value="{{ $user->UserGroup->group_name ?? '' }}"
                                            class="form-control"
                                            readonly>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('root') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.validator.addMethod("UpdatepasswordMatch", function(value, element) {
                return value === $("#update_create_password").val();
            }, "Passwords do not match.");

            $('#updateProfileForm').validate({
                rules: {
                    user_type: { required: true },
                    password: { minlength: 6, maxlength: 50 }, // You can adjust the minlength as needed
                    confirm_password: { UpdatepasswordMatch: true } // Use custom method here
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Are you sure you want to Update Your Profile?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // If user clicks "Yes", submit the form
                        }
                    });
                }
            });
        });

        // Validate and Compress Image
        document.getElementById('file_input').addEventListener('change', async function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image_preview');

            // Reset the preview image if no file is selected
            if (!file) {
                preview.src = "{{ asset('images/no_image.jpg') }}";
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file type',
                    text: 'Only JPG, JPEG, and PNG files are allowed.'
                });
                event.target.value = ''; // Clear the input
                preview.src = "{{ asset('images/no_image.jpg') }}"; // Reset preview
                return;
            }

            // Validate file size (4 MB = 4 * 1024 * 1024 bytes)
            const maxSize = 4 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File size exceeds 4 MB',
                    text: 'Please choose a smaller file.'
                });
                event.target.value = ''; // Clear the input
                preview.src = "{{ asset('images/no_image.jpg') }}"; // Reset preview
                return;
            }

            // Show loading indicator if compression is needed
            const maxAllowedSize = 2 * 1024 * 1024;
            let finalFile = file;

            if (file.size > maxAllowedSize) {
                // Show the SweetAlert loading indicator
                Swal.fire({
                    title: 'Compressing image...',
                    text: 'Please wait while the image is being compressed.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const options = {
                        maxSizeMB: 2, // Maximum size in MB
                        maxWidthOrHeight: 1920, // Maintain aspect ratio
                        useWebWorker: true
                    };
                    finalFile = await imageCompression(file, options);
                    console.log('Compressed file size:', finalFile.size);

                    // Close the loading indicator
                    Swal.close();
                } catch (error) {
                    console.error('Image compression failed:', error);

                    // Close the loading indicator and show error
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Compression error',
                        text: 'An error occurred during image compression. Please try again.'
                    });
                    return;
                }
            }

            // Display the preview of the uploaded image
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(finalFile);
        });
    </script>


@endsection
