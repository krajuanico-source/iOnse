@extends('layouts/contentNavbarLayout')

@section('title', 'Take Course')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
  <!-- Back to Courses -->
  <div class="mb-3">
    <a href="{{ route('employee.courses_list') }}" class="btn btn-outline-secondary">
      ← Back to Courses
    </a>
  </div>
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

  <!-- Course Done -->
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
          <div id="questionContainer">
            @foreach($questions as $index => $q)
            <div class="question mb-4" data-index="{{ $index }}">
              <p><strong>Q{{ $index + 1 }}:</strong> {{ $q->question }}</p>
              @php
              $shuffledOptions = $q->options;
              shuffle($shuffledOptions);
              @endphp
              @foreach($shuffledOptions as $option)
              <div class="form-check">
                <input class="form-check-input" type="radio" name="q{{ $q->id }}" value="{{ $option }}" required>
                <label class="form-check-label">{{ $option }}</label>
              </div>
              @endforeach
            </div>
            @endforeach
          </div>
          <div class="d-flex justify-content-between mt-3">
            <button type="button" id="prevBtn" class="btn btn-secondary">Previous</button>
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
            <button type="submit" id="submitBtn" class="btn btn-success" style="display:none;">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Score Modal -->
<div class="modal fade" id="scoreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assessment Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="scoreText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
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
    clearInterval(timerInterval);

    // ✅ Show success alert
    alert("✅ Success! You have finished the course. Please take the final assessment.");

    let assessmentModal = new bootstrap.Modal(document.getElementById('assessmentModal'));
    assessmentModal.show();
  });
</script>
<script>
  let currentQuestion = 0;
  const questions = document.querySelectorAll('.question');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');

  function showQuestion(index) {
    questions.forEach((q, i) => q.style.display = (i === index) ? 'block' : 'none');
    prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
    nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
    submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
  }

  prevBtn.addEventListener('click', () => {
    if (currentQuestion > 0) {
      currentQuestion--;
      showQuestion(currentQuestion);
    }
  });

  nextBtn.addEventListener('click', () => {
    if (currentQuestion < questions.length - 1) {
      currentQuestion++;
      showQuestion(currentQuestion);
    }
  });

  // Initialize
  showQuestion(currentQuestion);
</script>

<script>
  document.getElementById('assessmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let score = 0;

    @foreach($questions as $q)
    if (formData.get('q{{ $q->id }}') === {
        !!json_encode($q - > answer) !!
      }) score++;
    @endforeach

    let totalQuestions = {
      {
        $questions - > count()
      }
    };
    let percentage = (score / totalQuestions) * 100;

    formData.append('time_spent', hours * 3600 + minutes * 60 + seconds);
    formData.append('score', score);

    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch("{{ route('user.completeCourse', $course->id) }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('scoreText').innerHTML =
            `You answered ${score} out of ${totalQuestions} correctly.<br>
           Score: ${percentage.toFixed(2)}%<br>
           Time spent: ${document.getElementById('timer').textContent}`;

          // Hide assessment modal
          let assessmentModalEl = document.getElementById('assessmentModal');
          let assessmentModal = bootstrap.Modal.getInstance(assessmentModalEl);
          assessmentModal.hide();

          // After hide, show score modal
          assessmentModalEl.addEventListener('hidden.bs.modal', function() {
            let scoreModal = new bootstrap.Modal(document.getElementById('scoreModal'));
            scoreModal.show();
          }, {
            once: true
          });
        }
      })
      .catch(err => console.error(err));
  });
</script>


@endpush