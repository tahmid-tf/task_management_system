<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/mail-center', [\App\Http\Controllers\Admin\MailCenterController::class, 'index'])->name('admin.mail-center.index');
    Route::post('/mail-center/delayed-all', [\App\Http\Controllers\Admin\MailCenterController::class, 'sendDelayedToAll'])->name('admin.mail-center.delayed-all');
    Route::post('/mail-center/delayed-user', [\App\Http\Controllers\Admin\MailCenterController::class, 'sendDelayedToUser'])->name('admin.mail-center.delayed-user');
    Route::post('/mail-center/custom', [\App\Http\Controllers\Admin\MailCenterController::class, 'sendCustomMail'])->name('admin.mail-center.custom');
    Route::get('/mail-system', [\App\Http\Controllers\Admin\MailSystemController::class, 'index'])->name('admin.mail-system.index');
    Route::patch('/mail-system', [\App\Http\Controllers\Admin\MailSystemController::class, 'update'])->name('admin.mail-system.update');

    // --------------------------- view users ---------------------------
    Route::get('/view-users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.view-users');
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    // --------------------------- add or view user route ---------------------------
    Route::get('/add-user', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.add-user');
    Route::post('/add-user', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.add-user.store');

});

Route::middleware(['auth', 'verified', 'role:Admin|Team Member|Viewer'])->prefix('admin')->group(function () {
    Route::get('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'index'])->name('admin.tasks.board');
    Route::get('/tasks/table', [\App\Http\Controllers\Admin\TaskController::class, 'table'])->name('admin.tasks.table');
    Route::get('/tasks/archived', [\App\Http\Controllers\Admin\TaskController::class, 'archived'])->name('admin.tasks.archived');
    Route::get('/tasks/export', [\App\Http\Controllers\Admin\TaskExportController::class, 'index'])->name('admin.tasks.export');
    Route::get('/tasks/export/download', [\App\Http\Controllers\Admin\TaskExportController::class, 'download'])->name('admin.tasks.export.download');
    Route::get('/tasks/{task}/details', [\App\Http\Controllers\Admin\TaskController::class, 'details'])->name('admin.tasks.details');

    Route::middleware(['role:Admin|Team Member'])->group(function () {
        Route::get('/tasks/create', [\App\Http\Controllers\Admin\TaskController::class, 'create'])->name('admin.tasks.create');
        Route::post('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'store'])->name('admin.tasks.store');
        Route::get('/tasks/{task}/edit', [\App\Http\Controllers\Admin\TaskController::class, 'edit'])->name('admin.tasks.edit');
        Route::put('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'update'])->name('admin.tasks.update');
        Route::patch('/tasks/{task}/move', [\App\Http\Controllers\Admin\TaskController::class, 'move'])->name('admin.tasks.move');
        Route::patch('/tasks/reorder', [\App\Http\Controllers\Admin\TaskController::class, 'reorder'])->name('admin.tasks.reorder');
        Route::patch('/tasks/{task}/archive', [\App\Http\Controllers\Admin\TaskController::class, 'archive'])->name('admin.tasks.archive');
        Route::patch('/tasks/{task}/unarchive', [\App\Http\Controllers\Admin\TaskController::class, 'unarchive'])->name('admin.tasks.unarchive');
        Route::post('/tasks/{task}/duplicate', [\App\Http\Controllers\Admin\TaskController::class, 'duplicate'])->name('admin.tasks.duplicate');
        Route::post('/tasks/{task}/comments', [\App\Http\Controllers\Admin\TaskController::class, 'comment'])->name('admin.tasks.comments.store');
        Route::post('/tasks/{task}/attachments', [\App\Http\Controllers\Admin\TaskController::class, 'attachment'])->name('admin.tasks.attachments.store');

        Route::delete('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'destroy'])
            ->middleware('role:Admin')
            ->name('admin.tasks.destroy');

        Route::get('/task-categories', [\App\Http\Controllers\Admin\TaskCategoryController::class, 'index'])->name('admin.task-categories.index');
        Route::post('/task-categories', [\App\Http\Controllers\Admin\TaskCategoryController::class, 'store'])->name('admin.task-categories.store');
        Route::put('/task-categories/{taskCategory}', [\App\Http\Controllers\Admin\TaskCategoryController::class, 'update'])->name('admin.task-categories.update');
        Route::delete('/task-categories/{taskCategory}', [\App\Http\Controllers\Admin\TaskCategoryController::class, 'destroy'])->name('admin.task-categories.destroy');
        Route::patch('/task-categories/reorder', [\App\Http\Controllers\Admin\TaskCategoryController::class, 'reorder'])->name('admin.task-categories.reorder');
    });
});
