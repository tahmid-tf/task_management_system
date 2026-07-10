@php
    $modalId = $modalId ?? 'createTaskModal';
    $formId = $formId ?? 'createTaskForm';
    $modalTitle = $modalTitle ?? 'Create Task';
    $submitLabel = $submitLabel ?? 'Create Task';
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="{{ $formId }}" action="{{ route('admin.tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $modalId }}Label">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.tasks._form-fields', [
                        'task' => null,
                        'categories' => $categories ?? collect(),
                        'users' => $users ?? collect(),
                        'statuses' => $statuses ?? [],
                        'priorities' => $priorities ?? [],
                        'labelsValue' => '',
                        'defaultCategoryId' => $defaultCategoryId ?? null,
                        'defaultStatus' => $defaultStatus ?? 'todo',
                        'showActions' => false,
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
