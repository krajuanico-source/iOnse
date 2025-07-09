@extends('layouts/contentNavbarLayout')

@section('title', 'Employee List')

@section('content')

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
          <h4>
            @if (Request::is('planning/retired-employees'))
              Retired Employees
            @elseif (Request::is('planning/resigned-employees'))
              Resigned Employees
            @elseif (Request::is('planning/active-employees'))
              Active Employees
            @else
              List of Employees
            @endif
          </h4>

          @unless (Request::is('planning/retired-employees') || Request::is('planning/resigned-employees'))
            <a href="{{ url('planning/registration-form') }}" class="btn btn-success">Add New Employee</a>
          @endunless
        </div>
    <div class="table-responsive">
      <table id="empTable" class="table">
      <thead class="table-light">
        <tr>
          <!-- <th>Photo</th> -->
          <th style="width: 0;">ID No.</th>
          <th>Employee Name</th>
          <!-- <th>Email</th> NEW -->
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
          <!-- <td>
            @if ($employee->profile_image)
              <img src="{{ asset($employee->profile_image) }}" alt="Profile" width="50" height="50" class="rounded-circle">
            @else
              <img src="{{ asset('default-user.png') }}" alt="No Photo" width="50" height="50" class="rounded-circle">
            @endif
          </td> -->
          <td>{{ $employee->employee_id }}</td>
          <td>
            {{ Str::upper($employee->first_name) }}
            {{ Str::upper($employee->middle_name) }}
            {{ Str::upper($employee->last_name) }}
            {{ Str::upper($employee->extension_name) }}
          </td>
          <!-- <td>{{ $employee->email }}</td> NEW -->
          <td>{{ Str::upper($employee->employmentStatus->abbreviation ?? '') }}</td>
          <td>{{ Str::upper($employee->section->abbreviation ?? '') }}</td>
          <td>{{ Str::upper($employee->division->abbreviation ?? '') }}</td>
          <td>{{ Str::lower($employee->username) }}</td>
          <td class="text-capitalize">{{ $employee->status }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('employee.show-view', $employee->id) }}" class="btn btn-sm btn-primary">View</a>
              <form action="{{ route('employee.delete', $employee->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
              </form>
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
  columnDefs: [
    { targets: 0, width: "50px", visible: true, searchable: false }
  ]
});
</script>
@endpush
