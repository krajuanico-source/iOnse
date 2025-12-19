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
  <div class="mt-3 text-end">
    <button
      class="btn btn-success"
      id="requestAuthenticCopyBtn"
      disabled>
      Request Authentic Copy
    </button>
  </div><br>
  <table id="outslipTable" class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Select</th>
        <th>Rating</th>
        <th hidden>CPR ID</th>
        <th>Supporting File</th> <!-- ✅ NEW -->
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
      @endphp

      <tr>
        <td>{{ $cpr->id }}</td>

        <!-- Employee IDs -->
        <td>
          @forelse($cpr->employees as $emp)
          <div class="form-check">
            <input
              class="form-check-input employeeCheckbox"
              type="checkbox"
              name="employee_ids[]"
              value="{{ $emp->employee_id }}"
              id="emp_{{ $cpr->id }}_{{ $emp->employee_id }}"
              data-rating="{{ $emp->rating }}"
              data-cpr-id="{{ $cpr->id }}"
              data-status="{{ $emp->status ?? 'Pending' }}"
              @if($emp->status !== 'Validated') disabled @endif
            @if($emp->employee_id === auth()->user()->employee?->id && $emp->status === 'Validated') checked @endif
            >
          </div>
          @empty
          <span class="text-muted">N/A</span>
          @endforelse
        </td>

        <!-- Ratings -->
        <td>
          @forelse($cpr->employees as $emp)
          {{ $emp->rating }}<br>
          @empty
          N/A
          @endforelse
        </td>

        <td hidden>{{ $cpr->id }}</td>

        <!-- Supporting File -->
        <td class="text-center">
          @if($filePath && Storage::disk('public')->exists($filePath))
          <a href="{{ asset('storage/' . $filePath) }}"
            target="_blank"
            class="btn btn-sm btn-outline-primary">
            View File
          </a>
          @else
          <span class="text-muted">No File</span>
          @endif
        </td>

        <td>{{ $cpr->created_at->format('Y-m-d') }}</td>

        <td>
          @forelse($cpr->employees as $emp)
          @switch($emp->status ?? 'Pending')
          @case('Validated')
          <span class="badge bg-success">Validated</span>
          @break
          @case('Pending')
          <span class="badge bg-warning text-dark">Pending</span>
          @break
          @default
          <span class="badge bg-secondary">{{ $emp->pivot->status }}</span>
          @endswitch
          <br>
          @empty
          N/A
          @endforelse

        </td>
        <!-- Actions -->
        <td>
          @php
          $isActive = strtolower(trim($cpr->status)) === 'active';
          $userId = auth()->id();
          $employeeStatus = $cpr->employees
          ->where('user_id', $userId)
          ->first()?->pivot->status ?? 'Pending';
          $isValidated = $employeeStatus === 'Validated';
          $signedPath = $cpr->signed_pdf_path ?? null;
          @endphp

          @if($isActive)
          @forelse($cpr->employees as $emp)
          @switch($emp->status ?? 'Pending')
          @case('Validated')
          <button
            class="btn btn-sm btn-primary updateCprBtn" disabled
            data-cpr-id="{{ $cpr->id }}"
            data-employee-id="{{ $userId }}"
            data-rating="{{ $firstEmployee?->rating }}"
            @if($isValidated) disabled title="This CPR is already validated" @endif>
            Update
          </button>
          @break
          @case('Pending')
          <button
            class="btn btn-sm btn-primary updateCprBtn"
            data-cpr-id="{{ $cpr->id }}"
            data-employee-id="{{ $userId }}"
            data-rating="{{ $firstEmployee?->rating }}"
            @if($isValidated) disabled title="This CPR is already validated" @endif>
            Update
          </button>
          @break
          @default
          <span class="badge bg-secondary">{{ $emp->pivot->status }}</span>
          @endswitch
          <br>
          @empty
          N/A
          @endforelse

          @if($isValidated)
          <small class="text-muted d-block mt-1">Already validated</small>
          @endif

          <!-- Download Signed PDF Button -->
          <a href="{{ route('authentic-copy.wetSign', $cpr->id) }}"
            class="btn btn-sm btn-success mt-1">
            <i class="bi bi-download"></i> Download Signed PDF
          </a>

          @else
          <button
            class="btn btn-sm btn-warning requestActivationBtn"
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
            value="{{ auth()->user()->id }}"
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

    $('.updateCprBtn').on('click', function() {
      $('#update_cpr_id').val($(this).data('cpr-id'));
      $('#update_employee_id').val($(this).data('employee-id'));
      $('#update_rating').val($(this).data('rating'));

      $('#updateCprEmployeeModal').modal('show');
    });

    // ✅ FIXED SUBMIT HANDLER
    $('#updateCprEmployeeForm').on('submit', function(e) {
      e.preventDefault();

      const cprId = $('#update_cpr_id').val();
      const formData = new FormData(this); // 👈 INCLUDES cpr_file

      $.ajax({
        url: '/employee/' + cprId + '/update',
        method: 'POST', // 👈 MUST be POST
        data: formData,
        processData: false, // 👈 REQUIRED
        contentType: false, // 👈 REQUIRED
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
          alert('Rating updated successfully!');
          location.reload();
        },
        error: function(err) {
          console.error(err.responseText);
          alert('Error updating rating.');
        }
      });
    });

  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const requestBtn = document.getElementById('requestAuthenticCopyBtn');

    document.addEventListener('change', () => {
      const checked = document.querySelectorAll('.employeeCheckbox:checked');
      requestBtn.disabled = checked.length === 0;
    });

    requestBtn.addEventListener('click', () => {
      const selected = [...document.querySelectorAll('.employeeCheckbox:checked')]
        .map(cb => ({
          cpr_id: cb.dataset.cprId,
          rating: cb.dataset.rating
        }));

      fetch("{{ route('authentic-copy.request') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({
            selections: selected
          })
        })
        .then(res => res.json())
        .then(data => {
          alert(data.message);
          location.reload();
        });
    });
  });
</script>

@endsection