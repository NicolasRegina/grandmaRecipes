@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-4">Grupos</h1>
        @auth
            <a href="{{ route('groups.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Grupo
            </a>
        @endauth
    </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Nombre" value="{{ request('name') }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @if(request('category') == $cat) selected @endif>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="is_private" class="form-select">
                <option value="">Privacidad</option>
                <option value="0" @if(request('is_private')==='0') selected @endif>Público</option>
                <option value="1" @if(request('is_private')==='1') selected @endif>Privado</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="invite_code" class="form-control" placeholder="Código Invitación" value="{{ request('invite_code') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary w-100">Buscar</button>
            <button type="button" class="btn btn-outline-secondary w-100" onclick="
                this.form.name.value='';
                this.form.category.value='';
                this.form.is_private.value='';
                this.form.invite_code.value='';
            ">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        </div>
    </form>

    <h3 class="mt-4">Grupos Privados</h3>
    <div class="row">
        @forelse($privateGroups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    @if($group->image)
                        <img src="{{ Storage::url($group->image) }}" class="card-img-top" alt="{{ $group->name }}" style="object-fit:cover; height:180px;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="card-title mb-0 flex-grow-1">{{ $group->name }}</h5>
                            <span class="badge bg-dark ms-2">Privado</span>
                            @php
                                $pendingCount = $group->memberships->where('role', 'pending')->count();
                                $isAdmin = Auth::user()?->is_admin || $group->creator_id === Auth::id() || ($group->memberships->where('user_id', Auth::id())->where('role', 'admin')->count() > 0);
                            @endphp
                            @if($isAdmin && $pendingCount > 0)
                                <span class="badge bg-warning text-dark ms-2" title="Solicitudes pendientes">
                                    <i class="fas fa-user-clock"></i> {{ $pendingCount }}
                                </span>
                            @endif
                        </div>
                        <span class="badge bg-info text-dark mb-2">{{ $group->category }}</span>
                        <p class="card-text small text-muted">{{ $group->description }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-primary btn-sm">Ver</a>
                            @if($group->creator_id === Auth::id() || Auth::user()?->is_admin || ($group->memberships->where('user_id', Auth::id())->where('role', 'admin')->count() > 0))
                                <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este grupo? Se eliminarán todas las recetas y membresías asociadas.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            @endif
                            @if($group->is_private && $group->invite_code && !Auth::user()?->groups->contains($group->id))
                                <form action="{{ route('memberships.store') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                    <input type="hidden" name="invite_code" value="{{ $group->invite_code }}">
                                    <button type="submit" class="btn btn-outline-success btn-sm">Solicitar Unirse</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted ms-3">No perteneces a ningún grupo privado.</p>
        @endforelse
    </div>

    <h3 class="mt-5">Grupos Públicos</h3>
    <div class="row">
        @forelse($publicGroups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    @if($group->image)
                        <img src="{{ Storage::url($group->image) }}" class="card-img-top" alt="{{ $group->name }}" style="object-fit:cover; height:180px;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="card-title mb-0 flex-grow-1">{{ $group->name }}</h5>
                            <span class="badge bg-success ms-2">Público</span>
                        </div>
                        <span class="badge bg-info text-dark mb-2">{{ $group->category }}</span>
                        <p class="card-text small text-muted">{{ $group->description }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-primary btn-sm">Ver</a>
                            @if($group->creator_id === Auth::id() || Auth::user()?->is_admin || ($group->memberships->where('user_id', Auth::id())->where('role', 'admin')->count() > 0))
                                <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este grupo? Se eliminarán todas las recetas y membresías asociadas.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            @endif
                            @auth
                                @if(!Auth::user()->groups->contains($group->id))
                                    <form action="{{ route('memberships.store') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                                        <button type="submit" class="btn btn-outline-success btn-sm">Unirse</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted ms-3">No hay grupos públicos disponibles.</p>
        @endforelse
    </div>
</div>
@endsection
