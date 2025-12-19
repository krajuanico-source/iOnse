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
        <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center gap-3">
          <span class="app-brand-logo d-flex align-items-center gap-3 bg-white p-2 rounded">
            <img src="{{ asset('assets/img/logo-dswd1.png') }}" alt="DSWD Logo" height="80">
            <img src="{{ asset('assets/img/hrprime-logo.png') }}" alt="HR Prime Logo" height="75">
          </span>
        </a>  
        </div>
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-4 text-center text-2xl font-semibold">Welcome to HR PRIME</h4>
          <p class="mb-5">Please sign-in to your account</p>
          <form id="formAuthentication" class="mb-5" action="{{ route('login.store') }}" method="POST">
            @csrf

            {{-- Email or Username --}}
          <div class="mb-5 position-relative">
            <label for="login" class="form-label position-absolute top-50 start-0 translate-middle-y text-muted ms-3" id="loginLabel">
              Email or Username
            </label>
            <input type="text" class="form-control ps-5" id="login" name="login" placeholder="" autofocus required>
          </div>

            {{-- Password --}}
            <div class="mb-5 position-relative">
              <label for="password" 
                    class="form-label position-absolute top-50 start-0 translate-middle-y text-muted ms-3"
                    id="passwordLabel">
                Password
              </label>

              <div class="input-group">
                <input type="password"
                      id="password"
                      class="form-control ps-5"
                      name="password"
                      required>

                <span class="input-group-text cursor-pointer" id="togglePassword">
                  <i class="ri-eye-off-line ri-20px"></i>
                </span>
              </div>
            </div>

            {{-- Remember Me --}}
            <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
              <div class="form-check mb-0">
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
      <!-- </div>
      <img src="{{asset('assets/img/illustrations/tree-3.png')}}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block">
      <img src="{{asset('assets/img/illustrations/auth-basic-mask-light.png')}}" class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg">
      <img src="{{asset('assets/img/illustrations/tree.png')}}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block">
    </div> -->
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // LOGIN FIELD
  const loginInput = document.getElementById('login');
  const loginLabel = document.getElementById('loginLabel');

  if (loginInput && loginLabel) {
    loginInput.addEventListener('focus', () => {
      loginLabel.style.display = 'none';
    });

    loginInput.addEventListener('blur', () => {
      if (!loginInput.value) {
        loginLabel.style.display = 'block';
      }
    });
  }

  // PASSWORD FIELD
  const passwordInput = document.getElementById('password');
  const passwordLabel = document.getElementById('passwordLabel');
  const togglePassword = document.getElementById('togglePassword');
  const icon = togglePassword?.querySelector('i');

  if (passwordInput && passwordLabel) {
    passwordInput.addEventListener('focus', () => {
      passwordLabel.style.display = 'none';
    });

    passwordInput.addEventListener('blur', () => {
      if (!passwordInput.value) {
        passwordLabel.style.display = 'block';
      }
    });
  }

  // PASSWORD VISIBILITY TOGGLE
  if (togglePassword && icon) {
    togglePassword.addEventListener('click', () => {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;

      icon.classList.toggle('ri-eye-line');
      icon.classList.toggle('ri-eye-off-line');
    });
  }

});
</script>

@endsection
