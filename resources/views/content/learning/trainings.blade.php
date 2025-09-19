@extends('layouts/contentNavbarLayout')

@section('title', 'List of Trainings - Trainings')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: #f8f9fa;
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .course-title {
    max-width: 250px;
    /* adjust based on your layout */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
  }

  .search-input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 250px;
  }

  .search-button {
    padding: 10px 20px;
    background-color: #b22217;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .filters {
    display: flex;
    gap: 10px;
  }

  select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  .course-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .course-card {
    width: 250px;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }

  .course-image {
    height: 140px;
    background: #eaeaea;
    position: relative;
  }

  .badge {
    background: rgba(255, 0, 0, 0.8);
    color: white;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 3px;
    position: absolute;
    top: 10px;
    left: 10px;
  }

  .course-content {
    padding: 10px;
  }

  .course-type {
    font-size: 10px;
    color: #888;
    text-transform: uppercase;
  }

  .course-title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin: 4px 0;
  }

  .course-code,
  .course-info,
  .course-dates {
    font-size: 12px;
    color: #666;
  }

  .course-buttons {
    display: flex;
    border-top: 1px solid #eee;
  }

  .course-buttons button {
    flex: 1;
    padding: 10px;
    border: none;
    cursor: pointer;
  }

  .btn-add {
    background: linear-gradient(to right, blue, red);
    color: white;
  }

  /* Modal */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-content {
    background: #fff;
    margin: auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    position: relative;
  }

  .close {
    color: #aaa;
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .modal-content input,
  .modal-content select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
  }

  .modal-content button[type="submit"] {
    background-color: #2e7d32;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
</style>

<div class="card">
  <div class="container py-4">
    <div class="header">
      <h2>Courses</h2>
      <button id="openModalBtn" style="padding:10px 20px;background-color:#2e7d32;color:white;border:none;border-radius:5px;cursor:pointer;">Add Course</button>

    </div>

    <div class="header">
      <div>
        <input type="text" class="search-input" placeholder="Search course">
        <button class="search-button">Search</button>
      </div>
      <div class="filters">
        <select class="type-filter">
          <option>All</option>
          <option>Technical</option>
          <option>Leadership</option>
          <option>Onboarding</option>
          <option>Compliance</option>
        </select>
        <select class="sort-filter">
          <option>Sort</option>
          <option>Date</option>
        </select>
      </div>
    </div>

    <div class="course-container" id="courseContainer">
      @foreach ($courses as $course)
      <div class="course-card">
        <div class="course-image">
          @if($course->is_enrollable)
          <div class="badge">ENROLL NOW</div>
          @endif
        </div>
        <div class="course-content">
          <div class="course-type">Course Type: {{ $course->type }}</div>
          <div class="course-title">{{ $course->title }}</div>
          <div class="course-code">Course Code: {{ $course->code }}</div>
          <div class="course-info">Duration (hrs): {{ $course->duration }}</div>
          <div class="course-dates">Date Started: {{ $course->date }}</div>
        </div>
        <div class="course-buttons">
          <button class="btn-add view-course-btn"
            data-id="{{ $course->id }}"
            data-title="{{ $course->title }}"
            data-code="{{ $course->code }}"
            data-type="{{ $course->type }}"
            data-duration="{{ $course->duration }}"
            data-date="{{ $course->date }}"
            data-file="{{ $course->file_path ? asset('storage/'.$course->file_path) : '' }}">
            View Course
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Edit Course Modal -->
<div id="editCourseModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Edit Course</h3>
    <form id="editCourseForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="editCourseId" />

      <label>Course Title:</label>
      <input type="text" name="title" id="editCourseTitle" required />

      <label>Course Code:</label>
      <input type="text" name="code" id="editCourseCode" required />

      <label>Type:</label>
      <select name="type" id="editCourseType" required>
        <option value="Technical">Technical</option>
        <option value="Leadership">Leadership</option>
        <option value="Onboarding">Onboarding</option>
        <option value="Compliance">Compliance</option>
      </select>

      <label>Duration:</label>
      <input type="text" name="duration" id="editCourseDuration" required />

      <label>Date Started:</label>
      <input type="date" name="date" id="editCourseDate" required />

      <label>Upload File:</label>
      <input type="file" name="file_path" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov,.avi" />

      <!-- File Preview -->
      <div id="filePreview" style="margin-top:1rem;"></div>

      <div class="mt-3">
        <button type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Course Modal -->
<div id="addCourseModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Add New Course</h3>
    <form id="courseForm" method="POST" enctype="multipart/form-data">
      @csrf
      <label>Course Title:</label>
      <input type="text" name="title" required>
      <label>Course Code:</label>
      <input type="text" name="code" required>
      <label>Type:</label>
      <select name="type">
        <option value="Technical">Technical</option>
        <option value="Leadership">Leadership</option>
        <option value="Onboarding">Onboarding</option>
        <option value="Compliance">Compliance</option>
      </select>
      <label>Duration:</label>
      <input type="text" name="duration" required>
      <label>Date Started:</label>
      <input type="date" name="date" required>
      <label>Upload File:</label>
      <input type="file" name="file_path" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov,.avi" />
      <button type="submit">Add Course</button>
    </form>
  </div>
</div>

<script>
  // Handle View Course -> open Edit modal
  document.querySelectorAll('.view-course-btn').forEach(button => {
    button.addEventListener('click', () => {
      const modal = document.getElementById('editCourseModal');
      const form = modal.querySelector('form');

      form.id.value = button.dataset.id;
      form.title.value = button.dataset.title;
      form.code.value = button.dataset.code;
      form.type.value = button.dataset.type;
      form.duration.value = button.dataset.duration;
      form.date.value = button.dataset.date;

      const filePreview = document.getElementById('filePreview');
      filePreview.innerHTML = '';
      const fileUrl = button.dataset.file;

      if (fileUrl) {
        const ext = fileUrl.split('.').pop().toLowerCase();
        if (['mp4', 'mov', 'avi'].includes(ext)) {
          filePreview.innerHTML = `<video width="100%" controls><source src="${fileUrl}" type="video/${ext}"></video>`;
        } else if (ext === 'pdf') {
          filePreview.innerHTML = `<embed src="${fileUrl}" type="application/pdf" width="100%" height="400px"/>`;
        } else {
          filePreview.innerHTML = `<a href="${fileUrl}" target="_blank">Download File</a>`;
        }
      }

      modal.style.display = 'block';
    });
  });

  // Close modals
  document.querySelectorAll('.modal .close').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.modal').style.display = 'none');
  });
  window.addEventListener('click', (e) => {
    document.querySelectorAll('.modal').forEach(modal => {
      if (e.target === modal) modal.style.display = 'none';
    });
  });

  // Submit Edit Course (update)
  document.getElementById('editCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const courseId = formData.get('id');

    fetch(`/courses/${courseId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => {
        console.error(err);
        alert('Error updating course.');
      });
  });

  // Add Course Modal
  document.getElementById('openModalBtn').addEventListener('click', () => {
    document.getElementById('addCourseModal').style.display = 'block';
  });
  document.getElementById('courseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("{{ route('courses.store') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => {
        console.error(err);
        alert('Error adding course.');
      });
  });
</script>
@endsection
