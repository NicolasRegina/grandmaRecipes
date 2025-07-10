@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Grupo</h1>
    <form action="{{ route('groups.update', $group) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Grupo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $group->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Categoría</label>
            <select class="form-select" id="category" name="category" required>
                <option value="">Selecciona una categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @if(old('category', $group->category) == $cat) selected @endif>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="invite_code" class="form-label">Código de Invitación</label>
            <input type="text" class="form-control" id="invite_code" name="invite_code" value="{{ old('invite_code', $group->invite_code) }}">
        </div>
        <div class="mb-3">
            <label for="is_private" class="form-label">Privacidad</label>
            <select class="form-select" id="is_private" name="is_private">
                <option value="0" @if(!$group->is_private) selected @endif>Público</option>
                <option value="1" @if($group->is_private) selected @endif>Privado</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Imagen del Grupo</label>
            @if($group->image)
                <div class="mb-2">
                    <img src="{{ Storage::url($group->image) }}" alt="Imagen actual" style="max-width: 150px;">
                </div>
            @endif
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>

    <hr>
    <h3 class="mt-4">Miembros del Grupo</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group->memberships as $membership)
                <tr>
                    <td>{{ $membership->user->name }} @if($membership->user_id == $group->creator_id)<span class="badge bg-primary ms-2">Creador</span>@endif</td>
                    <td>
                        @if($membership->user_id == $group->creator_id)
                            <span class="badge bg-primary">Admin</span>
                        @else
                            @php
                                $currentUser = Auth::user();
                                $isWebMaster = $currentUser->is_admin;
                                $isGroupAdmin = $group->memberships->where('user_id', $currentUser->id)->where('role', 'admin')->count() > 0;
                            @endphp
                            @if($isWebMaster || $isGroupAdmin)
                                <form action="{{ route('memberships.update', $membership) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                        <option value="member" @if($membership->role == 'member') selected @endif>Miembro</option>
                                        <option value="admin" @if($membership->role == 'admin') selected @endif>Admin</option>
                                    </select>
                                </form>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($membership->role) }}</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($membership->user_id != $group->creator_id)
                            <form action="{{ route('memberships.destroy', $membership) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario del grupo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
