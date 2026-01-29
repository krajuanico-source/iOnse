@extends('layouts.app')

@section('content')
<h2>Assign Role to {{ $user->name }}</h2>

<form action="{{ route('users.assignRole', $user->id) }}" method="POST">
    @csrf

    <label for="role">Select Role:</label>
    <select name="role" id="role" required>
        <option value="">-- Choose Role --</option>
        @foreach($roles as $role)
            <option value="{{ $role->name }}" 
                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    <button type="submit">Assign Role</button>
</form>
@endsection
