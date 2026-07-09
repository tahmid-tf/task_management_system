@extends('layouts.admin')

@section('content')
    <div class="container-xl px-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Create Task</h1>
                <div class="text-muted">Add a new task to the board.</div>
            </div>
        </div>

        <form action="{{ route('admin.tasks.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.tasks._form', [
                'task' => null,
                'method' => 'POST',
                'submitLabel' => 'Create Task',
            ])
        </form>
    </div>
@endsection
