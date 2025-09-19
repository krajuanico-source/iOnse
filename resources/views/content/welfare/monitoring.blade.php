@extends('layouts/contentNavbarLayout')

@section('title', 'Monitoring and Evaluation of the Awardee')

@section('content')

   <!-- Toastr CSS -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Monitoring and Evaluation of the Awardee</h1>
        <button id="addcharacterBtn" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Add Awardees
        </button>
    </div>



@endsection