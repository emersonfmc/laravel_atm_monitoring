<!-- JAVASCRIPT -->

<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/metismenu/metismenu.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js')}}"></script>


<script>
    $('#change-password').on('submit',function(event){
        event.preventDefault();
        var Id = $('#data_id').val();
        var current_password = $('#current-password').val();
        var password = $('#password').val();
        var password_confirm = $('#password-confirm').val();
        $('#current_passwordError').text('');
        $('#passwordError').text('');
        $('#password_confirmError').text('');
        $.ajax({
            url: "{{ url('update-password') }}" + "/" + Id,
            type:"POST",
            data:{
                "current_password": current_password,
                "password": password,
                "password_confirmation": password_confirm,
                "_token": "{{ csrf_token() }}",
            },
            success:function(response){
                $('#current_passwordError').text('');
                $('#passwordError').text('');
                $('#password_confirmError').text('');
                if(response.isSuccess == false){
                    $('#current_passwordError').text(response.Message);
                }else if(response.isSuccess == true){
                    setTimeout(function () {
                        window.location.href = "{{ route('root') }}";
                    }, 1000);
                }
            },
            error: function(response) {
                $('#current_passwordError').text(response.responseJSON.errors.current_password);
                $('#passwordError').text(response.responseJSON.errors.password);
                $('#password_confirmError').text(response.responseJSON.errors.password_confirmation);
            }
        });
    });
</script>

@yield('script')

<!-- App js -->


<script src="{{ URL::asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/datatable-server-side.init.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('assets/js/app.min.js')}}"></script>

<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/select2.init.js') }}"></script>

<script src="{{ URL::asset('assets/libs/jquery-validation/jquery-validation.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/inputmask/inputmask.min.js') }}"></script>

{{-- <script src="{{ URL::asset('/assets/js/pages/ecommerce-select2.init.js') }}"></script> --}}
<script src="{{ URL::asset('assets/js/pages/add_script.js') }}"></script>

<script src="{{ URL::asset('assets/libs/compress/compress.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/instascan/instascan.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".contactnumber").inputmask({
            mask: '+6399-99-999-999',
            placeholder: '0',
        });

        $('.balance_input_mask').inputmask({
            'alias': 'currency',
            allowMinus: false,
            'prefix': "₱ ",
            max: 999999999999.99,
        });

        $(".atm_card_input_mask").inputmask({
            mask: '9999-9999-9999-9999-9999', // Custom mask for the card number
            placeholder: '', // Placeholder to show the expected format
            showMaskOnHover: false,  // Hide the mask when the user is not interacting with the field
            showMaskOnFocus: true,   // Show the mask when the field is focused
            rightAlign: false       // Align the input to the left
        });
    });
</script>



@yield('script-bottom')
