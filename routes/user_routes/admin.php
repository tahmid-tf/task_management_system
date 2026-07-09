<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->group(function () {

    // --------------------------- view users ---------------------------
    Route::get('/view-users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.view-users');
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    // --------------------------- add or view user route ---------------------------
    Route::get('/add-user', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.add-user');
    Route::post('/add-user', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.add-user.store');

});
