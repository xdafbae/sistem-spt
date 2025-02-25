@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tetapkan Role ke User</h1>
    <form action="{{ route('roles.assign-to-user') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Pilih User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="role_id">Pilih Role</label>
            <select name="role_id" id="role_id" class="form-control" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tetapkan Role</button>
    </form>
</div>
@endsection