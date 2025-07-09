@extends('layouts/contentNavbarLayout')

@section('title', 'HR Reports')

@section('content')
<div class="container py-4">
  <h4 style="color: #1d4bb2;" class="mb-4">Report Generation</h4>

  <form method="GET" action="{{ route('planning.reports') }}" class="row g-3 mb-4">
    <div class="col-md-6">
      <label for="report_type" class="form-label">Select Report Type</label>
      <select
        name="type"
        id="report_type"
        class="form-select"
        onchange="this.form.submit()">
        <option value="">-- Select Report --</option>
        <option value="personnel-status" {{ request('type') == 'personnel-status' ? 'selected' : '' }}>
          Personnel Status Report
        </option>
        <option value="complement-status" {{ request('type') == 'complement-status' ? 'selected' : '' }}>
          Complement Per Status and PAP
        </option>
        <option value="rsw-breakdown" {{ request('type') == 'rsw-breakdown' ? 'selected' : '' }}>
          Breakdown of Registered Social Workers
        </option>
        <option value="special-groups" {{ request('type') == 'special-groups' ? 'selected' : '' }}>
          Breakdown of Solo Parent, PWD, Senior Citizen, IP
        </option>
        <option value="vacant-positions" {{ request('type') == 'vacant-positions' ? 'selected' : '' }}>
          Monitoring of Vacant Positions
        </option>
        <option value="issued-appointments" {{ request('type') == 'issued-appointments' ? 'selected' : '' }}>
          List of Issued Appointments
        </option>
        <option value="moa-cos" {{ request('type') == 'moa-cos' ? 'selected' : '' }}>
          List of MOA/COS Issued
        </option>
        <option value="database-creations" {{ request('type') == 'database-creations' ? 'selected' : '' }}>
          Database of Approved Requests
        </option>
      </select>
    </div>
  </form>

  <div class="card">
    <div class="card-body">
      @if($reportView)
      @include($reportView)
      @else
      <p class="text-muted">Please select a report type above.</p>
      @endif
    </div>
  </div>
</div>
@endsection
