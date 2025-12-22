@extends('layouts/contentNavbarLayout')

@section('title', 'Employee List')

@section('content')
@php use Illuminate\Support\Str; @endphp

@if(session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Employees</h4>
    </div>

    <div class="table-responsive">
      <table id="empTable" class="table table-striped">
        <thead class="table-light">
          <tr>
            <th>ID No.</th>
            <th>Employee Name</th>
            <th>Employment Status</th>
            <th>Section</th>
            <th>Division</th>
            <th>Username</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($employees as $employee)
          <tr>
            <td>{{ $employee->employee_id }}</td>
            <td>
              {{ Str::upper($employee->first_name) }}
              {{ $employee->middle_name ? Str::upper($employee->middle_name[0]).'.' : '' }}
              {{ Str::upper($employee->last_name) }}
              {{ Str::upper($employee->extension_name) }}
            </td>
            <td>{{ Str::upper($employee->employmentStatus->abbreviation ?? '') }}</td>
            <td>{{ Str::upper($employee->section->abbreviation ?? '') }}</td>
            <td>{{ Str::upper($employee->division->abbreviation ?? '') }}</td>
            <td>{{ Str::lower($employee->username) }}</td>
            <td class="text-capitalize">{{ $employee->status }}</td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-sm btn-primary">View</a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $('#empTable').DataTable({
    columnDefs: [{
      targets: 0,
      width: "50px",
      visible: true,
      searchable: false
    }]
  });
</script>
@endpush
