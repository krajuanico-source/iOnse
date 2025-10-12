@extends('layouts/contentNavbarLayout')

@section('title', 'Take Course')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
  <h3>{{ $course->title }} ({{ $course->code }})</h3>
  <p><strong>Type:</strong> {{ $course->type }}</p>
  <p><strong>Duration:</strong> {{ $course->duration }}</p>

  <!-- Timer -->
  <div class="mb-3">
    <strong>Time spent:</strong> <span id="timer">00:00:00</span>
  </div>

  <!-- Course content -->
  <div class="mb-4">
    @if(Str::endsWith($course->file_path, ['mp4', 'webm', 'ogg']))
    <video id="courseVideo" width="100%" height="480" controls>
      <source src="{{ asset('storage/' . $course->file_path) }}" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    @elseif(Str::endsWith($course->file_path, ['pdf']))
    <iframe src="{{ asset('storage/' . $course->file_path) }}" width="100%" height="600px"></iframe>
    @else
    <p>Course file not supported for preview. <a href="{{ asset('storage/' . $course->file_path) }}" target="_blank">Download</a></p>
    @endif
  </div>

  <!-- Course Done button -->
  <button id="courseDoneBtn" class="btn btn-success">Course Done</button>
</div>

<!-- Assessment Modal -->
<div class="modal fade" id="assessmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Final Assessment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="assessmentForm">
          @foreach($questions as $index => $q)
          <div class="mb-3">
            <p><strong>Q{{ $index + 1 }}:</strong> {{ $q->question }}</p>
            @foreach($q->options as $option)
            <div class="form-check">
              <input class="form-check-input" type="radio" name="q{{ $q->id }}" value="{{ $option }}" required>
              <label class="form-check-label">{{ $option }}</label>
            </div>
            @endforeach
          </div>
          @endforeach
          <button type="submit" class="btn btn-primary">Submit Assessment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assessment Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="resultText"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let hours = 0,
    minutes = 0,
    seconds = 0;
  let timerInterval = setInterval(updateTimer, 1000);

  function updateTimer() {
    seconds++;
    if (seconds === 60) {
      seconds = 0;
      minutes++;
    }
    if (minutes === 60) {
      minutes = 0;
      hours++;
    }
    document.getElementById('timer').textContent =
      String(hours).padStart(2, '0') + ':' +
      String(minutes).padStart(2, '0') + ':' +
      String(seconds).padStart(2, '0');
  }

  // Show Assessment modal when Course Done
  document.getElementById('courseDoneBtn').addEventListener('click', function() {
    clearInterval(timerInterval); // stop timer
    var assessmentModal = new bootstrap.Modal(document.getElementById('assessmentModal'));
    assessmentModal.show();
  });

  // Submit assessment
  document.getElementById('assessmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    let totalQuestions = {
      {
        count($questions)
      }
    };
    let score = 0;

    @foreach($questions as $q)
    let ans = formData.get('q{{ $q->id }}');
    if (ans === '{{ $q->answer }}') {
      score++;
    }
    @endforeach

    let percentage = (score / totalQuestions) * 100;

    let resultText = `You answered ${score} out of ${totalQuestions} correctly.<br>
      Score: ${percentage.toFixed(2)}%<br>
      Time spent: ${document.getElementById('timer').textContent}`;

    document.getElementById('resultText').innerHTML = resultText;

    // Close assessment modal & show result modal
    var assessmentModalEl = document.getElementById('assessmentModal');
    var assessmentModal = bootstrap.Modal.getInstance(assessmentModalEl);
    assessmentModal.hide();

    var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
    resultModal.show();

    // Send AJAX to save score & time
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('time_spent', hours * 3600 + minutes * 60 + seconds);
    formData.append('score', score);

    fetch("{{ route('employee.completeCourse', $course->id) }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      }).then(res => res.json())
      .then(data => console.log('Saved:', data))
      .catch(err => console.error(err));
  });
</script>
@endpush