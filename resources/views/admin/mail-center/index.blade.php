@extends('layouts.admin')

@section('content')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="mail"></i>
                                </div>
                                Send Mail
                            </h1>
                            <div class="page-header-subtitle">Send delayed task reminders or custom emails to active users.</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-bold">Active Users</div>
                            <div class="display-6 fw-bold mt-2">{{ $activeUserCount }}</div>
                            <div class="small text-muted">Only active users appear in the send forms.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-bold">Users With Delays</div>
                            <div class="display-6 fw-bold mt-2">{{ $usersWithDelays }}</div>
                            <div class="small text-muted">Users that currently have overdue tasks.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-bold">Delayed Tasks</div>
                            <div class="display-6 fw-bold mt-2">{{ $delayedTaskCount }}</div>
                            <div class="small text-muted">Incomplete tasks past their due date.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span>Automated Reminder - All Active Users</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Send an automated reminder to every active user who currently has delayed tasks.
                            </p>
                            <form method="POST" action="{{ route('admin.mail-center.delayed-all') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="send" class="me-1"></i>
                                    Send to All Active Users
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header">Automated Reminder - Individual User</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.mail-center.delayed-user') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="delayed_user_id">Select Active User</label>
                                    <select name="user_id" id="delayed_user_id" class="form-select @error('user_id') is-invalid @enderror">
                                        <option value="">Choose user</option>
                                        @foreach ($activeUsers as $user)
                                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                                {{ $user->name }} ({{ $user->delayed_tasks_count }} delayed)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-outline-primary">
                                    Send Delayed Reminder
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header">Custom Mail to Active User</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.mail-center.custom') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="custom_user_id">Select Active User</label>
                                        <select name="user_id" id="custom_user_id" class="form-select @error('user_id') is-invalid @enderror">
                                            <option value="">Choose user</option>
                                            @foreach ($activeUsers as $user)
                                                <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="subject">Subject</label>
                                        <input type="text" name="subject" id="subject"
                                            value="{{ old('subject') }}"
                                            class="form-control @error('subject') is-invalid @enderror"
                                            placeholder="Write a subject line">
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="message">Message</label>
                                        <textarea name="message" id="message" rows="7"
                                            class="form-control @error('message') is-invalid @enderror"
                                            placeholder="Write your custom message">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        Send Custom Mail
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm">
                        <div class="card-header">Active Users Overview</div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Delayed Tasks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activeUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <div class="small text-muted">{{ $user->email }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->delayed_tasks_count > 0 ? 'danger' : 'secondary' }}">
                                                    {{ $user->delayed_tasks_count }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">No active users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @if (session('success'))
        <script>
            $(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    confirmButtonText: 'Okay',
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            $(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonText: 'Okay',
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            $(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation error',
                    text: @json($errors->first()),
                    confirmButtonText: 'Okay',
                });
            });
        </script>
    @endif
@endsection
