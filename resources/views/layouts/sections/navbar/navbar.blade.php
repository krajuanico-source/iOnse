@php
use Illuminate\Support\Facades\Auth;
@endphp

<style>
  .custom-navbar-bg {
    background-color: rgb(255, 255, 255);
  }
</style>

<nav class="layout-navbar navbar navbar-expand-xl align-items-center custom-navbar-bg fixed-top w-100" id="layout-navbar">
  <div class="d-flex justify-content-between align-items-center w-100 px-4">

    <!-- Menu Toggle -->
    <div class="layout-menu-toggle d-xl-none">
      <a class="nav-link px-0 text-dark" href="javascript:void(0)">
        <i class="ri-menu-fill ri-24px"></i>
      </a>
    </div>

    <!-- Right Nav Section -->
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
      <ul class="navbar-nav flex-row align-items-center ms-auto">

        @if(Auth::check())
            @php $user = Auth::user(); @endphp
            <li class="nav-item dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img 
                          src="{{ $user->profile_image && file_exists(storage_path('app/public/' . $user->profile_image)) 
                                  ? asset('storage/' . $user->profile_image) 
                                  : asset('assets/img/avatars/1.png') }}" 
                          class="rounded-circle" 
                          alt="Avatar"
                          style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ccc; box-shadow: 0 2px 4px rgba(0,0,0,0.2); margin-right: 10px;">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <img 
                                  src="{{ $user->profile_image && file_exists(storage_path('app/public/' . $user->profile_image)) 
                                          ? asset('storage/' . $user->profile_image) 
                                          : asset('assets/img/avatars/1.png') }}" 
                                  class="rounded-circle" 
                                  alt="Avatar"
                                  style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ccc; box-shadow: 0 2px 4px rgba(0,0,0,0.2); margin-right: 10px;">
                                <div>
                                    <h6 class="mb-0 small text-uppercase">
                                        {{ $user->first_name }} 
                                        {{ $user->middle_name ? substr($user->middle_name,0,1) . '.' : '' }} 
                                        {{ $user->last_name }}
                                    </h6>
                                    <small class="text-muted">{{ $user->role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <div class="px-3 py-1">
                          <a class="btn btn-danger w-100" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ri-logout-box-r-line me-2"></i> Logout
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                              @csrf
                          </form>
                        </div>
                    </li>
                </ul>
            </li>
        @else
            <!-- Session expired: redirect immediately -->
            <script>
              window.location.href = "{{ route('auth-login-basic') }}";
            </script>
        @endif

      </ul>
    </div>
  </div>
</nav>
