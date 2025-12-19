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

<script>
$(function() {
    let userId;

    $('#selectUser').change(function() {
        userId = $(this).val();
        if(!userId) return $('#permissionsContainer').hide();

        $('#permissionsContainer').show();
        $('.permission-checkbox').prop('checked', false);

        $.get(`/planning/user-permission/${userId}`, function(userPermissions){
            $('.permission-checkbox').each(function(){
                const permName = $(this).data('permission-name');
                if(userPermissions.includes(permName)) $(this).prop('checked', true);
            });
        });
    });

    $(document).on('change', '.permission-checkbox', function() {
        const permName = $(this).data('permission-name');
        $.post("{{ route('user-permission.update') }}", {
            _token: $('meta[name="csrf-token"]').attr('content'),
            user_id: userId,
            permission_name: permName,
            granted: $(this).is(':checked')
        })
        .done(res => console.log(res.success))
        .fail(xhr => {
            console.error('Failed to update permission', xhr.responseText);
            alert('Failed to update permission');
        });
    });
});
</script>
@endsection
