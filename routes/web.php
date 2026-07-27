<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrownesiaController;
use App\Http\Controllers\AuthController;

// Public Landing Page (with Navbar)
Route::get('/', [GrownesiaController::class, 'landing'])->name('landing');

// Dedicated Authentication Pages (Split 2-Column Screen Layout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Logged-in User Dashboard (with Sidebar)
Route::get('/dashboard', [GrownesiaController::class, 'dashboard'])->middleware('auth')->name('user.dashboard');
