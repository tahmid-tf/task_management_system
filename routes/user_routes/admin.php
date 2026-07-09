<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->group(function () {
    // --------------------------- add or view user route ---------------------------
    Route::get('/add-user', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.add-user');
});
