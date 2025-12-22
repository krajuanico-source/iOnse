@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
<style>
  input[readonly] {
      background-color: #e9ecef;
      cursor: not-allowed;
  }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Users</h4>
      <button id="openModalBtn" class="btn btn-success">Add User</button>
    </div>

    <div class="table-responsive">
      <table id="usersTable" class="table table-striped">
        <thead class="table-light">
          <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 15%;">Name</th>
            <th style="width: 25%;">Division</th>
            <th style="width: 25%;">Section</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 15%;">Action</th>
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
              <input type="text" name="first_name" id="first_name" class="form-control text-uppercase" required readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label>Middle Name</label>
              <input type="text" name="middle_name" id="middle_name" class="form-control text-uppercase" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label>Last Name</label>
              <input type="text" name="last_name" id="last_name" class="form-control text-uppercase" required readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label>Extension Name</label>
              <input type="text" name="extension_name" id="extension_name" class="form-control text-uppercase" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label>Division</label>
              <select name="division_id" id="division_id" class="form-control" required>
                <option value="">Select Division</option>
                @foreach($divisions as $division)
                  <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Section</label>
              <select name="section_id" id="section_id" class="form-control" required>
                <option value="">Select Section</option>
              </select>
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

<!-- Deactivate / Activate Modal -->
<div class="modal fade" id="confirmDeactivateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Status Change</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="modalBodyText">Are you sure?</p>
        <div class="mb-3" id="remarksContainer">
          <label for="remarks" class="form-label">Remarks / Reason:</label>
          <textarea id="remarks" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeactivateBtn" class="btn btn-danger">Confirm</button>
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
let deactivateId = null;

// Initialize DataTable
const table = $('#usersTable').DataTable({
  processing: true,
  serverSide: true,
  ajax: "{{ route('user-management.list') }}",
  columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
    { data: 'name', name: 'name' },
    { data: 'division', name: 'division' },
    { data: 'section', name: 'section' },
    { data: 'is_active', name: 'is_active' },
    { data: 'action', name: 'action', orderable:false, searchable:false },
  ]
});

// Open Add User Modal
$('#openModalBtn').click(function() {
  $('#userForm')[0].reset();
  $('#user_id').val('');
  $('#section_id').html('<option value="">Select Section</option>');
  $('#userModalTitle').text('Add New User');
  $('#saveUserBtn').text('Save');
  new bootstrap.Modal(document.getElementById('userModal')).show();
});

// Load Sections based on Division
function loadSections(divisionId, selectedSectionId = null) {
    if (!divisionId) {
        $('#section_id').html('<option value="">Select Section</option>');
        return;
    }
    $.get(`/planning/user-management/sections/${divisionId}`, function(data) {
        let options = '<option value="">Select Section</option>';
        data.forEach(section => options += `<option value="${section.id}">${section.name}</option>`);
        $('#section_id').html(options);
        if (selectedSectionId) $('#section_id').val(selectedSectionId);
    });
}

$('#division_id').on('change', function() {
    loadSections($(this).val());
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
        $('#division_id').val(data.division_id);
        loadSections(data.division_id, data.section_id);
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
      table.ajax.reload(null, false);
    },
    error: function(xhr) {
      const errors = xhr.responseJSON?.errors;
      if(errors) $.each(errors, (key, value) => toastr.error(value[0]));
      else toastr.error('Something went wrong!');
    }
  });
});

let toggleUserId = null;
let toggleAction = '';

$('#usersTable').on('click', '.toggle-status', function() {
    toggleUserId = $(this).data('id');
    toggleAction = $(this).text().trim().toLowerCase();

    if (toggleAction === 'deactivate') {
        $('#confirmDeactivateModal .modal-title').text('Confirm Deactivate');
        $('#modalBodyText').text('Are you sure you want to deactivate this user?');
        $('#confirmDeactivateBtn').text('Deactivate');
        $('#remarksContainer').show(); // show remarks
        $('#remarks').val('');
        new bootstrap.Modal(document.getElementById('confirmDeactivateModal')).show();
    } else if (toggleAction === 'activate') {
        $('#confirmDeactivateModal .modal-title').text('Confirm Activate');
        $('#modalBodyText').text('Are you sure you want to activate this user?');
        $('#confirmDeactivateBtn').text('Activate');
        $('#remarksContainer').hide(); // hide remarks for activation
        new bootstrap.Modal(document.getElementById('confirmDeactivateModal')).show();
    }
});

$('#confirmDeactivateBtn').click(function() {
    if (!toggleUserId) return;

    if (toggleAction === 'deactivate') {
        const reason = $('#remarks').val().trim();
        if (!reason) {
            toastr.error('Please provide a reason for deactivation.');
            return;
        }

        $.ajax({
            url: `/planning/user-management/deactivate/${toggleUserId}`,
            type: 'PATCH',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { reason: reason },
            success: function(response) {
                toastr.success(response.success);
                bootstrap.Modal.getInstance(document.getElementById('confirmDeactivateModal')).hide();
                $('#remarks').val('');
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                toastr.error('Failed to deactivate user.');
            }
        });

    } else if (toggleAction === 'activate') {
        $.ajax({
            url: `/planning/user-management/activate/${toggleUserId}`,
            type: 'PATCH',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                toastr.success(response.success);
                bootstrap.Modal.getInstance(document.getElementById('confirmDeactivateModal')).hide();
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                toastr.error('Failed to activate user.');
            }
        });
    }
});





</script>
@endpush
