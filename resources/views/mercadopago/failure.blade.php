@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h2 class="text-danger mb-3"><i class="fas fa-times-circle"></i> Pago fallido</h2>
    <p>Hubo un problema con tu pago. Por favor, intenta nuevamente o contacta soporte.</p>
    <a href="{{ route('mp.show-buy-form') }}" class="btn btn-warning mt-3">Intentar de nuevo</a>
</div>
@endsection
