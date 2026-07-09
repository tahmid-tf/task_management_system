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
            $isTasksActive = request()->routeIs(
                'admin.tasks.*',
                'admin.task-categories.*',
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

        <a class="nav-link {{ $isTasksActive ? '' : 'collapsed' }}" href="javascript:void(0);" data-bs-toggle="collapse"
            data-bs-target="#collapseTasks" aria-expanded="{{ $isTasksActive ? 'true' : 'false' }}"
            aria-controls="collapseTasks">
            <div class="nav-link-icon"><i data-feather="layers"></i></div>
            Tasks
            <div class="sidenav-collapse-arrow">
                <i class="fas fa-angle-down"></i>
            </div>
        </a>
        <div class="collapse {{ $isTasksActive ? 'show' : '' }}" id="collapseTasks" data-bs-parent="#accordionSidenav">
            <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavTaskPages">
                <a class="nav-link {{ request()->routeIs('admin.tasks.board') ? 'active' : '' }}"
                    href="{{ route('admin.tasks.board') }}">
                    Board
                </a>
                <a class="nav-link {{ request()->routeIs('admin.tasks.table') ? 'active' : '' }}"
                    href="{{ route('admin.tasks.table') }}">
                    Table View
                </a>
                <a class="nav-link {{ request()->routeIs('admin.tasks.archived') ? 'active' : '' }}"
                    href="{{ route('admin.tasks.archived') }}">
                    Archived Tasks
                </a>
                <a class="nav-link {{ request()->routeIs('admin.task-categories.index') ? 'active' : '' }}"
                    href="{{ route('admin.task-categories.index') }}">
                    Categories
                </a>
            </nav>
        </div>
    </div>
</div>
