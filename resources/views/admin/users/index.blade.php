@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Listado de Usuarios</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Crear Usuario
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Servicio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->is_admin ? 'Administrador' : 'Usuario' }}</td>
                            <td>{{ $user->is_premium ? 'Premium' : 'Free' }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-eye"></i> Ver detalle
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
