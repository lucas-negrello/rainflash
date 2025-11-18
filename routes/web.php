<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirecionar tentativas de entrar diretamente nos logins do Filament para o login unificado
Route::redirect('/admin/login', '/login');
