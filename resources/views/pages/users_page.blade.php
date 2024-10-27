@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Pages @endslot
        @slot('title') Users @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <div class="col-md-8 text-start">
                            <h4 class="card-title">Users</h4>
                            <p class="card-title-desc">
                                Individuals who interact with a system, accessing its features and
                                functionalities based on their roles and permissions.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal"><i
                                class="fas fa-plus-circle me-1"></i> Create Users</button>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Created Date</th>
                                    <th>Roles and Permission</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td><a href="#" class="badge bg-danger">Encrypted</a></td>
                                    <td> {{ date('F j, Y', strtotime($item->created_at)) }}</td>
                                    <td><a href="#" class="text-primary fw-bold h6">View</a></td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="me-2"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class='bx bxs-pencil text-warning fs-5'></i>
                                            </a>

                                            <a href="#" class="text-danger me-2"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class='bx bxs-trash fs-5'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>


                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal fade" id="createUserModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-uppercase" id="createUserModalLabel">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="#" id="createUserValidateForm">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-5">
                                <div class="mt form-group">
                                    <label>Total Quantity</label>
                                    <input id="totalQuantity" name="quantity" class="form-control Quantity" type="text"
                                        placeholder="Total Quantity" readonly>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <label for="example-text-input" class="col-md-2 col-form-label">Remarks</label>
                                <div class="form-group col-md-12">
                                    <textarea name="notes" class="form-control" rows="7" style="resize: none;"
                                            minlength="0" maxlength="300"
                                            placeholder="We would like to hear from you what other details you would like to include in this transfer"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')

@endsection
