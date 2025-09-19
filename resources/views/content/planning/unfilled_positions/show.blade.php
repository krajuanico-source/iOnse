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
  </div>
  <div class="mt-3">
    <a href="{{ route('itemNumbers.print', $item->id) }}" target="_blank" class="btn btn-primary">
      <i class="bi bi-printer me-1"></i> Print Notice of Vacancy
    </a>
  </div>
</div>


<hr>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="applicantTabs" role="tablist">

  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-applicants" type="button" role="tab">
      List of Applicants
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link " id="add-tab" data-bs-toggle="tab" data-bs-target="#add-applicant" type="button" role="tab">
      Add Applicant
    </button>
  </li>
</ul>

<!-- Tab content -->
<div class="tab-content mt-3" id="applicantTabsContent">

  <!-- Add Applicant Form -->
  <div class="tab-pane fade" id="add-applicant" role="tabpanel">
    <form action="{{ route('unfilled_positions.applicants.store', $item->id) }}" method="POST">
      @csrf
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">First Name <span class="text-danger">*</span></label>
          <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Middle Name</label>
          <input type="text" name="middle_name" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Last Name <span class="text-danger">*</span></label>
          <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Extension Name</label>
          <input type="text" name="extension_name" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Sex <span class="text-danger">*</span></label>
          <select name="sex" class="form-select" required>
            <option value="">-- Select --</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
          <input type="date" name="date_of_birth" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Date Applied <span class="text-danger">*</span></label>
          <input type="date" name="date_applied" class="form-control" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="2"></textarea>
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-save me-1"></i> Save Applicant
        </button>
      </div>
    </form>
  </div>

  <!-- List Applicants -->
  <div class="tab-pane fade  show active" id="list-applicants" role="tabpanel">
    <h6 class="mb-3">Applicants for <strong>{{ $item->item_number }}</strong></h6>

    <div class="table-responsive">
      <table id="applicantsTable" class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Applicant No.</th>
            <th>Name</th>
            <th>Sex</th>
            <th>Date of Birth</th>
            <th>Date Applied</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($applicants as $applicant)
          <tr>
            <td>{{ $applicant->id }}</td>
            <td>
              {{ $applicant->first_name }}
              @if($applicant->middle_name) {{ $applicant->middle_name }} @endif
              {{ $applicant->last_name }}
              @if($applicant->extension_name) {{ $applicant->extension_name }} @endif
            </td>
            <td>{{ $applicant->sex }}</td>
            <td>{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($applicant->date_applied)->format('M d, Y') }}</td>
            <td>
              <span class="badge bg-{{ $applicant->status === 'Hired' ? 'success' : ($applicant->status === 'Not Hired' ? 'danger' : 'secondary') }}">
                {{ $applicant->status }}
              </span>
            </td>
            <td>{{ $applicant->remarks }}</td>
            <td>
              <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $applicant->id }}">
                Update Status
              </button>
            </td>
          </tr>

          <!-- Update Status Modal -->
          <div class="modal fade" id="updateStatusModal{{ $applicant->id }}" tabindex="-1">
            <div class="modal-dialog modal-md">
              <div class="modal-content">

              </div>
            </div>
          </div>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted">No applicants yet</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>


@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  $('#applicantsTable').DataTable();
</script>
@endpush