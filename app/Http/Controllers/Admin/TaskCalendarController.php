<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TaskCalendarController extends Controller
{
    private const STATUSES = [
        'backlog' => 'Backlog',
        'todo' => 'To Do',
        'in_progress' => 'In Progress',
        'done' => 'Done',
    ];

    public function index(Request $request): View
    {
        $today = Carbon::today();
        $tasks = $this->visibleTasksQuery($request)
            ->whereNotNull('due_date')
            ->with(['category', 'creator', 'assignee', 'labels'])
            ->orderBy('due_date')
            ->orderBy('position')
            ->orderBy('created_at')
            ->get();

        $calendarEvents = $tasks->map(function (Task $task) use ($today) {
            $isOverdue = $task->due_date?->lt($today) && $task->status !== 'done';
            $statusColor = match ($task->status) {
                'backlog' => '#64748b',
                'todo' => '#2563eb',
                'in_progress' => '#f59e0b',
                'done' => '#0f766e',
                default => '#172033',
            };

            return [
                'id' => $task->id,
                'title' => Str::limit($task->title, 28),
                'start' => optional($task->due_date)->format('Y-m-d'),
                'allDay' => true,
                'backgroundColor' => $isOverdue ? '#ef4444' : $statusColor,
                'borderColor' => $isOverdue ? '#dc2626' : $statusColor,
                'textColor' => '#ffffff',
                'classNames' => [$isOverdue ? 'calendar-event-overdue' : 'calendar-event-'.$task->status],
                'extendedProps' => [
                    'full_title' => $task->title,
                    'status' => $task->status,
                    'status_label' => self::STATUSES[$task->status] ?? Str::headline($task->status),
                    'priority' => $task->priority,
                    'priority_label' => ucfirst($task->priority),
                    'due_date' => optional($task->due_date)->format('M d, Y'),
                    'category' => $task->category?->name ?? '-',
                    'assignee' => $task->assignee?->name ?? 'Unassigned',
                    'creator' => $task->creator?->name ?? 'System',
                    'is_overdue' => $isOverdue,
                ],
            ];
        })->values()->all();

        $dateSummaries = $tasks
            ->groupBy(fn (Task $task) => optional($task->due_date)->format('Y-m-d'))
            ->map(function (Collection $items, string $date) use ($today) {
                $counts = [
                    'todo' => $items->where('status', 'todo')->count(),
                    'in_progress' => $items->where('status', 'in_progress')->count(),
                    'done' => $items->where('status', 'done')->count(),
                ];

                $total = array_sum($counts);
                $completed = $counts['done'];
                $open = $counts['todo'] + $counts['in_progress'];
                $isPastDate = Carbon::parse($date)->lt($today);

                return [
                    'date' => $date,
                    'label' => Carbon::parse($date)->format('D, M j'),
                    'total' => $total,
                    'todo' => $counts['todo'],
                    'in_progress' => $counts['in_progress'],
                    'done' => $counts['done'],
                    'open' => $open,
                    'completed' => $completed,
                    'signal' => $this->resolveSignal($isPastDate, $open, $completed),
                ];
            })
            ->all();

        $visibleTaskCount = $tasks->count();
        $completedTaskCount = $tasks->where('status', 'done')->count();
        $dueSoonTaskCount = $tasks->filter(function (Task $task) use ($today) {
            return $task->due_date
                && $task->due_date->greaterThanOrEqualTo($today)
                && $task->due_date->lessThanOrEqualTo($today->copy()->addDays(7))
                && $task->status !== 'done';
        })->count();
        $overdueTaskCount = $tasks->filter(function (Task $task) use ($today) {
            return $task->due_date
                && $task->due_date->lt($today)
                && $task->status !== 'done';
        })->count();
        $todayTaskCount = $tasks->filter(fn (Task $task) => $task->due_date?->isSameDay($today))->count();

        return view('admin.tasks.calendar', [
            'calendarEvents' => $calendarEvents,
            'dateSummaries' => $dateSummaries,
            'visibleTaskCount' => $visibleTaskCount,
            'completedTaskCount' => $completedTaskCount,
            'dueSoonTaskCount' => $dueSoonTaskCount,
            'overdueTaskCount' => $overdueTaskCount,
            'todayTaskCount' => $todayTaskCount,
            'isAdmin' => $this->isAdmin($request->user()),
            'isTeamMember' => $this->isTeamMember($request->user()),
            'statusLegend' => self::STATUSES,
        ]);
    }

    private function visibleTasksQuery(Request $request): Builder
    {
        $query = Task::query()->whereNull('archived_at');

        if ($this->isTeamMember($request->user())) {
            $query->where('assigned_to', $request->user()?->id);
        }

        return $query;
    }

    private function resolveSignal(bool $isPastDate, int $openCount, int $completedCount): string
    {
        if ($isPastDate && $openCount > 0) {
            return 'danger';
        }

        if ($openCount > $completedCount) {
            return 'warning';
        }

        return 'success';
    }

    private function isAdmin(?User $user): bool
    {
        return (bool) $user?->hasRole('Admin');
    }

    private function isTeamMember(?User $user): bool
    {
        return (bool) $user?->hasRole('Team Member') && ! $user?->hasRole('Admin');
    }
}
