@extends('layouts.app')

@section('content')
<main class="main-content position-relative border-radius-lg ">

<div class="container">
    <h1>Tambah Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama Role</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
</main>
@endsection