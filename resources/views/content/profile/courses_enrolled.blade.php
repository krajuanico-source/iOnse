@extends('layouts/contentNavbarLayout')

@section('title', 'My Enrolled Courses')

@section('content')
<div class="container">
  <h3>{{ $user->name }} – Enrolled Courses</h3>

  @if($courses->isEmpty())
  <p>No courses enrolled yet.</p>
  @else
  <ul class="list-group">
    @foreach($courses as $course)
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <a href="{{ route('employee.showCourse', $course->id) }}">
        {{ $course->title }}
      </a>
      <span class="badge bg-secondary">
        {{ $course->pivot->status ?? 'Ongoing' }}
      </span>
    </li>
    @endforeach
  </ul>
  @endif
</div>
@endsection