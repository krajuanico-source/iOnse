@extends('layouts/contentNavbarLayout')

@section('title', 'Employee List')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
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


    </div>
    <div class="table-responsive">
      <table id="empTable" class="table">
        <thead class="table-light">
          <tr>
            <th style="width: 0;">ID No.</th>
            <th>Employee Name</th>
            <th>Employment Status</th>
            <th>Section</th>
            <th>Division</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th> <!-- Added -->
          </tr>
        </thead>
        <tbody>
          @foreach($employees as $employee)
          <tr>
            <td>{{ $employee->employee_id }}</td>
            <td>
              {{ \Illuminate\Support\Str::upper($employee->first_name) }}
              {{ \Illuminate\Support\Str::upper($employee->middle_name) }}
              {{ \Illuminate\Support\Str::upper($employee->last_name) }}
              {{ \Illuminate\Support\Str::upper($employee->extension_name) }}
            </td>
            <td>{{ \Illuminate\Support\Str::upper($employee->employmentStatus->abbreviation ?? '') }}</td>
            <td>{{ \Illuminate\Support\Str::upper($employee->section->abbreviation ?? '') }}</td>
            <td>{{ \Illuminate\Support\Str::upper($employee->division->abbreviation ?? '') }}</td>
            <td>{{ \Illuminate\Support\Str::lower($employee->username) }}</td>
            <td>{{ \Illuminate\Support\Str::lower($employee->role) }}</td>
            <td class="text-capitalize">{{ $employee->status }}</td>

            <td>
              <!-- Assign Role Button -->
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                data-bs-target="#assignRoleModal{{ $employee->id }}">
                <i class="bi bi-person-gear me-1"></i> Assign Role
              </button>
            </td>
          </tr>

          <!-- Assign Role Modal -->
          <div class="modal fade" id="assignRoleModal{{ $employee->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <form action="{{ route('employee.assignRole', $employee->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Assign Role for {{ $employee->first_name }} {{ $employee->last_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="role" class="form-label">Select Role</label>
                      <select name="role" id="role" class="form-select" required>
                        <option value="">-- Choose Role --</option>
                        @foreach(['Employee', 'HR-Planning', 'HR-PAS', 'HR-L&D', 'HR-Welfare'] as $role)
                        <option value="{{ $role }}" {{ $employee->role === $role ? 'selected' : '' }}>
                          {{ $role }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-save me-1"></i> Save Role
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

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