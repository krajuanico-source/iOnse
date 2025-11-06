@extends('layouts/contentNavbarLayout')

@section('title', 'Skills')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color:#1d4bb2;">Skills</h4>
      <button class="btn btn-success" id="openSkillModalBtn">Add Skill</button>
    </div>

    <div class="table-responsive">
      <table id="skillsTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>ID</th>
            <th>Skill Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($skills as $s)
          <tr data-id="{{ $s->id }}">
            <td>{{ $s->id }}</td>
            <td>{{ $s->skill_name }}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-primary edit-skill-btn"
                      data-id="{{ $s->id }}"
                      data-skill_name="{{ $s->skill_name }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-skill-btn"
                      data-id="{{ $s->id }}">
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
<div class="modal fade" id="skillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="skillForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Skill Name</label>
            <input type="text" name="skill_name" class="form-control" required>
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
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSkillForm">
        @csrf
        <input type="hidden" name="id" id="editSkillId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Skill Name</label>
            <input type="text" name="skill_name" id="editSkillName" class="form-control" required>
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
<div class="modal fade" id="confirmDeleteSkillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this skill?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteSkillBtn" class="btn btn-danger">Delete</button>
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
$('#openSkillModalBtn').click(() => {
  $('#skillForm')[0].reset();
  new bootstrap.Modal(document.getElementById('skillModal')).show();
});

// ✏️ Fill Edit Modal
$(document).on('click', '.edit-skill-btn', function() {
  $('#editSkillId').val($(this).data('id'));
  $('#editSkillName').val($(this).data('skill_name'));
  new bootstrap.Modal(document.getElementById('editSkillModal')).show();
});

// 💾 Add
$('#skillForm').submit(function(e) {
  e.preventDefault();
  $.ajax({
    url: '{{ route("profile.skills.store") }}',
    method: 'POST',
    data: $(this).serialize(),
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: () => {
      toastr.success('Skill added successfully!');
      bootstrap.Modal.getInstance(document.getElementById('skillModal')).hide();
      setTimeout(() => location.reload(), 500);
    },
    error: () => toastr.error('Failed to add skill.')
  });
});

// 📝 Update
$('#editSkillForm').submit(function(e) {
  e.preventDefault();
  const id = $('#editSkillId').val();
  $.ajax({
    url: `/profile/skills/${id}`,
    method: 'POST',
    data: $(this).serialize() + '&_method=PUT',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: () => {
      toastr.success('Skill updated successfully!');
      bootstrap.Modal.getInstance(document.getElementById('editSkillModal')).hide();
      setTimeout(() => location.reload(), 500);
    },
    error: () => toastr.error('Failed to update skill.')
  });
});

// 🗑️ Delete
$(document).on('click', '.delete-skill-btn', function() {
  deleteId = $(this).data('id');
  new bootstrap.Modal(document.getElementById('confirmDeleteSkillModal')).show();
});

$('#confirmDeleteSkillBtn').click(function() {
  if (!deleteId) return;
  $.ajax({
    url: `/profile/skills/${deleteId}`,
    method: 'POST',
    data: { _method: 'DELETE' },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: () => {
      toastr.success('Skill deleted successfully!');
      bootstrap.Modal.getInstance(document.getElementById('confirmDeleteSkillModal')).hide();
      setTimeout(() => location.reload(), 500);
    },
    error: () => toastr.error('Failed to delete skill.')
  });
});

// 📊 DataTable Init
$('#skillsTable').DataTable({
  paging: true,
  searching: true,
  info: true
});
</script>
@endpush
