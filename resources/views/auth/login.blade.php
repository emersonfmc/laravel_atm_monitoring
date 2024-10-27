@extends('layouts.master-without-nav')

@section('title')
@lang('translation.Login') 2
@endsection

@section('css')
<!-- owl.carousel css -->
    {{-- <link rel="stylesheet" href="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.css') }}"> --}}
@endsection

@section('body')

<body class="auth-body-bg">
    @endsection

    @section('content')

    <div class="login-page">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <!-- end col -->
            <div class="col-12 col-sm-8 col-md-7 col-lg-5 col-xl-3">
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <div class="card-body">

                        <div class="text-center">
                            <img src="{{ asset('images/EverfirstLogo.png') }}" class="img-fluid image-size me-5" alt="Everfirst Logo">
                            <img src="{{ asset('images/MultiLineLogo.png') }}" class="img-fluid image-size" alt="Everfirst Logo">
                        </div>

                        <div class="mb-4">
                            <a href="#" class="d-block auth-logo">
                                <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="18" class="auth-logo-dark">
                                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="18" class="auth-logo-light">
                            </a>
                        </div>

                        <div>
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p class="text-muted">Sign in to continue to ATM Monitoring.</p>
                        </div>

                        <div class="mt-4">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Email</label>
                                    <input name="email" type="text" class="form-control @error('email') is-invalid @enderror" id="username" placeholder="Enter Email" autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="float-end">
                                        @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                                        @endif
                                    </div>
                                    <label class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                        <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" id="userpassword" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                        <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                </div>

                            </form>
                            <div class="mt-5 text-center">
                                <p>Don't have an account ? <a href="{{ url('register') }}" class="fw-medium text-primary"> Signup now </a> </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
    </div>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden; /* Prevent any overflow and scrollbars */
        }
        .login-page {
            background-image: url('{{ asset('images/background_image.jpg') }}');
            background-size: cover; /* Ensures the image covers the entire container */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-position: center center; /* Centers the background image */
            min-height: 100vh; /* Ensures the container takes up the full viewport height */
            padding: 0;
            margin: 0;
        }
        .image-size {
            height: 25px;
            /* width: 250px; */
        }

        /* @media (max-width: 768px) {
            .login-form-card {
                max-width: 30%;
            }
        } */
    </style>

    @endsection
    @section('script')
    <!-- owl.carousel js -->
    <script src="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.js') }}"></script>
    <!-- auth-2-carousel init -->
    <script src="{{ URL::asset('/assets/js/pages/auth-2-carousel.init.js') }}"></script>
    @endsection
