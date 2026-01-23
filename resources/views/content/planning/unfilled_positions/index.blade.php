@extends('layouts.contentNavbarLayout')

@section('title', 'Unfilled Positions')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <h4 class="mb-4">List of Unfilled Positions</h4>

    <div class="table-responsive">
     <table id="unfilledpos" class="table table-bordered">
          <thead>
              <tr>
                  <th style="width: 30%;">Item Number</th>
                  <th style="width: 30%;">Position</th>
                  <th style="width: 10%;">Salary Grade</th>
                  <th style="width: 15%;">Status</th>
                  <th style="width: 20%;">Action</th>
              </tr>
          </thead>
          <tbody>
              @foreach($positions as $position)
                <tr>
                    <td>{{ $position->item_no ?? '-' }}</td>
                    <td>{{ $position->position_name }}</td>
                    <td>{{ $position->salaryGrade->salary_grade ?? '-' }}</td>
                    <td>{{ ucfirst($position->status) }} / {{ $position->group_count }}</td>
                    <td>
                        <a href="{{ route('unfilled_positions.show', $position->group_id) }}" class="btn btn-sm btn-primary">
                            View Details
                        </a>
                    </td>
                </tr>
              @endforeach
          </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function() {
    $('#unfilledpos').DataTable({
      paging: true,
      searching: true,
      info: true,
      ordering: true,
      lengthChange: true,
      pageLength: 10
    });
  });
</script>
@endpush
