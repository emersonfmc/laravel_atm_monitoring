@extends('layouts.documents_monitoring.documents_master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('documents')

    @component('components.breadcrumb')
        @slot('li_1') Settings @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


@endsection
@section('script')

@endsection
