@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <span class="display-1 text-warning"><i class="fas fa-crown"></i></span>
                <h1 class="display-4 fw-bold mb-3">¡Hazte Premium!</h1>
                <p class="lead">Disfruta de beneficios exclusivos, acceso anticipado a recetas, grupos privados, insignias especiales y mucho más.</p>
            </div>
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">Ventajas de ser Premium</h2>
                    <ul class="list-group list-group-flush text-start mb-3">
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Acceso a recetas y grupos exclusivos</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Insignia Premium visible en tu perfil</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Prioridad en soporte y sugerencias</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> ¡Y mucho más!</li>
                    </ul>
                    @auth
                        @if (!Auth::user()->is_premium)
                            <a href="{{ route('mp.show-buy-form') }}" class="btn btn-warning btn-lg mt-3" style="font-weight:600;">
                                <i class="fas fa-crown me-1"></i> Hazte Premium
                            </a>
                        @else
                            <button class="btn btn-success btn-lg mt-3" disabled style="font-weight:600;">
                                <i class="fas fa-crown me-1"></i> ¡Ya eres Premium!
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-warning btn-lg mt-3" style="font-weight:600;">
                            <i class="fas fa-sign-in-alt me-1"></i> Inicia sesión para ser Premium
                        </a>
                    @endauth
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection
