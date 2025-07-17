<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoController extends Controller
{
    public function showBuyForm()
    {
        $user = Auth::user();
        $item = [
            [
                'title' => 'Membresía Premium Recetario de la Abuela',
                'unit_price' => 20000,
                'quantity' => 1
            ]
        ];

        MercadoPagoConfig::setAccessToken("APP_USR-8882687493628409-071019-d239541fcb667c39bc4374f37b70a177-2508934062");

        $preferenceFactory = new PreferenceClient();
        try {
            $preference = $preferenceFactory->create([
                'items' => $item,
                // 'back_urls' => [
                //     'success' => config('app.url') . '/mp/exito',
                //     'failure' => config('app.url') . '/mp/fallo',
                //     'pending' => config('app.url') . '/mp/pendiente'
                // ],
                // "auto_return" => 'approved',
                'external_reference' => $user->id
            ]);
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            dd($e->getApiResponse()->getContent());
        }

        return view('mercadopago.buy-form', compact('user', 'preference'));
    }

    public function success(Request $request)
    {
        $userId = $request->input('external_reference');
        $user = \App\Models\User::find($userId);
        if ($user) {
            $user->is_premium = true;
            $user->save();
        }
        return view('mercadopago.success');
    }

    public function pending(Request $request)
    {
        return view('mercadopago.pending');
    }

    public function failure(Request $request)
    {
        return view('mercadopago.failure');
    }
}
