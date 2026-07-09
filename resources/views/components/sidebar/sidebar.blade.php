<div class="sidenav-menu">
    <div class="nav accordion" id="accordionSidenav">
        @php
            $isDashboardActive = request()->routeIs('dashboard');
            $isUsersActive = request()->routeIs(
                'admin.add-user',
                'admin.add-user.store',
                'admin.view-users',
                'admin.users.*',
            );
        @endphp

        <div class="sidenav-menu-heading">Core</div>

        <a class="nav-link {{ $isDashboardActive ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <div class="nav-link-icon"><i data-feather="bar-chart"></i></div>
            Dashboard
        </a>

        <a class="nav-link {{ $isUsersActive ? '' : 'collapsed' }}" href="javascript:void(0);" data-bs-toggle="collapse"
            data-bs-target="#collapseAddUser" aria-expanded="{{ $isUsersActive ? 'true' : 'false' }}"
            aria-controls="collapseAddUser">
            <div class="nav-link-icon"><i data-feather="activity"></i></div>
            Users
            <div class="sidenav-collapse-arrow">
                <i class="fas fa-angle-down"></i>
            </div>
        </a>
        <div class="collapse {{ $isUsersActive ? 'show' : '' }}" id="collapseAddUser"
            data-bs-parent="#accordionSidenav">
            <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                <a class="nav-link {{ request()->routeIs('admin.add-user') ? 'active' : '' }}"
                    href="{{ route('admin.add-user') }}">
                    Add User
                </a>
                <a class="nav-link {{ request()->routeIs('admin.view-users', 'admin.users.edit') ? 'active' : '' }}"
                    href="{{ route('admin.view-users') }}">
                    View Users
                </a>
            </nav>
        </div>
    </div>
</div>
