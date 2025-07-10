@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Detalle de Usuario</h2>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name ?? $user->email }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Rol:</strong> {{ $user->is_admin ? 'Administrador' : 'Usuario' }}</p>
            <p class="card-text"><strong>Servicio contratado:</strong> {{ $user->is_premium ? 'Premium' : 'Free' }}</p>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Grupos a los que pertenece</div>
        <div class="card-body">
            @if($user->groups && count($user->groups))
                <ul>
                    @foreach($user->groups as $group)
                        <li>{{ $group->name }}</li>
                    @endforeach
                </ul>
            @else
                <p>No pertenece a ningún grupo.</p>
            @endif
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Recetas creadas</div>
        <div class="card-body">
            @if($user->recipes && count($user->recipes))
                <ul>
                    @foreach($user->recipes as $recipe)
                        <li>{{ $recipe->title }}</li>
                    @endforeach
                </ul>
            @else
                <p>No ha creado recetas.</p>
            @endif
        </div>
    </div>
    <div class="mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Editar
        </a>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </form>
    </div>
</div>
@endsection
