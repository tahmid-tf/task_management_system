<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserMailNotification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class MailCenterController extends Controller
{
    public function index(): View
    {
        $activeUsers = User::query()
            ->where('status', 'active')
            ->withCount([
                'assignedTasks as delayed_tasks_count' => fn ($query) => $this->delayScope($query),
            ])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'image', 'status']);

        $delayedTaskCount = Task::query()
            ->whereNull('archived_at')
            ->where('status', '!=', 'done')
            ->whereDate('due_date', '<', today())
            ->count();

        return view('admin.mail-center.index', [
            'activeUsers'       => $activeUsers,
            'activeUserCount'   => $activeUsers->count(),
            'usersWithDelays'   => $activeUsers->where('delayed_tasks_count', '>', 0)->count(),
            'delayedTaskCount'   => $delayedTaskCount,
        ]);
    }

    public function sendDelayedToAll(Request $request): RedirectResponse
    {
        $users = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $sent = 0;
        $skipped = 0;
        $failed = [];

        foreach ($users as $user) {
            $tasks = $this->delayedTasksForUser($user);

            if ($tasks->isEmpty()) {
                $skipped++;
                continue;
            }

            try {
                Mail::to($user->email)->send(new UserMailNotification(
                    subjectLine: 'Delayed task reminder - '.config('app.name'),
                    heading: 'Delayed task reminder',
                    intro: "Hello {$user->name}, this is an automated reminder about tasks that are past due and still pending.",
                    tasks: $this->formatTasks($tasks),
                    customMessage: 'Please review the tasks below and update the progress as soon as possible.',
                    closingLine: 'Thank you for staying on top of your work.',
                ));

                $sent++;
            } catch (Throwable $e) {
                Log::warning('Failed to send delayed task mail.', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'error'   => $e->getMessage(),
                ]);

                $failed[] = $user->name;
            }
        }

        $message = "Delayed task mails sent to {$sent} active user".($sent === 1 ? '' : 's').'.';

        if ($skipped > 0) {
            $message .= " {$skipped} active user".($skipped === 1 ? '' : 's').' had no delayed tasks and were skipped.';
        }

        if ($failed !== []) {
            $message .= ' Some users could not be notified: '.implode(', ', $failed).'.';
        }

        return redirect()
            ->route('admin.mail-center.index')
            ->with($failed ? 'error' : 'success', $message);
    }

    public function sendDelayedToUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = User::query()
            ->where('id', $validated['user_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $tasks = $this->delayedTasksForUser($user);

        if ($tasks->isEmpty()) {
            return back()->with('error', "{$user->name} currently has no delayed tasks.");
        }

        Mail::to($user->email)->send(new UserMailNotification(
            subjectLine: 'Delayed task reminder - '.config('app.name'),
            heading: 'Your delayed task reminder',
            intro: "Hello {$user->name}, the following tasks are currently overdue or delayed.",
            tasks: $this->formatTasks($tasks),
            customMessage: 'Please review the list below and take the next action needed.',
            closingLine: 'Thank you.',
        ));

        return redirect()
            ->route('admin.mail-center.index')
            ->with('success', "Delayed task reminder sent to {$user->name}.");
    }

    public function sendCustomMail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $user = User::query()
            ->where('id', $validated['user_id'])
            ->where('status', 'active')
            ->firstOrFail();

        Mail::to($user->email)->send(new UserMailNotification(
            subjectLine: $validated['subject'],
            heading: 'A message from the admin team',
            intro: "Hello {$user->name},",
            tasks: [],
            customMessage: $validated['message'],
            closingLine: 'Best regards, '.config('app.name'),
        ));

        return redirect()
            ->route('admin.mail-center.index')
            ->with('success', "Custom mail sent to {$user->name}.");
    }

    private function delayedTasksForUser(User $user): Collection
    {
        return $user->assignedTasks()
            ->with(['category', 'creator'])
            ->whereNull('archived_at')
            ->where('status', '!=', 'done')
            ->whereDate('due_date', '<', today())
            ->orderBy('due_date')
            ->get();
    }

    private function formatTasks(Collection $tasks): array
    {
        return $tasks->map(static fn (Task $task) => [
            'title'       => $task->title,
            'category'    => $task->category?->name ?? 'No category',
            'priority'    => ucfirst($task->priority),
            'status'      => ucfirst(str_replace('_', ' ', $task->status)),
            'due_date'    => optional($task->due_date)->format('M d, Y') ?? '-',
            'assigned_by' => $task->creator?->name ?? 'System',
        ])->values()->all();
    }

    private function delayScope(Builder $query): void
    {
        $query->whereNull('archived_at')
            ->where('status', '!=', 'done')
            ->whereDate('due_date', '<', today());
    }
}
