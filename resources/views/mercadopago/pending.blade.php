@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h2 class="text-warning mb-3"><i class="fas fa-hourglass-half"></i> Pago pendiente</h2>
    <p>Tu pago está siendo procesado. Recibirás una notificación cuando se confirme.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Ir al inicio</a>
</div>
@endsection
