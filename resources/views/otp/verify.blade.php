@extends('layouts.app')
@section('content')
<?php
    if (!isset($pc_info)) {
        header('Location: /login');
        exit();
    }
?>
<div class="auth-card">
    <div>
        @csrf
        <label class="block">
            <span class="text-gray-700 text-sm">OTP Code</span>
            <input type="number" name="otp_code" id="otp_code" class="form-input" autofocus>
        </label>

        <input type="hidden" name="email" id="email" value="{{$email}}">
        <input type="hidden" name="pc_info" id="pc_info" value="{{$pc_info}}">

        <div class="mt-6">
            <button class="btn btn-block btn-info mb-2" id="verify_btn">
                Verify
            </button>
            <button class="btn btn-block btn-primary" id="back_btn">
                Back
            </button>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    const inputElement = document.getElementById('otp_code');

    inputElement.addEventListener('input', function(event) {
        const inputValue = event.target.value;
        
        // Remove any non-digit characters
        const numericValue = inputValue.replace(/\D/g, '');
        
        // Limit the input to a maximum of 6 digits
        const limitedValue = numericValue.slice(0, 6);
        
        // Update the input value with the restricted value
        event.target.value = limitedValue;
    });
    var time;
    var timeout = false;
    var intervalId = 0;
    var _token = null;
    const sendcode = () => {
        $('#otp_code').val('');
        $('#otp_code').attr('disabled', false);
        $.ajax({
            url: '{{route("otp.sendverification")}}',
            type: 'POST',
            headers: {'x-csrf-token': _token},
            data: {
                pc_info: $('#pc_info').val(),
                email: $('#email').val(),
            },
            success: (res) => {
            },
            error: (xhr, status, error) => {
            }
        });
        timeout = false;
        time = 60;
        intervalId = setInterval(() => {
            $('#verify_btn')[0].innerText = `Verify (${time--}s)`;
            if (time == 0){
                clearInterval(intervalId);
                $('#verify_btn')[0].innerText = `Resend Code`;
                $('#otp_code').attr('disabled', true);
                timeout = true;
            }
        }, 1000);
    };
    const verifycode = () => {
        let otp_code = $('#otp_code').val();
        if (otp_code == ''){
            $('#otp_code').focus();
            return;
        }
        clearInterval(intervalId);
        $('#verify_btn')[0].innerHTML = `<div class="spinner-border text-white" role="status">
            <span class="sr-only">Loading...</span>
            </div>`;
        $.ajax({
            url: "{{route('otp.verifycode')}}",
            type: 'POST',
            headers: {'x-csrf-token': _token},
            data: {
                otp_code: otp_code,
                pc_info: $('#pc_info').val(),
                email: $('#email').val(),
            },
            success: (res) => {
                res = JSON.parse(res);
                if (res.success !== true) {
                    $('#verify_btn')[0].innerHTML = '';
                    $('#verify_btn')[0].innerText = `Resend Code`;
                    $('#otp_code').attr('disabled', true);
                    timeout = true;
                } else {
                    window.location.href = "/home";
                }
            },
            error: (xhr, status, error) => {
                $('#verify_btn')[0].innerHTML = '';
                $('#verify_btn')[0].innerText = `Resend Code`;
                $('#otp_code').attr('disabled', true);
                timeout = true;
            }
        });
    };
    $(document).ready(function(){
        _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        sendcode();
    });
    $(document).on('click', '#verify_btn', () => {
        if (timeout) {
            sendcode();
        } else {
            verifycode();
        }
    });
    $(document).on('click', '#back_btn', () => {
        window.history.go(-1);
    });
</script>
@endsection