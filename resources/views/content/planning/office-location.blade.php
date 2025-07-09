@extends('layouts/contentNavbarLayout')

@section('title', 'Office Location')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Office Locations</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Office Location</button>
    </div>

    <div class="table-responsive">
      <table id="locationTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Abbreviation</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($officeLocations as $index => $location)
          <tr data-id="{{ $location->id }}">
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ Str::upper($location->name) }}</td>
            <td>{{ Str::upper($location->abbreviation) }}</td>
            <td>
              <button class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $location->id }}"
                data-name="{{ $location->name }}"
                data-abbreviation="{{ $location->abbreviation }}">Edit</button>
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $location->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">Add New Office Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      <form id="locationForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Location Name</label>
           <input type="text" name="name" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">  
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" class="form-control  text-uppercase" required>
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
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editLocationForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Office Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editLocationId">
          <div class="mb-3">
            <label>Location Name</label>
          <input type="text" name="name" id="editLocationName" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" id="editLocationAbbreviation" class="form-control text-uppercase">
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
        Are you sure you want to delete this office location?
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
    $('#locationForm')[0].reset();
    new bootstrap.Modal(document.getElementById('locationModal')).show();
  });

  $('#locationForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("office-location.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Office Location added!');
          bootstrap.Modal.getInstance(document.getElementById('locationModal')).hide();
          setTimeout(() => location.reload(), 500);
        }
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    $('#editLocationId').val($(this).data('id'));
    $('#editLocationName').val($(this).data('name'));
    $('#editLocationAbbreviation').val($(this).data('abbreviation'));
    new bootstrap.Modal(document.getElementById('editLocationModal')).show();
  });

  $('#editLocationForm').submit(function(e) {
    e.preventDefault();
    let id = $('#editLocationId').val();
    $.ajax({
      url: `/planning/office-location/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Office Location updated!');
          bootstrap.Modal.getInstance(document.getElementById('editLocationModal')).hide();
          setTimeout(() => location.reload(), 500);
        }
      }
    });
  });

  let deleteId = null;
  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
  });

  $('#confirmDeleteBtn').click(function() {
    $.ajax({
      url: `/planning/office-location/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Deleted successfully!');
          bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
          setTimeout(() => location.reload(), 500);
        }
      }
    });
  });

  $('#locationTable').DataTable();
</script>
@endpush
