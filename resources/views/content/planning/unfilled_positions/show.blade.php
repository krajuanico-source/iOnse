@extends('layouts.contentNavbarLayout')

@section('title', 'Position Details')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="container-fluid ">
    <h4 class="mb-3">Applicants for Position: {{ $position->position_name }}</h4>
    <hr>
</div>
    <!-- Position Details Card -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Position:</strong> {{ $position->position_name }}</p>
            <p><strong>Abbreviation:</strong> {{ $position->abbreviation }}</p>
            <p><strong>Parenthetical Title:</strong> {{ $position->parenthetical_title ?? '-' }}</p>
            <p><strong>Salary Grade:</strong> {{ $position->salary_grade }}</p>
            <p><strong>Employment Status:</strong> {{ $position->employment_status }}</p>
            <p><strong>Fund Source:</strong> {{ $position->fund_source ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($position->status) }}</p>

            <div class="mt-3 d-flex flex-wrap gap-2">
                <!-- <a href="{{ route('unfilled_positions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a> -->
                <a href="{{ route('itemNumbers.print', $position->id) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Print Notice of Vacancy
                </a>

                @php
                    $enabledStatuses = ['Newly-Created', 'Unfilled'];
                @endphp

                <button class="btn btn-info"
                        data-bs-toggle="modal"
                        data-bs-target="#addApplicantModal"
                        {{ in_array($position->status, $enabledStatuses) ? '' : 'disabled' }}>
                    <i class="bi bi-person-plus"></i> Add Applicant
                </button>

                <button class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#updateStatusModal">
                    <i class="bi bi-pencil-square"></i> Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- Applicants Table -->
    <div class="card">
        <div class="card-body">
            <table id="applicantsTable" class="table table-bordered table-striped">
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
                            <td>{{ $applicant->applicant_no }}</td>
                            <td>{{ $applicant->first_name }} {{ $applicant->middle_name }} {{ $applicant->last_name }}</td>
                            <td>{{ $applicant->sex }}</td>
                            <td>{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($applicant->date_applied)->format('M d, Y') }}</td>
                            <td>
                                @php
                                    switch ($applicant->status) {
                                        case 'Hired': $badgeClass = 'success'; break;
                                        case 'Rejected': $badgeClass = 'danger'; break;
                                        case 'Examination': $badgeClass = 'info'; break;
                                        case 'Deliberation': $badgeClass = 'warning'; break;
                                        case 'Submission of Requirements': $badgeClass = 'primary'; break;
                                        case 'On-Boarding': $badgeClass = 'secondary'; break;
                                        default: $badgeClass = 'light';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $applicant->status }}</span>
                            </td>
                            <td>{{ $applicant->remarks }}</td>
                            <td class="d-flex flex-wrap gap-1">
                                @if($applicant->appointment_letter_path)
                                    <a href="{{ asset('storage/' . $applicant->appointment_letter_path) }}" target="_blank" class="btn btn-sm btn-info mb-1">
                                        View Letter
                                    </a>
                                @endif
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


<!-- Main Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('itemnumbers.updateStatus', $position->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusLabel">Update Position Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label fw-bold">Select New Status</label>
                    <select name="status" class="form-select" required>
                        <option value="" disabled selected>Select status</option>
                        <option value="On going Hiring">On going Hiring</option>
                        <option value="Close Hiring">Close Hiring</option>
                        <option value="For Examination">For Examination</option>
                        <option value="For Interview">For Interview</option>
                        <option value="Filled">Filled</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <div class="flex-grow-1"></div>
                    <button type="submit" class="btn btn-warning">Update Status</button>
                </div>

            </form>
        </div>
    </div>
</div>

@include('content.planning.unfilled_positions.partials.add-applicant-modal')

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#applicantsTable').DataTable({
        paging: true,
        searching: true,
        info: true,
        ordering: true,
        lengthChange: true,
        pageLength: 10,
        responsive: true
    });
});
</script>
@endpush
