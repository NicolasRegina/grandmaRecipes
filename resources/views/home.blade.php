@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 text-center">
            <div class="mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Recetario de la Abuela" class="mb-3" style="max-width:120px;">
                <h1 class="display-4 fw-bold mb-3">Recetario de la Abuela</h1>
                <p class="lead">Un espacio para digitalizar, preservar y compartir las recetas que forman parte de tu historia familiar y de tus amistades. Aquí puedes crear tu propio recetario, invitar a tus seres queridos y formar comunidades culinarias en grupos temáticos.</p>
                <p class="mb-0">La idea nació al descubrir un cuaderno de recetas escrito por mi abuela, y querer compartir esos sabores y recuerdos con mi familia. Hoy, puedes hacer lo mismo con tus recetas favoritas, ya sean heredadas, inventadas o encontradas.</p>
            </div>
            <div class="card shadow-lg mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">¿Por qué usar Recetario de la Abuela?</h2>
                    <ul class="list-group list-group-flush text-start mb-3">
                        <li class="list-group-item"><i class="fas fa-users text-success me-2"></i> Crea o únete a grupos para compartir recetas con familia, amigos o comunidades.</li>
                        <li class="list-group-item"><i class="fas fa-utensils text-success me-2"></i> Sube tus propias recetas, agrégales fotos, pasos y tips personales.</li>
                        <li class="list-group-item"><i class="fas fa-search text-success me-2"></i> Descubre recetas de otros usuarios y guarda tus favoritas.</li>
                        <li class="list-group-item"><i class="fas fa-crown text-warning me-2"></i> ¡Hazte Premium y desbloquea todo el potencial de la plataforma!</li>
                    </ul>
                    <h3 class="h5 mt-4 mb-2">Comparativa de cuentas</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th><span class="fs-4">🥉</span> Free</th>
                                    <th><span class="fs-4">👑</span> Premium</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Grupos a los que puedes unirte</td>
                                    <td>Hasta 5</td>
                                    <td>Sin límite</td>
                                </tr>
                                <tr>
                                    <td>Recetas totales que puedes subir</td>
                                    <td>Hasta 30</td>
                                    <td>Sin límite</td>
                                </tr>
                                <tr>
                                    <td>Recetas por día</td>
                                    <td>5</td>
                                    <td>Sin límite</td>
                                </tr>
                                <tr>
                                    <td>Insignia especial en tu perfil</td>
                                    <td><span class="fs-5">🥉</span> Bronce</td>
                                    <td><span class="fs-5">👑</span> Premium</td>
                                </tr>
                                <tr>
                                    <td>Soporte prioritario y sugerencias</td>
                                    <td><i class="fas fa-times text-danger"></i></td>
                                    <td><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Acceso anticipado a nuevas funciones</td>
                                    <td><i class="fas fa-times text-danger"></i></td>
                                    <td><i class="fas fa-check text-success"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('premium.info') }}" class="btn btn-warning btn-lg mt-3" style="font-weight:600;">
                        <i class="fas fa-crown me-1"></i> Más sobre Premium
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
