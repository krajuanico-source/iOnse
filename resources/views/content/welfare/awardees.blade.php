@extends('layouts/contentNavbarLayout')

@section('title', 'Hall of Awardees')

@section('content')

<!-- Toastr and Bootstrap JS Bundle with Popper -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-monitor me-2"></i> Hall of Awardees</h1>
        <button id="addAwardBtn" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Add Awardees
        </button>
    </div>

@endsection
    