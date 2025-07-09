@extends('layouts/blankLayout')

@section('title', 'Login Page')

@section('page-style')
@vite([
'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6 mx-4">

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
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-1">Welcome to HR PRIME 👋🏻</h4>
          <p class="mb-5">Please sign-in to your account and start the adventure</p>
          <form id="formAuthentication" class="mb-5" action="{{ route('login.store') }}" method="POST">
            @csrf

            {{-- Email or Username --}}
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="login" name="login" placeholder="Enter your email or username" autofocus required>
              <label for="login">Email or Username</label>
            </div>

            {{-- Password --}}
            <div class="mb-5">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer">
                    <i class="ri-eye-off-line ri-20px"></i>
                  </span>
                </div>
              </div>
            </div>

            {{-- Remember Me --}}
            <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
              <div class="form-check mb-0">
                <!-- <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label> -->
              </div>
              <a href="{{ url('auth/forgot-password-basic') }}" class="float-end mb-1">
                <span>Forgot Password?</span>
              </a>
            </div>

            {{-- Submit --}}
            <div class="mb-5">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>

            {{-- Optional: Display login error --}}
            @if ($errors->any())
            <div class="alert alert-danger text-center">
              {{ $errors->first() }}
            </div>
            @endif
          </form>

          <p hidden class="text-center mb-5">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-basic')}}">
              <span>Create an account</span>
            </a>
          </p>
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
