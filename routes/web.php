<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirects to unified login route
Route::redirect('/admin/login', '/login');
Route::redirect('/user/login', '/login');

// Logout routes for Filament panels
Route::post('/user/logout', [LoginController::class, 'logout'])->name('filament.user.auth.logout');

