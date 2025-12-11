  @php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
  @endphp

   
  <style>
    .custom-navbar-bg {
      background-color:rgb(255, 255, 255);
    }
  </style>

  <nav class="layout-navbar navbar navbar-expand-xl align-items-center custom-navbar-bg fixed-top w-100" id="layout-navbar">
    <!-- ✅ Remove container so navbar fills full width -->
    <div class="d-flex justify-content-between align-items-center w-100 px-4">
      
      <!-- Brand or Logo -->
      <!-- <div class="navbar-brand app-brand d-none d-xl-flex py-0 me-4">
        <a href="{{ url('dashboard') }}" class="app-brand-link text-white fw-bold fs-4">
          <i class="ri-home-2-line me-2"></i> {{ config('variables.templateName', 'HR Prime') }}
        </a>
      </div> -->

      <!-- Menu Toggle -->
      <div class="layout-menu-toggle d-xl-none">
        <a class="nav-link px-0 text-white" href="javascript:void(0)">
          <i class="ri-menu-fill ri-24px"></i>
        </a>
      </div>

      <!-- Right Nav Section -->
      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">

          <!-- GitHub Button -->
          <!-- <li class="nav-item me-3">
            <a class="github-button text-white" href="{{ config('variables.repository') }}" target="_blank">
              <i class="ri-star-line me-1"></i> Star
            </a>
          </li> -->

          <!-- User Dropdown -->
          <li class="nav-item dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="#" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img 
                      src="{{ Auth::user()->profile_image && file_exists(storage_path('app/public/' . Auth::user()->profile_image)) 
                              ? asset('storage/' . Auth::user()->profile_image) 
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
                      src="{{ Auth::user()->profile_image && file_exists(storage_path('app/public/' . Auth::user()->profile_image)) 
                              ? asset('storage/' . Auth::user()->profile_image) 
                              : asset('assets/img/avatars/1.png') }}" 
                      class="rounded-circle" 
                      alt="Avatar"
                      style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ccc; box-shadow: 0 2px 4px rgba(0,0,0,0.2); margin-right: 10px;">
                      <div>
                        <h6 class="mb-0 small text-uppercase">
                          {{ Auth::user()->first_name }} 
                          {{ Auth::user()->middle_name ? substr(Auth::user()->middle_name, 0, 1) . '.' : '' }} 
                          {{ Auth::user()->last_name }}
                        </h6>
                        <small class="text-muted">
                          {{ Auth::user()->role }}
                        </small>
                      </div>
                  </div>
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <!-- <a class="dropdown-item" href="{{ url('/pages/account-settings-account') }}">
                  <i class="ri-user-line me-2"></i> My Profile
                </a> -->
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <div class="px-3 py-1">
                  <a class="btn btn-danger w-100" href="{{ url('/') }}">
                    <i class="ri-logout-box-r-line me-2"></i> Logout
                  </a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </div>
  </nav>
