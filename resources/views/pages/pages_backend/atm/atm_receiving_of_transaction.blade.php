@extends('layouts.master')

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ATM / Passbook / Simcard @endslot
        @slot('title') Receiving of Transaction @endslot
    @endcomponent

@endsection
