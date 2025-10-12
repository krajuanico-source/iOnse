@extends('layouts/contentNavbarLayout')

@section('title', 'Course Details')

@section('content')
<div class="container">
  <h3>{{ $course->title }}</h3>
  <p><strong>Code:</strong> {{ $course->code }}</p>
  <p><strong>Type:</strong> {{ $course->type }}</p>
  <p><strong>Duration:</strong> {{ $course->duration }}</p>
  <p><strong>Date:</strong> {{ $course->date }}</p>

  @if($course->file_path)
  <p>
    <a href="{{ asset($course->file_path) }}" target="_blank">📂 View Course File</a>
  </p>
  @endif

  <p><strong>Status:</strong> {{ $enrollment->pivot->status ?? 'Ongoing' }}</p>

  <a href="{{ route('employee.courses_enrolled') }}" class="btn btn-primary">← Back to Courses</a>
</div>
@endsection