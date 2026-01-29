@extends('layouts.app')

@section('content')
<h2>Users</h2>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
            <td>
                <a href="{{ route('users.editRole', $user->id) }}">Edit Role</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
