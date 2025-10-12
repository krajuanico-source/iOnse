<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-body">
    <h5 class="mb-3">Item Number: {{ $item->item_number }}</h5>
    <p><strong>Position:</strong> {{ $item->position->position_name }}</p>
    <p><strong>Salary Grade:</strong> {{ $item->salaryGrade->id }}</p>
    <p><strong>Employment Status:</strong> {{ $item->employmentStatus->name }}</p>
    <p><strong>Fund Source:</strong> {{ $item->fundSource->fund_source ?? '-' }}</p>
    <p><strong>Stature:</strong> {{ ucfirst($item->stature) }}</p>
    <p><strong>Date of Posting:</strong> {{ $item->date_posting ? \Carbon\Carbon::parse($item->date_posting)->format('M d, Y') : '-' }}</p>
    <p><strong>Date End of Submission:</strong> {{ $item->date_end_submission ? \Carbon\Carbon::parse($item->date_end_submission)->format('M d, Y') : '-' }}</p>
    <center>
      <div class="mt-3">
        <a href="{{ route('itemNumbers.print', $item->id) }}" target="_blank" class="btn btn-primary">
          <i class="bi bi-printer me-1"></i> Print Notice of Vacancy
        </a>
        <a href="{{ route('unfilled_positions.applicants', $item->id) }}" class="btn btn-success ms-2">
          <i class="bi bi-people me-1"></i> View Applicants
        </a>
      </div>
    </center>
  </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  // ✅ Initialize DataTables safely
  $(document).ready(function() {
    $('#unfilledpos').DataTable();
    $('#applicantsTable').DataTable();
  });

  // ✅ Handle "View Details" button clicks with safer modal handling
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.view-details').forEach(button => {
      button.addEventListener('click', function() {
        const id = this.dataset.id;
        const url = `/planning/unfilled-positions/${id}`;

        fetch(url)
          .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.text();
          })
          .then(html => {
            const modalEl = document.getElementById('detailsModal');
            if (!modalEl) return;

            // Replace content inside modal body
            document.getElementById('detailsContent').innerHTML = html;

            // ✅ Safely re-initialize modal instance if lost
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (!modalInstance) {
              modalInstance = new bootstrap.Modal(modalEl);
            }

            modalInstance.show();
          })
          .catch(error => {
            document.getElementById('detailsContent').innerHTML =
              "<div class='alert alert-danger'>Failed to load details.</div>";
            console.error("Error loading modal:", error);
          });
      });
    });
  });

  // ✅ Prevent "aria-hidden" / focus conflict errors
  document.addEventListener('hidden.bs.modal', function(event) {
    // If focus is still inside the just-hidden modal, blur it
    if (document.activeElement && event.target.contains(document.activeElement)) {
      document.activeElement.blur();
    }
  });

  // ✅ Optional: automatically close child modals before parent closes
  document.addEventListener('hide.bs.modal', function(event) {
    const openChildModals = document.querySelectorAll('.modal.show');
    openChildModals.forEach(modal => {
      const instance = bootstrap.Modal.getInstance(modal);
      if (instance && modal !== event.target) {
        instance.hide();
      }
    });
  });
</script>
@endpush