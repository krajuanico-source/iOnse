
@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'List of Employee')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>List of Employees</h4>
    </div>

    <div class="table-responsive">
      <table id="employeesTable" class="table">
      <thead class="table-light">
        <tr>
          <th style="width: 0;">ID No.</th>
          <th>Employee Name</th>
          <th>Email</th>
          <th>Employment Status</th>
          <th>Section</th>
          <th>Division</th>
          <th>Username</th>
        </tr>
      </thead>
      <tbody>
        @foreach($employees as $employee)
        <tr>
             <td>{{ $employee->employee_id }}</td>
          <td>
            {{ Str::upper($employee->first_name) }}
            {{ Str::upper($employee->middle_name) }}
            {{ Str::upper($employee->last_name) }}
            {{ Str::upper($employee->extension_name) }}
          </td>
          <td>{{ $employee->email }}</td>
          <td>{{ Str::upper($employee->employmentStatus->abbreviation ?? '') }}</td>
          <td>{{ Str::upper($employee->section->abbreviation ?? '') }}</td>
          <td>{{ Str::upper($employee->division->abbreviation ?? '') }}</td>
          <td>{{ Str::lower($employee->username) }}</td>
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
 $('#employeesTable').DataTable({
  columnDefs: [
    { targets: 0, width: "50px", visible: true, searchable: false }
  ]
});
</script>

@endpush
