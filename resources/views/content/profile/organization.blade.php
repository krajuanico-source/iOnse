@extends('layouts/contentNavbarLayout')

@section('title', 'Organizations')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Organizations</h4>
      <button class="btn btn-success" id="openOrganizationModalBtn">Add Organization</button>
    </div>

    <div class="table-responsive">
      <table id="organizationTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>Organization Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($organizations as $o)
          <tr data-id="{{ $o->id }}">
            <td>{{ $o->organization_name }}</td>
            <td class="text-center">
              <button 
                class="btn btn-sm btn-primary edit-org-btn"
                data-id="{{ $o->id }}"
                data-name="{{ $o->organization_name }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-org-btn" data-id="{{ $o->id }}">
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
<div class="modal fade" id="organizationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="organizationForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Organization</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>Organization Name</label>
          <input type="text" name="organization_name" class="form-control" required>
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
<div class="modal fade" id="editOrganizationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editOrganizationForm">
        @csrf
        <input type="hidden" name="id" id="editOrganizationId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Organization</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>Organization Name</label>
          <input type="text" name="organization_name" id="editOrganizationName" class="form-control" required>
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
<div class="modal fade" id="confirmDeleteOrganizationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this organization?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteOrganizationBtn" class="btn btn-danger">Delete</button>
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

// ✅ Open Add Modal
$('#openOrganizationModalBtn').click(() => {
  $('#organizationForm')[0].reset();
  new bootstrap.Modal(document.getElementById('organizationModal')).show();
});

// ✅ Fill Edit Modal
$(document).on('click', '.edit-org-btn', function() {
  $('#editOrganizationId').val($(this).data('id'));
  $('#editOrganizationName').val($(this).data('name'));
  new bootstrap.Modal(document.getElementById('editOrganizationModal')).show();
});

// ✅ Add Organization
$('#organizationForm').submit(function(e) {
  e.preventDefault();
  $.ajax({
    url: '{{ route("profile.organization.store") }}',
    method: 'POST',
    data: $(this).serialize(),
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: () => {
      toastr.success('Organization added successfully!');
      bootstrap.Modal.getInstance(document.getElementById('organizationModal')).hide();
      setTimeout(() => location.reload(), 500);
    },
    error: () => toastr.error('Failed to add organization.')
  });
});

// ✅ Update Organization
$('#editOrganizationForm').submit(function(e) {
  e.preventDefault();
  const id = $('#editOrganizationId').val();
  $.ajax({
    url: '{{ route("profile.organization.update", ":id") }}'.replace(':id', id),
    method: 'POST',
    data: $(this).serialize() + '&_method=PUT',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: () => {
      toastr.success('Organization updated successfully!');
      bootstrap.Modal.getInstance(document.getElementById('editOrganizationModal')).hide();
      setTimeout(() => location.reload(), 500);
    },
    error: () => toastr.error('Failed to update organization.')
  });
});

// ✅ Delete Organization
$(document).on('click', '.delete-org-btn', function() {
  deleteId = $(this).data('id');
  new bootstrap.Modal(document.getElementById('confirmDeleteOrganizationModal')).show();
});

$('#confirmDeleteOrganizationBtn').click(function() {
  if (!deleteId) return;
  $.ajax({
    url: `/profile/organization/${deleteId}`,
    method: 'POST',
    data: { _method: 'DELETE' },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: () => {
      toastr.success('Organization deleted successfully!');
      bootstrap.Modal.getInstance(document.getElementById('confirmDeleteOrganizationModal')).hide();
      setTimeout(() => location.reload(), 500);
    },
    error: () => toastr.error('Failed to delete organization.')
  });
});

// ✅ DataTable Initialization
$('#organizationTable').DataTable({
  paging: true,
  searching: true,
  info: true
});
</script>
@endpush
