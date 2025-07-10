<!DOCTYPE html>
<html>
<head>
    <title>{{ $recipe->title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                @if($recipe->image)
                    <img src="{{ Storage::url($recipe->image) }}" class="card-img-top" alt="{{ $recipe->title }}" style="object-fit: cover; height: 400px;">
                @endif

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h2 mb-0">{{ $recipe->title }}</h1>
                        {{-- Botones de Acción --}}
                        @auth
                            @if(Auth::id() === $recipe->author_id || Auth::user()->is_admin)
                                <div class="btn-group gap-2">
                                    <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta receta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted">
                            Publicado por <a href="#">{{ $recipe->author->name }}</a> el {{ $recipe->created_at->format('d/m/Y') }}
                            @if($recipe->group)
                                @php
                                    $showGroup = false;
                                    if (!$recipe->group->is_private) {
                                        $showGroup = true;
                                    } elseif (Auth::check()) {
                                        $user = Auth::user();
                                        $isAdmin = $user->is_admin;
                                        $isWebMaster = $user->is_admin; // Si tienes otro campo para Web Master, cámbialo aquí
                                        $isMember = $recipe->group->members->contains($user->id);
                                        if ($isAdmin || $isWebMaster || $isMember) {
                                            $showGroup = true;
                                        }
                                    }
                                @endphp
                                @if($showGroup)
                                    en el grupo <a href="#">{{ $recipe->group->name }}</a>
                                @endif
                            @endif
                        </p>
                        <p>{{ $recipe->description }}</p>
                    </div>

                    {{-- Detalles de la Receta --}}
                    <div class="d-flex justify-content-around align-items-center p-3 mb-4 bg-light rounded">
                        <div><i class="fas fa-tag me-1"></i> <strong>Categoría:</strong> <span class="badge bg-secondary">{{ $recipe->category }}</span></div>
                        <div><i class="fas fa-chart-bar me-1"></i> <strong>Dificultad:</strong> <span class="badge bg-info">{{ $recipe->difficulty }}</span></div>
                        <div><i class="fas fa-clock me-1"></i> <strong>Prep:</strong> {{ $recipe->prep_time }} min</div>
                        <div><i class="fas fa-fire me-1"></i> <strong>Cocción:</strong> {{ $recipe->cook_time }} min</div>
                        <div><i class="fas fa-users me-1"></i> <strong>Porciones:</strong> {{ $recipe->servings }}</div>
                    </div>

                    <div class="row">
                        {{-- Ingredientes --}}
                        <div class="col-md-5">
                            <h3 class="h4">Ingredientes</h3>
                            <ul class="list-group">
                                @foreach (is_array($recipe->ingredients) ? $recipe->ingredients : [] as $ingredient)
                                    <li class="list-group-item">
                                        {{ $ingredient['quantity'] }} {{ $ingredient['unit'] }} - <strong>{{ $ingredient['name'] }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Pasos --}}
                        <div class="col-md-7">
                            <h3 class="h4">Pasos de Preparación</h3>
                            <ol class="list-group list-group-numbered">
                                @foreach (is_array($recipe->steps) ? $recipe->steps : [] as $step)
                                    <li class="list-group-item">{{ $step['description'] }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
</html>
