<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MembershipController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'store'])->name('register.store');

// Página de inicio - mostrar home
Route::get('/', function() { return view('home'); })->name('home');
Route::get('/index', function() { return view('home'); });

// Rutas de recetas
// La ruta 'create' debe definirse ANTES que la ruta con el parámetro '{recipe}' para evitar conflictos.
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create')->middleware('auth');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    // Rutas para gestión de recetas (crear, editar, eliminar)
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::patch('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // Rutas para grupos
    Route::resource('groups', GroupController::class);
    // Rutas para membresías
    Route::resource('memberships', MembershipController::class);
});

// Rutas de administración (solo para admins)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// Página de información de Premium
Route::get('/premium', function () {
    return view('premium.info');
})->name('premium.info');
