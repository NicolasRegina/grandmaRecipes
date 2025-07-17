@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Comprar Membresía Premium</h2>
    <p>Usuario: {{ $user->name }} ({{ $user->email }})</p>
    <p>Precio: <strong>$20000</strong></p>
    <div id="checkout"></div>
</div>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago("APP_USR-a4a7e26b-cfd1-47d5-a7a8-ad13213d8efa");
    mp.bricks().create(
        "wallet",
        "checkout",
        {
            initialization: {
                preferenceId: '{{ $preference->id }}'
            }
        }
    );
</script>
@endsection
