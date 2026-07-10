<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page.welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'active_account'])
    ->name('dashboard');

require __DIR__ . '/auth.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/user_routes/admin.php';
