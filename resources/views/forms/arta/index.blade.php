@extends('layouts/contentNavbarLayout')

@section('title', 'ARTA Form')

@section('content')
<div class="card p-4">
  <h4 class="mb-4">{{ $arta ? 'Update ARTA Record' : 'New ARTA Record' }}</h4>

  <form action="{{ $arta ? route('arta.update', $arta->id) : route('arta.store') }}" method="POST" enctype="multipart/form-data">

    @csrf
    @if($arta)
    @method('PUT')
    @endif

    <table class="table table-bordered">

      <!-- Date -->
      <tr>
        <th style="width: 25%">Date</th>
        <td>
          <input type="date" name="arta_date" class="form-control"
            value="{{ old('arta_date', $arta->arta_date ?? '') }}" required>
        </td>
      </tr>

      <!-- Type -->
      <tr>
        <th>Type</th>
        <td>
          @php
          $types = ['Type A', 'Type B', 'Type C', 'Type D'];
          @endphp
          <select name="arta_type" class="form-select" required>
            <option value="">Select…</option>
            @foreach($types as $type)
            <option value="{{ $type }}" {{ old('arta_type', $arta->arta_type ?? '') == $type ? 'selected' : '' }}>
              {{ $type }}
            </option>
            @endforeach
          </select>
        </td>
      </tr>

      <!-- Additional Info -->
      <tr>
        <th>Additional Info</th>
        <td>
          @php
          $specifies = ['Option 1', 'Option 2', 'Others (please specify)'];
          @endphp
          <select name="arta_specify" id="arta_specify" class="form-select">
            <option value="">Select…</option>
            @foreach($specifies as $spec)
            <option value="{{ $spec }}" {{ old('arta_specify', $arta->arta_specify ?? '') == $spec ? 'selected' : '' }}>
              {{ $spec }}
            </option>
            @endforeach
          </select>

          <input type="text" name="arta_specify_other" id="arta_specify_other"
            class="form-control mt-2 {{ (old('arta_specify', $arta->arta_specify ?? '') == 'Others (please specify)') ? '' : 'd-none' }}"
            placeholder="Please specify…"
            value="{{ old('arta_specify_other', $arta->arta_specify_other ?? '') }}">
        </td>
      </tr>

      <!-- Purpose -->
      <tr>
        <th>Purpose</th>
        <td>
          <input type="text" name="arta_purpose" class="form-control"
            value="{{ old('arta_purpose', $arta->arta_purpose ?? '') }}" required>
        </td>
      </tr>

      <!-- Mode -->
      <tr>
        <th>Mode</th>
        <td>
          <select name="arta_mode" class="form-select" required>
            <option value="">Select…</option>
            <option value="Email" {{ old('arta_mode', $arta->arta_mode ?? '') == 'Email' ? 'selected' : '' }}>Email</option>
            <option value="Pick Up" {{ old('arta_mode', $arta->arta_mode ?? '') == 'Pick Up' ? 'selected' : '' }}>Pick Up</option>
          </select>
        </td>
      </tr>

      <!-- File Upload -->
      <tr>
        <th>Upload File (optional)</th>
        <td>
          <input type="file" name="arta_file" class="form-control">
          @if(!empty($arta->arta_file))
          <small class="text-muted">
            Current file:
            <a href="{{ asset('storage/' . $arta->arta_file) }}" target="_blank">View Uploaded File</a>
          </small>
          @endif
        </td>
      </tr>

      <!-- Submit -->
      <tr>
        <td colspan="2" class="text-center">
          <button type="submit" class="btn btn-primary px-4">
            {{ $arta ? 'Update ARTA' : 'Create ARTA' }}
          </button>
        </td>
      </tr>

    </table>
  </form>
</div>
@endsection

@section('scripts')
<script>
  const artaSelect = document.getElementById('arta_specify');
  const artaOther = document.getElementById('arta_specify_other');

  artaSelect.addEventListener('change', function() {
    artaOther.classList.toggle('d-none', this.value !== 'Others (please specify)');
    if (this.value !== 'Others (please specify)') artaOther.value = '';
  });
</script>
@endsection