@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')
<div class="container py-4">
  <h3>GAD Profile Details</h3>
  <hr>


  <div class="card mb-3">
    <div class="card-header">Employee Information</div>
    <div class="card-body">
      <p><strong>Employee ID:</strong> {{ $profile->user_id }}</p>
      <p><strong>Gender:</strong> {{ $profile->gender }}</p>
      <p><strong>Honorifics:</strong> {{ $profile->honorifics }}</p>
    </div>
  </div>


  <a href="{{ route('forms.gad_profile.print',$profile->id) }}" class="btn btn-secondary" target="_blank">Print PDF</a>
</div>
@endsection