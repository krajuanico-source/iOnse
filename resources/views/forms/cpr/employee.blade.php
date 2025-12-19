@extends('layouts/contentNavbarLayout')

@section('title', 'CPR List')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="card p-4">
  <div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold">CPR – Employee Ratings</h4>
  </div>
  <table id="outslipTable" class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Employee ID</th>
        <th>Employee Name</th> <!-- NEW -->
        <th>Rating</th>
        <th>Rating Period</th> <!-- NEW -->
        <th>CPR ID</th>
        <th>Supporting File</th>
        <th>Date Created</th>
        <th>Status</th>
        <th width="250">Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach($cprs as $cpr)
      @php
      $firstEmployee = $cpr->employees->first();
      $filePath = $firstEmployee?->cpr_file;
      $ratingPeriod = $cpr->rating_period_start . ' - ' . $cpr->semester;
      @endphp

      <tr>
        <td>{{ $cpr->id }}</td>

        <!-- Employee IDs -->
        <td>
          @foreach($cpr->employees as $emp)
          {{ $emp->employee_id }}<br>
          @endforeach
        </td>

        <!-- Employee Names -->
        <td>
          @foreach($cpr->employees as $emp)
          {{ $emp->user?->first_name ?? 'N/A' }}<br>
          @endforeach
        </td>

        <!-- Ratings -->
        <td>
          @foreach($cpr->employees as $emp)
          {{ $emp->rating ?? 'N/A' }}<br>
          @endforeach
        </td>

        <!-- Rating Period -->
        <td>{{ $ratingPeriod }}</td>

        <td>{{ $cpr->id }}</td>

        <!-- Supporting File -->
        <td class="text-center">
          @if($filePath && Storage::disk('public')->exists($filePath))
          <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary">
            View File
          </a>
          @else
          <span class="text-muted">No File</span>
          @endif
        </td>

        <td>{{ $cpr->created_at->format('Y-m-d') }}</td>

        <!-- Status -->
        <td>
          @if($cpr->status === 'Active')
          <span class="badge bg-success">Active</span>
          @endif
        </td>

        <!-- Actions -->
        <td>
          @php
          $isActive = strtolower(trim($cpr->status)) === 'active';
          $userId = auth()->id();
          @endphp

          @if($isActive)
          @foreach($cpr->employees as $emp)
          <button
            type="button"
            class="btn btn-sm btn-primary updateCprBtn"
            data-bs-toggle="modal"
            data-bs-target="#updateCprEmployeeModal"
            data-cpr-id="{{ $cpr->id }}"
            data-employee-id="{{ $emp->employee_id }}"
            data-rating="{{ $emp->rating }}">
            Update
          </button>
          @endforeach
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<!-- Update CPR Employee Modal -->
<div class="modal fade" id="updateCprEmployeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="updateCprEmployeeForm" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title">Update CPR Employee Rating</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <input type="hidden" name="cpr_id" id="update_cpr_id">
        @php
        $employeeId = auth()->user()?->employee?->id ?? '';
        @endphp

        <div class="mb-3">
          <label for="update_employee_id" class="form-label">Employee ID</label>
          <input
            type="text"
            id="update_employee_id"
            name="employee_id"
            class="form-control"
            readonly>
        </div>

        <!-- Rating Input -->
        <div class="mb-3">
          <label for="update_rating" class="form-label">Rating</label>
          <input type="number" id="update_rating" name="rating" class="form-control"
            min="0" max="100" step="0.01" required>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Validate</button>
      </div>
    </form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
  // DataTable Init
  jQuery(function($) {
    $('#outslipTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
  $(document).ready(function() {

    // Open modal and populate
    $('.updateCprBtn').click(function() {
      $('#update_cpr_id').val($(this).data('cpr-id'));
      $('#update_employee_id').val($(this).data('employee-id'));
      $('#update_rating').val($(this).data('rating'));
      $('#updateCprEmployeeModal').modal('show');
    });
  });
</script>
<script>
  document.getElementById('updateCprEmployeeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const cprId = document.getElementById('update_cpr_id').value;
    const formData = new FormData(this);

    fetch(`/forms/cpr/${cprId}/employee/validate`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(async res => {
        const data = await res.json();
        console.log('STATUS:', res.status);
        console.log('RESPONSE:', data);
        return data;
      })
      .then(data => {
        if (data.success) {
          window.open(data.pdf_url, '_blank');
          bootstrap.Modal.getInstance(
            document.getElementById('updateCprEmployeeModal')
          ).hide();
        } else {
          alert(JSON.stringify(data, null, 2));
        }
      })
      .catch(err => {
        console.error(err);
        alert('Server error');
      });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.updateCprBtn').forEach(button => {
      button.addEventListener('click', function() {

        const cprId = this.dataset.cprId;
        const employeeId = this.dataset.employeeId;
        const rating = this.dataset.rating;

        document.getElementById('update_cpr_id').value = cprId;
        document.getElementById('update_employee_id').value = employeeId;
        document.getElementById('update_rating').value = rating;
      });
    });
  });
</script>
@endsection
