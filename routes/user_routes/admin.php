<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->group(function () {
    Route::view('/esfsef', 'layouts.admin')->name('admin.dashboard');
});
