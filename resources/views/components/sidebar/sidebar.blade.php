<div class="sidenav-menu">
    <div class="nav accordion" id="accordionSidenav">
        @php
            $isDashboardActive = request()->routeIs('dashboard');
            $isMailCenterActive = request()->routeIs('admin.mail-center.*');
            $isExportActive = request()->routeIs('admin.tasks.export*');
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

        <a class="nav-link {{ $isMailCenterActive ? 'active' : '' }}" href="{{ route('admin.mail-center.index') }}">
            <div class="nav-link-icon"><i data-feather="mail"></i></div>
            Send Mail
        </a>

        <a class="nav-link {{ $isExportActive ? 'active' : '' }}" href="{{ route('admin.tasks.export') }}">
            <div class="nav-link-icon"><i data-feather="download"></i></div>
            Export Tasks
        </a>

        <a class="nav-link" href="javascript:void(0);" aria-expanded="true" aria-controls="collapseAddUser">
            <div class="nav-link-icon"><i data-feather="activity"></i></div>
            Users
        </a>
        <div class="collapse show" id="collapseAddUser">
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

        <a class="nav-link" href="javascript:void(0);" aria-expanded="true" aria-controls="collapseTasks">
            <div class="nav-link-icon"><i data-feather="layers"></i></div>
            Tasks
        </a>
        <div class="collapse show" id="collapseTasks">
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
                {{-- <a class="nav-link {{ request()->routeIs('admin.tasks.export*') ? 'active' : '' }}"
                    href="{{ route('admin.tasks.export') }}">
                    Export Tasks
                </a> --}}
                <a class="nav-link {{ request()->routeIs('admin.task-categories.index') ? 'active' : '' }}"
                    href="{{ route('admin.task-categories.index') }}">
                    Categories
                </a>
            </nav>
        </div>
    </div>
</div>
