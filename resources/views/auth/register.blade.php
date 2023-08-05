@extends('layouts.app')
@section('styles')
    <link href="{{ asset('css/slidercaptcha.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <?php
    
    $clientIP = $_SERVER['REMOTE_ADDR'];
    $pc_info = "{$clientIP}";
    $pc_info = hash('sha256', $pc_info);
    ?>

    <div class="auth-card">
        <div class="flex flex-shrink-0 justify-center">
            <a href="{{ route('login') }}">
                <img class="responsive" src="{{ asset('img/logo.png') }}" alt="logo">
            </a>
        </div>

        @if (session('message'))
            <div class="alert success">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label class="d-block">
                <span class="text-gray-700 text-sm">{{ trans('global.login_name') }}</span>
                <input type="text" name="name" id="name"
                    class="form-input {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" autofocus
                    required>
                @if ($errors->has('name'))
                    <p class="invalid-feedback">{{ $errors->first('name') }}</p>
                @endif
            </label>

            <label class="d-block mt-3">
                <span class="text-gray-700 text-sm">{{ trans('global.login_email') }}</span>
                <input type="email" name="email" class="form-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
                    value="{{ old('email') }}" autofocus required>
                @if ($errors->has('email'))
                    <p class="invalid-feedback">{{ $errors->first('email') }}</p>
                @endif
            </label>

            <label class="d-block mt-3">
                <span class="text-gray-700 text-sm">{{ trans('global.login_phone') }}</span>
                <input type="tel" name="phone" class="form-input{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                    pattern="\+\d{10,15}" required>
                @if ($errors->has('phone'))
                    <p class="invalid-feedback">{{ $errors->first('phone') }}</p>
                @endif
            </label>

            <label class="d-block mt-3 password-panel">
                <span class="text-gray-700 text-sm">{{ trans('global.login_password') }}</span>
                <input type="password" name="password"
                    class="form-input{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                @if ($errors->has('password'))
                    <p class="invalid-feedback">{{ $errors->first('password') }}</p>
                @endif
                <span id="toggle-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility(event)"></i></span>
            </label>

            <label class="d-block mt-3 password-panel">
                <span class="text-gray-700 text-sm">{{ trans('global.login_password_confirmation') }}</span>
                <input type="password" name="password_confirmation" class="form-input" required>
                @if ($errors->has('password_confirmation'))
                    <p class="invalid-feedback">{{ $errors->first('password_confirmation') }}</p>
                @endif
                <span id="toggle-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility(event)"></i></span>
            </label>

            <label class="d-block mt-3">
                <div class="slidercaptcha card">
                    <div class="card-body">
                        <div id="captcha" style="height: 195px;"></div>
                    </div>
                </div>
                @if ($errors->has('captcha'))
                    <p class="invalid-feedback">Please select tile</p>
                @endif
                <input type="hidden" name="captcha">
            </label>
            <input type="hidden" name="pc_info" value="{{ $pc_info }}">

            <div class="flex justify-center items-center mt-4">
                <div>
                    <a class="link" href="{{ route('login') }}">Sign In Here</a>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="button" id="submit_btn" disabled
                    style="background-color: rgba(90, 103, 103, var(--bg-opacity))">
                    {{ trans('global.register') }}
                </button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/longbow.slidercaptcha.min.js') }}"></script>
    <script>
        function togglePasswordVisibility(event) {
            var toggleIcon = event.target;
            console.log(toggleIcon);
            var passwordInput = toggleIcon.parentNode.parentNode.children[1];
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
        var captcha = sliderCaptcha({
            width: 280,
            height: 155,
            PI: Math.PI,
            sliderL: 21,
            sliderR: 4.5,
            offset: 5,
            loadingText: 'Loading...',
            failedText: 'Try It Again',
            barText: 'Slide the Puzzle',
            repeatIcon: 'fa fa-repeat',
            maxLoadCount: 3,
            id: 'captcha',

            onSuccess: function() {
                document.querySelector('input[name="captcha"]').value = 'success';
                document.getElementById("submit_btn").disabled = false;
                document.getElementById("submit_btn").style.backgroundColor =
                    "rgba(90, 103, 216, var(--bg-opacity))";
            }
        });
    </script>
@endsection
