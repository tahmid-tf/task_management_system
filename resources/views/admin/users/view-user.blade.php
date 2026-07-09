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
                                    <i data-feather="users"></i>
                                </div>
                                View Users
                            </h1>
                            <div class="page-header-subtitle">Manage the registered users in your system</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>User List</span>
                    <a href="{{ route('admin.add-user') }}" class="btn btn-primary btn-sm">
                        <i data-feather="user-plus" class="me-1"></i>
                        Add User
                    </a>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Address</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>
                                        <img
                                            src="{{ $user->image ? asset('storage/' . $user->image) : asset('assets/img/illustrations/profiles/profile-1.png') }}"
                                            alt="{{ $user->name }}"
                                            class="rounded-circle"
                                            style="width: 44px; height: 44px; object-fit: cover;"
                                        >
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary text-white rounded-pill">
                                            {{ $user->getRoleNames()->first() ?? 'No Role' }}
                                        </span>
                                    </td>
                                    <td class="status-cell">
                                        @if ($user->status === 'active')
                                            <span class="badge bg-success text-white rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-secondary text-white rounded-pill">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->address ? \Illuminate\Support\Str::limit($user->address, 60) : '-' }}</td>
                                    <td>{{ optional($user->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <a
                                            href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                            title="Edit user"
                                        >
                                            <i data-feather="edit" width="18" height="18"></i>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-datatable btn-icon btn-transparent-dark toggle-user-status"
                                            data-url="{{ route('admin.users.toggle-status', $user) }}"
                                            data-status="{{ $user->status }}"
                                            data-name="{{ $user->name }}"
                                        >
                                            <i data-feather="{{ $user->status === 'active' ? 'slash' : 'check-circle' }}" width="18" height="18"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

    <script>
        $(function() {
            $(document).on('click', '.toggle-user-status', function() {
                const $button = $(this);
                const url = $button.data('url');
                const currentStatus = String($button.data('status'));
                const userName = $button.data('name');
                const nextStatus = currentStatus === 'active' ? 'inactive' : 'active';
                const actionText = nextStatus === 'active' ? 'activate' : 'deactivate';

                Swal.fire({
                    icon: 'warning',
                    title: `Are you sure to ${actionText} this user?`,
                    text: `${userName} will be marked as ${nextStatus}.`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: url,
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        success: function(response) {
                            const $row = $button.closest('tr');
                            const $statusCell = $row.find('.status-cell');

                            if (response.status === 'active') {
                                $statusCell.html('<span class="badge bg-success text-white rounded-pill">Active</span>');
                                $button.data('status', 'active');
                                $button.html('<i data-feather="slash" width="18" height="18"></i>');
                            } else {
                                $statusCell.html('<span class="badge bg-secondary text-white rounded-pill">Inactive</span>');
                                $button.data('status', 'inactive');
                                $button.html('<i data-feather="check-circle" width="18" height="18"></i>');
                            }

                            if (typeof feather !== 'undefined') {
                                feather.replace();
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Status updated successfully.',
                                confirmButtonText: 'Okay',
                            });
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON || {};

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Unable to update status.',
                                confirmButtonText: 'Okay',
                            });
                        },
                    });
                });
            });
        });
    </script>
@endsection
