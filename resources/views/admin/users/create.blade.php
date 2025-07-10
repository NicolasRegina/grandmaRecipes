@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Crear Usuario</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}">
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <input type="text" name="bio" id="bio" class="form-control" value="{{ old('bio') }}">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_admin" id="is_admin" class="form-check-input" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                    <label for="is_admin" class="form-check-label">Administrador</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_premium" id="is_premium" class="form-check-input" value="1" {{ old('is_premium') ? 'checked' : '' }}>
                    <label for="is_premium" class="form-check-label">Premium</label>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Crear Usuario
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
