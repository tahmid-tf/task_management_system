@extends('layouts.admin')

@section('content')
    @php
        $isAdmin = $isAdmin ?? auth()->user()?->hasRole('Admin');
        $isTeamMember = $isTeamMember ?? auth()->user()?->hasRole('Team Member');
        $canChangeStatuses = $isAdmin || $isTeamMember;
    @endphp
    <div class="container-xl px-4 py-4">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Task Board</h1>
                <div class="text-muted">
                    {{ $selectedCategory ? 'Kanban board for '.$selectedCategory->name.'.' : 'Kanban board for all active tasks.' }}
                    Drag tasks between statuses to update progress.
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tasks.table') }}" class="btn btn-outline-primary">Table View</a>
                <a href="{{ route('admin.tasks.calendar') }}" class="btn btn-outline-primary">Calendar View</a>
                @if ($isAdmin)
                    <a href="{{ route('admin.task-categories.index') }}" class="btn btn-outline-secondary">Manage Categories</a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">Create Task</button>
                @endif
            </div>
        </div>

        @if ($categories->isEmpty())
            <div class="alert alert-warning">
                No categories yet. Create your first category to start organizing tasks.
            </div>
        @else
            <div class="d-flex flex-wrap gap-2 overflow-auto pb-3 mb-3 border-bottom">
                <a href="{{ route('admin.tasks.board') }}"
                    class="btn btn-sm {{ $selectedCategory ? 'btn-outline-primary' : 'btn-primary' }}">
                    All Tasks
                    <span class="badge bg-light text-dark ms-1">{{ $categories->sum('open_tasks_count') }}</span>
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('admin.tasks.board', ['category' => $category->slug]) }}"
                        class="btn btn-sm {{ $selectedCategory?->id === $category->id ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $category->name }}
                        <span class="badge bg-light text-dark ms-1">{{ $category->open_tasks_count }}</span>
                    </a>
                @endforeach
            </div>

            <form class="row g-2 align-items-end mb-4" method="GET" action="{{ route('admin.tasks.board') }}">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                @endif
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" name="q" value="{{ $querySearch }}" class="form-control"
                        placeholder="Search tasks by title or description">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ $selectedCategory ? route('admin.tasks.board', ['category' => $selectedCategory->slug]) : route('admin.tasks.board') }}"
                        class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="row g-3">
                @foreach ($statuses as $statusKey => $statusLabel)
                    @php
                        $items = $boardTasks->get($statusKey, collect());
                    @endphp
                    <div class="col-12 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div class="fw-semibold">{{ $statusLabel }}</div>
                                <span class="badge bg-secondary">{{ $summaryCounts[$statusKey] ?? 0 }}</span>
                            </div>
                            <div class="card-body p-2">
                                <div class="task-column" style="min-height: 250px;" data-status="{{ $statusKey }}"
                                    data-category="{{ $selectedCategory?->id ?? '' }}">
                                    @forelse ($items as $task)
                                        <div class="task-card card mb-2 shadow-sm" data-id="{{ $task->id }}">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between gap-2">
                                                    <div class="fw-semibold small">{{ $task->title }}</div>
                                                    <button type="button" class="btn btn-sm btn-link p-0 js-task-details"
                                                        data-id="{{ $task->id }}">
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    {{ \Illuminate\Support\Str::limit($task->description, 100) }}
                                                </div>
                                                <div class="d-flex flex-wrap gap-1 mt-2">
                                                    @foreach ($task->labels as $label)
                                                        <span class="badge" style="background: {{ $label->color ?? '#6c757d' }}">
                                                            {{ $label->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div class="small text-muted">
                                                        {{ optional($task->due_date)->format('M d') ?? 'No due date' }}
                                                    </div>
                                                    @if ($task->assignee)
                                                        <div class="d-inline-flex align-items-center gap-2" title="{{ $task->assignee->name }}">
                                                            <span class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center text-primary fw-semibold"
                                                                style="width: 32px; height: 32px;">
                                                                {{ \Illuminate\Support\Str::substr($task->assignee->name, 0, 1) }}
                                                            </span>
                                                            <span class="small fw-semibold text-dark">{{ $task->assignee->name }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="small text-muted mt-2">
                                                    Category: {{ $task->category?->name ?? '-' }}
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    Assigned by: {{ $task->creator?->name ?? 'Unknown' }}
                                                </div>
                                                @if ($isAdmin)
                                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                        <button type="button" class="btn btn-sm btn-outline-dark js-task-duplicate"
                                                            data-id="{{ $task->id }}">Duplicate</button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger js-task-archive"
                                                            data-id="{{ $task->id }}">Archive</button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger js-task-delete"
                                                            data-id="{{ $task->id }}" data-title="{{ $task->title }}">
                                                            Delete
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted small py-5">Drop tasks here</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($isAdmin)
        @include('admin.tasks._create-modal', [
            'modalId' => 'createTaskModal',
            'formId' => 'createTaskForm',
            'defaultCategoryId' => $selectedCategory?->id,
            'defaultStatus' => 'todo',
        ])
    @endif

    <div class="modal fade" id="taskDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="taskDetailsModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        $(function () {
            const canChangeStatuses = @json($canChangeStatuses);
            const isAdmin = @json($isAdmin);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (canChangeStatuses) {
                $('.task-column').each(function () {
                    new Sortable(this, {
                        group: 'tasks',
                        animation: 150,
                        ghostClass: 'bg-light',
                        onEnd: function () {
                            saveBoardOrder();
                        }
                    });
                });
            }

            function saveBoardOrder() {
                const columns = {};
                $('.task-column').each(function () {
                    const status = $(this).data('status');
                    columns[status] = $(this).find('.task-card').map(function () {
                        return $(this).data('id');
                    }).get();
                });

                $.ajax({
                    url: '{{ route('admin.tasks.reorder') }}',
                    method: 'PATCH',
                    data: {
                        category_id: '{{ $selectedCategory?->id ?? '' }}',
                        columns: columns
                    }
                }).fail(function (xhr) {
                    const message = xhr.responseJSON?.message
                        || xhr.responseJSON?.errors?.category_id?.[0]
                        || xhr.responseJSON?.errors?.columns?.[0]
                        || 'Unable to save task order.';

                    Swal.fire('Error', message, 'error');
                });
            }

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

            @if (request()->boolean('create_task'))
                const createTaskModal = new bootstrap.Modal(document.getElementById('createTaskModal'));
                createTaskModal.show();
            @endif

            $(document).on('click', '.js-task-details', function () {
                $.get('{{ url('/admin/tasks') }}/' + $(this).data('id') + '/details', function (response) {
                    const task = response.task;
                    let labels = '';
                    task.labels.forEach(function (label) {
                        labels += `<span class="badge me-1 mb-1" style="background:${label.color || '#6c757d'}">${label.name}</span>`;
                    });
                    let comments = '';
                    task.comments.forEach(function (comment) {
                        comments += `<div class="border-bottom pb-2 mb-2"><strong>${comment.user || 'Unknown'}</strong><div class="small text-muted">${comment.created_at}</div><div>${comment.body}</div></div>`;
                    });
                    let attachments = '';
                    task.attachments.forEach(function (attachment) {
                        attachments += `<li class="list-group-item"><a href="${attachment.url}" target="_blank">${attachment.original_name}</a></li>`;
                    });
                    let activity = '';
                    task.activity_logs.forEach(function (entry) {
                        activity += `<li class="list-group-item"><strong>${entry.action}</strong> ${entry.description || ''}<div class="small text-muted">${entry.user || 'System'} &middot; ${entry.created_at}</div></li>`;
                    });

                    const html = `
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <h4>${task.title}</h4>
                                <p class="text-muted">${task.description || 'No description provided.'}</p>
                                <div class="mb-3">${labels}</div>
                                @if ($isAdmin)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <form class="js-comment-form" data-task-id="${task.id}">
                                                <textarea class="form-control mb-2" name="body" rows="3" placeholder="Write a comment"></textarea>
                                                <button class="btn btn-primary btn-sm">Add Comment</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                <h6>Comments</h6>
                                <div>${comments || '<div class="text-muted">No comments yet.</div>'}</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="list-group mb-3">
                                    <div class="list-group-item"><strong>Status:</strong> ${task.status}</div>
                                    <div class="list-group-item"><strong>Priority:</strong> ${task.priority}</div>
                                    <div class="list-group-item"><strong>Category:</strong> ${task.category?.name || '-'}</div>
                                    <div class="list-group-item"><strong>Assigned By:</strong> ${task.assigned_by?.name || task.creator?.name || '-'}</div>
                                    <div class="list-group-item"><strong>Assignee:</strong> ${task.assignee?.name || '-'}</div>
                                    <div class="list-group-item"><strong>Due Date:</strong> ${task.due_date || '-'}</div>
                                </div>
                                <h6>Attachments</h6>
                                <ul class="list-group mb-3">${attachments || '<li class="list-group-item text-muted">No attachments.</li>'}</ul>
                                @if ($isAdmin)
                                    <form class="js-attachment-form" data-task-id="${task.id}" enctype="multipart/form-data">
                                        <input type="file" name="file" class="form-control mb-2" required>
                                        <button class="btn btn-outline-primary btn-sm">Upload</button>
                                    </form>
                                @endif
                                <h6 class="mt-3">Activity</h6>
                                <ul class="list-group">${activity || '<li class="list-group-item text-muted">No activity yet.</li>'}</ul>
                            </div>
                        </div>
                    `;

                    $('#taskDetailsModalBody').html(html);
                    new bootstrap.Modal(document.getElementById('taskDetailsModal')).show();
                });
            });

            $(document).on('submit', '.js-comment-form', function (e) {
                e.preventDefault();
                const taskId = $(this).data('task-id');
                $.post('{{ url('/admin/tasks') }}/' + taskId + '/comments', $(this).serialize())
                    .done(function () {
                        Swal.fire('Success', 'Comment added successfully.', 'success').then(() => window.location.reload());
                    })
                    .fail(function () {
                        Swal.fire('Error', 'Unable to add comment.', 'error');
                    });
            });

            $(document).on('submit', '.js-attachment-form', function (e) {
                e.preventDefault();
                const taskId = $(this).data('task-id');
                $.ajax({
                    url: '{{ url('/admin/tasks') }}/' + taskId + '/attachments',
                    method: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                }).done(function () {
                    Swal.fire('Success', 'Attachment uploaded successfully.', 'success').then(() => window.location.reload());
                }).fail(function () {
                    Swal.fire('Error', 'Unable to upload attachment.', 'error');
                });
            });

            $(document).on('click', '.js-task-archive', function () {
                const taskId = $(this).data('id');
                Swal.fire({
                    title: 'Archive this task?',
                    text: 'You can restore it later.',
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

            $(document).on('click', '.js-task-duplicate', function () {
                const taskId = $(this).data('id');
                $.post('{{ url('/admin/tasks') }}/' + taskId + '/duplicate', {})
                    .done(function (response) {
                        const message = response.assignment_mail_sent
                            ? `${response.message} Assignment email sent to the assignee.`
                            : response.message;

                        Swal.fire('Success', message, 'success').then(() => window.location.reload());
                    })
                    .fail(function () {
                        Swal.fire('Error', 'Unable to duplicate task.', 'error');
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
        });
    </script>
@endpush
