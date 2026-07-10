@php
    $modalId = $modalId ?? 'editTaskModal';
    $formId = $formId ?? 'editTaskForm';
    $modalTitle = $modalTitle ?? 'Edit Task';
    $submitLabel = $submitLabel ?? 'Update Task';
@endphp

<div class="modal-header">
    <h5 class="modal-title" id="{{ $modalId }}Label">{{ $modalTitle }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="{{ $formId }}" action="{{ route('admin.tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        @include('admin.tasks._form-fields', [
            'task' => $task,
            'categories' => $categories ?? collect(),
            'users' => $users ?? collect(),
            'statuses' => $statuses ?? [],
            'priorities' => $priorities ?? [],
            'labelsValue' => $task->labels->pluck('name')->implode(', '),
            'showActions' => false,
        ])
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary js-task-submit" data-loading-label="Updating...">
            {{ $submitLabel }}
        </button>
    </div>
</form>
