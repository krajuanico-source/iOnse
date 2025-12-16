@extends('layouts.contentNavbarLayout')

@section('title', 'Unfilled Positions')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card">
  <div class="container py-4">
    <h4 class="mb-4">List of Unfilled Positions</h4>

    <div class="table-responsive">
      <table id="unfilledpos" class="table table-bordered">
        <thead>
          <tr>
            <th>Item Number</th>
            <th>Position</th>
            <th>Salary Grade</th>
            <th>Status</th>
            <th>Stature</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($itemNumbers as $item)
          <tr>
            <td>{{ $item->item_number }}</td>
            <td>{{ $item->position->position_name }}</td>
            <td>{{ $item->salaryGrade->id }}</td>
            <td>{{ $item->employmentStatus->name }}</td>
            <td>{{ ucfirst($item->stature) }}</td>
            <td>
              <button class="btn btn-sm btn-primary view-details"
                data-id="{{ $item->id }}">
                View Details
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered"> <!-- changed from modal-lg to modal-xl -->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Position Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="detailsContent">
            Loading...
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Make sure bootstrap.bundle.js is included in your layout -->
<script>
  document.addEventListener("DOMContentLoaded", function() {

    // Redirect to applicants page when "View Details" button is clicked
    document.querySelectorAll('.view-details').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.dataset.id;
        window.location.href = `/planning/unfilled-positions/${id}/applicants`;
      });
    });
  });
</script>
<script>
  // Initialize DataTable
  $('#unfilledpos').DataTable({
    paging: true, // Enable pagination
    searching: true, // Enable search/filter
    info: true, // Show "Showing X of Y entries"
    ordering: true, // Enable column sorting
    lengthChange: true, // Allow user to change page length
    pageLength: 10 // Default rows per page
  });
</script>

@endpush
