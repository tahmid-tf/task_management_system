<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/user_routes/admin.php';

Route::get('/test', function () {
    return view('admin.user.add-user');
});
