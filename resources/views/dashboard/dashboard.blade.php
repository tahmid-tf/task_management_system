@extends('layouts.admin')

@section('content')
    @php
        $statusLabels = [
            'backlog' => 'Backlog',
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'done' => 'Done',
        ];

        $statusColors = [
            'backlog' => '#64748b',
            'todo' => '#0d6efd',
            'in_progress' => '#f59e0b',
            'done' => '#198754',
        ];
    @endphp

    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="grid"></i>
                                </div>
                                Dashboard
                            </h1>
                            <div class="page-header-subtitle">
                                Operational overview of users, tasks, activity, and exports
                            </div>
                        </div>

                        <div class="col-12 col-xl-auto mt-4">
                            <div class="d-flex flex-wrap gap-2 justify-content-xl-end">
                                <a href="{{ route('admin.tasks.board') }}" class="btn btn-light">
                                    <i data-feather="columns" class="me-1"></i>
                                    Task Board
                                </a>
                                <a href="{{ route('admin.tasks.export') }}" class="btn btn-outline-light">
                                    <i data-feather="download" class="me-1"></i>
                                    Export Tasks
                                </a>
                                <a href="{{ route('admin.view-users') }}" class="btn btn-outline-light">
                                    <i data-feather="users" class="me-1"></i>
                                    Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">Total Tasks</div>
                                    <div class="display-6 fw-bold">{{ $totalTasks }}</div>
                                </div>
                                <div class="btn btn-icon btn-primary-soft text-primary rounded-circle">
                                    <i data-feather="check-square"></i>
                                </div>
                            </div>
                            <div class="small text-muted mt-3">{{ $backlogTasks }} tasks waiting in backlog</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">In Progress</div>
                                    <div class="display-6 fw-bold">{{ $inProgressTasks }}</div>
                                </div>
                                <div class="btn btn-icon btn-warning-soft text-warning rounded-circle">
                                    <i data-feather="loader"></i>
                                </div>
                            </div>
                            <div class="small text-muted mt-3">{{ $dueSoonTasks }} tasks due within 7 days</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">Done</div>
                                    <div class="display-6 fw-bold">{{ $doneTasks }}</div>
                                </div>
                                <div class="btn btn-icon btn-success-soft text-success rounded-circle">
                                    <i data-feather="check-circle"></i>
                                </div>
                            </div>
                            <div class="small text-muted mt-3">{{ $completionRate }}% completion rate</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">Users</div>
                                    <div class="display-6 fw-bold">{{ $activeUsers }}</div>
                                </div>
                                <div class="btn btn-icon btn-info-soft text-info rounded-circle">
                                    <i data-feather="users"></i>
                                </div>
                            </div>
                            <div class="small text-muted mt-3">{{ $totalUsers }} total users in the system</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1">Task Distribution</h5>
                                <div class="text-muted small">How work is moving across the board</div>
                            </div>
                            <a href="{{ route('admin.tasks.table') }}" class="btn btn-sm btn-outline-primary">Open Table</a>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-5">
                                    <div style="height: 280px;">
                                        <canvas id="taskStatusChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    @foreach ($statusLabels as $key => $label)
                                        @php
                                            $count = $statusCounts[$key] ?? 0;
                                            $percent = $totalTasks > 0 ? round(($count / $totalTasks) * 100) : 0;
                                        @endphp
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div class="fw-semibold">{{ $label }}</div>
                                                <div class="text-muted small">{{ $count }} tasks</div>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar"
                                                    style="width: {{ $percent }}%; background: {{ $statusColors[$key] }};"
                                                    role="progressbar"
                                                    aria-valuenow="{{ $percent }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-1">Recent Activity</h5>
                            <div class="text-muted small">Latest task updates in the workspace</div>
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-xs">
                                @forelse ($recentActivity as $activity)
                                    @php
                                        $activityColor = match ($activity->action) {
                                            'created' => '#0d6efd',
                                            'updated' => '#0ea5e9',
                                            'moved' => '#f59e0b',
                                            'commented' => '#8b5cf6',
                                            'attachment_added' => '#14b8a6',
                                            'archived', 'unarchived' => '#64748b',
                                            default => '#64748b',
                                        };
                                    @endphp
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </div>
                                            <div class="timeline-item-marker-indicator" style="background: {{ $activityColor }};"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            <div class="fw-semibold">{{ \Illuminate\Support\Str::headline($activity->action) }}</div>
                                            <div class="small text-muted">
                                                {{ $activity->task?->title ?? 'Task' }}
                                                by {{ $activity->user?->name ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        No recent activity yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-0">
                <div class="col-xl-7">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1">Recent Tasks</h5>
                                <div class="text-muted small">Latest active tasks in the system</div>
                            </div>
                            <a href="{{ route('admin.tasks.board') }}" class="btn btn-sm btn-outline-primary">Open Board</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Task</th>
                                            <th>Status</th>
                                            <th>Assignee</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentTasks as $task)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $task->title }}</div>
                                                    <div class="small text-muted">
                                                        {{ \Illuminate\Support\Str::limit($task->description, 70) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill"
                                                        style="background: rgba(13, 110, 253, 0.10); color: #0d6efd;">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $task->assignee?->name ?? 'Unassigned' }}</td>
                                                <td>{{ $task->category?->name ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    No tasks found yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-1">Quick Actions</h5>
                            <div class="text-muted small">Fast access to common admin pages</div>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('admin.tasks.board') }}" class="list-group-item list-group-item-action px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Task Board</div>
                                            <div class="text-muted small">Manage tasks by category</div>
                                        </div>
                                        <i data-feather="chevron-right"></i>
                                    </div>
                                </a>
                                <a href="{{ route('admin.tasks.export') }}" class="list-group-item list-group-item-action px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Export Tasks</div>
                                            <div class="text-muted small">Download XLSX reports</div>
                                        </div>
                                        <i data-feather="chevron-right"></i>
                                    </div>
                                </a>
                                <a href="{{ route('admin.view-users') }}" class="list-group-item list-group-item-action px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Users</div>
                                            <div class="text-muted small">Manage account roles and status</div>
                                        </div>
                                        <i data-feather="chevron-right"></i>
                                    </div>
                                </a>
                                <a href="{{ route('admin.task-categories.index') }}" class="list-group-item list-group-item-action px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Categories</div>
                                            <div class="text-muted small">Organize board tabs</div>
                                        </div>
                                        <i data-feather="chevron-right"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(function () {
            const ctx = document.getElementById('taskStatusChart');

            if (ctx && typeof Chart !== 'undefined') {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_values($statusLabels)),
                        datasets: [{
                            data: @json(array_values($statusCounts)),
                            backgroundColor: @json(array_values($statusColors)),
                            borderWidth: 0,
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 16,
                            }
                        },
                        tooltips: {
                            displayColors: false,
                        }
                    }
                });
            }
        });
    </script>
@endpush
