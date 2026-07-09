@extends('layouts.admin')

@section('content')
    <div class="container-xl px-4 py-4">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Export Tasks</h1>
                <div class="text-muted">Download task data by date range, user, status, or full export.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tasks.table') }}" class="btn btn-outline-primary">Back to Table</a>
                <a href="{{ route('admin.tasks.board') }}" class="btn btn-outline-secondary">Board View</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Full Export</h5>
                        <a class="btn btn-primary"
                            href="{{ route('admin.tasks.export.download', ['mode' => 'all']) }}">
                            Export All Tasks (XLSX)
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Date Based Export</h5>
                        <form method="GET" action="{{ route('admin.tasks.export.download') }}" class="row g-3">
                            <input type="hidden" name="mode" value="date">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Export Date Range (XLSX)</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">User Based Export</h5>
                        <form method="GET" action="{{ route('admin.tasks.export.download') }}" class="row g-3">
                            <input type="hidden" name="mode" value="user">
                            <div class="col-12">
                                <label class="form-label">Select User</label>
                                <select name="user_id" class="form-select">
                                    <option value="">Choose a user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} @if ($user->status !== 'active') (Inactive) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Export User Tasks (XLSX)</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Todo Tasks</h5>
                        <a class="btn btn-outline-primary w-100"
                            href="{{ route('admin.tasks.export.download', ['mode' => 'status', 'status' => 'todo']) }}">
                            Export Todo (XLSX)
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">In Progress Tasks</h5>
                        <a class="btn btn-outline-primary w-100"
                            href="{{ route('admin.tasks.export.download', ['mode' => 'status', 'status' => 'in_progress']) }}">
                            Export In Progress (XLSX)
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Done Tasks</h5>
                        <a class="btn btn-outline-primary w-100"
                            href="{{ route('admin.tasks.export.download', ['mode' => 'status', 'status' => 'done']) }}">
                            Export Done (XLSX)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
