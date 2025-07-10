@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Grupo</h1>
    <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Grupo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Categoría</label>
            <select class="form-select" id="category" name="category" required>
                <option value="">Selecciona una categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @if(old('category') == $cat) selected @endif>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="invite_code" class="form-label">Código de Invitación (opcional)</label>
            <input type="text" class="form-control" id="invite_code" name="invite_code" value="{{ old('invite_code') }}">
        </div>
        <div class="mb-3">
            <label for="is_private" class="form-label">Privacidad</label>
            <select class="form-select" id="is_private" name="is_private">
                <option value="0" @if(old('is_private')==='0') selected @endif>Público</option>
                <option value="1" @if(old('is_private')==='1') selected @endif>Privado</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Imagen del Grupo</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Crear Grupo</button>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
