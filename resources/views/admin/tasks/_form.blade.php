@csrf
@if (isset($method) && $method !== 'POST')
    @method($method)
@endif

@include('admin.tasks._form-fields', [
    'task' => $task ?? null,
    'categories' => $categories ?? collect(),
    'users' => $users ?? collect(),
    'statuses' => $statuses ?? [],
    'priorities' => $priorities ?? [],
    'submitLabel' => $submitLabel ?? 'Save Task',
    'showActions' => true,
])
