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
    const mp = new MercadoPago("APP_USR-8e40272b-b5b5-48bc-80d1-4c0755f88c2c");
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
