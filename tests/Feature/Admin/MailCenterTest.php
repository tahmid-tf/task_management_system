<?php

use App\Mail\UserMailNotification;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

function makeMailCenterAdmin(): User
{
    Role::findOrCreate('Admin', 'web');

    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'status'            => 'active',
    ]);

    $admin->assignRole('Admin');

    return $admin;
}

function createDelayedTaskFor(User $assignee, User $creator, string $title): Task
{
    $category = TaskCategory::create([
        'name'     => 'Operations',
        'color'    => '#0f766e',
        'position' => 1,
    ]);

    return Task::create([
        'task_category_id' => $category->id,
        'created_by'       => $creator->id,
        'assigned_to'      => $assignee->id,
        'assigned_at'      => now(),
        'title'            => $title,
        'description'      => 'Follow up with the overdue work item.',
        'status'           => 'todo',
        'priority'         => 'high',
        'due_date'         => now()->subDay()->toDateString(),
        'estimated_time'   => 2,
        'actual_time'      => 0,
        'position'         => 1,
    ]);
}

test('mail center can send a delayed reminder to a single active user', function () {
    Mail::fake();

    $admin = makeMailCenterAdmin();
    $recipient = User::factory()->create([
        'email_verified_at' => now(),
        'status'            => 'active',
    ]);

    createDelayedTaskFor($recipient, $admin, 'Prepare overdue report');

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.mail-center.delayed-user'), [
            'user_id' => $recipient->id,
        ]);

    $response
        ->assertRedirect(route('admin.mail-center.index', absolute: false))
        ->assertSessionHas('success');

    Mail::assertSent(UserMailNotification::class, function (UserMailNotification $mail) {
        return $mail->heading === 'Your delayed task reminder';
    });
});

test('mail center can send reminders to all active users and skip users without delayed tasks', function () {
    Mail::fake();

    $admin = makeMailCenterAdmin();
    $recipient = User::factory()->create([
        'email_verified_at' => now(),
        'status'            => 'active',
    ]);
    $skippedUser = User::factory()->create([
        'email_verified_at' => now(),
        'status'            => 'active',
    ]);

    createDelayedTaskFor($recipient, $admin, 'Prepare overdue report');

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.mail-center.delayed-all'));

    $response
        ->assertRedirect(route('admin.mail-center.index', absolute: false))
        ->assertSessionHas('success');

    Mail::assertSentCount(1);
    Mail::assertSent(UserMailNotification::class, function (UserMailNotification $mail) {
        return $mail->heading === 'Delayed task reminder';
    });
});

test('mail center reports cleanly when all active users are up to date', function () {
    Mail::fake();

    $admin = makeMailCenterAdmin();
    User::factory()->create([
        'email_verified_at' => now(),
        'status'            => 'active',
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.mail-center.delayed-all'));

    $response
        ->assertRedirect(route('admin.mail-center.index', absolute: false))
        ->assertSessionHas('success');

    Mail::assertNothingSent();
});
