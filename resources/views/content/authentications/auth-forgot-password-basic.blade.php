@extends('layouts/blankLayout')

@section('title', 'Forgot Password')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6 mx-4">

      <!-- Forgot Password Card -->
      <div class="card p-7">

        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{ url('/') }}" class="app-brand-link gap-3">
            <img src="{{ asset('assets/img/logo-dswd1.png') }}" alt="DSWD Logo" height="100" style="background: #fff;">
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-1">Forgot Password? 🔒</h4>
          <p class="mb-4">Enter your email and we'll send you instructions to reset your password.</p>

          {{-- ✅ Display status or error messages --}}
          @if (session('status'))
          <div class="alert alert-success text-center">
            {{ session('status') }}
          </div>
          @endif

          @if ($errors->any())
          <div class="alert alert-danger text-center">
            {{ $errors->first() }}
          </div>
          @endif

          <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="form-floating form-floating-outline mb-4">
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
                autofocus>
              <label for="email">Email</label>
            </div>

            <button class="btn btn-primary d-grid w-100 mb-4" type="submit">
              Send Reset Link
            </button>
          </form>

          <!-- /Form -->

          <div class="text-center">
            <a href="{{ url('auth/login-basic') }}" class="d-flex align-items-center justify-content-center">
              <i class="ri-arrow-left-s-line ri-20px me-1_5"></i> Back to login
            </a>
          </div>
        </div>
      </div>
      <!-- /Forgot Password Card -->

      <!-- Background Illustrations -->
      <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block">
      <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg">
      <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block">
    </div>
  </div>
</div>
@endsection