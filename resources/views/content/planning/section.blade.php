@extends('layouts/contentNavbarLayout')

@section('title', 'Sections')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Sections</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Section</button>
    </div>

    <div class="table-responsive">
      <table id="sectionTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Division</th>
            <th>Section Name</th>
            <th>Abbreviation</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sections as $index => $section)
          <tr data-id="{{ $section->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $section->division->name }}</td>
            <td>{{ Str::upper($section->name) }}</td>
            <td>{{ Str::upper($section->abbreviation) }}</td>
          <td>
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $section->id }}"
                data-division_id="{{ $section->division_id }}"
                data-name="{{ $section->name }}"
                data-abbreviation="{{ $section->abbreviation }}">
                Edit
              </button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $section->id }}">
                Delete
              </button>
            </div>
          </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Section</h5>
      <form id="sectionForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Division</label>
            <select name="division_id" class="form-control text-uppercase" required>
              <option value="">Select Division</option>
              @foreach($divisions as $division)
              <option value="{{ $division->id }}">{{ $division->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Section Name</label>
            <input type="text" name="name" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" class="form-control text-uppercase" required>
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

<!-- Edit Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSectionForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editSectionId">
          <div class="mb-3">
            <label>Division</label>
            <select name="division_id" id="editDivisionId" class="form-control text-uppercase" required>
              <option value="">Select Division</option>
              @foreach($divisions as $division)
              <option value="{{ $division->id }}">{{ $division->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Section Name</label>
            <input type="text" name="name" id="editSectionName" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" id="editSectionAbbreviation" class="form-control text-uppercase" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this section?
      </div>
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
  $('#openModalBtn').click(function() {
    $('#sectionForm')[0].reset();
    var modal = new bootstrap.Modal(document.getElementById('sectionModal'));
    modal.show();
  });

  $('#sectionForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
      url: '{{ route("section.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Section added successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('sectionModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to add section.");
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText);
        toastr.error("Something went wrong.");
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    const division_id = $(this).data('division_id');
    const name = $(this).data('name');
    const abbreviation = $(this).data('abbreviation');

    $('#editSectionId').val(id);
    $('#editDivisionId').val(division_id);
    $('#editSectionName').val(name);
    $('#editSectionAbbreviation').val(abbreviation);

    var editModal = new bootstrap.Modal(document.getElementById('editSectionModal'));
    editModal.show();
  });

  $('#editSectionForm').submit(function(e) {
    e.preventDefault();

    const id = $('#editSectionId').val();

    $.ajax({
      url: `/planning/section/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Section updated successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('editSectionModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to update section.");
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText);
        toastr.error("Something went wrong.");
      }
    });
  });

  let deleteId = null;

  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('id');
    var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    deleteModal.show();
  });

  $('#confirmDeleteBtn').click(function() {
    if (!deleteId) return;

    $.ajax({
      url: `/planning/section/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Section deleted successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to delete section.");
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText);
        toastr.error("Something went wrong.");
      }
    });
  });

  $('#sectionTable').DataTable();
</script>
@endpush
