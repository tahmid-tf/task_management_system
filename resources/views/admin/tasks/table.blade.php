@extends('layouts.admin')

@section('content')
    @php
        $isAdmin = $isAdmin ?? auth()->user()?->hasRole('Admin');
        $isTeamMember = $isTeamMember ?? auth()->user()?->hasRole('Team Member');
        $canChangeStatuses = $canChangeStatuses ?? ($isAdmin || $isTeamMember);
    @endphp
    <div class="container-xl px-4 py-4">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Task Table</h1>
                <div class="text-muted">All tasks in a responsive DataTable view.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tasks.board') }}" class="btn btn-outline-primary">Board View</a>
                @if ($isAdmin)
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
                                    <td><span class="badge bg-info text-white">{{ ucfirst($task->priority) }}</span></td>
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
                                                @if ($isAdmin)
                                                    <button class="btn btn-outline-secondary js-task-edit"
                                                        data-id="{{ $task->id }}">Edit</button>
                                                    <button class="btn btn-outline-dark js-task-archive"
                                                        data-id="{{ $task->id }}">Archive</button>
                                                    <button class="btn btn-outline-danger js-task-delete"
                                                        data-id="{{ $task->id }}"
                                                        data-title="{{ $task->title }}">Delete</button>
                                                @endif
                                            </div>

                                            @if ($canChangeStatuses)
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

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" id="editTaskModalContent">
                <div class="modal-body py-5 text-center">
                    <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                    <div class="mt-3 text-muted">Loading task editor...</div>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
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

            const formatStatusLabel = function (value) {
                if (value === 'in_progress') {
                    return 'In Progress';
                }

                if (value === 'todo') {
                    return 'To Do';
                }

                if (value === 'done') {
                    return 'Done';
                }

                if (value === 'backlog') {
                    return 'Backlog';
                }

                return value
                    .replaceAll('_', ' ')
                    .replace(/\b\w/g, function (letter) {
                        return letter.toUpperCase();
                    });
            };

            const escapeHtml = function (value) {
                return $('<div>').text(value ?? '').html();
            };

            const updateTaskRow = function (task) {
                const row = $(`tbody tr`).filter(function () {
                    return $(this).find('.js-task-edit').data('id') == task.id;
                }).first();

                if (!row.length) {
                    return;
                }

                row.find('td').eq(0).html(`
                    <div class="fw-semibold">${escapeHtml(task.title)}</div>
                    <div class="text-muted small">${escapeHtml(task.description ? task.description.substring(0, 80) : '')}</div>
                `);
                row.find('td').eq(1).text(task.category?.name || '-');
                row.find('td').eq(2).html(`<span class="badge bg-secondary">${formatStatusLabel(task.status)}</span>`);
                row.find('td').eq(3).html(`<span class="badge bg-info text-white">${escapeHtml(task.priority ? task.priority.charAt(0).toUpperCase() + task.priority.slice(1) : '-')}</span>`);
                row.find('td').eq(4).text(task.assignee?.name || '-');
                row.find('td').eq(5).text(task.due_date || '-');
                row.find('td').eq(6).html((task.labels || []).map(function (label) {
                    return `<span class="badge me-1" style="background:${escapeHtml(label.color || '#6c757d')}">${escapeHtml(label.name)}</span>`;
                }).join(''));

                const deleteButton = row.find('.js-task-delete');
                if (deleteButton.length) {
                    deleteButton.data('title', task.title);
                }

                const statusSelect = row.find('.js-task-status-change');
                if (statusSelect.length) {
                    statusSelect.val(task.status);
                    statusSelect.data('previous', task.status);
                }
            };

            $('.js-task-status-change').each(function () {
                $(this).data('previous', $(this).val());
            });

            $('#createTaskForm').on('submit', function (e) {
                e.preventDefault();

                const form = $(this);
                const submitButton = form.find('.js-task-submit');
                const originalLabel = submitButton.data('original-label') || submitButton.text().trim();
                const loadingLabel = submitButton.data('loading-label') || 'Creating...';

                submitButton
                    .data('original-label', originalLabel)
                    .prop('disabled', true)
                    .html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingLabel}`);

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
                    },
                    complete: function () {
                        submitButton
                            .prop('disabled', false)
                            .html(submitButton.data('original-label') || originalLabel);
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

            $(document).on('click', '.js-task-edit', function () {
                const taskId = $(this).data('id');
                const modalElement = document.getElementById('editTaskModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

                $('#editTaskModalContent').html(`
                    <div class="modal-body py-5 text-center">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                        <div class="mt-3 text-muted">Loading task editor...</div>
                    </div>
                `);

                modal.show();

                $.ajax({
                    url: '{{ url('/admin/tasks') }}/' + taskId + '/edit',
                    method: 'GET',
                    dataType: 'json',
                }).done(function (response) {
                    $('#editTaskModalContent').html(response.html);
                }).fail(function (xhr) {
                    modal.hide();
                    Swal.fire('Error', xhr.responseJSON?.message || 'Unable to load task editor.', 'error');
                });
            });

            $(document).on('submit', '#editTaskForm', function (e) {
                e.preventDefault();

                const form = $(this);
                const submitButton = form.find('.js-task-submit');
                const originalLabel = submitButton.data('original-label') || submitButton.text().trim();
                const loadingLabel = submitButton.data('loading-label') || 'Updating...';

                submitButton
                    .data('original-label', originalLabel)
                    .prop('disabled', true)
                    .html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingLabel}`);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                }).done(function (response) {
                    updateTaskRow(response.task);
                    bootstrap.Modal.getInstance(document.getElementById('editTaskModal'))?.hide();
                    Swal.fire('Success', response.message || 'Task updated successfully.', 'success');
                }).fail(function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Unable to update task.', 'error');
                }).always(function () {
                    submitButton
                        .prop('disabled', false)
                        .html(submitButton.data('original-label') || originalLabel);
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
                const originalStatus = select.data('previous') || select.find('option:first').val();

                const statusBadgeHtml = function (value) {
                    const label = value === 'in_progress'
                        ? 'In Progress'
                        : value === 'todo'
                            ? 'To Do'
                            : value === 'done'
                                ? 'Done'
                                : value;

                    return `<span class="badge bg-secondary">${label}</span>`;
                };

                Swal.fire({
                    title: 'Change task status?',
                    text: `Move this task to ${select.find('option:selected').text()}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Change'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        select.val(originalStatus);
                        return;
                    }

                    select.prop('disabled', true);

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
                        select.closest('tr').find('td').eq(2).html(statusBadgeHtml(status));
                        Swal.fire('Success', response.message, 'success');
                    }).fail(function (xhr) {
                        select.val(originalStatus);
                        Swal.fire('Error', xhr.responseJSON?.message || 'Unable to change task status.', 'error');
                    }).always(function () {
                        select.prop('disabled', false);
                    });
                });
            });
        });
    </script>
@endpush
