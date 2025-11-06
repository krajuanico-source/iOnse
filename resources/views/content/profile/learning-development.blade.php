@extends('layouts/contentNavbarLayout')

@section('title', 'Learning and Development')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Learning and Development</h4>
      <button class="btn btn-success" id="openLdModalBtn">Add Training</button>
    </div>

    <div class="table-responsive">
      <table id="ldTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>Title of Training</th>
            <th>No. of Hours</th>
            <th>Type of LD</th>
            <th>Conducted / Sponsored By</th>
            <th>From</th>
            <th>To</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trainings as $t)
          <tr data-id="{{ $t->id }}">
            <td>{{ $t->title }}</td>
            <td>{{ $t->number_of_hours }}</td>
            <td>{{ $t->type_of_ld }}</td>
            <td>{{ $t->conducted_by }}</td>
            <td>{{ $t->inclusive_date_from }}</td>
            <td>{{ $t->inclusive_date_to }}</td>
            <td>
              <button 
                class="btn btn-sm btn-primary edit-ld-btn"
                data-id="{{ $t->id }}"
                data-title="{{ $t->title }}"
                data-from="{{ $t->inclusive_date_from }}"
                data-to="{{ $t->inclusive_date_to }}"
                data-hours="{{ $t->number_of_hours }}"
                data-type="{{ $t->type_of_ld }}"
                data-conducted="{{ $t->conducted_by }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-ld-btn" data-id="{{ $t->id }}">
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
<div class="modal fade" id="ldModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="ldForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Training</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Title of Training</label>
            <input type="text" name="title" class="form-control uppercase" required>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label>FROM</label>
              <input type="date" name="inclusive_date_from" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>TO</label>
              <input type="date" name="inclusive_date_to" class="form-control">
            </div>
          </div>
          <div class="mb-3">
            <label>Number of Hours</label>
            <input type="number" name="number_of_hours" class="form-control" min="0">
          </div>
          <div class="mb-3">
            <label>Type of LD</label>
            <input type="text" name="type_of_ld" class="form-control uppercase">
          </div>
          <div class="mb-3">
            <label>Conducted / Sponsored By</label>
            <input type="text" name="conducted_by" class="form-control uppercase">
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
<div class="modal fade" id="editLdModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editLdForm">
        @csrf
        <input type="hidden" name="id" id="editLdId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Training</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Title of Training</label>
            <input type="text" name="title" id="editTitle" class="form-control uppercase" required>
          </div>
          <div class="row mb-3">
            <div class="col-md-6"><label>FROM</label><input type="date" name="inclusive_date_from" id="editFrom" class="form-control" required></div>
            <div class="col-md-6"><label>TO</label><input type="date" name="inclusive_date_to" id="editTo" class="form-control"></div>
          </div>
          <div class="mb-3"><label>Number of Hours</label><input type="number" name="number_of_hours" id="editHours" class="form-control"></div>
          <div class="mb-3"><label>Type of LD</label><input type="text" name="type_of_ld" id="editType" class="form-control uppercase"></div>
          <div class="mb-3"><label>Conducted / Sponsored By</label><input type="text" name="conducted_by" id="editConducted" class="form-control uppercase"></div>
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
<div class="modal fade" id="confirmDeleteLdModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this training?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteLdBtn" class="btn btn-danger">Delete</button>
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
$('#openLdModalBtn').click(() => {
    $('#ldForm')[0].reset();
    new bootstrap.Modal(document.getElementById('ldModal')).show();
});

// Fill Edit Modal
$(document).on('click', '.edit-ld-btn', function() {
    $('#editLdId').val($(this).data('id'));
    $('#editTitle').val($(this).data('title'));
    $('#editFrom').val($(this).data('from'));
    $('#editTo').val($(this).data('to'));
    $('#editHours').val($(this).data('hours'));
    $('#editType').val($(this).data('type'));
    $('#editConducted').val($(this).data('conducted'));
    new bootstrap.Modal(document.getElementById('editLdModal')).show();
});

// Add
$('#ldForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("profile.ld.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Training added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('ldModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to add training.')
    });
});

// Update
$('#editLdForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editLdId').val();
    $.ajax({
        url: '{{ route("profile.ld.update", ":id") }}'.replace(':id', id),
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Training updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editLdModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to update training.')
    });
});

// Delete
let ldDeleteId = null;
$(document).on('click', '.delete-ld-btn', function() {
    ldDeleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteLdModal')).show();
});

$('#confirmDeleteLdBtn').click(function() {
    if (!ldDeleteId) return;
    $.ajax({
        url: `/profile/learning-and-development/${ldDeleteId}`,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Training deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteLdModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to delete training.')
    });
});

// DataTable
$('#ldTable').DataTable({
    paging: true,
    searching: true,
    info: true
});
</script>
@endpush
