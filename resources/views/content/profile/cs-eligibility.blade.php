@extends('layouts/contentNavbarLayout')

@section('title', 'CS Eligibility')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Civil Service Eligibility</h4>
      <button class="btn btn-success" id="addEligibilityBtn">Add Eligibility</button>
    </div>

    <div class="table-responsive">
      <table id="eligibilityTable" class="table">
        <thead class="table-light">
          <tr>
            <th style="width: 25%;">CAREER SERVICE/RA 1080 (BOARD/BAR) UNDER SPECIAL LAWS/CES/CSEE/ BARANGAY ELIGIBILITY/DRIVERS LICENSE</th>
            <th style="width: 10%;">RATING</th>
            <th style="width: 10%;">DATE OF EXAMINATION</th>
            <th style="width: 10%;">PLACE OF EXAMINATION</th>
            <th style="width: 10%;">LICENSE</th>
            <th style="width: 10%;">LICENSE VALIDITY</th>
            <th style="width: 16%;">ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach($eligibilities as $e)
          <tr data-id="{{ $e->id }}">
            <td>{{ $e->eligibility }}</td>
            <td>{{ $e->rating }}</td>
            <td>{{ $e->exam_date }}</td>
            <td>{{ $e->exam_place }}</td>
            <td>{{ $e->license_number }}</td>
            <td>{{ $e->license_validity }}</td>
            <td>
              <button class="btn btn-sm btn-primary edit-eligibility-btn"
                data-id="{{ $e->id }}"
                data-eligibility="{{ $e->eligibility }}"
                data-rating="{{ $e->rating }}"
                data-date="{{ $e->exam_date }}"
                data-place="{{ $e->exam_place }}"
                data-license="{{ $e->license_number }}"
                data-validity="{{ $e->license_validity }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-eligibility-btn" data-id="{{ $e->id }}">Delete</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="eligibilityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="eligibilityForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Eligibility</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>CAREER SERVICE/RA 1080 (BOARD/BAR)
              UNDER SPECIAL LAWS/CES/CSEE/
              BARANGAY ELIGIBILITY/DRIVERS
              LICENSE</label>
            <input type="text" name="eligibility" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>RATING</label>
            <input type="text" name="rating" class="form-control">
          </div>
          <div class="mb-3">
            <label>DATE OF EXAMINATION</label>
            <input type="date" name="exam_date" class="form-control">
          </div>
          <div class="mb-3">
            <label>PLACE OF EXAMINATION</label>
            <input type="text" name="exam_place" class="form-control">
          </div>
          <div class="mb-3">
            <label>LICENSE</label>
            <input type="text" name="license_number" class="form-control">
          </div>
          <div class="mb-3">
            <label>LICENSE VALIDITY</label>
            <input type="date" name="license_validity" class="form-control">
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
<div class="modal fade" id="editEligibilityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editEligibilityForm">
        @csrf
        <input type="hidden" name="id" id="editEligibilityId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Eligibility</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>CAREER SERVICE/RA 1080 (BOARD/BAR)
              UNDER SPECIAL LAWS/CES/CSEE/
              BARANGAY ELIGIBILITY/DRIVERS
              LICENSE</label>
            <input type="text" name="eligibility" id="editCareer" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>RATING</label>
            <input type="text" name="rating" id="editRating" class="form-control">
          </div>
          <div class="mb-3">
            <label>DATE OF EXAMINATION</label>
            <input type="date" name="exam_date" id="editDate" class="form-control">
          </div>
          <div class="mb-3">
            <label>PLACE OF EXAMINATION</label>
            <input type="text" name="exam_place" id="editPlace" class="form-control">
          </div>
          <div class="mb-3">
            <label>LICENSE</label>
            <input type="text" name="license_number" id="editLicense" class="form-control">
          </div>
          <div class="mb-3">
            <label>LICENSE VALIDITY</label>
            <input type="date" name="license_validity" id="editLicenseValidity" class="form-control">
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
<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteEligibilityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this eligibility record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteEligibilityBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // DataTable init
  $('#eligibilityTable').DataTable();

  // Add Modal
  $('#addEligibilityBtn').click(() => {
    new bootstrap.Modal(document.getElementById('eligibilityModal')).show();
  });

  // Add Save
  $('#eligibilityForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("profile.cs-eligibility.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: () => {
        toastr.success('Eligibility added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('eligibilityModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: () => toastr.error('Failed to add eligibility.')
    });
  });

  // Fill Edit Modal
  $(document).on('click', '.edit-eligibility-btn', function() {
    $('#editEligibilityId').val($(this).data('id'));
    $('#editCareer').val($(this).data('eligibility'));
    $('#editRating').val($(this).data('rating'));
    $('#editDate').val($(this).data('date'));
    $('#editPlace').val($(this).data('place'));
    $('#editLicense').val($(this).data('license'));
    $('#editLicenseValidity').val($(this).data('validity'));
    new bootstrap.Modal(document.getElementById('editEligibilityModal')).show();
  });

      // Update
    // ✅ Correct Update AJAX
    $('#editEligibilityForm').submit(function(e) {
      e.preventDefault();
      const id = $('#editEligibilityId').val();

      $.ajax({
        url: '{{ route("profile.cs-eligibility.update", ":id") }}'.replace(':id', id),
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
          toastr.success('Eligibility updated successfully!');
          bootstrap.Modal.getInstance(document.getElementById('editEligibilityModal')).hide();
          setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          toastr.error('Failed to update eligibility.');
        }
      });
    });


  // Delete Modal
let deleteEligibilityId = null;

$(document).on('click', '.delete-eligibility-btn', function() {
    deleteEligibilityId = $(this).data('id');
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteEligibilityModal'));
    modal.show();
});

$('#confirmDeleteEligibilityBtn').click(function() {
    if (!deleteEligibilityId) return;

    $.ajax({
        url: '{{ route("profile.cs-eligibility.destroy", ":id") }}'.replace(':id', deleteEligibilityId),
        method: 'POST', // Laravel DELETE requires _method
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            toastr.success('Eligibility deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteEligibilityModal')).hide();
            deleteEligibilityId = null;
            setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            toastr.error('Failed to delete eligibility.');
        }
    });
});


</script>
@endpush
