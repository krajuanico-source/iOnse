@extends('layouts/contentNavbarLayout')

@section('title', 'JO Requests')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<div class="container py-4">
  <h4 class="mb-4">Requests for Creation, Extension, or Abolition of COS and JO Positions</h4>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('planning.jo-requests.store') }}" class="row g-3 mb-4">bfg
    @csrf

    <div class="col-md-3">
      <label class="form-label">Request Type</label>
      <select name="type" class="form-select" required>
        <option value="">Select Type</option>
        <option value="CMF">CMF</option>
        <option value="DR">DR</option>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Subject</label>
      <input type="text" name="subject" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Section</label>
      <select name="section_id" class="form-select" required>
        <option value="">Select Section</option>
        @foreach($sections as $section)
        <option value="{{ $section->id }}">{{ $section->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Fund Source</label>
      <select name="fund_source_id" class="form-select">
        <option value="">-- Optional --</option>
        @foreach($fundSources as $fund)
        <option value="{{ $fund->id }}">{{ $fund->fund_source }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Position Name</label>
      <input type="text" name="position_name" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">No. of Positions</label>
      <input type="number" name="no_of_position" class="form-control" min="1" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Effectivity Date</label>
      <input type="date" name="effectivity_of_position" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Remarks</label>
      <textarea name="remarks" class="form-control" rows="1"></textarea>
    </div>

    <div class="col-md-12">
      <button type="submit" class="btn btn-primary">Submit Request</button>
    </div>
  </form>

  <table id="requestTable" class="table table-bordered table-sm">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>Subject</th>
        <th>Type</th>
        <th>Position</th>
        <th>Section</th>
        <th>No. of Positions</th>
        <th>Effectivity</th>
        <th>Status</th>
        <th>Action</th> <!-- NEW -->
      </tr>
    </thead>
    <tbody>
      @forelse($requests as $req)
      <tr>
        <td>{{ $req->id }}</td>
        <td>{{ $req->subject }}</td>
        <td>{{ ucfirst($req->type) }}</td>
        <td>{{ $req->position_name }}</td>
        <td>{{ $req->section->name ?? 'N/A' }}</td>
        <td>{{ $req->no_of_position }}</td>
        <td>{{ \Carbon\Carbon::parse($req->effectivity_of_position)->format('Y-m-d') }}</td>

        <td>{{ $req->status }}</td>
        <td>
          <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $req->id }}">
            View
          </button>

          <!-- Modal -->
          <div class="modal fade" id="viewModal{{ $req->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $req->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewModalLabel{{ $req->id }}">Request Details (ID: {{ $req->id }})</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <dl class="row">
                    <dt class="col-sm-3">Subject</dt>
                    <dd class="col-sm-9">{{ $req->subject }}</dd>

                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9">{{ ucfirst($req->type) }}</dd>

                    <dt class="col-sm-3">Position</dt>
                    <dd class="col-sm-9">{{ $req->position_name }}</dd>

                    <dt class="col-sm-3">Section</dt>
                    <dd class="col-sm-9">{{ $req->section->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">No. of Positions</dt>
                    <dd class="col-sm-9">{{ $req->no_of_position }}</dd>

                    <dt class="col-sm-3">Fund Source</dt>
                    <dd class="col-sm-9">{{ $req->fundSource->fund_source ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Effectivity</dt>
                    <dd class="col-sm-9">{{ \Carbon\Carbon::parse($req->effectivity_of_position)->format('Y-m-d') }}</dd>

                    <dt class="col-sm-3">Remarks</dt>
                    <dd class="col-sm-9">{{ $req->remarks }}</dd>
                  </dl>
                </div>

                <div class="modal-footer">
                  <!-- Approve Button -->
                  <form method="POST" action="{{ route('planning.jo-requests.approve', $req->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to approve this request?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <!-- Disapprove Button -->
                  <form method="POST" action="{{ route('planning.jo-requests.disapprove', $req->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to disapprove this request?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                  </form>

                  <a href="{{ route('planning.jo-requests.edit', $req->id) }}" class="btn btn-secondary">Update</a>

                  <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <a href="{{ route('planning.jo-requests.print', $req->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="bx bx-printer"></i> Print
          </a>
        </td>
      </tr>
      @empty
      @endforelse
    </tbody>
  </table>

</div>
@endsection
@push('scripts')
<script>
  document.querySelectorAll('.confirm-action').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to proceed with this action.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, continue',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  $('#requestTable').DataTable();
</script>
@endpush