@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Pengguna</h4>
    <a href="/pengguna" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form method="POST" action="/pengguna/update/{{ $user->id }}">
    @csrf

    <div class="card card-box p-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control mb-2" required>

        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control mb-2" required>

        <label class="form-label">Role</label>
        <select name="role" class="form-control mb-2" required>
            @foreach($roles as $value => $label)
                <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control mb-3" minlength="8" placeholder="Kosongkan jika tidak diganti">

        <button class="btn btn-success">Update</button>
    </div>
</form>

@endsection
