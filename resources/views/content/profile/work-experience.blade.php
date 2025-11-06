@extends('layouts/contentNavbarLayout')

@section('title', 'Work Experience')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<style>
  /* Make table text uppercase */
  #workTable td, #workTable th {
      text-transform: uppercase;
  }

  /* Make input and select text uppercase visually */
  input.uppercase, select.uppercase {
      text-transform: uppercase;
  }
</style>

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Work Experience</h4>
      <button class="btn btn-success" id="openWorkModalBtn">Add Work Experience</button>
    </div>

    <div class="table-responsive">
      <table id="workTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>Position Title</th>
            <th>Department/Agency</th>
            <th>Salary</th>
            <th>Salary Grade</th>
            <th>Status</th>
            <th>Government Service</th>
            <th>From</th>
            <th>To</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($experiences as $exp)
          <tr data-id="{{ $exp->id }}">
            <td>{{ $exp->position_title }}</td>
            <td>{{ $exp->department_agency }}</td>
            <td>{{ $exp->monthly_salary }}</td>
            <td>{{ $exp->salary_grade }}</td>
            <td>{{ $exp->status_of_appointment }}</td>
            <td>{{ $exp->govt_service }}</td>
                        <td>{{ $exp->date_from }}</td>
            <td>{{ $exp->date_to }}</td>
            <td>
              <button class="btn btn-sm btn-primary edit-work-btn"
                data-id="{{ $exp->id }}"
                data-from="{{ $exp->date_from }}"
                data-to="{{ $exp->date_to }}"
                data-position="{{ $exp->position_title }}"
                data-dept="{{ $exp->department_agency }}"
                data-salary="{{ $exp->monthly_salary }}"
                data-grade="{{ $exp->salary_grade }}"
                data-status="{{ $exp->status_of_appointment }}"
                data-govt="{{ $exp->govt_service }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-work-btn" data-id="{{ $exp->id }}">
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
<div class="modal fade" id="workModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="workForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Work Experience</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
            <div class="col-md-6">
            <label>FROM</label>
            <input type="date" name="date_from" class="form-control uppercase" required>
          </div>
          <div class="col-md-6">
            <label>TO</label>
            <input type="date" name="date_to" class="form-control uppercase" required>
          </div>
        </div>
          <div class="mb-3">
            <label>POSITION TITLE</label>
            <input type="text" name="position_title" class="form-control uppercase" required>
          </div>
          <div class="mb-3">
            <label>DEPARTMENT/AGENCY/BUSINESS</label>
            <input type="text" name="department_agency" class="form-control uppercase">
          </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                <label for="monthly_salary">MONTHLY SALARY</label>
                <input type="number" name="monthly_salary" class="form-control uppercase" step="0.01" min="0">
                </div>
                <div class="col-md-6 mb-3">
                <label for="salary_grade">SALARY GRADE</label>
                <input type="number" name="salary_grade" class="form-control uppercase" min="0">
                </div>
            </div>
          <div class="mb-3">
            <label>STATUS OF APPOINTMENT</label>
            <input type="text" name="status_of_appointment" class="form-control uppercase">
          </div>
          <div class="mb-3">
            <label>GOVERNMENT SERVICE</label>
              <select name="govt_service" class="form-select uppercase" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
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
<div class="modal fade" id="editWorkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editWorkForm">
        @csrf
        <input type="hidden" name="id" id="editWorkId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Work Experience</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
          <div class="col-md-6"><label>FROM</label><input type="date" name="date_from" id="editFrom" class="form-control uppercase" required></div>
          <div class="col-md-6"><label>TO</label><input type="date" name="date_to" id="editTo" class="form-control uppercase" required></div>
          </div>
          <div class="mb-3"><label>POSITION TITLE</label><input type="text" name="position_title" id="editPosition" class="form-control uppercase" required></div>
          <div class="mb-3"><label>DEPATMENT/AGENCY</label><input type="text" name="department_agency" id="editDept" class="form-control uppercase"></div>
          <div class="row mb-3">
          <div class="col-md-6"><label>MONTHLY SALARY</label><input type="text" name="monthly_salary" id="editSalary" class="form-control uppercase"></div>
          <div class="col-md-6"><label>SALARY GRADE</label><input type="text" name="salary_grade" id="editGrade" class="form-control uppercase"></div>
        </div>
          <div class="mb-3"><label>STATUS OF APPOINTMENT</label><input type="text" name="status_of_appointment" id="editStatus" class="form-control uppercase"></div>
          <div class="mb-3"><label>GOVERNMENT SERVICE</label><input type="text" name="govt_service" id="editGovt" class="form-control uppercase"></div>
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
<div class="modal fade" id="confirmDeleteWorkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this work experience?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteWorkBtn" class="btn btn-danger">Delete</button>
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
  
// Open Add Modal
$('#openWorkModalBtn').click(function() {
    $('#workForm')[0].reset();
    $('#workExperienceId').val(''); // ensure it's empty for Add
    new bootstrap.Modal(document.getElementById('workModal')).show();
});

// Fill Edit Modal
$(document).on('click', '.edit-work-btn', function() {
    $('#editWorkId').val($(this).data('id'));
    $('#editFrom').val($(this).data('from'));
    $('#editTo').val($(this).data('to'));
    $('#editPosition').val($(this).data('position'));
    $('#editDept').val($(this).data('dept'));
    $('#editSalary').val($(this).data('salary'));
    $('#editGrade').val($(this).data('grade'));
    $('#editStatus').val($(this).data('status'));
    $('#editGovt').val($(this).data('govt'));

    // Open the **Edit Modal**, NOT the Add modal
    new bootstrap.Modal(document.getElementById('editWorkModal')).show();
});
$('#workForm, #editWorkForm').on('submit', function() {
    $(this).find('input[type="text"], select').each(function() {
        $(this).val($(this).val().toUpperCase());
    });
});


// Submit Form (Add/Edit)
$('#workForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: '{{ route("profile.work-experience.store") }}',
        method: 'POST',
        data: $(this).serialize(), // sends work_experience_id if editing
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            toastr.success(response.message || 'Work experience saved successfully!');
            bootstrap.Modal.getInstance(document.getElementById('workModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            toastr.error('Failed to add work experience.');
        }
    });
});


  // Update
  $('#editWorkForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editWorkId').val();
    
    $.ajax({
      url: '{{ route("profile.work-experience.update", ":id") }}'.replace(':id', id),
      method: 'POST',
      data: $(this).serialize() + '&_method=PUT',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: () => {
        toastr.success('Work experience updated successfully!');
        bootstrap.Modal.getInstance(document.getElementById('editWorkModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: () => toastr.error('Failed to update work experience.')
    });
  });

  // Delete Modal Script
  let workDeleteId = null;
  $(document).on('click', '.delete-work-btn', function() {
    workDeleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteWorkModal')).show();
  });

  $('#confirmDeleteWorkBtn').click(function() {
    if (!workDeleteId) return;
    $.ajax({
      url: `/profile/work-experience/${workDeleteId}`,
      method: 'POST',
      data: { _method: 'DELETE' },
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: () => {
        toastr.success('Work experience deleted successfully!');
        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteWorkModal')).hide();
        setTimeout(() => location.reload(), 500);
      },
      error: () => toastr.error('Failed to delete work experience.')
    });
  });

  // DataTable Init
  jQuery(function($) {
    $('#workTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endpush
