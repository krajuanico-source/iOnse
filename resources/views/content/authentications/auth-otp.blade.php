@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
@vite([
'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Login -->
      <div class="card p-7">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
        <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center gap-3">
          <span class="app-brand-logo d-flex align-items-center gap-3 bg-white p-2 rounded">
            <img src="{{ asset('assets/img/logo-dswd1.png') }}" alt="DSWD Logo" height="80">
            <img src="{{ asset('assets/img/hrprime-logo.png') }}" alt="HR Prime Logo" height="75">
          </span>
        </a>
        </div>
        <div class="card-body mt-1">
              <h4 class="mb-4 text-center text-2xl font-semibold">OTP Verification</h4>
              <p>Enter the 6-digit code sent to your email.</p>

              <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="mb-4 position-relative">
                  <label for="otp"
                        id="otpLabel"
                        class="form-label position-absolute top-50 start-0 translate-middle-y text-muted ms-3">
                    One-Time Password
                  </label>

                  <input type="text"
                        class="form-control ps-5"
                        id="otp"
                        name="otp"
                        maxlength="6"
                        inputmode="numeric"
                        required>
                </div>


                <button type="submit" class="mt-8 btn btn-primary d-grid w-100">Verify</button>

                @if ($errors->any())
                <div class="alert alert-danger text-center mt-3">
                  {{ $errors->first() }}
                </div>
                @endif
              </form>

        </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const otpInput = document.getElementById('otp');
  const otpLabel = document.getElementById('otpLabel');

  if (otpInput && otpLabel) {
    otpInput.addEventListener('focus', () => {
      otpLabel.style.display = 'none';
    });

    otpInput.addEventListener('blur', () => {
      if (!otpInput.value) {
        otpLabel.style.display = 'block';
      }
    });
  }

});
</script>

@endsection