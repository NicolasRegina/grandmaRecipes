@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h2 class="text-success mb-3"><i class="fas fa-check-circle"></i> ¡Pago exitoso!</h2>
    <p>Tu membresía premium ha sido activada. ¡Gracias por apoyar el Recetario de la Abuela!</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Ir al inicio</a>
</div>
@endsection
