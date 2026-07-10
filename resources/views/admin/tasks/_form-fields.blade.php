@php
    $task = $task ?? null;
    $categories = $categories ?? collect();
    $users = $users ?? collect();
    $statuses = $statuses ?? [];
    $priorities = $priorities ?? [];
    $labelsValue = $labelsValue ?? old('labels', $task ? $task->labels->pluck('name')->implode(', ') : '');
    $defaultCategoryId = $defaultCategoryId ?? null;
    $defaultStatus = $defaultStatus ?? 'todo';
    $showActions = $showActions ?? true;
@endphp

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" class="form-control"
                        placeholder="Enter task title">
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="7" placeholder="Write task details">{{ old('description', $task->description ?? '') }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Labels</label>
                    <input type="text" name="labels" value="{{ $labelsValue }}" class="form-control"
                        placeholder="Design, Frontend, Urgent">
                    <div class="form-text">Separate labels with commas.</div>
                    @error('labels')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Attachments</label>
                    <input type="file" name="attachments[]" class="form-control" multiple>
                    <div class="form-text">You can upload more than one file.</div>
                    @error('attachments')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                @if (! empty($task?->attachments?->count()))
                    <div class="mt-3">
                        <label class="form-label">Existing Attachments</label>
                        <div class="list-group">
                            @foreach ($task->attachments as $attachment)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($attachment->path) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                    target="_blank">
                                    <span>{{ $attachment->original_name }}</span>
                                    <span class="small text-muted">{{ number_format(($attachment->size ?? 0) / 1024, 1) }} KB</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="task_category_id" class="form-select">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('task_category_id', $task->task_category_id ?? $defaultCategoryId) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('task_category_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $task->status ?? $defaultStatus) === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        @foreach ($priorities as $key => $label)
                            <option value="{{ $key }}" @selected(old('priority', $task->priority ?? 'medium') === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Assignee</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @if (isset($task) && $task && $task->assigned_to && ! $users->contains('id', $task->assigned_to))
                            <option value="{{ $task->assigned_to }}" selected disabled>
                                {{ $task->assignee?->name ?? 'Current Assignee (Inactive)' }} (Inactive)
                            </option>
                        @endif
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to ?? '') == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', optional($task?->due_date)->format('Y-m-d')) }}" class="form-control">
                    @error('due_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Estimated Time</label>
                    <input type="number" step="0.25" min="0" name="estimated_time"
                        value="{{ old('estimated_time', $task->estimated_time ?? '') }}" class="form-control"
                        placeholder="e.g. 4">
                </div>
                <div class="mb-3">
                    <label class="form-label">Actual Time Spent</label>
                    <input type="number" step="0.25" min="0" name="actual_time"
                        value="{{ old('actual_time', $task->actual_time ?? '') }}" class="form-control"
                        placeholder="e.g. 2.5">
                </div>
            </div>
        </div>

        @if ($showActions)
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ $submitLabel ?? 'Save Task' }}</button>
                <a href="{{ route('admin.tasks.board') }}" class="btn btn-light">Cancel</a>
            </div>
        @endif
    </div>
</div>
