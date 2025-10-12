@extends('layouts.contentNavbarLayout')

@section('title', 'Unfilled Positions')

@section('content')
<div class="container-fluid">
  <h4>Applicants for Item No. {{ $item->item_number }} — {{ $item->position->position_name }}</h4>
  <hr>
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

      <div class="mb-3">
        <a href="{{ route('unfilled_positions.index', $item->id) }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Back to Position Details
        </a>
        <a href="{{ route('itemNumbers.print', $item->id) }}" target="_blank" class="btn btn-primary">
          <i class="bi bi-printer me-1"></i> Print Notice of Vacancy
        </a>
        <button class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#addApplicantModal">
          <i class="bi bi-person-plus"></i> Add Applicant
        </button>
      </div>
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
            <td>{{ $applicant->first_name }} {{ $applicant->middle_name }} {{ $applicant->last_name }}</td>
            <td>{{ $applicant->sex }}</td>
            <td>{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($applicant->date_applied)->format('M d, Y') }}</td>
            <td>
              @php
              switch ($applicant->status) {
              case 'Hired':
              $badgeClass = 'success';
              break;
              case 'Rejected':
              $badgeClass = 'danger';
              break;
              case 'Examination':
              $badgeClass = 'info';
              break;
              case 'Deliberation':
              $badgeClass = 'warning';
              break;
              case 'Submission of Requirements':
              $badgeClass = 'primary';
              break;
              case 'On-Boarding':
              $badgeClass = 'secondary';
              break;
              default:
              $badgeClass = 'light';
              }
              @endphp

              <span class="badge bg-{{ $badgeClass }}">
                {{ $applicant->status }}
              </span>

            </td>
            <td>{{ $applicant->remarks }}</td>
            <td>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $applicant->id }}">
                Update Status
              </button>
            </td>
          </tr>
          @include('content.planning.unfilled_positions.partials.update-status-modal', ['applicant' => $applicant])
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted">No applicants found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@include('content.planning.unfilled_positions.partials.add-applicant-modal')

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#applicantsTable').DataTable();
  });
</script>
@endpush
@endsection