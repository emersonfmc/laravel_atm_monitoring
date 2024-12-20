@yield('css')

<!-- Bootstrap Css -->

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/css/loaders.css') }}" rel="stylesheet" type="text/css" />

{{-- Additional Design such as Modal Size, Button styling and etc. --}}
<link href="{{ URL::asset('assets/css/add_style.min.css') }}" rel="stylesheet" type="text/css" />



<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables/datatable_style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/select2/select2_style.css') }}" rel="stylesheet" type="text/css" />

<style>
        .table_scrollable {
            max-height: 400px; /* Adjust the height as needed */
            overflow-y: auto;
        }

        .table_scrollable::-webkit-scrollbar {
            width: 9px;
        }

        .table_scrollable::-webkit-scrollbar-track {
            border-radius: 8px;
            background-color: #ffffff;
            border: 1px solid #ffffff;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        }

        .table_scrollable::-webkit-scrollbar-thumb {
            border-radius: 8px;
            background-color: #C1C1C1;
        }
</style>

