@props(['ref'])

<div class="modal fade" id="signatureTypeModal_{{ $ref }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Choose Signature Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p>Select the type of signature for this document:</p>

        <div class="d-grid gap-2">

          <!-- DIGITAL SIGNATURE -->
          <button
            class="btn btn-outline-success btn-sm"
            data-bs-dismiss="modal"
            data-bs-toggle="modal"
            data-bs-target="#digitalSignatureModal_{{ $ref }}">
            Digital Signature
          </button>

          <!-- WET / ELECTRONIC SIGNATURE -->
          <button
            class="btn btn-outline-info btn-sm"
            data-bs-dismiss="modal"
            data-bs-toggle="modal"
            data-bs-target="#electronicSignatureModal_{{ $ref }}">
            Electronic (Wet) Signature
          </button>

        </div>
      </div>

    </div>
  </div>
</div>