<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::middleware(['estudio'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/dashboard', function () {
    return 'Bienvenido, ' . session('usuario.nombre');
});

Route::get('/', function () {
    return 'Inicio sin middleware';
});
