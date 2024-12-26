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
            @slot('li_1') Main Dashboard @endslot
            @slot('title') Announcements @endslot
        @endcomponent

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="text-start">
                            <h4 class="card-title text-uppercase">Important Announcements</h4>
                            <p class="card-title-desc">
                                This section provides an overview of system announcements.
                            </p>
                        </div>
                        <hr>

                        @php
                            $iconClass = '';
                            $displayText = '';
                            if($SystemAnnouncements->type == 'New Features'){
                                $iconClass = 'fs-1 fas fa-plus-square text-success';
                                $displayText = 'New System Features';
                            } else if ($SystemAnnouncements->type == 'Notification'){
                                $iconClass = 'fs-1 far fa-bell text-info';
                                $displayText = 'System Notification';
                            } else if   ($SystemAnnouncements->type == 'Enhancements'){
                                $iconClass = 'fs-1 fas fa-edit text-warning';
                                $displayText = 'System Enhancements';
                            } else if   ($SystemAnnouncements->type == 'Maintenance'){
                                $iconClass = 'fs-1 fas fa-tools text-danger';
                                $displayText = 'System Maintenance';
                            } else {
                                $iconClass = 'fas fa-info-circle'; // Default icon
                                $displayText = 'System Maintenance';
                            }
                        @endphp

                        <div class="row">
                            <div class="col-md-8">
                                <span class="me-2 fs-1"><li class="{{ $iconClass }}"></li></span>
                                <span class="h1 fw-bold text-uppercase">{{ $displayText }}</span>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="fw-bold h6">{{ $SystemAnnouncements->announcement_id }}</span>
                            </div>
                        </div>
                        <hr>

                        <div class="text-primary fw-bold h6 mt-3 mb-2">{{ $SystemAnnouncements->title }}</div>
                        <div class="text-muted ms-2">{{ $SystemAnnouncements->description }}</div>

                        <div class="text-muted mt-3">
                            @if (empty($SystemAnnouncements->date_start) && empty($SystemAnnouncements->date_end))
                                <span>Date not available</span>
                            @elseif (empty($SystemAnnouncements->date_start))
                                <span>End Date: {{ \Carbon\Carbon::parse($SystemAnnouncements->date_end)->format('F d, Y') }}</span>
                            @elseif (empty($SystemAnnouncements->date_end))
                                <span>Start Date: {{ \Carbon\Carbon::parse($SystemAnnouncements->date_start)->format('F d, Y') }}</span>
                            @elseif ($SystemAnnouncements->date_start == $SystemAnnouncements->date_end)
                                {{ \Carbon\Carbon::parse($SystemAnnouncements->date_end)->format('F d, Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($SystemAnnouncements->date_start)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($SystemAnnouncements->date_end)->format('F d, Y') }}
                            @endif
                        </div>

                        <div class="text-start mt-3">
                            - {{ $SystemAnnouncements->Employee->name ?? '' }}
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('main_dashboard') }}" class="btn btn-secondary">Back to Home</a>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>


@endsection
