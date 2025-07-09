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
          <a href="{{url('/')}}" class="app-brand-link gap-3">
            <span class="app-brand-logo demo">
              <img src="{{ asset('assets/img/logo-dswd1.png') }}" alt="DSWD Logo" height="100" style="background: #fff;">
            </span>
          </a>
        </div>
        <div class="card-body mt-1">
          <div class="card p-4">
            <div class="card-body">
              <h4 class="mb-4">OTP Verification</h4>
              <p>Enter the 6-digit code sent to your email.</p>

              <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="form-floating form-floating-outline mb-4">
                  <input type="text" class="form-control" name="otp" placeholder="Enter 6-digits OTP" required>
                  <label for="otp">One-Time Password</label>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Verify</button>

                @if ($errors->any())
                <div class="alert alert-danger text-center mt-3">
                  {{ $errors->first() }}
                </div>
                @endif
              </form>
            </div>
          </div>

        </div>
      </div>
      <!-- /Login -->
      <img src="{{asset('assets/img/illustrations/tree-3.png')}}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block">
      <img src="{{asset('assets/img/illustrations/auth-basic-mask-light.png')}}" class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg">
      <img src="{{asset('assets/img/illustrations/tree.png')}}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block">
    </div>
  </div>
</div>
@endsection