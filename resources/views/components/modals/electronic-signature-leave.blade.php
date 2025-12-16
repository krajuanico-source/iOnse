@props(['ref'])

<div class="modal fade" id="electronicSignatureModal_{{ $ref }}" tabindex="-1" aria-labelledby="electronicSignatureLabel_{{ $ref }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('forms.leaves.electronicSignImage', $ref) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="electronicSignatureLabel_{{ $ref }}">Electronic Signature Leave (Upload)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Upload Your Signature Image</label>
            <input type="file" name="signature_image" class="form-control" required accept=".png,.jpg,.jpeg">
            <small class="text-muted">Accepted formats: PNG, JPG, JPEG. Max size 2MB.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Apply Signature</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>