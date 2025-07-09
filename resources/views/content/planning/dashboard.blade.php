@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
@php
  use Illuminate\Support\Facades\Auth;
  $user = Auth::user();
@endphp

<div class="row gy-4">
  <div class="col-md-6 col-lg-4">
    <div class="card border-0 shadow-sm text-center p-4 rounded-3 text-white" 
         style="min-height: 110px; background:rgb(255, 255, 255);">
      <div>
        <h1 class="mb-2">👋</h1>
        <h4 class="mb-1 fw-semibold">
          Welcome, Admin {{ $user->first_name ?? 'Admin' }}!
        </h4>
        <p class="mb-0" style="font-size: 0.9rem;">
          Hope you're having a productive day.
        </p>
      </div>
    </div>
  </div>


  <!--/ Welcome card -->

 <div class="col-lg-8">
  <div class="card h-100">
    <div class="card-header">
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">DSWD Field Office XI Employees</h5>
        <div class="dropdown">
          <button class="btn text-muted p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ri-more-2-line ri-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
            <a class="dropdown-item" href="javascript:void(0);">Update</a>
          </div>
        </div>
      </div>
      <p class="small mb-0">
        <span class="h6 mb-0">Employee Metrics Updated</span> 🧑‍💼
      </p>
    </div>

    <div class="card-body pt-lg-10">
      <div class="row g-6">
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-primary rounded shadow-xs">
                <i class="ri-user-line ri-24px"></i>
              </div>
            </div>
            <div class="ms-3">
              <p class="mb-0">Total</p>
              <h5 class="mb-0">{{ $totalEmployees }}</h5>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-success rounded shadow-xs">
                <i class="ri-women-line ri-24px"></i>
              </div>
            </div>
            <div class="ms-3">
              <p class="mb-0">Female</p>
              <h5 class="mb-0">{{ $femaleEmployees }}</h5>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-info rounded shadow-xs">
                <i class="ri-men-line ri-24px"></i>
              </div>
            </div>
            <div class="ms-3">
              <p class="mb-0">Male</p>
              <h5 class="mb-0">{{ $maleEmployees }}</h5>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center">
            <div class="avatar">
              <div class="avatar-initial bg-warning rounded shadow-xs">
                <i class="ri-briefcase-line ri-24px"></i>
              </div>
            </div>
            <div class="ms-3">
              <p class="mb-0">Vacant</p>
              <h5 class="mb-0">--</h5> <!-- Optional: Replace with real count if available -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Weekly Overview Chart -->
  <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Weekly Overview</h5>
          <div class="dropdown">
            <button class="btn text-muted p-0" type="button" id="weeklyOverviewDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="ri-more-2-line ri-24px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
              <a class="dropdown-item" href="javascript:void(0);">Update</a>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pt-lg-2">
        <div id="weeklyOverviewChart"></div>
        <div class="mt-1 mt-md-3">
          <div class="d-flex align-items-center gap-4">
            <h4 class="mb-0">45%</h4>
            <p class="mb-0">Your sales performance is 45% 😎 better compared to last month</p>
          </div>
          <div class="d-grid mt-3 mt-md-4">
            <button class="btn btn-primary" type="button">Details</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Weekly Overview Chart -->

  <!-- Total Earnings -->
  <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Total Earning</h5>
        <div class="dropdown">
          <button class="btn text-muted p-0" type="button" id="totalEarnings" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ri-more-2-line ri-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarnings">
            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
          </div>
        </div>
      </div>
      <div class="card-body pt-lg-8">
        <div class="mb-5 mb-lg-12">
          <div class="d-flex align-items-center">
            <h3 class="mb-0">$24,895</h3>
            <span class="text-success ms-2">
              <i class="ri-arrow-up-s-line"></i>
              <span>10%</span>
            </span>
          </div>
          <p class="mb-0">Compared to $84,325 last year</p>
        </div>
        <ul class="p-0 m-0">
          <li class="d-flex mb-6">
            <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
              <img src="{{asset('assets/img/icons/misc/zipcar.png')}}" alt="zipcar">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Zipcar</h6>
                <p class="mb-0">Vuejs, React & HTML</p>
              </div>
              <div>
                <h6 class="mb-2">$24,895.65</h6>
                <div class="progress bg-label-primary" style="height: 4px;">
                  <div class="progress-bar bg-primary" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </li>
          <li class="d-flex mb-6">
            <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
              <img src="{{asset('assets/img/icons/misc/bitbank.png')}}" alt="bitbank">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Bitbank</h6>
                <p class="mb-0">Sketch, Figma & XD</p>
              </div>
              <div>
                <h6 class="mb-2">$8,6500.20</h6>
                <div class="progress bg-label-info" style="height: 4px;">
                  <div class="progress-bar bg-info" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </li>
          <li class="d-flex">
            <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
              <img src="{{asset('assets/img/icons/misc/aviato.png')}}" alt="aviato">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Aviato</h6>
                <p class="mb-0">HTML & Angular</p>
              </div>
              <div>
                <h6 class="mb-2">$1,2450.80</h6>
                <div class="progress bg-label-secondary" style="height: 4px;">
                  <div class="progress-bar bg-secondary" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ Total Earnings -->

  <!-- Four Cards -->
  <div class="col-xl-4 col-md-6">
    <div class="row gy-6">
      <!-- Total Profit line chart -->
      <div class="col-sm-6">
        <div class="card h-100">
          <div class="card-header pb-0">
            <h4 class="mb-0">$86.4k</h4>
          </div>
          <div class="card-body">
            <div id="totalProfitLineChart" class="mb-3"></div>
            <h6 class="text-center mb-0">Total Profit</h6>
          </div>
        </div>
      </div>
      <!--/ Total Profit line chart -->
      <!-- Total Profit Weekly Project -->
      <div class="col-sm-6">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="avatar">
              <div class="avatar-initial bg-secondary rounded-circle shadow-xs">
                <i class="ri-pie-chart-2-line ri-24px"></i>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn text-muted p-0" type="button" id="totalProfitID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ri-more-2-line ri-24px"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalProfitID">
                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                <a class="dropdown-item" href="javascript:void(0);">Update</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <h6 class="mb-1">Total Profit</h6>
            <div class="d-flex flex-wrap mb-1 align-items-center">
              <h4 class="mb-0 me-2">$25.6k</h4>
              <p class="text-success mb-0">+42%</p>
            </div>
            <small>Weekly Project</small>
          </div>
        </div>
      </div>
      <!--/ Total Profit Weekly Project -->
      <!-- New Yearly Project -->
      <div class="col-sm-6">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="avatar">
              <div class="avatar-initial bg-primary rounded-circle shadow-xs">
                <i class="ri-file-word-2-line ri-24px"></i>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn text-muted p-0" type="button" id="newProjectID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ri-more-2-line ri-24px"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="newProjectID">
                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                <a class="dropdown-item" href="javascript:void(0);">Update</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <h6 class="mb-1">New Project</h6>
            <div class="d-flex flex-wrap mb-1 align-items-center">
              <h4 class="mb-0 me-2">862</h4>
              <p class="text-danger mb-0">-18%</p>
            </div>
            <small>Yearly Project</small>
          </div>
        </div>
      </div>
      <!--/ New Yearly Project -->
      <!-- Sessions chart -->
      <div class="col-sm-6">
        <div class="card h-100">
          <div class="card-header pb-0">
            <h4 class="mb-0">2,856</h4>
          </div>
          <div class="card-body">
            <div id="sessionsColumnChart" class="mb-3"></div>
            <h6 class="text-center mb-0">Sessions</h6>
          </div>
        </div>
      </div>
      <!--/ Sessions chart -->
    </div>
  </div>
  <!--/ four cards -->
@endsection