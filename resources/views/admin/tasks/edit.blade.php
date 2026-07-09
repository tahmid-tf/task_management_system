@extends('layouts.admin')

@section('content')
    <div class="container-xl px-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Edit Task</h1>
                <div class="text-muted">Update the selected task.</div>
            </div>
        </div>

        <form action="{{ route('admin.tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
            @include('admin.tasks._form', [
                'task' => $task,
                'method' => 'PUT',
                'submitLabel' => 'Update Task',
            ])
        </form>
    </div>
@endsection
