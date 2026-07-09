<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->group(function () {
    Route::view('/', 'layouts.admin')->name('admin.dashboard');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/profile.php';
