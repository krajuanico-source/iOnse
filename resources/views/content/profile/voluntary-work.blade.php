@extends('layouts/contentNavbarLayout')

@section('title', 'Voluntary Works')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Voluntary Works</h4>
      <button class="btn btn-success" id="openVoluntaryModalBtn">Add Voluntary Work</button>
    </div>

    <div class="table-responsive">
      <table id="voluntaryTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>Name & Address of Organization</th>
            <th>From</th>
            <th>To</th>
            <th>Number of Hours</th>
            <th>Position / Nature of Work</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($voluntaryWorks as $work)
          <tr data-id="{{ $work->id }}">
            <td>{{ $work->organization_name }}</td>
            <td>{{ $work->date_from }}</td>
            <td>{{ $work->date_to }}</td>
            <td>{{ $work->number_of_hours }}</td>
            <td>{{ $work->position_nature_of_work }}</td>
            <td>
              <button 
                class="btn btn-sm btn-primary edit-voluntary-btn"
                data-id="{{ $work->id }}"
                data-org="{{ $work->organization_name }}"
                data-from="{{ $work->date_from }}"
                data-to="{{ $work->date_to }}"
                data-hours="{{ $work->number_of_hours }}"
                data-position="{{ $work->position_nature_of_work }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-voluntary-btn" data-id="{{ $work->id }}">
                Delete
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="voluntaryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="voluntaryForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Voluntary Work</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name & Address of Organization</label>
            <input type="text" name="organization_name" class="form-control uppercase" required>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label>FROM</label>
              <input type="date" name="date_from" class="form-control uppercase" required>
            </div>
            <div class="col-md-6">
              <label>TO</label>
              <input type="date" name="date_to" class="form-control uppercase" required>
            </div>
          </div>
          <div class="mb-3">
            <label>Number of Hours</label>
            <input type="number" name="number_of_hours" class="form-control uppercase" min="0">
          </div>
          <div class="mb-3">
            <label>Position / Nature of Work</label>
            <input type="text" name="position_nature_of_work" class="form-control uppercase">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editVoluntaryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editVoluntaryForm">
        @csrf
        <input type="hidden" name="id" id="editVoluntaryId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Voluntary Work</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name & Address of Organization</label>
            <input type="text" name="organization_name" id="editOrg" class="form-control uppercase" required>
          </div>
          <div class="row mb-3">
            <div class="col-md-6"><label>FROM</label><input type="date" name="date_from" id="editFrom" class="form-control uppercase" required></div>
            <div class="col-md-6"><label>TO</label><input type="date" name="date_to" id="editTo" class="form-control uppercase" required></div>
          </div>
          <div class="mb-3"><label>Number of Hours</label><input type="number" name="number_of_hours" id="editHours" class="form-control uppercase"></div>
          <div class="mb-3"><label>Position / Nature of Work</label><input type="text" name="position_nature_of_work" id="editPosition" class="form-control uppercase"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Delete -->
<div class="modal fade" id="confirmDeleteVoluntaryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this voluntary work?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteVoluntaryBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$('#openVoluntaryModalBtn').click(() => {
    $('#voluntaryForm')[0].reset();
    new bootstrap.Modal(document.getElementById('voluntaryModal')).show();
});

// Fill Edit Modal
$(document).on('click', '.edit-voluntary-btn', function() {
    $('#editVoluntaryId').val($(this).data('id'));
    $('#editOrg').val($(this).data('org'));
    $('#editFrom').val($(this).data('from'));
    $('#editTo').val($(this).data('to'));
    $('#editHours').val($(this).data('hours'));
    $('#editPosition').val($(this).data('position'));
    new bootstrap.Modal(document.getElementById('editVoluntaryModal')).show();
});

// Add
$('#voluntaryForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("profile.voluntary-work.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: (res) => {
            toastr.success('Voluntary work added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('voluntaryModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to add voluntary work.')
    });
});

// Update
$('#editVoluntaryForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editVoluntaryId').val();
    $.ajax({
        url: '{{ route("profile.voluntary-work.update", ":id") }}'.replace(':id', id),
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Voluntary work updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editVoluntaryModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to update voluntary work.')
    });
});

// Delete
let voluntaryDeleteId = null;
$(document).on('click', '.delete-voluntary-btn', function() {
    voluntaryDeleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteVoluntaryModal')).show();
});

$('#confirmDeleteVoluntaryBtn').click(function() {
    if (!voluntaryDeleteId) return;
    $.ajax({
        url: `/profile/voluntary-work/${voluntaryDeleteId}`,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Voluntary work deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteVoluntaryModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to delete voluntary work.')
    });
});

// DataTable
$('#voluntaryTable').DataTable({
    paging: true,
    searching: true,
    info: true
});
</script>
@endpush
