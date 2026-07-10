<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivityLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $activeTasksQuery = Task::query()->whereNull('archived_at');
        $statusCounts = [
            'backlog' => (clone $activeTasksQuery)->where('status', 'backlog')->count(),
            'todo' => (clone $activeTasksQuery)->where('status', 'todo')->count(),
            'in_progress' => (clone $activeTasksQuery)->where('status', 'in_progress')->count(),
            'done' => (clone $activeTasksQuery)->where('status', 'done')->count(),
        ];
        $totalTasks = array_sum($statusCounts);
        $doneTasks = $statusCounts['done'];
        $completionRate = $totalTasks > 0 ? (int) round(($doneTasks / $totalTasks) * 100) : 0;
        $dueSoonTasks = (clone $activeTasksQuery)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->count();

        $recentTasks = Task::query()
            ->with(['category', 'assignee', 'creator'])
            ->whereNull('archived_at')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.dashboard', [
            'user' => $user,
            'totalUsers' => User::count(),
            'activeUsers' => User::query()->where('status', 'active')->count(),
            'totalTasks' => $totalTasks,
            'backlogTasks' => $statusCounts['backlog'],
            'todoTasks' => $statusCounts['todo'],
            'inProgressTasks' => $statusCounts['in_progress'],
            'doneTasks' => $doneTasks,
            'archivedTasks' => Task::query()->whereNotNull('archived_at')->count(),
            'overdueTasks' => Task::query()
                ->whereNull('archived_at')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', Carbon::today())
                ->count(),
            'dueSoonTasks' => $dueSoonTasks,
            'completionRate' => $completionRate,
            'statusCounts' => $statusCounts,
            'recentTasks' => $recentTasks,
            'recentActivity' => TaskActivityLog::query()
                ->with(['task.category', 'user'])
                ->latest()
                ->limit(6)
                ->get(),
        ]);
    }
}
