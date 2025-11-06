@extends('layouts/contentNavbarLayout')

@section('title', 'Government IDs')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">Government Identification Cards</h4>
      <button class="btn btn-success" id="openGovIdModalBtn">Add Government ID</button>
    </div>

    <div class="table-responsive">
      <table id="govIdTable" class="table">
        <thead class="table-light text-center">
          <tr>
            <th>SSS</th>
            <th>GSIS</th>
            <th>PAG-IBIG</th>
            <th>PHILHEALTH</th>
            <th>TIN</th>
            <th>Gov. Issued ID</th>
            <th>ID Number</th>
            <th>Date Issuance</th>
            <th>Place Issuance</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($governmentIds as $g)
          <tr data-id="{{ $g->id }}">
            <td>{{ $g->sss_id }}</td>
            <td>{{ $g->gsis_id }}</td>
            <td>{{ $g->pagibig_id }}</td>
            <td>{{ $g->philhealth_id }}</td>
            <td>{{ $g->tin }}</td>
            <td>{{ $g->gov_issued_id }}</td>
            <td>{{ $g->id_number }}</td>
            <td>{{ $g->date_issuance }}</td>
            <td>{{ $g->place_issuance }}</td>
            <td>
              <button 
                class="btn btn-sm btn-primary edit-gov-btn"
                data-id="{{ $g->id }}"
                data-sss="{{ $g->sss_id }}"
                data-gsis="{{ $g->gsis_id }}"
                data-pagibig="{{ $g->pagibig_id }}"
                data-philhealth="{{ $g->philhealth_id }}"
                data-tin="{{ $g->tin }}"
                data-govid="{{ $g->gov_issued_id }}"
                data-idnum="{{ $g->id_number }}"
                data-date="{{ $g->date_issuance }}"
                data-place="{{ $g->place_issuance }}">
                Update
              </button>
              <button class="btn btn-sm btn-danger delete-gov-btn" data-id="{{ $g->id }}">
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
<div class="modal fade" id="govIdModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="govIdForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Government ID</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label>SSS ID</label>
            <input type="text" name="sss_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label>GSIS ID</label>
            <input type="text" name="gsis_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label>PAG-IBIG ID</label>
            <input type="text" name="pagibig_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label>PHILHEALTH ID</label>
            <input type="text" name="philhealth_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label>TIN</label>
            <input type="text" name="tin" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Government Issued ID</label>
            <input type="text" name="gov_issued_id" class="form-control">
          </div>
          <div class="col-md-6">
            <label>ID / License / Passport No.</label>
            <input type="text" name="id_number" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Date Issuance</label>
            <input type="date" name="date_issuance" class="form-control">
          </div>
          <div class="col-md-12">
            <label>Place Issuance</label>
            <input type="text" name="place_issuance" class="form-control">
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
<div class="modal fade" id="editGovIdModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editGovIdForm">
        @csrf
        <input type="hidden" name="id" id="editGovId">
        <div class="modal-header">
          <h5 class="modal-title">Edit Government ID</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label>SSS ID</label>
            <input type="text" name="sss_id" id="editSssId" class="form-control">
          </div>
          <div class="col-md-6">
            <label>GSIS ID</label>
            <input type="text" name="gsis_id" id="editGsisId" class="form-control">
          </div>
          <div class="col-md-6">
            <label>PAG-IBIG ID</label>
            <input type="text" name="pagibig_id" id="editPagibigId" class="form-control">
          </div>
          <div class="col-md-6">
            <label>PHILHEALTH ID</label>
            <input type="text" name="philhealth_id" id="editPhilhealthId" class="form-control">
          </div>
          <div class="col-md-6">
            <label>TIN</label>
            <input type="text" name="tin" id="editTin" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Government Issued ID</label>
            <input type="text" name="gov_issued_id" id="editGovIssuedId" class="form-control">
          </div>
          <div class="col-md-6">
            <label>ID Number</label>
            <input type="text" name="id_number" id="editIdNumber" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Date Issuance</label>
            <input type="date" name="date_issuance" id="editDateIssuance" class="form-control">
          </div>
          <div class="col-md-12">
            <label>Place Issuance</label>
            <input type="text" name="place_issuance" id="editPlaceIssuance" class="form-control">
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
<div class="modal fade" id="confirmDeleteGovIdModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteGovIdBtn" class="btn btn-danger">Delete</button>
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

$('#openGovIdModalBtn').click(() => {
    $('#govIdForm')[0].reset();
    new bootstrap.Modal(document.getElementById('govIdModal')).show();
});

// Fill Edit Modal
$(document).on('click', '.edit-gov-btn', function() {
    $('#editGovId').val($(this).data('id'));
    $('#editSssId').val($(this).data('sss'));
    $('#editGsisId').val($(this).data('gsis'));
    $('#editPagibigId').val($(this).data('pagibig'));
    $('#editPhilhealthId').val($(this).data('philhealth'));
    $('#editTin').val($(this).data('tin'));
    $('#editGovIssuedId').val($(this).data('govid'));
    $('#editIdNumber').val($(this).data('idnum'));
    $('#editDateIssuance').val($(this).data('date'));
    $('#editPlaceIssuance').val($(this).data('place'));
    new bootstrap.Modal(document.getElementById('editGovIdModal')).show();
});

// Add
$('#govIdForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("profile.government-ids.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Government ID added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('govIdModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to add Government ID.')
    });
});

// Update
$('#editGovIdForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editGovId').val();
    $.ajax({
        url: '{{ route("profile.government-ids.update", ":id") }}'.replace(':id', id),
        method: 'POST',
        data: $(this).serialize() + '&_method=PUT',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Government ID updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editGovIdModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to update Government ID.')
    });
});

// Delete
$(document).on('click', '.delete-gov-btn', function() {
    deleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteGovIdModal')).show();
});

$('#confirmDeleteGovIdBtn').click(function() {
    if (!deleteId) return;
    $.ajax({
        url: `/profile/government-ids/${deleteId}`,
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: () => {
            toastr.success('Government ID deleted successfully!');
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteGovIdModal')).hide();
            setTimeout(() => location.reload(), 500);
        },
        error: () => toastr.error('Failed to delete Government ID.')
    });
});

// DataTable
$('#govIdTable').DataTable({
    paging: true,
    searching: true,
    info: true
});
</script>
@endpush
