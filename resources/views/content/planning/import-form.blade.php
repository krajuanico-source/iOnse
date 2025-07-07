@extends('layouts/contentNavbarLayout')

@section('title', 'Import Employees')

@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-header">
      <h4 class="mb-0">Import Employees</h4>
    </div>

    <div class="card-body">
      <form action="{{ route('planning.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
          <label for="file">Upload CSV File</label>
          <input type="file" name="file" id="file" accept=".csv" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
      </form>

      @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
      @elseif (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
