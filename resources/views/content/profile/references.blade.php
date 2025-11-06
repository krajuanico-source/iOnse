@extends('layouts/contentNavbarLayout')

@section('title', 'References')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">References</h4>
      <button class="btn btn-success" id="openRefModalBtn">Add Reference</button>
    </div>

    <div class="table-responsive">
      <table id="refTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Position</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($references as $r)
          <tr data-id="{{ $r->id }}">
            <td>{{ $r->name }}</td>
            <td>{{ $r->contact_number }}</td>
            <td>{{ $r->position }}</td>
            <td>
              <button 
                class="btn btn-sm btn-primary edit-ref-btn"
                data-id="{{ $r->id }}"
                data-name="{{ $r->name }}"
                data-contact="{{ $r->contact_number }}"
                data-position="{{ $r->position }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-ref-btn" data-id="{{ $r->id }}">
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
<div class="modal fade" id="refModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="refForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Reference</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control uppercase" required>
          </div>
          <div class="mb-3">
            <label>Contact Number</label>
            <input type="text" name="contact_number" class="form-control">
          </div>
          <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" class="form-control uppercase">
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
<div class="modal fade" id="editRefModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editRefForm">
        @csrf
        <input type="hidden" name="id" id="editRefId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Reference</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" id="editRefName" class="form-control uppercase" required>
          </div>
          <div class="mb-3">
            <label>Contact Number</label>
            <input type="text" name="contact_number" id="editRefContact" class="form-control">
          </div>
          <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" id="editRefPosition" class="form-control uppercase">
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
<div class="modal fade" id="confirmDeleteRefModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this reference?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteRefBtn" class="btn btn-danger">Delete</button>
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
$('#openRefModalBtn').click(() => {
    $('#refForm')[0].reset();
    new bootstrap.Modal(document.getElementById('refModal')).show();
});

// Fill Edit Modal
$(document).on('click', '.edit-ref-btn', function() {
    $('#editRefId').val($(this).data('id'));
    $('#editRefName').val($(this).data('name'));
    $('#editRefContact').val($(this).data('contact'));
    $('#editRefPosition').val($(this).data('position'));
    new bootstrap.Modal(document.getElementById('editRefModal')).show();
});

// Add
$('#refForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("profile.references.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Reference added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('refModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to add reference.')
    });
});

// Update
$('#editRefForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editRefId').val();
    $.ajax({
        url: '{{ route("profile.references.update", ":id") }}'.replace(':id', id),
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Reference updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editRefModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to update reference.')
    });
});

// Delete
let refDeleteId = null;
$(document).on('click', '.delete-ref-btn', function() {
    refDeleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteRefModal')).show();
});

$('#confirmDeleteRefBtn').click(function() {
    if (!refDeleteId) return;
    $.ajax({
        url: `/profile/references/${refDeleteId}`,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Reference deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteRefModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to delete reference.')
    });
});

// DataTable
$('#refTable').DataTable({
    paging: true,
    searching: true,
    info: true
});
</script>
@endpush
