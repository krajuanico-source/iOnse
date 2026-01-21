@extends('layouts/contentNavbarLayout')

@section('title', 'CPR List')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
$user = auth()->user();
$userId = $user->id;
@endphp

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold mb-0">CPR – Employee Ratings</h4>
  </div>
  <div class="text-end mb-3">
    <button class="btn btn-info" id="viewMyRequestsBtn">
      <i class="bi bi-card-list"></i> View My Requests
    </button>
  </div>

  {{-- REQUEST AUTHENTIC COPY --}}
  <div class="text-end mb-3">
    <button class="btn btn-success" id="requestAuthenticCopyBtn" disabled>
      Request Authentic Copy
    </button>
  </div>

  {{-- TABLE --}}
  <table id="cpremployeesTable" class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Select</th>
        <th>Rating</th>
        <th hidden>CPR ID</th>
        <th>Supporting File</th>
        <th>Date Created</th>
        <th>Status</th>
        <th width="260">Action</th>
      </tr>
    </thead>

    <tbody>
      @foreach($cprs as $cpr)
      @php
      $employee = $cpr->employees->firstWhere('user_id', $userId);
      $employeeStatus = $employee?->pivot->status ?? 'Pending';
      $isValidated = $employeeStatus === 'Validated';
      $isActive = strtolower($cpr->status) === 'active';
      $filePath = $employee?->cpr_file;
      @endphp

      <tr>
        <td>{{ $cpr->id }}</td>

        {{-- SELECT --}}
        <td>
          @forelse($cpr->employees as $emp)
          <div class="form-check">
            <input class="form-check-input employeeCheckbox"
              type="checkbox"
              data-cpr-id="{{ $cpr->id }}"
              data-rating="{{ $emp->rating }}"
              @checked($emp->employee_id === $user->employee?->id && $emp->status === 'Validated')
            @disabled($emp->status !== 'Validated')
            >
          </div>
          @empty
          <span class="text-muted">N/A</span>
          @endforelse
        </td>

        {{-- RATING --}}
        <td>
          @forelse($cpr->employees as $emp)
          {{ $emp->rating }}<br>
          @empty
          N/A
          @endforelse
        </td>

        <td hidden>{{ $cpr->id }}</td>

        {{-- SUPPORTING FILE --}}
        <td class="text-center">
          @if($filePath && Storage::disk('public')->exists($filePath))
          <a href="{{ asset('storage/'.$filePath) }}"
            target="_blank"
            class="btn btn-sm btn-outline-primary">
            View File
          </a>
          @else
          <span class="text-muted">No File</span>
          @endif
        </td>

        {{-- DATE --}}
        <td>{{ $cpr->created_at->format('Y-m-d') }}</td>

        {{-- STATUS --}}
        <td>
          @forelse($cpr->employees as $emp)
          <span class="badge {{ $emp->status === 'Validated' ? 'bg-success' : 'bg-warning text-dark' }}">
            {{ $emp->status ?? 'Pending' }}
          </span><br>
          @empty
          N/A
          @endforelse
        </td>

        {{-- ACTION --}}
        <td>
          @if($isActive)
          <button class="btn btn-sm btn-primary updateCprBtn"
            data-cpr-id="{{ $cpr->id }}"
            data-employee-id="{{ $userId }}"
            data-rating="{{ $employee?->rating }}"
            @disabled($isValidated)
            title="{{ $isValidated ? 'Already validated' : '' }}">
            Update
          </button>

          @if($isValidated)
          <small class="text-muted d-block mt-1">Already validated</small>
          @endif
          @else
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

{{-- UPDATE MODAL --}}
<div class="modal fade" id="updateCprEmployeeModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateCprEmployeeForm" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Update CPR Employee Rating</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="update_cpr_id">

        <div class="mb-3">
          <label class="form-label">Employee ID</label>
          <input class="form-control" value="{{ $userId }}" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Rating</label>
          <input type="number" class="form-control" id="update_rating" min="0" max="100" step="0.01" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Upload Supporting File</label>
          <input type="file" name="cpr_file" class="form-control"
            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="myRequestsModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">My Authentic Copy Requests</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="myRequestsTable">
          <thead class="table-light">
            <tr>
              <th>Request ID</th>
              <th>Status</th>
              <th>Date Requested</th>
              <th>Action</th> <!-- ✅ Download button -->
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $(function() {

    $('#cpremployeesTable').DataTable();

    // Enable request button
    $(document).on('change', '.employeeCheckbox', function() {
      $('#requestAuthenticCopyBtn').prop(
        'disabled',
        $('.employeeCheckbox:checked').length === 0
      );
    });

    // Request activation
    $(document).on('click', '.requestActivationBtn', function() {
      if (!confirm('Send request to HR to activate this rating period?')) return;

      $.post("{{ route('cpr.requestActivation') }}", {
        _token: "{{ csrf_token() }}",
        cpr_id: $(this).data('cpr-id')
      }).done(res => {
        toastr.success(res.message);
        location.reload();
      });
    });

    // Open update modal
    $(document).on('click', '.updateCprBtn', function() {
      $('#update_cpr_id').val($(this).data('cpr-id'));
      $('#update_rating').val($(this).data('rating'));
      $('#updateCprEmployeeModal').modal('show');
    });

    // Submit update
    $('#updateCprEmployeeForm').submit(function(e) {
      e.preventDefault();
      const cprId = $('#update_cpr_id').val();
      const formData = new FormData(this);

      $.ajax({
        url: `/employee/${cprId}/update`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
      }).done(() => {
        toastr.success('Rating updated successfully');
        location.reload();
      }).fail(() => toastr.error('Error updating rating'));
    });

    // Request authentic copy
    $('#requestAuthenticCopyBtn').click(function() {
      const selections = $('.employeeCheckbox:checked').map(function() {
        return {
          cpr_id: $(this).data('cpr-id'),
          rating: $(this).data('rating')
        };
      }).get();

      fetch("{{ route('authentic-copy.request') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify({
            selections
          })
        })
        .then(res => res.json())
        .then(data => {
          toastr.success(data.message);
          location.reload();
        });
    });

  });
</script>
<script>
  $(document).ready(function() {
    $('#viewMyRequestsBtn').click(function() {
      $.get("{{ route('cpr.getMyRequests') }}", function(data) {
        let tbody = '';
        data.forEach((req, index) => {
          const statusBadge = req.status === 'Approved' ?
            '<span class="badge bg-success">Approved</span>' :
            req.status === 'Rejected' ?
            '<span class="badge bg-danger">Rejected</span>' :
            '<span class="badge bg-warning text-dark">Pending</span>';

          // Download button only if approved and file exists
          const downloadBtn = (req.status === 'Approved' && req.signed_pdf_path) ?
            `<a href="/storage/${req.signed_pdf_path}"
                          target="_blank"
                          class="btn btn-sm btn-success">
                          <i class="bi bi-download"></i> Download
                       </a>` :
            `<button class="btn btn-sm btn-secondary" disabled>
                          <i class="bi bi-download"></i> Download
                       </button>`;

          tbody += `<tr>
                            <td>${req.id}</td>
                            <td>${statusBadge}</td>
                            <td>${new Date(req.created_at).toLocaleString()}</td>
                            <td class="text-center">${downloadBtn}</td>
                          </tr>`;
        });
        $('#myRequestsTable tbody').html(tbody);
        $('#myRequestsModal').modal('show');
      });
    });
  });
</script>

@endsection
