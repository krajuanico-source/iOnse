@extends('layouts/contentNavbarLayout')

@section('title', 'Non-Academic Distinctions')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Non-Academic Distinctions / Recognitions</h4>
      <button class="btn btn-success" id="openNonAcademicModalBtn">Add Recognition</button>
    </div>

    <div class="table-responsive">
      <table id="nonAcademicTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>ID</th>
            <th>Recognition / Distinction</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($nonAcademics as $n)
          <tr data-id="{{ $n->id }}">
            <td>{{ $n->id }}</td>
            <td>{{ $n->recognition }}</td>
            <td class="text-center">
              <button 
                class="btn btn-sm btn-primary edit-nonacademic-btn"
                data-id="{{ $n->id }}"
                data-recognition="{{ $n->recognition }}">
                Update
              </button>
              <button 
                class="btn btn-sm btn-danger delete-nonacademic-btn"
                data-id="{{ $n->id }}">
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
<div class="modal fade" id="nonAcademicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="nonAcademicForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Recognition</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Distinction / Recognition</label>
            <input type="text" name="recognition" class="form-control" required>
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
<div class="modal fade" id="editNonAcademicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editNonAcademicForm">
        @csrf
        <input type="hidden" name="id" id="editNonAcademicId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Recognition</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Distinction / Recognition</label>
            <input type="text" name="recognition" id="editRecognition" class="form-control" required>
          </div>
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
<div class="modal fade" id="confirmDeleteNonAcademicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteNonAcademicBtn" class="btn btn-danger">Delete</button>
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
let deleteId = null;

// 🆕 Add Modal
$('#openNonAcademicModalBtn').click(() => {
    $('#nonAcademicForm')[0].reset();
    new bootstrap.Modal(document.getElementById('nonAcademicModal')).show();
});

// ✏️ Fill Edit Modal
$(document).on('click', '.edit-nonacademic-btn', function() {
    $('#editNonAcademicId').val($(this).data('id'));
    $('#editRecognition').val($(this).data('recognition'));
    new bootstrap.Modal(document.getElementById('editNonAcademicModal')).show();
});

// 💾 Add
$('#nonAcademicForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("profile.non-academic.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Recognition added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('nonAcademicModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to add recognition.')
    });
});

// 📝 Update
$('#editNonAcademicForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editNonAcademicId').val();
    $.ajax({
        url: `/profile/non-academic/${id}`,
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Recognition updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editNonAcademicModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to update recognition.')
    });
});

// 🗑️ Delete
$(document).on('click', '.delete-nonacademic-btn', function() {
    deleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteNonAcademicModal')).show();
});

$('#confirmDeleteNonAcademicBtn').click(function() {
    if (!deleteId) return;
    $.ajax({
        url: `/profile/non-academic/${deleteId}`,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Recognition deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteNonAcademicModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to delete recognition.')
    });
});

// 📊 DataTable Init
$('#nonAcademicTable').DataTable({
    paging: true,
    searching: true,
    info: true
});
</script>
@endpush
