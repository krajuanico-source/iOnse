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

  <!-- Toastr CSS -->

 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@section('content')

<!-- ================= DASHBOARD TITLE CARD ================= -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card text-center p-4">
      <h4 class="fw-bold mb-1">Human Resource and Planning and Performance Management Section (HRPPMS)</h4>
      <p class="text-muted mb-0">
        Overview of workforce statistics, headcount distribution, and employment status
      </p>
    </div>
  </div>
</div>
<!-- ================= KPI CARDS ================= -->
<div class="row g-4 mb-4">
<div class="row g-4 mb-4">

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Overall Employees</h6></div>
      <div class="card-body"><h3>{{ $overallEmployees }}</h3></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Vacancy</h6></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Attrition Rate</h6></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Active Employees</h6></div>
      <div class="card-body"><h3>{{ $activeemployees }}</h3></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Average Age</h6></div>
    </div>
  </div>

</div>
</div>

<!-- ================= ROW 1 ================= -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Gender</h6></div>
      <div class="card-body"><div id="genderChart"></div></div>
    </div>
  </div>

<div class="col-md-4">
    <div class="card h-100">
        <div class="card-header"><h6>Headcount by Age Group</h6></div>
        <div class="card-body"><div id="ageChart"></div></div>
    </div>
</div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Division</h6></div>
      <div class="card-body"><div id="divisionChart"></div></div>
    </div>
  </div>
</div>

<!-- ================= ROW 2 ================= -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Office Location</h6></div>
      <div class="card-body"><div id="locationChart"></div></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Percentage by Employment Status</h6></div>
      <div class="card-body"><div id="contractChart"></div></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Employment Status</h6></div>
      <div class="card-body"><div id="employmentStatusChart"></div></div>
    </div>
  </div>
</div>



<!-- ================= INLINE JS (NO FILE NEEDED) ================= -->

<script>
document.addEventListener("DOMContentLoaded", function () {

    // GENDER
    new ApexCharts(document.querySelector("#genderChart"), {
        chart: { type: 'pie', height: 280 },
        series: @json([$male, $female]),
        labels: ['Male', 'Female'],
        colors: ['#1d4bb2', '#ac1109']
    }).render();


    // DIVISION
    new ApexCharts(document.querySelector("#divisionChart"), {
        chart: { type: 'bar', height: 280 },
        series: [{
            name: "Employees",
            data: @json(array_values($divisions))
        }],
        xaxis: {
            categories: @json(array_keys($divisions))
        },
        colors: ['#1d4bb2']
    }).render();

    // OFFICE LOCATION
    new ApexCharts(document.querySelector("#locationChart"), {
        chart: { type: 'bar', height: 280 },
        series: [{
            name: "Employees",
            data: @json(array_values($office_locations))
        }],
        xaxis: {
            categories: @json(array_keys($office_locations))
        },
        colors: ['#1d4bb2']
    }).render();

    // EMPLOYMENT STATUS — FIXED ✔✔
    new ApexCharts(document.querySelector("#contractChart"), {
        chart: { 
            type: 'donut', 
            height: 350, // adjust the height
            width: 400   // optional: set a specific width
        },
        series: @json(array_values($employment_status)),
        labels: @json(array_keys($employment_status)),   // Names, not IDs
        colors: ['#1d4bb2', '#b21810ff', '#ffcc00', '#00897b', '#6a1b9a'],
        legend: { position: 'bottom' } // optional: reposition legend
    }).render();


    new ApexCharts(document.querySelector("#locationChart"), {
    chart: { type: 'bar', height: 280 },
    series: [{  
        name: "Employees",
        data: @json(array_values($office_locations))
    }],
    xaxis: {
        categories: @json(array_keys($office_locations))
    },
    colors: ['#1d4bb2']
}).render();
      // EMPLOYMENT STATUS HORIZONTAL BAR
      new ApexCharts(document.querySelector("#employmentStatusChart"), {
          chart: { type: 'bar', height: 350, stacked: true },
          plotOptions: { bar: { horizontal: true } },
          series: [
              { name: 'Male', data: @json($malePerStatus) },
              { name: 'Female', data: @json($femalePerStatus) }
          ],
          xaxis: { categories: @json($statuses) },
          colors: ['#1d4bb2', '#ac1109'],
          legend: { position: 'top' },
          dataLabels: { enabled: true }
      }).render();



});
</script>



@endsection
