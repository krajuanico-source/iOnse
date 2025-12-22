@extends('layouts/contentNavbarLayout')
@section('title', 'Assigned Permissions')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card p-3">
    <div class="mb-3">
        <select id="selectUser" class="form-control w-50">
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-responsive" id="permissionsContainer" style="display:none;">
        <table class="table table-bordered">
            <thead>
    <tr>
        <th>Module</th>
        @foreach(['view', 'create', 'edit'] as $action)
            <th>{{ ucfirst($action) }}</th>
        @endforeach
    </tr>
                </thead>

                <tbody>
                    @foreach($modules as $module)
                    <tr data-module-id="{{ $module->id }}">
                        <td>{{ $module->name }}</td>
                        @foreach(['view', 'create', 'edit'] as $action)
                            @php
                                $permissionName = $module->slug . '.' . $action;
                            @endphp
                            <td class="text-center">
                                <input type="checkbox" class="permission-checkbox"
                                    data-permission-name="{{ $permissionName }}">
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
</div>
<!-- Permission Update Confirmation Modal -->
<div class="modal fade" id="permissionConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Permission Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="permissionConfirmText">
        <!-- This will be filled dynamically -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmPermissionBtn" class="btn btn-success">Yes, Update</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function() {
    let userId;

    $('#selectUser').change(function() {
        userId = $(this).val();
        if(!userId) return $('#permissionsContainer').hide();

        $('#permissionsContainer').show();
        $('.permission-checkbox').prop('checked', false);

        // Fetch user permissions
        $.get(`/planning/user-permission/${userId}`, function(userPermissions){
            $('.permission-checkbox').each(function(){
                const permName = $(this).data('permission-name');
                if(userPermissions.includes(permName)) {
                    $(this).prop('checked', true);
                }
            });
        });
    });

    // Single change handler with confirmation
    $(document).on('change', '.permission-checkbox', function() {
        const permName = $(this).data('permission-name');
        const isChecked = $(this).is(':checked');

        if(!userId) {
            alert('Please select a user first.');
            $(this).prop('checked', !isChecked); // revert
            return;
        }

        // Ask for confirmation
        const actionText = isChecked ? 'grant' : 'revoke';
        if(!confirm(`Are you sure you want to ${actionText} the permission "${permName}"?`)) {
            $(this).prop('checked', !isChecked); // revert
            return;
        }

        // Send update request
        $.ajax({
            url: "{{ route('user-permission.update') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: userId,
                permission_name: permName,
                granted: isChecked ? 1 : 0
            },
            success: function(res) {
                console.log(res.success);
            },
            error: function(xhr) {
                console.error('Failed to update permission', xhr.responseText);
                alert('Failed to update permission');
                $(this).prop('checked', !isChecked); // revert
            }
        });
    });
});
let pendingCheckbox = null;
let pendingGranted = false;

$(document).on('change', '.permission-checkbox', function() {
    const permName = $(this).data('permission-name');
    const isChecked = $(this).is(':checked');

    if (!userId) {
        alert('Please select a user first.');
        $(this).prop('checked', !isChecked); // revert
        return;
    }

    // Store the checkbox and action
    pendingCheckbox = $(this);
    pendingGranted = isChecked;

    // Set modal text dynamically
    const actionText = isChecked ? 'grant' : 'revoke';
    $('#permissionConfirmText').text(`Are you sure you want to ${actionText} the permission "${permName}"?`);

    // Show modal
    new bootstrap.Modal(document.getElementById('permissionConfirmModal')).show();
});

// Confirm button click
$('#confirmPermissionBtn').click(function() {
    if (!pendingCheckbox) return;

    $.ajax({
        url: "{{ route('user-permission.update') }}",
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            user_id: userId,
            permission_name: pendingCheckbox.data('permission-name'),
            granted: pendingGranted ? 1 : 0
        },
        success: function(res) {
            toastr.success(res.success || 'Permission updated!');
        },
        error: function(xhr) {
            toastr.error('Failed to update permission');
            pendingCheckbox.prop('checked', !pendingGranted); // revert
        },
        complete: function() {
            pendingCheckbox = null;
            pendingGranted = false;
            bootstrap.Modal.getInstance(document.getElementById('permissionConfirmModal')).hide();
        }
    });
});

</script>
@endsection
