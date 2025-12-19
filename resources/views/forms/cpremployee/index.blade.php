@extends('layouts/contentNavbarLayout')

@section('title', 'CPR List')

@section('content')

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
        <th>Rating</th>
        <th>CPR ID</th>
        <th>Date Created</th>
        <th>Status</th>
        <th width="250">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cprs as $cpr)
      <tr>
        <td>{{ $cpr->id }}</td>

        <!-- Employees IDs -->
        <td>
          @if($cpr->employees->count())
          @foreach($cpr->employees as $emp)
          {{ $emp->employee_id }}<br>
          @endforeach
          @else
          N/A
          @endif
        </td>

        <!-- Ratings -->
        <td>
          @if($cpr->employees->count())
          @foreach($cpr->employees as $emp)
          {{ $emp->rating }}<br>
          @endforeach
          @else
          N/A
          @endif
        </td>

        <td>{{ $cpr->id }}</td>
        <td>{{ $cpr->created_at->format('Y-m-d') }}</td>
        <td>
          <!-- Status badge -->
          @if($cpr->status == 'Active')
          <span class="badge bg-success">Active</span>
          @endif
        </td>

        <!-- Edit button -->
        @php
        $isActive = trim(strtolower($cpr->status)) === 'active';
        $firstEmployee = $cpr->employees->first();
        $userId = \Illuminate\Support\Facades\Auth::id(); // logged-in user
        @endphp

        <td>
          <!-- Update button -->
          @if($isActive)
          <button class="btn btn-sm btn-primary updateCprBtn"
            data-cpr-id="{{ $cpr->id }}"
            data-employee-id="{{ $userId }}"
            data-rating="{{ $firstEmployee ? $firstEmployee->rating : '' }}">
            Update
          </button>
          @endif

          <!-- Request Activation -->
          @if(!$isActive)
          <button class="btn btn-sm btn-warning requestActivationBtn"
            data-cpr-id="{{ $cpr->id }}">
            Request Activation
          </button>
          @endif

        </td>

      </tr>
      @endforeach

    </tbody>

  </table>
</div>
<!-- Modal Update CPR Employee -->
<div class="modal fade" id="updateCprEmployeeModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateCprEmployeeForm"
      method="POST"
      class="modal-content"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Update CPR Employee Rating</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="cpr_id" id="update_cpr_id">

        <!-- Employee ID (read-only) -->
        <div class="mb-3">
          <label class="form-label">Employee ID</label>
          <input type="text"
            class="form-control"
            id="update_employee_id"
            name="employee_id"
            readonly>
        </div>

        <!-- Rating -->
        <div class="mb-3">
          <label class="form-label">Rating</label>
          <input type="number"
            name="rating"
            id="update_rating"
            class="form-control"
            min="0"
            max="100"
            step="0.01"
            required>
        </div>

        <!-- File Upload -->
        <div class="mb-3">
          <label class="form-label">Upload Supporting File</label>
          <input type="file"
            name="cpr_file"
            class="form-control"
            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
          <small class="text-muted">
            Allowed: PDF, JPG, PNG, DOC, DOCX
          </small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal">
          Close
        </button>
        <button type="submit"
          class="btn btn-success">
          Update
        </button>
      </div>
    </form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  $(document).ready(function() {
    // Cancel (Reject) button click
    $('.rejects').click(function() {
      const id = $(this).data('id');

      // Confirmation dialog
      if (!confirm('Are you sure you want to cancel this Out Slip?')) {
        return; // Stop if user clicks "Cancel"
      }

      // AJAX request to reject the out slip
      $.post('/forms/outslips/' + id + '/reject', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.error(res.message);
        location.reload();
      });
    });

    // Approve button click
    $('.approve').click(function() {
      const id = $(this).data('id');

      if (!confirm('Are you sure you want to approve this Out Slip?')) {
        return; // Stop if user clicks "Cancel"
      }

      $.post('/forms/outslips/' + id + '/approve', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });
  });
</script>


<script>
  $(document).ready(function() {
    $('.approve').click(function() {
      const id = $(this).data('id');
      $.post('/forms/outslips/' + id + '/approve', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });

    $('.reject').click(function() {
      const id = $(this).data('id');
      $.post('/forms/outslips/' + id + '/reject', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.error(res.message);
        location.reload();
      });
    });
  });

  // DataTable Init
  jQuery(function($) {
    $('#outslipTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('.requestActivationBtn').click(function() {
      const cprId = $(this).data('cpr-id');

      if (!confirm('Send request to HR to activate this rating period?')) return;

      $.post("{{ route('cpr.requestActivation') }}", {
        _token: '{{ csrf_token() }}',
        cpr_id: cprId
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });
  });

  $('.requestActivationBtn').click(function() {
    const cprId = $(this).data('cpr-id');

    if (!confirm('Send request to HR to activate this rating period?')) return;

    $.post("{{ route('cpr.requestActivation') }}", {
      _token: '{{ csrf_token() }}',
      cpr_id: cprId
    }, function(res) {
      toastr.success(res.message);
      location.reload();
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('.updateCprBtn').click(function() {
      let cprId = $(this).data('cpr-id');
      let employeeId = $(this).data('employee-id');
      let rating = $(this).data('rating');

      $('#update_cpr_id').val(cprId);
      $('#update_employee_id').val(employeeId);
      $('#update_rating').val(rating);

      $('#updateCprEmployeeModal').modal('show');
    });

    // Handle form submission
    $('#updateCprEmployeeForm').submit(function(e) {
      e.preventDefault();

      let cprId = $('#update_cpr_id').val();
      let employeeId = $('#update_employee_id').val();
      let rating = $('#update_rating').val();
      let token = $('input[name="_token"]').val();

      $.ajax({
        url: '/employee/' + cprId + '/update',
        type: 'PUT',
        data: {
          _token: token,
          employee_id: employeeId,
          rating: rating
        },
        success: function(res) {
          alert('Rating updated successfully!');
          location.reload(); // refresh table
        },
        error: function(err) {
          alert('Error updating rating.');
          console.log(err);
        }
      });
    });
  });
</script>


@endsection