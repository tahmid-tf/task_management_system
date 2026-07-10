<?php

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-smtp', function () {
    Mail::raw('SMTP test mail from ' . config('app.name') . ' at ' . now(), function ($message) {
        $message->to('tahmid.tf1@gmail.com')
            ->subject('SMTP Test from ' . config('app.name'));
    });

    return 'Test mail sent to tahmid.tf1@gmail.com';
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/user_routes/admin.php';
