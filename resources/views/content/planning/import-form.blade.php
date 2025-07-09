@extends('layouts/contentNavbarLayout')

@section('title', 'Import Employees')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="container py-4">


  {{-- ✅ Upload Form Card --}}
  <div class="card mb-4">
    <div class="card-header">
      <h4 class="mb-0">Import Employees</h4>
    </div>

    <div class="card-body">
      <form action="{{ route('planning.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
          <label for="file">Upload CSV File</label>
          <input type="file" name="file" id="file" accept=".csv" class="form-control" required>
        </div>
        <div class="float-end">
          <button type="submit" class="btn btn-success">Import</button>
        </div>
      </form>
    </div>
  </div>

  {{-- ✅ Import Log History Card --}}
  @php
    $logs = \App\Models\ImportLog::latest()->take(10)->get();
  @endphp

  @if ($logs->count())
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Import History</h5>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Filename</th>
              <th>Status</th>
              <th>Imported At</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($logs as $log)
              <tr>
                <td>{{ $log->filename }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->imported_at ? \Carbon\Carbon::parse($log->imported_at)->format('Y-m-d H:i') : '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

</div>
@push('scripts')
<!-- Toastr Notification -->
 <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "timeOut": "3000",
    "positionClass": "toast-top-right"
  };

  @if (session('success'))
    toastr.success('Imported successfully!');
  @elseif (session('error'))
    toastr.error("{{ session('error') }}");
  @endif
</script>
@endpush
@endsection
