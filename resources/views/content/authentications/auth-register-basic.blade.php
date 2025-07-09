@extends('layouts/blankLayout')

@section('title', 'Register Account')

@section('page-style')
@vite([
'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection


@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6 mx-4">

      <!-- Register Card -->
      <div class="card p-7">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-3">
            <img src="{{ asset('assets/img/logo-dswd1.png') }}" alt="DSWD Logo" height="100" style="background: #fff;">
          </a>
        </div>
        <!-- /Logo -->
        <div class="card-body mt-1">
          <h4 class="mb-1">Adventure starts here 🚀</h4>
          <p class="mb-5">Make your app management easy and fun!</p>

          <form id="formAuthentication" action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
              <label for="username">Username</label>
            </div>

            <div class="form-floating form-floating-outline mb-5">
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
              <label for="email">Email</label>
            </div>

            <div class="mb-5 form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                  <label for="password">Password</label>
                </div>
              </div>
            </div>

            <div class="mb-5 py-2">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required>
                <label class="form-check-label" for="terms-conditions">
                  I agree to
                  <a href="#">Privacy Policy & Terms</a>
                </label>
              </div>
            </div>

            <button class="btn btn-primary d-grid w-100 mb-5">Sign up</button>
          </form>


          <p class="text-center mb-5">
            <span>Already have an account?</span>
            <a href="{{url('auth/login-basic')}}">
              <span>Sign in instead</span>
            </a>
          </p>
        </div>
      </div>
      <!-- Register Card -->
      <img src="{{asset('assets/img/illustrations/tree-3.png')}}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block">
      <img src="{{asset('assets/img/illustrations/auth-basic-mask-light.png')}}" class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg">
      <img src="{{asset('assets/img/illustrations/tree.png')}}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block">
    </div>
  </div>
</div>
@endsection