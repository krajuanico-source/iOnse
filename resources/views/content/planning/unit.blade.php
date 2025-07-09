@extends('layouts/contentNavbarLayout')

@section('title', 'Units')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Units</h4>
      <button id="openModalBtn" class="btn btn-success">+ Add New Unit</button>
    </div>

    <div class="table-responsive">
      <table id="unitTable" class="table">
        <thead class="table-light">
            <th>No.</th>
            <th>Division</th>
            <th>Section</th>
            <th>Unit Name</th>
            <th>Abbreviation</th>
            <th>Actions</th>
        <tbody>
          @foreach($units as $index => $unit)
          <tr>
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $unit->section->division->name ?? 'N/A' }}</td>
            <td>{{ strtoupper($unit->section->name ?? 'N/A') }}</td>
            <td>{{ strtoupper($unit->name) }}</td>
            <td>{{ strtoupper($unit->abbreviation) }}</td>
            <td>
              <div class="d-flex gap-1">
                <button class="btn btn-primary btn-sm editBtn"
                  data-id="{{ $unit->id }}"
                  data-division="{{ $unit->section->division_id }}"
                  data-section="{{ $unit->section_id }}"
                  data-name="{{ $unit->name }}"
                  data-abbreviation="{{ $unit->abbreviation }}">
                  Edit
                </button>

                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $unit->id }}">
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

<!-- Add Unit Modal -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Unit</h5>
      <form id="unitForm">
        <div class="modal-body">
          <div class="mb-3">
            <input type="hidden" name="unit_id" id="unit_id">
            <label>Division</label>
            <select name="division_id" id="division_id" class="form-control" required>
              <option value="">Select Division</option>
              @foreach($divisions as $division)
              <option value="{{ $division->id }}">{{ $division->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Section</label>
            <select name="section_id" id="section_id" class="form-control" required>
              <option value="">Select Section</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Unit Name</label>
            <input type="text" name="name" class="form-control text-uppercase" text required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" class="form-control text-uppercase" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3 ">Edit Unit</h5>
      <form id="editUnitForm">
        <div class="modal-body">
          <input type="hidden" name="unit_id" id="edit_unit_id">
          <div class="mb-3">
            <label>Division</label>
            <select name="division_id" id="edit_division_id" class="form-control text-uppercase" required>
              <option value="">Select Division</option>
              @foreach($divisions as $division)
              <option value="{{ $division->id }}">{{ $division->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Section</label>
            <select name="section_id" id="edit_section_id" class="form-control text-uppercase" required>
              <option value="">Select Section</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Unit Name</label>
            <input type="text" name="name" id="edit_name" class="form-control text-uppercase" required>
          </div>
          <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" id="edit_abbreviation" class="form-control text-uppercase" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  // Initialize DataTable
  $('#unitTable').DataTable();

  // Show Add Unit Modal
  $('#openModalBtn').click(function() {
    $('#unitForm')[0].reset();
    $('#section_id').html('<option value="">Select Section</option>');
    new bootstrap.Modal(document.getElementById('unitModal')).show();
  });

  // Load Sections when Division changes (Add Modal)
  $(document).on('change', '#division_id', function() {
    const divisionId = $(this).val();
    const sectionSelect = $('#section_id').html('<option>Loading...</option>');

    if (divisionId) {
      $.get(`/planning/unit/sections/by-division/${divisionId}`, function(sections) {
        let options = '<option value="">Select Section</option>';
        sections.forEach(section => {
          options += `<option value="${section.id}">${section.name}</option>`;
        });
        sectionSelect.html(options);
      }).fail(() => {
        sectionSelect.html('<option>Error loading sections</option>');
      });
    } else {
      sectionSelect.html('<option value="">Select Section</option>');
    }
  });

  // Submit Add/Update Unit Form
  $('#unitForm').submit(function(e) {
    e.preventDefault();

    const unitId = $('#unit_id').val();
    const isUpdate = unitId !== "";
    const url = isUpdate ? `/units/${unitId}` : '{{ route("unit.store") }}';
    const method = isUpdate ? 'PUT' : 'POST';

    $.ajax({
      url,
      method,
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success(`Unit ${isUpdate ? 'updated' : 'added'} successfully!`);
          bootstrap.Modal.getInstance(document.getElementById('unitModal')).hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error("Operation failed.");
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        toastr.error("Something went wrong.");
      }
    });
  });

  // Show Edit Modal with data
  $(document).on('click', '.editBtn', function() {
    const unitId = $(this).data('id');
    const divisionId = $(this).data('division');
    const sectionId = $(this).data('section');
    const name = $(this).data('name');
    const abbreviation = $(this).data('abbreviation');

    $('#edit_unit_id').val(unitId);
    $('#edit_name').val(name);
    $('#edit_abbreviation').val(abbreviation);
    $('#edit_division_id').val(divisionId).trigger('change');

    if (divisionId) {
      $.get(`/planning/unit/sections/by-division/${divisionId}`, function(sections) {
        let options = '<option value="">Select Section</option>';
        sections.forEach(section => {
          options += `<option value="${section.id}" ${section.id == sectionId ? 'selected' : ''}>${section.name}</option>`;
        });
        $('#edit_section_id').html(options);
      });
    }

    new bootstrap.Modal(document.getElementById('editUnitModal')).show();
  });

  // Load Sections when Division changes (Edit Modal)
  $(document).on('change', '#edit_division_id', function() {
    const divisionId = $(this).val();
    const sectionSelect = $('#edit_section_id').html('<option>Loading...</option>');

    if (divisionId) {
      $.get(`/planning/unit/sections/by-division/${divisionId}`, function(sections) {
        let options = '<option value="">Select Section</option>';
        sections.forEach(section => {
          options += `<option value="${section.id}">${section.name}</option>`;
        });
        sectionSelect.html(options);
      }).fail(() => {
        sectionSelect.html('<option>Error loading sections</option>');
      });
    } else {
      sectionSelect.html('<option value="">Select Section</option>');
    }
  });

  // Submit Edit Form
  $('#editUnitForm').submit(function(e) {
    e.preventDefault();

    const unitId = $('#edit_unit_id').val();

    $.ajax({
      url: `/planning/unit/${unitId}`,
      method: 'PUT',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          toastr.success('Unit updated successfully!');
          bootstrap.Modal.getInstance(document.getElementById('editUnitModal')).hide();
          setTimeout(() => location.reload(), 500);
        } else {
          toastr.error('Update failed.');
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        toastr.error('Something went wrong.');
      }
    });
  });

  // Show View Modal
  $(document).on('click', '.viewBtn', function() {
    $('#viewDivision').text($(this).data('division'));
    $('#viewSection').text($(this).data('section'));
    $('#viewName').text($(this).data('name'));
    $('#viewAbbreviation').text($(this).data('abbreviation'));
    new bootstrap.Modal(document.getElementById('viewUnitModal')).show();
  });

  // Delete Unit
  $(document).on('click', '.deleteBtn', function() {
    const unitId = $(this).data('id');

    if (confirm('Are you sure you want to delete this unit?')) {
      $.ajax({
        url: `/planning/unit/${unitId}`,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.success) {
            toastr.success('Unit deleted successfully!');
            setTimeout(() => location.reload(), 500);
          } else {
            toastr.error('Failed to delete unit.');
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          toastr.error('Error occurred during deletion.');
        }
      });
    }
  });
</script>
<script>
  $('#division_id').on('change', function() {
    const divisionId = $(this).val();
    const sectionSelect = $('#section_id');

    sectionSelect.html('<option value="">Loading...</option>');

    if (divisionId) {
      $.ajax({
        url: `/sections/by-division/${divisionId}`,
        type: 'GET',
        success: function(sections) {
          sectionSelect.html('<option value="">Select Section</option>');
          sections.forEach(function(section) {
            sectionSelect.append(`<option value="${section.id}">${section.name}</option>`);
          });
        },
        error: function() {
          sectionSelect.html('<option value="">Error loading sections</option>');
        }
      });
    } else {
      sectionSelect.html('<option value="">Select Section</option>');
    }
  });
</script>
@endpush
