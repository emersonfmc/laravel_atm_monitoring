@extends('layouts.main_dashboard_master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboards @endslot
    @slot('title') Dashboard @endslot
@endcomponent

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="">
                    <img
                        src="{{ URL::asset('/images/EverfirstLogo.png') }}"
                        class="img-fluid"  style="height: 500px; width: 500px;"
                        alt="Everfirst Logo">


                        <h1>test</h1>
                        <h1>test</h1>
                        <h1>test</h1>
                        <h1>test</h1>

                        <h1>test</h1>
                        <h1>test</h1> <h1>test</h1>
                        <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1> <h1>test</h1>
                </div>
            </div>
        </div>
                            <!-- Image Section -->

    </div>
@endsection
