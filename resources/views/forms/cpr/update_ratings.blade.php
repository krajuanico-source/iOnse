@extends('layouts/contentNavbarLayout')

@section('title', 'Update CPR Ratings')

@section('content')
<div class="card p-4">
  <h4 class="fw-bold mb-3">Update CPR Ratings for {{ $cpr->semester }} ({{ \Carbon\Carbon::parse($cpr->rating_period_start)->format('M Y') }})</h4>

  <form action="{{ route('employee.update', $cpr->id) }}" method="POST">
    @csrf
    @method('PUT')

    @foreach($employees as $emp)
    <div class="mb-3">
      <label class="form-label">Employee ID: {{ $emp->employee_id }}</label>
      <input type="number" name="ratings[{{ $emp->id }}]" value="{{ $emp->rating }}" class="form-control" min="0" max="100" step="0.01" required>
    </div>
    @endforeach

    <button type="submit" class="btn btn-success">Update Ratings</button>
  </form>
</div>
@endsection