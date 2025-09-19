@extends('layouts/contentNavbarLayout')

@section('title', 'Archive of Memorandum')

@section('content')

<!-- Toastr and Bootstrap JS Bundle with Popper -->
 <meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Memorandum Card -->
<div class="card mb-4 shadow-sm">
    <!-- Card Header: Title + Add Button -->
    <div class="card-header d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3" style="font-family: Arial, sans-serif;">
    <i class="fas fa-archive me-2"></i> Archive of Memorandum</h1>
        <button id="addMemorandumBtn" class="btn btn-success d-flex align-items-center">
            <i class="fas fa-plus me-2"></i>Add Memorandum
        </button>
    </div>

    <!-- Card Body: Search + Filters -->
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('welfare.memorandum') }}" id="searchFilterForm">
            <div class="row g-4 align-items-end">
                <!-- Search Input -->
                <div class="col-md-6">
                    <label for="searchInput" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                            placeholder="Search by issuance number or subject" class="form-control" autocomplete="off">
                    </div>
                </div>

                <!-- Award Type Filter -->
                <div class="col-md-6">
                    <label for="awardTypeFilter" class="form-label">Award Type</label>
                    <div class="d-flex gap-2">
                        <select id="awardTypeFilter" name="award_type" class="form-select">
                            <option value="all" {{ request('award_type') == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="character" {{ request('award_type') == 'character' ? 'selected' : '' }}>Character Building</option>
                            <option value="praise" {{ request('award_type') == 'praise' ? 'selected' : '' }}>PRAISE</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


    @php 
    ob_start(); 
    @endphp

    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th scope="col" class="text-center">Issuance Number</th>
                <th scope="col" class="text-center">Subject</th>
                <th scope="col" class="text-center">Date of Issuance</th>
                <th scope="col" class="text-center">Notes</th>
                <th scope="col" class="text-center">PDF File</th>
                <th scope="col" class="text-center">Actions</th>
             </tr>
        </thead>
        <tbody id="memorandumTableBody">
    @if ($memorandums->count() > 0)
        @foreach ($memorandums as $memo)
            <tr>
                <td class="text-center small">{{ $memo->issuance_number }}</td>
                <td class="text-center small">{{ $memo->subject }}</td>
                <td class="text-center small">{{ $memo->date_of_issuance }}</td>
                <td class="text-center align-middle" style="max-width: 300px;">
                    <div style="display: inline-block; white-space: pre-line; line-height: 0.3;">
                        <span style="display: block; margin: 0;">
                            {{ Str::limit(strip_tags($memo->notes), 7) }}
                        </span>
                        @if (Str::length(strip_tags($memo->notes)) > 6)
                            <a href="#" class="read-more-link"
                               data-notes="{{ e($memo->notes) }}"
                               data-bs-toggle="modal"
                               data-bs-target="#readMoreModal"
                               style="font-size: 0.75rem; display: block; margin-top: 5px;">
                                Read more
                            </a>
                        @endif
                    </div>
                </td>
                <td class="text-center">
                    <a href="#"
                    class="btn btn-link btn-sm text-nowrap view-file-btn"
                    data-url="{{ Storage::url($memo->file_path) }}">
                        <i class="fas fa-file-pdf me-1"></i>
                        <span style="font-size: 0.85rem;">View File</span>
                    </a>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $memo->id }}"
                            data-issuance_number="{{ $memo->issuance_number }}"
                            data-subject="{{ $memo->subject }}"
                            data-award_type="{{ $memo->award_type }}"
                            data-date="{{ $memo->date_of_issuance }}"
                            data-notes="{{ $memo->notes }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <!-- Delete Button
                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                            data-id="{{ $memo->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button> -->
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="text-center text-muted">
                @if(request()->has('search') || request()->has('award_type'))
                    No matching records found.
                @else
                    No data available.
                @endif
            </td>
        </tr>
    @endif
    </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-1">
        <div class="text-muted">
            Showing {{ $memorandums->firstItem() ?? 0 }} to {{ $memorandums->lastItem() ?? 0 }} of {{ $memorandums->total() }} entries
        </div>
        <div>
            {{ $memorandums->links() }}
        </div>
    </div>

    
    @php 
    $memorandumTable = ob_get_clean(); 
    @endphp


            <!-- Read More Modal -->
        <div class="modal fade" id="readMoreModal" tabindex="-1" aria-labelledby="readMoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="readMoreModalLabel">Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-10 py-6">
                <div id="fullNotesContent" style="white-space: pre-wrap; font-family: inherit; text-align: justify;"></div>
            </div>
            </div>
        </div>
        </div>

            <!-- Full-Screen Spinner Overlay with Bigger Spinner For the View File -->
            <div id="fileLoadingOverlay"
                    class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-75 d-none"
                    style="z-index: 9999;">
                    <!-- Add margin-bottom using Bootstrap class -->
                    <div class="spinner-border text-success mb-4"
                        role="status"
                        style="width: 5rem; height: 5rem; border-width: .6em; border-color: #274beae7 #f3f3f3 #f3f3f3;">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <!-- ✅ Visible "Loading..." text with spacing and smaller size -->
                    <!-- Centered loading text -->
                        <div style="color: white; font-size: 0.9rem; font-weight: 500; text-align: center;">
                            <div class="mb-2">Please wait while we load the file.</div>
                        </div>

                </div>


                    <!-- Global Full-Screen Spinner for the Filter -->
                        <div id="globalLoadingOverlay"
                            class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark bg-opacity-75 d-none"
                            style="z-index: 9999;">
                            <div class="spinner-border text-success mb-3"
                                role="status"
                                style="width: 4rem; height: 4rem; border-width: .4rem; border-color: #274beae7 #f3f3f3 #f3f3f3;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div id="globalLoadingMessage"
                                style="color: white; font-size: 0.9rem; font-weight: 500; text-align: center;">
                                <div>Filtering Results...</div>
                                <div>Please wait.</div>
                            </div>
                        </div>


        <!-- Add Memorandum Modal -->
        <div class="modal fade" id="addMemorandumModal" tabindex="-1" aria-labelledby="addMemorandumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('memorandums.store') }}" enctype="multipart/form-data" class="modal-content" id="memorandumForm">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="addMemorandumModalLabel">Add Memorandum</h4>
            </div>
            <div class="modal-body">
                <!-- Row 1: Issuance Number & Subject -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="issuance_number" class="form-label fw-bold">
                            Issuance Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="issuance_number" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subject" class="form-label fw-bold">
                            Subject <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                </div>


                <!-- Row 2: Award Type & Date of Issuance -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_award_type" class="form-label fw-bold">Award Type</label>
                        <select name="award_type" id="edit_award_type" class="form-select text-muted" required>
                            <option value="" disabled selected>Select Award Type</option>
                            <option value="character" class="text-dark">Character Building</option>
                            <option value="praise" class="text-dark">PRAISE</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="date_of_issuance" class="form-label fw-bold">Date of Issuance <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date_of_issuance" required>
                    </div>
                </div>

                <!-- Row 3: Upload PDF -->
                <div class="mb-3">
                    <label for="file" class="form-label fw-bold text-muted">Upload PDF <span class="text-danger">*</span></label>
                    <input type="file" class="form-control text-muted" name="file" accept=".pdf" required>
                    <small class="text-muted">Max size: 5MB</small>
                </div>


                <!-- Row 4: Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label fw-bold">Notes</label>
                    <textarea name="notes" class="form-control" rows="6" style="font-size:1.1rem"></textarea>
                </div>
            </div>

            <!-- ✅ Modal Footer with Cancel and Add buttons -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Add Memorandum</button>
            </div>
        </form>
    </div>
</div>




        <!-- Edit Memorandum Modal (Landscape Layout) -->
        <div class="modal fade" id="editMemorandumModal" tabindex="-1" aria-labelledby="editMemorandumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <form method="POST" action="" enctype="multipart/form-data" class="modal-content" id="editMemorandumForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                <h4 class="modal-title" id="editMemorandumModalLabel">Edit Memorandum</h4>
                </div>
                <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">

                <!-- Row 1: Issuance Number & Subject -->
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="edit_issuance_number" class="form-label fw-bold">Issuance Number</label>
                    <input type="text" class="form-control" name="issuance_number" id="edit_issuance_number" required>
                    </div>
                    <div class="col-md-6">
                    <label for="edit_subject" class="form-label fw-bold">Subject</label>
                    <input type="text" class="form-control" name="subject" id="edit_subject" required>
                    </div>
                </div>

                <!-- Row 2: Award Type & Date of Issuance -->
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="edit_award_type" class="form-label fw-bold">Award Type</label>
                    <select name="award_type" id="edit_award_type" class="form-select" required>
                        <option value="character">Character Building</option>
                        <option value="praise">PRAISE</option>
                    </select>
                    </div>
                    <div class="col-md-6">
                    <label for="edit_date" class="form-label fw-bold">Date of Issuance</label>
                    <input type="date" class="form-control" name="date_of_issuance" id="edit_date" required>
                    </div>
                </div>

                <!-- Row 3: Replace PDF File -->
                <div class="mb-3">
                    <label for="edit_file" class="form-label fw-bold">Replace PDF File</label>
                    <input type="file" class="form-control text-muted" name="file" id="edit_file" accept=".pdf">
                </div>


                <!-- Row 4: Notes -->
                <div class="mb-3">
                    <label for="edit_notes" class="form-label fw-bold">Notes</label>
                    <textarea class="form-control" name="notes" id="edit_notes" rows="6" style="font-size:1.1rem"></textarea>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Update Memorandum</button>
                </div>
            </form>
            </div>
        </div>


                <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation</h5>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this Memorandum?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm</button>
                        </div>
                        </form>
                    </div>
                    </div>

    <!-- Memorandum List Section -->
    <div class="bg-white rounded shadow overflow-hidden">
        <div class="table-responsive">
            {!! $memorandumTable !!}
        </div>
    </div>
</div>


@endsection


@push('scripts')

<!-- jQuery (required for Toastr, skip if already included in layout) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- ✅ Toastr Notifications -->
@include('content.welfare.toastrwelfare.toastrwel')


<!-- Main Scripts -->
<script>


// ✅ All jQuery-related logic
$(function () {
    // Show Add Memorandum modal
    $('#addMemorandumBtn').on('click', function () {
        $('#addMemorandumModal').modal('show');
    });

    // Reset form when modal is hidden
    $('#addMemorandumModal').on('hidden.bs.modal', function () {
        $('#memorandumForm')[0].reset();
    });

    const updateUrlTemplate = "{{ route('memorandum.update', ['id' => '__ID__']) }}";
    const deleteUrlTemplate = "{{ route('memorandums.destroy', ['id' => '__ID__']) }}";

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const form = $('#editMemorandumForm');

        form.attr('action', updateUrlTemplate.replace('__ID__', id));
        $('#edit_id').val(id);
        $('#edit_issuance_number').val($(this).data('issuance_number'));
        $('#edit_subject').val($(this).data('subject'));
        $('#edit_award_type').val($(this).data('award_type'));
        $('#edit_date').val($(this).data('date'));
        $('#edit_notes').val($(this).data('notes'));

        $('#editMemorandumModal').modal('show');
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        $('#deleteForm').attr('action', deleteUrlTemplate.replace('__ID__', id));
        $('#deleteConfirmationModal').modal('show');
    });

    // Read More functionality
    $(document).on('click', '.read-more-link', function (e) {
        e.preventDefault();
        const fullNotes = $(this).data('notes');
        $('#fullNotesContent').text('· ' + fullNotes);
    });

    
    // ✅ If search input is cleared, reset award_type and submit #filterForm
        $('#searchInput').on('input', function () {
            if ($(this).val().trim() === '') {
                $('#awardTypeFilter').val('all');
                $('#filterForm').submit();
            }
        });


        $(document).on('click', '.view-file-btn', function (e) {
        e.preventDefault();

        const fileUrl = $(this).data('url');
        const overlay = $('#fileLoadingOverlay');

        // Show the full-screen loading spinner
        overlay.removeClass('d-none');

        // Open PDF after short delay
        setTimeout(() => {
            window.open(fileUrl, '_blank'); // Open the file
            overlay.addClass('d-none');     // Hide spinner
        }, 700); // Delay in milliseconds
    });


    // ✅ Show Global Spinner on Filter Submit
    $('#filterForm').on('submit', function () {
        $('#globalLoadingOverlay').removeClass('d-none');
        $('#globalLoadingMessage').html('<div>Filtering...</div><div>Please wait.</div>');
    });

});
</script>


@endpush

