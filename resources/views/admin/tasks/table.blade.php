@extends('layouts.admin')

@section('content')
    @php
        $canManageTasks = auth()->user()?->hasAnyRole(['Admin', 'Team Member']);
        $canDeleteTasks = auth()->user()?->hasRole('Admin');
    @endphp
    <div class="container-xl px-4 py-4">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Task Table</h1>
                <div class="text-muted">All tasks in a responsive DataTable view.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tasks.board') }}" class="btn btn-outline-primary">Board View</a>
                @if ($canManageTasks)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        Create Task
                    </button>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tasksTable" class="table table-striped table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assignee</th>
                                <th>Due Date</th>
                                <th>Labels</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $task->title }}</div>
                                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($task->description, 80) }}</div>
                                    </td>
                                    <td>{{ $task->category?->name ?? '-' }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($task->priority) }}</span></td>
                                    <td>{{ $task->assignee?->name ?? '-' }}</td>
                                    <td>{{ optional($task->due_date)->format('M d, Y') ?? '-' }}</td>
                                    <td>
                                        @foreach ($task->labels as $label)
                                            <span class="badge me-1" style="background: {{ $label->color ?? '#6c757d' }}">
                                                {{ $label->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex flex-column gap-2 align-items-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary js-task-details"
                                                    data-id="{{ $task->id }}">View</button>
                                                @if ($canManageTasks)
                                                    <a class="btn btn-outline-secondary"
                                                        href="{{ route('admin.tasks.edit', $task) }}">Edit</a>
                                                    <button class="btn btn-outline-dark js-task-archive"
                                                        data-id="{{ $task->id }}">Archive</button>
                                                    @if ($canDeleteTasks)
                                                        <button class="btn btn-outline-danger js-task-delete"
                                                            data-id="{{ $task->id }}"
                                                            data-title="{{ $task->title }}">Delete</button>
                                                    @endif
                                                @endif
                                            </div>

                                            @if ($canManageTasks)
                                                <select class="form-select form-select-sm js-task-status-change"
                                                    data-id="{{ $task->id }}"
                                                    data-category-id="{{ $task->task_category_id }}"
                                                    data-position="{{ $task->position }}"
                                                    style="max-width: 180px;">
                                                    @if (!in_array($task->status, ['todo', 'in_progress', 'done'], true))
                                                        <option value="{{ $task->status }}" selected disabled>
                                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }} (Current)
                                                        </option>
                                                    @endif
                                                    <option value="todo" @selected($task->status === 'todo')>To Do</option>
                                                    <option value="in_progress" @selected($task->status === 'in_progress')>In Progress</option>
                                                    <option value="done" @selected($task->status === 'done')>Done</option>
                                                </select>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="taskDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="taskDetailsModalBody"></div>
            </div>
        </div>
    </div>

    @if ($canManageTasks)
        @include('admin.tasks._create-modal', [
            'modalId' => 'createTaskModal',
            'formId' => 'createTaskForm',
            'defaultCategoryId' => $categories->first()->id ?? null,
            'defaultStatus' => 'todo',
        ])
    @endif
@endsection

@push('scripts')
    <script>
        $(function () {
            const table = new DataTable(document.getElementById('tasksTable'), {
                responsive: true,
                autoWidth: false,
                order: [],
                columnDefs: [
                    { targets: 0, responsivePriority: 1 },
                    { targets: 7, responsivePriority: 1 },
                    { targets: 2, responsivePriority: 2 },
                    { targets: 3, responsivePriority: 3 },
                    { targets: 4, responsivePriority: 4 },
                    { targets: 5, responsivePriority: 5 },
                    { targets: 6, responsivePriority: 6 },
                ],
            });

            $('.js-task-status-change').each(function () {
                $(this).data('previous', $(this).val());
            });

            $('#createTaskForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('admin.tasks.store') }}',
                    method: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const message = response.assignment_mail_sent
                            ? `${response.message} Assignment email sent to the assignee.`
                            : response.message;

                        Swal.fire('Success', message, 'success').then(function () {
                            window.location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Unable to create task.', 'error');
                    }
                });
            });

            $(document).on('click', '.js-task-details', function () {
                $.get('{{ url('/admin/tasks') }}/' + $(this).data('id') + '/details', function (response) {
                    const task = response.task;
                    const html = `
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <h4>${task.title}</h4>
                                <p class="text-muted">${task.description || 'No description provided.'}</p>
                                <div class="small text-muted">Created by ${task.creator?.name || '-'} on ${task.category?.name || '-'}</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="list-group">
                                    <div class="list-group-item"><strong>Status:</strong> ${task.status}</div>
                                    <div class="list-group-item"><strong>Priority:</strong> ${task.priority}</div>
                                    <div class="list-group-item"><strong>Assigned By:</strong> ${task.assigned_by?.name || task.creator?.name || '-'}</div>
                                    <div class="list-group-item"><strong>Due Date:</strong> ${task.due_date || '-'}</div>
                                    <div class="list-group-item"><strong>Assignee:</strong> ${task.assignee?.name || '-'}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#taskDetailsModalBody').html(html);
                    new bootstrap.Modal(document.getElementById('taskDetailsModal')).show();
                });
            });

            $(document).on('click', '.js-task-archive', function () {
                const taskId = $(this).data('id');
                Swal.fire({
                    title: 'Archive this task?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Archive'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.post('{{ url('/admin/tasks') }}/' + taskId + '/archive', {_method: 'PATCH'})
                        .done(function (response) {
                            Swal.fire('Success', response.message, 'success').then(() => window.location.reload());
                        })
                        .fail(function () {
                            Swal.fire('Error', 'Unable to archive task.', 'error');
                    });
                });
            });

            $(document).on('click', '.js-task-delete', function () {
                const taskId = $(this).data('id');
                const taskTitle = $(this).data('title') || 'this task';

                Swal.fire({
                    title: 'Delete this task?',
                    text: `${taskTitle} will be moved to trash and removed from active views.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#d33'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: '{{ url('/admin/tasks') }}/' + taskId,
                        method: 'DELETE'
                    }).done(function (response) {
                        Swal.fire('Success', response.message, 'success').then(() => window.location.reload());
                    }).fail(function (xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Unable to delete task.', 'error');
                    });
                });
            });

            $(document).on('change', '.js-task-status-change', function () {
                const select = $(this);
                const taskId = select.data('id');
                const status = select.val();
                const position = select.data('position');
                const categoryId = select.data('category-id');

                Swal.fire({
                    title: 'Change task status?',
                    text: `Move this task to ${select.find('option:selected').text()}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Change'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        select.val(select.data('previous') || select.find('option:first').val());
                        return;
                    }

                    $.ajax({
                        url: '{{ url('/admin/tasks') }}/' + taskId + '/move',
                        method: 'PATCH',
                        data: {
                            status: status,
                            position: position,
                            category_id: categoryId
                        }
                    }).done(function (response) {
                        select.data('previous', status);
                        Swal.fire('Success', response.message, 'success').then(() => window.location.reload());
                    }).fail(function (xhr) {
                        select.val(select.data('previous') || select.find('option:first').val());
                        Swal.fire('Error', xhr.responseJSON?.message || 'Unable to change task status.', 'error');
                    });
                });
            });
        });
    </script>
@endpush
