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
                                {{-- <th>Address</th> --}}
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    data-user-email="{{ $user->email }}"
                                    data-user-phone="{{ $user->phone ?? '-' }}"
                                    data-user-role="{{ $user->getRoleNames()->first() ?? 'No Role' }}"
                                    data-user-status="{{ $user->status }}"
                                    data-user-address="{{ $user->address ?? '-' }}"
                                    data-user-created="{{ optional($user->created_at)->format('d M Y, h:i A') }}"
                                    data-user-image="{{ $user->image ? asset('storage/' . $user->image) : asset('assets/img/illustrations/profiles/profile-1.png') }}"
                                >
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
                                    {{-- <td>{{ $user->address ? \Illuminate\Support\Str::limit($user->address, 60) : '-' }}</td> --}}
                                    <td>{{ optional($user->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-datatable btn-icon btn-transparent-dark view-user-details me-2"
                                            title="View user"
                                        >
                                            <i data-feather="eye" width="18" height="18"></i>
                                        </button>
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
            $(document).on('click', '.view-user-details', function() {
                const $row = $(this).closest('tr');
                const image = $row.data('user-image');
                const name = $row.data('user-name');
                const email = $row.data('user-email');
                const phone = $row.data('user-phone');
                const role = $row.data('user-role');
                const status = $row.data('user-status');
                const address = $row.data('user-address');
                const created = $row.data('user-created');

                const statusBadge = status === 'active'
                    ? '<span class="badge bg-success text-white rounded-pill">Active</span>'
                    : '<span class="badge bg-secondary text-white rounded-pill">Inactive</span>';

                Swal.fire({
                    title: 'User Details',
                    html: `
                        <div class="text-start" style="text-align:left;">
                            <div style="display:flex; gap:16px; align-items:center; padding:4px 0 18px; border-bottom:1px solid #e5e7eb; margin-bottom:18px;">
                                <img src="${image}" alt="${name}" style="width:84px;height:84px;object-fit:cover;border-radius:9999px;border:4px solid #eef2ff;box-shadow:0 10px 24px rgba(15,23,42,.12);">
                                <div style="min-width:0;">
                                    <div style="font-size:18px;font-weight:700;color:#0f172a;line-height:1.2;">${name}</div>
                                    <div style="font-size:13px;color:#64748b;margin-top:4px;">${email}</div>
                                    <div style="margin-top:10px;">${statusBadge}</div>
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
                                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Phone</div>
                                    <div style="font-size:14px;font-weight:600;color:#0f172a;word-break:break-word;">${phone}</div>
                                </div>
                                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Role</div>
                                    <div style="font-size:14px;font-weight:600;color:#0f172a;word-break:break-word;">${role}</div>
                                </div>
                                <div style="grid-column:1 / -1;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Address</div>
                                    <div style="font-size:14px;font-weight:600;color:#0f172a;line-height:1.6;">${address}</div>
                                </div>
                                <div style="grid-column:1 / -1;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Created</div>
                                    <div style="font-size:14px;font-weight:600;color:#0f172a;">${created}</div>
                                </div>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#0d6efd',
                    customClass: {
                        popup: 'shadow-lg',
                    },
                    width: 600,
                });
            });

            $(document).on('click', '.toggle-user-status', function() {
                const $button = $(this);
                const url = $button.data('url');
                const currentStatus = String($button.data('status'));
                const userName = $button.data('name');
                const nextStatus = currentStatus === 'active' ? 'inactive' : 'active';
                const actionText = nextStatus === 'active' ? 'activate' : 'deactivate';
                const originalHtml = $button.html();

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

                    $button
                        .prop('disabled', true)
                        .addClass('disabled')
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                    $.ajax({
                        url: url,
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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

                            $button.prop('disabled', false).removeClass('disabled');

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

                            $button
                                .prop('disabled', false)
                                .removeClass('disabled')
                                .html(originalHtml);

                            if (typeof feather !== 'undefined') {
                                feather.replace();
                            }

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
