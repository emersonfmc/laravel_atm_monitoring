@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Maintenance')
@endsection

@section('body')

    <body>
    @endsection

    @section('content')

        <div class="home-btn d-none d-sm-block">
            <a href="{{ route('elog_monitoring_dashboard') }}" class="text-dark"><i class="fas fa-home h2"></i></a>
        </div>

        <section class="my-5 pt-sm-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="home-wrapper">
                            <div class="mb-5">
                                <a href="index" class="d-block auth-logo">
                                    <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="20"
                                        class="auth-logo-dark mx-auto">
                                    <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="20"
                                        class="auth-logo-light mx-auto">
                                </a>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-sm-4">
                                    <div class="maintenance-img">
                                        <img src="{{ URL::asset('/assets/images/maintenace_image.jpg') }}" alt="" class="img-fluid mx-auto d-block">
                                    </div>
                                </div>
                            </div>
                            <h3 class="mt-5">Site is Under Maintenance</h3>
                            <p>Please check back in sometime.</p>

                            <div class="row g-4">
                                <div class="col-md-4 d-flex align-items-stretch">
                                    <div class="card shadow-lg maintenance-box flex-fill">
                                        <div class="card-body text-center">
                                            <i class="fas fa-tools mb-4 h1 text-primary"></i>
                                            <h5 class="font-size-15 text-uppercase">Why is the Site Down?</h5>
                                            <p class="text-muted mb-0">
                                                This page is currently undergoing maintenance to enhance your experience. We are working hard to implement improvements and fix any issues. Thank you for your patience!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-stretch">
                                    <div class="card shadow-lg maintenance-box flex-fill">
                                        <div class="card-body text-center">
                                            <i class="fas fa-history mb-4 h1 text-primary"></i>
                                            <h5 class="font-size-15 text-uppercase">What is the Downtime?</h5>
                                            <p class="text-muted mb-0">
                                                The term "downtime" (also known as a (system) outage or colloquially as a (system) drought) refers to intervals when a system is not accessible. This unavailability represents the portion of a time period during which the system is offline or unavailable.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-stretch">
                                    <div class="card shadow-lg maintenance-box flex-fill">
                                        <div class="card-body text-center">
                                            <i class="bx bx-envelope mb-4 h1 text-primary"></i>
                                            <h5 class="font-size-15 text-uppercase">Do you need Support?</h5>
                                            <p class="text-muted mb-0">
                                                You can contact us <br><br>
                                                <a href="#" class="text-decoration-underline">0958-858-4578</a> <br>
                                                <a href="#" class="text-decoration-underline">no-reply@domain.com</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- end row -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endsection
