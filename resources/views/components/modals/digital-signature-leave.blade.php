@props(['ref'])

<div class="modal fade" id="digitalSignatureModal_{{ $ref }}" tabindex="-1" aria-labelledby="digitalSignatureLabel_{{ $ref }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('forms.leaves.digitalSignImage', $ref) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="digitalSignatureLabel_{{ $ref }}">Digital Signature</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small">
            Upload your P12/PFX certificate and enter its password to digitally sign the travel order.
            Optionally, upload a signature image to appear on the PDF.
          </p>

          <!-- Certificate -->
          <div class="mb-3">
            <label class="form-label">Upload Certificate (.p12/.pfx)</label>
            <input type="file" name="certificate" class="form-control" required accept=".p12,.pfx">
          </div>

          <!-- Certificate Password -->
          <div class="mb-3">
            <label class="form-label">Certificate Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <!-- Optional Signature Image -->
          <div class="mb-3">
            <label class="form-label">Signature Image (optional)</label>
            <input type="file" name="signature_image" class="form-control" accept="image/png, image/jpg, image/jpeg">
            <small class="text-muted">This image will overlay above the Regional Director's name.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-check"></i> Sign PDF</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>