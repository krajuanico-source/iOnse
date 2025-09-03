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

  .header-left {
    display: flex;
    gap: 10px;
    align-items: center;
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
    background-image: url('your-image-url.jpg');
    background-size: cover;
    background-position: center;
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

  .course-price {
    font-size: 14px;
    font-weight: bold;
    color: #d35400;
    margin: 6px 0;
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

  .btn-learn {
    background: #eee;
    color: #333;
  }

  .btn-add {
    background: linear-gradient(to right, blue, red);
    color: white;
  }

  /* Modal styles */
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
    background-color: #fff;
    margin: auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 900px;
    /* You can adjust this max value */
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

  .modal-content form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .modal-content input,
  .modal-content select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
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
      <button id="openModalBtn" style="padding: 10px 20px; background-color: #2e7d32; color: white; border: none; border-radius: 5px; cursor: pointer;">+ Add Course</button>
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

    <div class="course-container mt-4">
      @foreach ($courses as $course)
      <div class="course-card">
        <div class="course-image">
          <!-- style="background-image: url('{{ $course->image_url ?? 'default-image.jpg' }}');" -->
          @if($course->is_enrollable)
          <div class="badge">ENROLL NOW</div>
          @endif
        </div>
        <div class="course-content">
          <div class="course-type">{{ $course->type }}</div>
          <div class="course-title">{{ $course->title }}</div>
          <div class="course-code">{{ $course->code }}</div>
          <div class="course-info">{{ $course->duration }}</div>
          <div class="course-dates">{{ $course->date }}</div>
        </div>
        <div class="course-buttons">
          <button class="btn-add view-course-btn"
            data-id="{{ $course->id }}"
            data-title="{{ $course->title }}"
            data-code="{{ $course->code }}"
            data-type="{{ $course->type }}"
            data-duration="{{ $course->duration }}"
            data-date="{{ $course->date }}">
            View Course
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
<!-- Edit Course Modal -->
<div id="editCourseModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Edit Course</h3>
    <form id="editCourseForm" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" />

      <label>Course Title:</label>
      <input type="text" name="title" required />

      <label>Course Code:</label>
      <input type="text" name="code" required />

      <label>Type:</label>
      <select name="type" required>
        <option value="Technical">Technical</option>
        <option value="Leadership">Leadership</option>
        <option value="Onboarding">Onboarding</option>
        <option value="Compliance">Compliance</option>
      </select>

      <label>Duration:</label>
      <input type="date" name="duration" required />

      <label>Date Range:</label>
      <input type="date" name="date" required />

      <label>Upload File:</label>
      <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov,.avi" />

      <button type="submit">Save Changes</button>
      <button type="button"
        onclick="window.open('{{ asset('storage/' . $course->file) }}', '_blank')"
        @if(!$course->file) disabled @endif>
        View File
      </button>

    </form>
    <div id="filePreviewContainer" style="display:none; margin-top: 1rem;">
      <iframe id="filePreviewIframe" width="100%" height="500px" frameborder="0"></iframe>
    </div>
  </div>
</div>
<div id="addCourseModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Add New Course</h3>
    <form id="courseForm">
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

      <label>Date Range:</label>
      <input type="text" name="date" required>

      <button type="submit">Add Course</button>
    </form>
  </div>
</div>
<!-- search and filter -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.search-input');
    const typeFilter = document.querySelector('.type-filter');
    const sortFilter = document.querySelector('.sort-filter');
    const courseContainer = document.querySelector('.course-container');
    const originalCourses = Array.from(courseContainer.querySelectorAll('.course-card')).map(card => card.cloneNode(true));

    const modal = document.getElementById('editCourseModal');
    const closeModal = modal.querySelector('.close');
    const editForm = document.getElementById('editCourseForm');

    function filterAndSortCourses() {
      const searchText = searchInput.value.toLowerCase();
      const selectedType = typeFilter.value;
      const sortOption = sortFilter.value;

      let filteredCourses = originalCourses.filter(card => {
        const title = card.querySelector('.course-title').textContent.toLowerCase();
        const type = card.querySelector('.course-type').textContent;

        const matchesSearch = title.includes(searchText);
        const matchesType = selectedType === 'All' || type === selectedType;

        return matchesSearch && matchesType;
      });

      if (sortOption === 'Date') {
        filteredCourses.sort((a, b) => {
          const dateA = new Date(a.querySelector('.course-dates').textContent);
          const dateB = new Date(b.querySelector('.course-dates').textContent);
          return dateA - dateB;
        });
      }

      courseContainer.innerHTML = '';
      filteredCourses.forEach(card => courseContainer.appendChild(card));
    }

    // Event listeners for search and filters
    searchInput.addEventListener('input', filterAndSortCourses);
    typeFilter.addEventListener('change', filterAndSortCourses);
    sortFilter.addEventListener('change', filterAndSortCourses);

    // View Course - open modal and populate fields
    courseContainer.addEventListener('click', (e) => {
      const btn = e.target.closest('.view-course-btn');
      if (!btn) return;

      const card = btn.closest('.course-card');

      editForm.id.value = btn.dataset.id;
      editForm.title.value = btn.dataset.title;
      editForm.code.value = btn.dataset.code;
      editForm.type.value = btn.dataset.type;
      editForm.duration.value = btn.dataset.duration;
      editForm.date.value = btn.dataset.date;

      modal.style.display = 'block';
    });

    // Close modal
    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    // Save Changes - update course card
    editForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const id = editForm.id.value;

      const allCards = courseContainer.querySelectorAll('.course-card');
      allCards.forEach(card => {
        const btn = card.querySelector('.view-course-btn');
        if (btn && btn.dataset.id === id) {
          // Update visible content
          card.querySelector('.course-title').textContent = editForm.title.value;
          card.querySelector('.course-code').textContent = editForm.code.value;
          card.querySelector('.course-type').textContent = editForm.type.value;
          card.querySelector('.course-info').textContent = editForm.duration.value;
          card.querySelector('.course-dates').textContent = editForm.date.value;

          // Update button dataset
          btn.dataset.title = editForm.title.value;
          btn.dataset.code = editForm.code.value;
          btn.dataset.type = editForm.type.value;
          btn.dataset.duration = editForm.duration.value;
          btn.dataset.date = editForm.date.value;
        }
      });

      modal.style.display = 'none';
    });
  });
</script>

<script>
  // Open edit modal and populate form
  document.querySelectorAll('.view-course-btn').forEach(button => {
    button.addEventListener('click', () => {
      const modal = document.getElementById('editCourseModal');
      const form = modal.querySelector('form');

      // Fill inputs with data attributes
      form.id.value = button.dataset.id;
      form.title.value = button.dataset.title;
      form.code.value = button.dataset.code;
      form.type.value = button.dataset.type;
      form.duration.value = button.dataset.duration;
      form.date.value = button.dataset.date;

      modal.style.display = 'block';
    });
  });

  // Close modal when clicking the close button or outside modal
  document.querySelector('#editCourseModal .close').addEventListener('click', () => {
    document.getElementById('editCourseModal').style.display = 'none';
  });
  window.addEventListener('click', (e) => {
    const modal = document.getElementById('editCourseModal');
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });

  // Handle form submit to send update request
  document.getElementById('editCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const courseId = formData.get('id');

    fetch(`/courses/${courseId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        },
        body: formData,
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
</script>
<script>
  // Open modal
  document.getElementById('openModalBtn').addEventListener('click', () => {
    document.getElementById('addCourseModal').style.display = 'block';
  });

  // Close modal
  document.querySelector('#addCourseModal .close').addEventListener('click', () => {
    document.getElementById('addCourseModal').style.display = 'none';
  });

  // Close modal on outside click
  window.addEventListener('click', (event) => {
    const modal = document.getElementById('addCourseModal');
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });

  // Form submission
  document.getElementById('courseForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;

    const courseData = {
      title: form.title.value.trim(),
      code: form.code.value.trim(),
      type: form.type.value,
      duration: form.duration.value.trim(),
      date: form.date.value.trim(), // this line saves the date string
    };
    fetch("{{ route('courses.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(courseData)
      })
      .then(async response => {
        if (!response.ok) {
          // If response is an error, try to parse JSON error message
          let errorMessage = 'Failed to save course.';
          try {
            const errorData = await response.json();
            errorMessage = errorData.message || errorMessage;
          } catch {
            // ignore parse error
          }
          throw new Error(errorMessage);
        }
        return response.json();
      })
      .then(data => {
        alert(data.message || 'Course added successfully!');
        form.reset();
        document.getElementById('addCourseModal').style.display = 'none';
        window.location.reload(); // Refresh the page
        // Optionally refresh course list or append new course card here
      })
      .catch(async (error) => {
        if (error instanceof Response) {
          const errorData = await error.json();
          alert(`Error: ${errorData.message}\n${errorData.error}`);
          console.error('Full error:', errorData);
        } else {
          alert('Unexpected error occurred: ' + error.message);
        }
      });
  });
</script>
<!-- Script -->
<script>
  const courseContainer = document.getElementById('courseContainer');

  courseContainer.addEventListener('click', (e) => {
    const btn = e.target.closest('.view-course-btn');
    if (!btn) return;

    const modal = document.getElementById('editCourseModal');
    const editForm = document.getElementById('editCourseForm');

    editForm.id.value = btn.dataset.id;
    editForm.title.value = btn.dataset.title;
    editForm.code.value = btn.dataset.code;
    editForm.type.value = btn.dataset.type;
    editForm.duration.value = btn.dataset.duration;
    editForm.date.value = btn.dataset.date;

    const filePreview = document.getElementById('filePreview');
    const fileUrl = btn.dataset.file;
    filePreview.innerHTML = ''; // Clear previous

    if (fileUrl) {
      const ext = fileUrl.split('.').pop().toLowerCase();

      if (['mp4', 'mov', 'avi'].includes(ext)) {
        filePreview.innerHTML = `
          <video width="100%" height="auto" controls>
            <source src="${fileUrl}" type="video/${ext}">
            Your browser does not support the video tag.
          </video>`;
      } else if (ext === 'pdf') {
        filePreview.innerHTML = `
          <embed src="${fileUrl}" type="application/pdf" width="100%" height="400px"/>`;
      } else {
        filePreview.innerHTML = `<a href="${fileUrl}" target="_blank">Download File</a>`;
      }
    }

    modal.style.display = 'block';
  });
</script>
@endsection