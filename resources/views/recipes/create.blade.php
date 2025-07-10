@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h2">Crear Nueva Receta</h1>
                    <p class="mb-0">¡Comparte tus mejores recetas con la comunidad!</p>
                </div>

                <div class="card-body">
                    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Título --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">Título de la Receta</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ingredientes --}}
                        <div class="mb-3">
                            <label class="form-label">Ingredientes</label>
                            <div id="ingredients-wrapper">
                                {{-- Entradas de ingredientes se agregarán aquí --}}
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-ingredient">Añadir Ingrediente</button>
                            @error('ingredients')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pasos --}}
                        <div class="mb-3">
                            <label class="form-label">Pasos de Preparación</label>
                            <div id="steps-wrapper">
                                {{-- Entradas de pasos se agregarán aquí --}}
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-step">Añadir Paso</button>
                             @error('steps')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="row">
                            {{-- Tiempo de preparación --}}
                            <div class="col-md-4 mb-3">
                                <label for="prep_time" class="form-label">Tiempo de Preparación (min)</label>
                                <input type="number" class="form-control @error('prep_time') is-invalid @enderror" id="prep_time" name="prep_time" value="{{ old('prep_time') }}" required>
                                @error('prep_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tiempo de cocción --}}
                            <div class="col-md-4 mb-3">
                                <label for="cook_time" class="form-label">Tiempo de Cocción (min)</label>
                                <input type="number" class="form-control @error('cook_time') is-invalid @enderror" id="cook_time" name="cook_time" value="{{ old('cook_time') }}" required>
                                @error('cook_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Porciones --}}
                            <div class="col-md-4 mb-3">
                                <label for="servings" class="form-label">Porciones</label>
                                <input type="number" class="form-control @error('servings') is-invalid @enderror" id="servings" name="servings" value="{{ old('servings') }}" required>
                                @error('servings')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Dificultad --}}
                            <div class="col-md-6 mb-3">
                                <label for="difficulty" class="form-label">Dificultad</label>
                                <select class="form-select @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                                    <option value="Fácil" {{ old('difficulty') == 'Fácil' ? 'selected' : '' }}>Fácil</option>
                                    <option value="Media" {{ old('difficulty') == 'Media' ? 'selected' : '' }}>Media</option>
                                    <option value="Difícil" {{ old('difficulty') == 'Difícil' ? 'selected' : '' }}>Difícil</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Categoría --}}
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Categoría</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" @if(old('category') == $cat) selected @endif>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Imagen --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen de la Receta</label>
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                             @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Grupo --}}
                        <div class="mb-3">
                            <label for="group_id" class="form-label">Asignar a un Grupo (Opcional)</label>
                            <select class="form-select @error('group_id') is-invalid @enderror" id="group_id" name="group_id">
                                <option value="">Sin grupo</option>
                                @foreach($allGroups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} {{ $group->is_private ? '(Privado)' : '(Público)' }}
                                    </option>
                                @endforeach
                            </select>
                             @error('group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Receta Privada --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_private" name="is_private" {{ old('is_private') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_private">
                                Hacer esta receta privada
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear Receta</button>
                        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ingredientes
    const ingredientsWrapper = document.getElementById('ingredients-wrapper');
    const addIngredientBtn = document.getElementById('add-ingredient');
    let ingredientIndex = 0;

    addIngredientBtn.addEventListener('click', () => {
        const ingredientHtml = `
            <div class="input-group mb-2 ingredient-item">
                <input type="text" name="ingredients[${ingredientIndex}][name]" class="form-control" placeholder="Nombre del ingrediente" required>
                <input type="text" name="ingredients[${ingredientIndex}][quantity]" class="form-control" placeholder="Cantidad" required>
                <input type="text" name="ingredients[${ingredientIndex}][unit]" class="form-control" placeholder="Unidad (ej. gr, ml)">
                <button type="button" class="btn btn-danger remove-ingredient">X</button>
            </div>`;
        ingredientsWrapper.insertAdjacentHTML('beforeend', ingredientHtml);
        ingredientIndex++;
    });

    ingredientsWrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-ingredient')) {
            e.target.closest('.ingredient-item').remove();
        }
    });

    // Pasos
    const stepsWrapper = document.getElementById('steps-wrapper');
    const addStepBtn = document.getElementById('add-step');
    let stepIndex = 0;

    addStepBtn.addEventListener('click', () => {
        const stepHtml = `
            <div class="input-group mb-2 step-item">
                <span class="input-group-text">Paso ${stepIndex + 1}</span>
                <textarea name="steps[${stepIndex}][description]" class="form-control" rows="2" required></textarea>
                <button type="button" class="btn btn-danger remove-step">X</button>
            </div>`;
        stepsWrapper.insertAdjacentHTML('beforeend', stepHtml);
        stepIndex++;
        updateStepNumbers();
    });

    stepsWrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-step')) {
            e.target.closest('.step-item').remove();
            updateStepNumbers();
        }
    });

    function updateStepNumbers() {
        const stepItems = stepsWrapper.querySelectorAll('.step-item');
        stepItems.forEach((item, index) => {
            item.querySelector('.input-group-text').textContent = `Paso ${index + 1}`;
        });
    }

    // Añadir un ingrediente y un paso por defecto
    addIngredientBtn.click();
    addStepBtn.click();
});
</script>
@endpush
@endsection
