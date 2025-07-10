@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 pb-2">
                <h1 class="text-success mb-0" style="font-weight:700; letter-spacing:-1px;">Recetas del Cuaderno de la Abuela</h1>
            </div>
            <div class="row g-2 align-items-center mb-3" style="margin-top:1.2rem;">
                <form method="GET" class="col d-flex flex-wrap gap-2 align-items-center">
                    <input type="text" name="title" class="form-control w-auto" style="min-width:180px;" placeholder="Nombre o descripción" value="{{ request('title') }}">
                    <select name="category" class="form-select w-auto">
                        <option value="">Categoría</option>
                        @foreach(\App\Helpers\RecipeCategories::all() as $cat)
                            <option value="{{ $cat }}" @if(request('category') == $cat) selected @endif>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select name="difficulty" class="form-select w-auto">
                        <option value="">Dificultad</option>
                        <option value="Fácil" @if(request('difficulty')=='Fácil') selected @endif>Fácil</option>
                        <option value="Media" @if(request('difficulty')=='Media') selected @endif>Media</option>
                        <option value="Difícil" @if(request('difficulty')=='Difícil') selected @endif>Difícil</option>
                    </select>
                    <select name="is_private" class="form-select w-auto">
                        <option value="">Privacidad</option>
                        <option value="0" @if(request('is_private')==='0') selected @endif>Pública</option>
                        <option value="1" @if(request('is_private')==='1') selected @endif>Privada</option>
                    </select>
                    <input type="text" name="author" class="form-control w-auto" style="min-width:120px;" placeholder="Autor" value="{{ request('author') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </form>
                @auth
                    <div class="col-auto">
                        <a href="{{ route('recipes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nueva Receta
                        </a>
                    </div>
                @endauth
            </div>

            @if($recipes->count() > 0)
                <div class="row">
                    @foreach ($recipes as $recipe)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @if($recipe->image)
                                    <img src="{{ Storage::url($recipe->image) }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-utensils fa-3x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column position-relative">
                                    <h5 class="card-title">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="text-dark text-decoration-none">{{ $recipe->title }}</a>
                                    </h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($recipe->description, 100) }}
                                    </p>

                                    <div class="recipe-meta mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $recipe->prep_time + $recipe->cook_time }} min
                                            <i class="fas fa-users ms-2"></i> {{ $recipe->servings }} porciones
                                            <i class="fas fa-signal ms-2"></i> {{ $recipe->difficulty }}
                                        </small>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Por: {{ $recipe->author->name }}
                                            @if($recipe->author->is_premium)
                                                <span class="badge bg-warning text-dark">Premium</span>
                                            @endif
                                        </small>
                                        @if($recipe->rating)
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($recipe->rating))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i <= $recipe->rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <small class="text-muted">({{ $recipe->rating_count }})</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-outline-success btn-sm flex-fill">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        @auth
                                            @if(Auth::id() === $recipe->author_id || Auth::user()->is_admin)
                                                <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta receta?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm flex-fill">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $recipes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">No hay recetas disponibles</h3>
                    <p class="text-muted">¡Sé el primero en compartir una receta de la abuela!</p>
                    @auth
                        <a href="{{ route('recipes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Crear Primera Receta
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Únete y Comparte
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
