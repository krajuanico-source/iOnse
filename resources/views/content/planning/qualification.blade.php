@extends('layouts/contentNavbarLayout')

@section('title', 'Qualifications')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Qualifications</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Qualification</button>
    </div>

    <div class="table-responsive">
      <table id="qualificationTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($qualifications as $index => $qualification)
          <tr data-id="{{ $qualification->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ Str::upper($qualification->title) }}</td>
            <td>{{ Str::upper($qualification->description) }}</td>
            <td>
              <div class="d-flex gap-1">
                <button
                  class="btn btn-sm btn-primary edit-btn"
                  data-id="{{ $qualification->id }}"
                  data-title="{{ $qualification->title }}"
                  data-description="{{ $qualification->description }}">
                  Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $qualification->id }}">
                  Delete
                </button>
              </div>
            </td>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Qualification Modal -->
<div class="modal fade" id="qualificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Qualification</h5>
      <form id="qualificationForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control text-uppercase" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Qualification Modal -->
<div class="modal fade" id="editQualificationModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editQualificationForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Qualification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editQualificationId">
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" id="editQualificationTitle" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="editQualificationDescription" class="form-control text-uppercase" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Are you sure you want to delete this qualification?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
  $('#qualificationTable').DataTable();

  let deleteId = null;

  $('#openModalBtn').click(function () {
    $('#qualificationForm')[0].reset();
    new bootstrap.Modal('#qualificationModal').show();
  });

  $('#qualificationForm').submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: '{{ route("qualifications.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function (res) {
        toastr.success("Qualification added!");
        bootstrap.Modal.getInstance(document.getElementById('qualificationModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function () {
        toastr.error("Something went wrong.");
      }
    });
  });

  $(document).on('click', '.edit-btn', function () {
    $('#editQualificationId').val($(this).data('id'));
    $('#editQualificationTitle').val($(this).data('title'));
    $('#editQualificationDescription').val($(this).data('description'));
    new bootstrap.Modal('#editQualificationModal').show();
  });

  $('#editQualificationForm').submit(function (e) {
    e.preventDefault();
    const id = $('#editQualificationId').val();

    $.ajax({
      url: `/planning/qualification/${id}`,
      method: 'PUT',
      data: $(this).serialize(),
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function () {
        toastr.success("Qualification updated!");
        bootstrap.Modal.getInstance(document.getElementById('editQualificationModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function () {
        toastr.error("Update failed.");
      }
    });
  });

  $(document).on('click', '.delete-btn', function () {
    deleteId = $(this).data('id');
    new bootstrap.Modal('#confirmDeleteModal').show();
  });

  $('#confirmDeleteBtn').click(function () {
    if (!deleteId) return;

    $.ajax({
      url: `/planning/qualification/${deleteId}`,
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function () {
        toastr.success("Qualification deleted!");
        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: function () {
        toastr.error("Failed to delete.");
      }
    });
  });
</script>
@endpush
