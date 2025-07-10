@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($group->image)
                    <img src="{{ Storage::url($group->image) }}" class="card-img-top" alt="{{ $group->name }}" style="object-fit: cover; height: 300px;">
                @endif
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="mb-0 d-inline">{{ $group->name }}</h2>
                        <span class="badge {{ $group->is_private ? 'bg-dark' : 'bg-success' }} ms-2">{{ $group->is_private ? 'Privado' : 'Público' }}</span>
                    </div>
                    <div>
                        @if(Auth::user()?->is_admin || $group->creator_id === Auth::id() || ($group->memberships->where('user_id', Auth::id())->where('role', 'admin')->count() > 0))
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning btn-sm me-2">Editar</a>
                        @endif
                        @if(Auth::user()?->is_admin || $group->creator_id === Auth::id())
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este grupo? Se eliminarán todas las recetas y membresías asociadas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Descripción:</strong> {{ $group->description }}</p>
                    <p class="mb-2"><strong>Categoría:</strong> <span class="badge bg-info text-dark">{{ $group->category }}</span></p>
                    @if($group->invite_code)
                        <p class="mb-2"><strong>Código de invitación:</strong> <span class="badge bg-secondary">{{ $group->invite_code }}</span></p>
                    @endif
                    <p class="mb-2"><strong>Creador:</strong> {{ $group->creator->name }}</p>
                    <hr>
                    <h5>Miembros</h5>
                    <ul class="list-group">
                        @foreach($group->memberships as $membership)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $membership->user->name }}
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $membership->role == 'admin' ? 'bg-primary' : ($membership->role == 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ ucfirst($membership->role) }}
                                    </span>
                                    @php
                                        $isAdmin = Auth::user()?->is_admin || $group->creator_id === Auth::id() || ($group->memberships->where('user_id', Auth::id())->where('role', 'admin')->count() > 0);
                                    @endphp
                                    @if($membership->role == 'pending' && $isAdmin)
                                        <form action="{{ route('memberships.update', $membership->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="role" value="member">
                                            <button type="submit" class="btn btn-success btn-sm ms-2">Aceptar</button>
                                        </form>
                                        <form action="{{ route('memberships.destroy', $membership->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Rechazar esta solicitud?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <h5>Recetas del Grupo</h5>
                    <ul class="list-group mb-3">
                        @forelse($group->recipes as $recipe)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->title }}</a>
                                <span class="badge bg-secondary">{{ $recipe->category }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No hay recetas en este grupo.</li>
                        @endforelse
                    </ul>
                    <hr>
                    @auth
                        @if($group->is_private && $group->invite_code && !Auth::user()->groups->contains($group->id))
                            <form action="{{ route('memberships.store') }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                <input type="hidden" name="invite_code" value="{{ $group->invite_code }}">
                                <button type="submit" class="btn btn-outline-success">Solicitar Unirse</button>
                            </form>
                        @elseif(!$group->is_private && !Auth::user()->groups->contains($group->id))
                            <form action="{{ route('memberships.store') }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                <button type="submit" class="btn btn-outline-success">Unirse</button>
                            </form>
                        @endif
                    @endauth
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
