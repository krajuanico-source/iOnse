@extends('layouts/contentNavbarLayout')

@section('title', 'Available Courses')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
  <h3 class="mb-4">Available Courses</h3>

  @if($courses->isEmpty())
  <p>No courses available at the moment.</p>
  @else
  <div class="table-responsive">
    <table id="coursesTable" class="table table-striped table-bordered">
      <thead class="text-center">
        <tr>
          <th>Title</th>
          <th>Code</th>
          <th>Type</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($courses as $course)
        <tr>
          <td>{{ $course->title }}</td>
          <td>{{ $course->code }}</td>
          <td>{{ $course->type }}</td>
          <td class="text-center">
            @if(in_array($course->id, $enrolledIds))
            <span class="badge bg-success">Enrolled</span>
            @else
            <a href="{{ route('employee.enrollCourse', $course->id) }}" class="btn btn-sm btn-primary">Enroll</a>
            @endif

            <!-- View Details Button -->
            <button class="btn btn-sm btn-info view-details"
              data-id="{{ $course->id }}"
              data-title="{{ $course->title }}"
              data-code="{{ $course->code }}"
              data-type="{{ $course->type }}"
              data-duration="{{ $course->duration }}"
              data-date="{{ $course->date }}"
              data-file="{{ $course->file_path }}">
              View Details
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

<!-- Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="courseTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Code:</strong> <span id="courseCode"></span></p>
        <p><strong>Type:</strong> <span id="courseType"></span></p>
        <p><strong>Duration:</strong> <span id="courseDuration"></span></p>
        <p><strong>Date:</strong> <span id="courseDate"></span></p>
      </div>
      <div class="modal-footer">
        <a id="takeCourseBtn" href="#" class="btn btn-success" target="_blank">Take Course</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS & Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  jQuery(function($) {
    // Initialize DataTable
    $('#coursesTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });

    // View Details modal
    $('.view-details').on('click', function() {
      let title = $(this).data('title');
      let code = $(this).data('code');
      let type = $(this).data('type');
      let duration = $(this).data('duration');
      let date = $(this).data('date');
      let file = $(this).data('file');

      $('#courseTitle').text(title);
      $('#courseCode').text(code);
      $('#courseType').text(type);
      $('#courseDuration').text(duration);
      $('#courseDate').text(date);

      if (file) {
        $('#takeCourseBtn')
          .attr('href', '/planning/profile/courses/view/' + $(this).data('id'))
          .removeAttr('target') // ensures same tab
          .removeClass('disabled');

      } else {
        $('#takeCourseBtn').attr('href', '#').addClass('disabled');
      }

      var modal = new bootstrap.Modal(document.getElementById('courseModal'));
      modal.show();
    });
  });
</script>
@endpush