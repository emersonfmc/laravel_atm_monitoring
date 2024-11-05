@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Profile @endslot
    @endcomponent

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

                    <form action="{{ route('users.profile.update',$user->employee_id) }}" method="POST" id="updateProfileForm">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $user->id }}">

                        <div class="text-center">
                            <span class="fw-bold h1 text-primary">{{ $user->user_types }}</span><br>
                            <label class="fw-bold h6">User Types</label>
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
    </script>


@endsection
