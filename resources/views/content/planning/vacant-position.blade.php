@extends('layouts/contentNavbarLayout')

@section('title', 'Vacant Position')

@section('content')

@stack('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Vacant Positions</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add Vacant Position</button>
    </div>

    <div class="table-responsive">
      <table id="vacantPositionTable" class="table table-striped display">
        <thead class="table-light">
          <tr>
            <th>Position Title</th>
            <th>Required Qualification</th>
            <th>Qualified Staff</th>
          </tr>
        </thead>
        <tbody>
        @forelse($vacantPositions as $position)
          <tr>
            <td>{{ $position->position->title ?? 'Not Set' }}</td>
            <td>{{ $position->qualification->qualification_name ?? 'Not Set' }}</td>
            <td>
              @if($position->qualifiedStaff->count())
                <ul class="mb-0 ps-3">
                  @foreach($position->qualifiedStaff as $staff)
                    <li>{{ $staff->first_name }} {{ $staff->last_name }}</li>
                  @endforeach
                </ul>
              @else
                <span class="text-muted">No qualified staff</span>
              @endif
            </td>
          </tr>
        @empty
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Vacant Position Modal -->
<div class="modal fade" id="vacantPositionModal" tabindex="-1" aria-labelledby="vacantPositionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="vacantPositionForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Vacant Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="position_id" class="form-label">Position Title</label>
            <select name="position_id" class="form-select" required>
              <option value="">Select Position</option>
              @foreach(\App\Models\Position::all() as $position)
                <option value="{{ $position->id }}">{{ $position->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="qualification_id" class="form-label">Required Qualification</label>
            <select name="qualification_id" class="form-select">
              <option value="">Select Qualification</option>
              @foreach(\App\Models\Qualification::all() as $qualification)
                <option value="{{ $qualification->id }}">{{ $qualification->qualification_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Post</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- DataTable Init -->
<script>
  $('#vacantPositionTable').DataTable();
</script>

<!-- Modal and AJAX Logic -->
<script>
  $(document).ready(function () {
    $('#openModalBtn').on('click', function () {
      $('#vacantPositionModal').modal('show');
    });

    $('#vacantPositionForm').on('submit', function (e) {
      e.preventDefault();

      $.ajax({
        type: 'POST',
        url: '{{ route("vacant.positions.store") }}',
        data: $(this).serialize(),
        success: function (response) {
          toastr.success(response.message);
          $('#vacantPositionModal').modal('hide');
          setTimeout(() => location.reload(), 1000);
        },
        error: function (xhr) {
          const errors = xhr.responseJSON?.errors;
          if (errors) {
            Object.values(errors).forEach(err => toastr.error(err[0]));
          } else {
            toastr.error('An error occurred while posting.');
          }
        }
      });
    });
  });
</script>
@endpush
