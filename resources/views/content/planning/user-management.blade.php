@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Users</h4>

    </div>

    <div class="table-responsive">
        <table id="usersTable" class="table table-striped">
        <thead class="table-light">
            <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
            </tr>
        </thead>
        </table>
    </div>
  </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <h5 class="modal-title m-3" id="userModalTitle">Add New User</h5>
      <form id="userForm">
        <div class="modal-body">
          <input type="hidden" name="user_id" id="user_id">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>First Name</label>
              <input type="text" name="first_name" id="first_name" class="form-control text-uppercase" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Middle Name</label>
              <input type="text" name="middle_name" id="middle_name" class="form-control text-uppercase">
            </div>
            <div class="col-md-6 mb-3">
              <label>Last Name</label>
              <input type="text" name="last_name" id="last_name" class="form-control text-uppercase" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Extension Name</label>
              <input type="text" name="extension_name" id="extension_name" class="form-control text-uppercase">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" id="saveUserBtn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Are you sure you want to delete this user?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
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

// Initialize DataTable
const table = $('#usersTable').DataTable({
  processing: true,
  serverSide: true,
  ajax: "{{ route('user-management.list') }}",
  columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
    { data: 'name', name: 'name' },
    { data: 'role', name: 'role' },
    { data: 'is_active', name: 'is_active' },
    { data: 'action', name: 'action', orderable:false, searchable:false },
  ]
});

// Open Add User Modal
$('#openModalBtn').click(function() {
  $('#userForm')[0].reset();
  $('#user_id').val('');
  $('#userModalTitle').text('Add New User');
  $('#saveUserBtn').text('Save');
  new bootstrap.Modal(document.getElementById('userModal')).show();
});

// Edit User
$('#usersTable').on('click', '.edit', function() {
  const user_id = $(this).data('id');
  $.get(`/planning/user-management/edit/${user_id}`, function(data) {
    $('#user_id').val(data.id);
    $('#first_name').val(data.first_name);
    $('#middle_name').val(data.middle_name);
    $('#last_name').val(data.last_name);
    $('#extension_name').val(data.extension_name);
    $('#role').val(data.role);
    $('#userModalTitle').text('Edit User');
    $('#saveUserBtn').text('Update');
    new bootstrap.Modal(document.getElementById('userModal')).show();
  });
});

// Save / Update User
$('#userForm').submit(function(e) {
  e.preventDefault();
  const user_id = $('#user_id').val();
  const url = user_id ? `/planning/user-management/update/${user_id}` : "{{ route('user-management.store') }}";

  $.ajax({
    url: url,
    type: 'POST',
    data: $(this).serialize(),
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function(response) {
      toastr.success(response.success);
      bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
      table.ajax.reload(null, false); // Reload table without resetting pagination
    },
    error: function(xhr) {
      const errors = xhr.responseJSON?.errors;
      if(errors){
        $.each(errors, (key, value) => toastr.error(value[0]));
      } else {
        toastr.error('Something went wrong!');
      }
    }
  });
});

// Delete User
$('#usersTable').on('click', '.delete', function() {
  deleteId = $(this).data('id');
  new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
});

$('#confirmDeleteBtn').click(function() {
  if(!deleteId) return;
  $.ajax({
    url: `/planning/user-management/destroy/${deleteId}`,
    type: 'DELETE',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function(response) { 
      toastr.success(response.success);
      bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
      table.ajax.reload(null, false); // Reload table without resetting pagination
    },
    error: function() {
      toastr.error('Failed to delete user.');
    }
  });
});

</script>
@endpush
