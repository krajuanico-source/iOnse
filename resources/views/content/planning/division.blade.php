@extends('layouts/contentNavbarLayout')

@section('title', 'Divisions')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Divisions</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Division</button>
    </div>

    <div class="table-responsive">
      <table id="divisionTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Division Name</th>
            <th>Abbreviation</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($divisions as $index => $division)
          <tr data-id="{{ $division->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ \Illuminate\Support\Str::upper($division->name) }}</td>
            <td>{{ \Illuminate\Support\Str::upper($division->abbreviation) }}</td>
            <td>
              <button
                class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $division->id }}"
                data-name="{{ $division->name }}"
                data-abbreviation="{{ $division->abbreviation }}">
                Edit
              </button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $division->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="divisionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Division</h5>
      <form id="divisionForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Division Name</label>
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
<div class="modal fade" id="editDivisionModal" tabindex="-1" aria-labelledby="editDivisionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editDivisionForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editDivisionModalLabel">Edit Division</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editDivisionId">
          <div class="mb-3">
            <label>Division Name</label>
            <input type="text" name="name" id="editDivisionName" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" id="editDivisionAbbreviation" class="form-control text-uppercase" required>
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
<!--Delete-->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this division?
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
    $('#divisionForm')[0].reset();
    var modal = new bootstrap.Modal(document.getElementById('divisionModal'));
    modal.show();
  });
  $('#divisionForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
      url: '{{ route("division.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Division added successfully!');
          var modal = bootstrap.Modal.getInstance(document.getElementById('divisionModal'));
          modal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to add division.");
        }
      },
      error: function(xhr, status, error) {
        console.log("XHR Response: ", xhr.responseText);
        console.log("Status: ", status);
        console.log("Error: ", error);
        toastr.error("Something went wrong: Check console log");
      }
    });

  });
  $(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const abbreviation = $(this).data('abbreviation');

    $('#editDivisionId').val(id);
    $('#editDivisionName').val(name);
    $('#editDivisionAbbreviation').val(abbreviation);

    var editModal = new bootstrap.Modal(document.getElementById('editDivisionModal'));
    editModal.show();
  });
  $('#editDivisionForm').submit(function(e) {
    e.preventDefault();

    const id = $('#editDivisionId').val();

    $.ajax({
      url: `/planning/division/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Division updated successfully!');
          var editModal = bootstrap.Modal.getInstance(document.getElementById('editDivisionModal'));
          editModal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to update division.");
        }
      },
      error: function(xhr, status, error) {
        console.log("XHR Response: ", xhr.responseText);
        console.log("Status: ", status);
        console.log("Error: ", error);
        toastr.error("Something went wrong: Check console log");
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
      url: `/planning/division/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Division deleted successfully!');
          var deleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
          deleteModal.hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Failed to delete division.");
        }
      },
      error: function(xhr, status, error) {
        console.log("XHR Response: ", xhr.responseText);
        console.log("Status: ", status);
        console.log("Error: ", error);
        toastr.error("Something went wrong: Check console log");
      }
    });
  });
</script>
<script>
  jQuery(function($) {
    $('#divisionTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>

@endpush