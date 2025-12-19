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
        <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center gap-3">
          <span class="app-brand-logo d-flex align-items-center gap-3 bg-white p-2 rounded">
            <img src="{{ asset('assets/img/logo-dswd1.png') }}" alt="DSWD Logo" height="80">
            <img src="{{ asset('assets/img/hrprime-logo.png') }}" alt="HR Prime Logo" height="75">
          </span>
        </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-4 text-center text-2xl font-semibold">Forgot Password</h4>
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

          <div class="mb-4 position-relative">
            <label for="email"
                  id="emailLabel"
                  class="form-label position-absolute top-50 start-0 translate-middle-y text-muted ms-3">
              Email
            </label>

            <input type="email"
                  class="form-control ps-5"
                  id="email"
                  name="email"
                  required
                  autofocus>
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
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

  const emailInput = document.getElementById('email');
  const emailLabel = document.getElementById('emailLabel');

  if (emailInput && emailLabel) {
    emailInput.addEventListener('focus', () => {
      emailLabel.style.display = 'none';
    });

    emailInput.addEventListener('blur', () => {
      if (!emailInput.value) {
        emailLabel.style.display = 'block';
      }
    });
  }

});
</script>

@endsection