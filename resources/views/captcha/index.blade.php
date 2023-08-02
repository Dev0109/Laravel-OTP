@extends('layouts.app')
@section('styles')
    <link href="{{ asset('css/slidercaptcha.min.css') }}" rel="stylesheet" />
@endsection
@section('content')

<div class="auth-card">
    <label class="d-block mt-3">
        <div class="slidercaptcha card">
            <div class="card-body">
                <div id="captcha" style="height: 195px;"></div>
            </div>
        </div>
    </label>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('js/longbow.slidercaptcha.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        var _token = null;
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
            id:'captcha',
            onSuccess:function () {
                $.ajax({
                    url: "{{route('captcha.verify')}}",
                    type: 'POST',
                    headers: {'x-csrf-token': _token},
                    data: {
                        verify: true,
                    },
                    success: (res) => {
                        res = JSON.parse(res);
                        if (res) {
                            window.location.href = "{{route('admin.home')}}";
                        } else {
                            window.location.href = "{{route('captcha')}}";
                        }
                    }
                });
            }
        });
        $(document).ready(function(){
            _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        });
    </script>
@endsection