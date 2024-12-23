@extends('layouts.main_dashboard_master')
@section('main_dashboard')

@component('components.breadcrumb')
    @slot('li_1') Main Dashboards @endslot
    @slot('title') Main Dashboard @endslot
@endcomponent


        <div class="row">
            <div class="col-12">
                <div class="rounded mb-2">
                    <div class="card-body position-relative">
                        <!-- Background Image -->
                        <img src="{{ URL::asset('/images/dashboard_background.jpg') }}"
                             class="img-fluid"
                             style="height: 400px; width: 100%;"
                             alt="Dashboard Background">

                        <!-- Everfirst Logo -->
                        <img src="{{ URL::asset('/images/EverfirstLogo.png') }}"
                             class="img-fluid position-absolute"
                             style="max-width: 90%; height: auto; width: auto; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                             alt="Everfirst Logo">
                    </div>
                </div>


                <div class="card-body">
                    <div class="row"> <!-- Added gx-3 for consistent spacing -->
                        <!-- Card Monitoring -->
                        <div class="col-md-6 d-flex align-items-stretch"> <!-- Ensure equal height for columns -->
                            <div class="card shadow-lg w-100 h-80 rounded-3 border border-dark"> <!-- Ensure the card takes full height -->
                                <div class="card-body d-flex flex-column"> <!-- Flex container -->
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-4 text-center">
                                            <img src="{{ URL::asset('/images/main_dashboard/atm_card.png') }}" class="img-fluid" alt="ATM Card" style="height: 150px; width:150px;">
                                        </div>
                                        <div class="mt-2 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-8 d-flex flex-column">
                                            <h4 class="text-uppercase fw-bold">Card Monitoring</h4>
                                            <label class="text-muted ms-2">
                                                Monitor and manage the status, and transactions
                                                of ATM cards, passbooks, and SIM cards in real-time to
                                                ensure security and operational efficiency.
                                            </label>
                                            <div class="mt-auto w-50"> <!-- Push the button to the bottom -->
                                                <a href="{{ route('elog_monitoring_dashboard') }}" class="btn btn-info w-100 text-truncate">
                                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        Card Monitoring
                                                    </span>
                                                    <i class="ms-2 fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="col-md-6 d-flex align-items-stretch"> <!-- Ensure equal height for columns -->
                            <div class="card shadow-lg w-100 h-80 rounded-3 border border-dark"> <!-- Ensure the card takes full height -->
                                <div class="card-body d-flex flex-column"> <!-- Flex container -->
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-4 text-center">
                                            <img src="{{ URL::asset('/images/main_dashboard/documentation.png') }}" class="img-fluid" alt="Documents" style="height: 150px; width:150px;">
                                        </div>
                                        <div class="mt-2 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-8 d-flex flex-column">
                                            <h4 class="text-uppercase fw-bold">Documents</h4>
                                            <label class="text-muted ms-2">
                                                This is where we manage the transaction of documents in our system.
                                            </label>
                                            <div class="mt-auto w-50"> <!-- Push the button to the bottom -->
                                                <a href="{{ route('documents.dashboard.page') }}" class="btn btn-info w-100 text-truncate">
                                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        Documents Monitoring
                                                    </span>
                                                    <i class="ms-2 fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="col-md-6 d-flex align-items-stretch"> <!-- Ensure equal height for columns -->
                            <div class="card shadow-lg w-100 h-80 rounded-3 border border-dark"> <!-- Ensure the card takes full height -->
                                <div class="card-body d-flex flex-column"> <!-- Flex container -->
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-4 text-center">
                                            <img src="{{ URL::asset('/images/main_dashboard/settings.png') }}" class="img-fluid" alt="Settings" style="height: 150px; width:150px;">
                                        </div>
                                        <div class="mt-2 col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-8 d-flex flex-column">
                                            <h4 class="text-uppercase fw-bold">Settings</h4>
                                            <label class="text-muted ms-2">
                                                This is where we manage the settings of our system.
                                            </label>
                                            <div class="mt-auto w-50"> <!-- Push the button to the bottom -->
                                                <a href="{{ route('settings.dashboard.page') }}" class="btn btn-info w-100 text-truncate">
                                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        Settings
                                                    </span>
                                                    <i class="ms-2 fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>







            </div>
        </div>
                            <!-- Image Section -->
@endsection
