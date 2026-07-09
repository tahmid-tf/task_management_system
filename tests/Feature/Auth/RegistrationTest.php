<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->hasRole('Viewer'))->toBeTrue();
});

test('admin dashboard requires the admin role', function () {
    Role::findOrCreate('Admin', 'web');

    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertForbidden();

    $user->assignRole('Admin');

    $this->actingAs($user)->get('/admin')->assertOk();
});
