@extends('frontEnd.layouts.master')
@section('title','Customer Verify')
@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="form-content">
                    <p class="auth-title">Customer Verify</p>
                    <form action="{{route('customer.account.verify')}}" method="POST"  data-parsley-validate="">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="otp">OTP</label>
                            <input type="number" id="otp" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" placeholder="Enter OTP" required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- col-end -->
                        <div class="form-group mb-3">
                            <button class="submit-btn">submit</button>
                        </div>
                     <!-- col-end -->
                     </form>
                     <div class="resend_otp">
                        <form action="{{route('customer.resendotp')}}" method="POST">
                            @csrf
                            <button id="resendButton" disabled><i data-feather="rotate-cw"></i> Resend OTP</button> <span id="timer" class="ms-2"></span> 
                        </form>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/form-validation.init.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otpSentTime = {{ session('otp_sent_time') }} * 1000;
        const currentTime = new Date().getTime();
        const cooldownTime = 2 * 60 * 1000;
        const timeLeft = otpSentTime + cooldownTime - currentTime;

        function startTimer(duration) {
            let timer = duration, minutes, seconds;
            const timerInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                document.getElementById('timer').textContent = `Resend OTP in ${minutes}:${seconds}`;

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    document.getElementById('resendButton').disabled = false; 
                    document.getElementById('timer').textContent = ''; 
                }
            }, 1000);
        }

        if (timeLeft > 0) {
            document.getElementById('resendButton').disabled = true;
            startTimer(Math.floor(timeLeft / 1000));
        } else {
            document.getElementById('resendButton').disabled = false;
        }
    });
</script>
@endpush