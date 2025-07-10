<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(){
        return view("auth.login");
    }

    public function authenticate(Request $request){
        // Validar los datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('recipes.index'))
                             ->with('status', '¡Sesión iniciada correctamente!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('status', '¡Sesión cerrada correctamente!');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Iniciar sesión con el nuevo usuario
        Auth::login($user);

        // Redirigir
        return redirect()->route('recipes.index')
                         ->with('status', '¡Bienvenido/a! Tu cuenta ha sido creada y has iniciado sesión.');
    }
}
