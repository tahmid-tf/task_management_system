@extends('layouts.admin')

@section('content')
    @php
        $quickLinks = [
            ['label' => 'Getting Started', 'href' => '#getting-started'],
            ['label' => 'Who Uses It', 'href' => '#roles'],
            ['label' => 'Main Screens', 'href' => '#screens'],
            ['label' => 'Working With Tasks', 'href' => '#tasks'],
            ['label' => 'Calendar Guide', 'href' => '#calendar-guide'],
            ['label' => 'Mail & Alerts', 'href' => '#mail-guide'],
            ['label' => 'Exports', 'href' => '#exports'],
            ['label' => 'Tips', 'href' => '#tips'],
        ];

        $roles = [
            [
                'title' => 'Admin',
                'icon' => 'shield',
                'accent' => '#172033',
                'soft' => '#e8edf4',
                'points' => [
                    'Can manage users, tasks, categories, mail settings, and exports.',
                    'Can create, edit, archive, delete, and duplicate tasks.',
                    'Can see all task data in the board, table, and calendar.',
                ],
            ],
            [
                'title' => 'Team Member',
                'icon' => 'user-check',
                'accent' => '#0f766e',
                'soft' => '#d9f0ed',
                'points' => [
                    'Sees only assigned tasks.',
                    'Can change task status on the board and in the table.',
                    'Can export only their own task data.',
                ],
            ],
            [
                'title' => 'Viewer',
                'icon' => 'eye',
                'accent' => '#2563eb',
                'soft' => '#dbeafe',
                'points' => [
                    'Can view task information and calendar data.',
                    'Cannot make changes to tasks.',
                    'Useful for supervisors or clients who only need visibility.',
                ],
            ],
        ];

        $screenCards = [
            [
                'title' => 'Dashboard',
                'icon' => 'grid',
                'accent' => '#172033',
                'soft' => '#f5f7fb',
                'description' => 'A snapshot of total work, overdue items, recent activity, and quick navigation.',
            ],
            [
                'title' => 'Task Board',
                'icon' => 'columns',
                'accent' => '#0f766e',
                'soft' => '#d9f0ed',
                'description' => 'Drag tasks between Backlog, To Do, In Progress, and Done columns.',
            ],
            [
                'title' => 'Task Table',
                'icon' => 'table',
                'accent' => '#2563eb',
                'soft' => '#dbeafe',
                'description' => 'A responsive list view for quick editing, status changes, and details.',
            ],
            [
                'title' => 'Calendar',
                'icon' => 'calendar',
                'accent' => '#f59e0b',
                'soft' => '#fef3c7',
                'description' => 'Shows due dates with traffic-signal colors and hover summaries.',
            ],
            [
                'title' => 'Exports',
                'icon' => 'download',
                'accent' => '#0f766e',
                'soft' => '#d9f0ed',
                'description' => 'Download task reports by date range, user, or status in XLSX format.',
            ],
            [
                'title' => 'Mail Center',
                'icon' => 'mail',
                'accent' => '#ef4444',
                'soft' => '#fee2e2',
                'description' => 'Send delayed-task reminders and custom messages to active users.',
            ],
        ];

        $taskSteps = [
            'Open the Task Board or Task Table from the sidebar.',
            'Create a task if you are an Admin, then choose the category, assignee, due date, and priority.',
            'Add comments or attachments when the task needs more context.',
            'Change the status as work moves from To Do to In Progress and then Done.',
            'Use Archive if a completed task should be hidden from the active list.',
        ];

        $calendarNotes = [
            'Hover a date to see how many tasks are in To Do, In Progress, and Done.',
            'Red dates mean overdue work needs attention.',
            'Yellow dates mean the day needs a closer look.',
            'Green dates mean the day is in good shape.',
        ];

        $mailItems = [
            'Delayed task reminder emails can be sent to all active users.',
            'You can also send a delayed reminder to one active user only.',
            'Custom emails are available when a specific message is needed.',
            'The mail system can be turned on or off from the sidebar.',
        ];

        $exportItems = [
            'Export a complete task list.',
            'Export tasks within a date range.',
            'Export tasks for one specific user.',
            'Export tasks by status such as To Do, In Progress, or Done.',
            'XLSX export keeps formatting and is easier to read in Excel.',
        ];

        $tips = [
            'Use the sidebar to jump between areas quickly.',
            'Check the calendar before planning new work so deadlines stay realistic.',
            'Only active users appear when assigning tasks.',
            'If a user becomes inactive, they will be blocked from using the dashboard until reactivated.',
        ];
    @endphp

    <main class="manual-shell">
        <section class="manual-hero mx-4 mt-4">
            <div class="manual-hero-copy container-xl px-4 py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <div class="manual-chip">
                            <i data-feather="book-open" class="me-1"></i>
                            User Manual
                        </div>
                        <h1 class="mt-3 mb-3 display-5 fw-bold text-white">
                            A clear guide for every person using the system.
                        </h1>
                        <p class="mb-4 lead" style="max-width: 56rem; color: rgba(255, 255, 255, .78);">
                            This page explains the dashboard in simple language, so your team can learn how to use the
                            software
                            without needing to understand the code behind it.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.tasks.board') }}" class="btn btn-light">
                                <i data-feather="columns" class="me-1"></i>
                                Task Board
                            </a>
                            <a href="{{ route('admin.tasks.calendar') }}" class="btn btn-outline-light">
                                <i data-feather="calendar" class="me-1"></i>
                                Calendar
                            </a>
                            <a href="{{ route('admin.tasks.table') }}" class="btn btn-outline-light">
                                <i data-feather="table" class="me-1"></i>
                                Task Table
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="d-grid gap-3">
                            <div class="manual-stat">
                                <div class="value">2</div>
                                <div class="label">User roles supported</div>
                            </div>
                            <div class="manual-stat">
                                <div class="value">4</div>
                                <div class="label">Core task states</div>
                            </div>
                            <div class="manual-stat">
                                <div class="value">1</div>
                                <div class="label">Simple workflow for the team</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <br>
        <div class="container-xl px-4 mt-n4 pb-4">
            <div class="row g-4">
                <div class="col-xl-3">
                    <div class="manual-side-card sticky-xl-top" style="top: 1.25rem;">
                        <div class="p-4">
                            <h2 class="h6 fw-bold mb-3">Quick Navigation</h2>
                            <div class="d-grid gap-2">
                                @foreach ($quickLinks as $link)
                                    <a href="{{ $link['href'] }}" class="manual-toc-link">{{ $link['label'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-9">
                    <section id="getting-started" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Getting Started</p>
                                <h2 class="h4 fw-bold mb-1">A simple way to begin</h2>
                                <div class="manual-lead">Most users only need three things: log in, open a screen from the
                                    sidebar, and follow the status flow.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach (['Log in with your email and password.', 'Use the sidebar to open the area you need.', 'Read the cards on the dashboard for a quick summary.', 'Open the board, table, calendar, or exports when needed.'] as $index => $step)
                                    <div class="col-md-6">
                                        <div class="manual-step">
                                            <div class="manual-step-number">{{ $index + 1 }}</div>
                                            <div class="manual-step-text">{{ $step }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="roles" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Who Uses It</p>
                                <h2 class="h4 fw-bold mb-1">Three roles, three levels of access</h2>
                                <div class="manual-lead">Each role sees the same friendly interface, but with the right
                                    permissions for their job.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach ($roles as $role)
                                    <div class="col-lg-4">
                                        <div class="manual-role-card"
                                            style="--manual-accent: {{ $role['accent'] }}; --manual-soft: {{ $role['soft'] }};">
                                            <div class="manual-role-card-top"></div>
                                            <div class="p-4">
                                                <div class="manual-role-icon"
                                                    style="color: {{ $role['accent'] }}; background: {{ $role['soft'] }};">
                                                    <i data-feather="{{ $role['icon'] }}"></i>
                                                </div>
                                                <h3 class="h5 fw-bold mt-3 mb-3">{{ $role['title'] }}</h3>
                                                <ul class="manual-bullet-list">
                                                    @foreach ($role['points'] as $point)
                                                        <li>{{ $point }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="screens" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Main Screens</p>
                                <h2 class="h4 fw-bold mb-1">Where to find the most important tools</h2>
                                <div class="manual-lead">These pages are the main places your team will use every day.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach ($screenCards as $card)
                                    <div class="col-md-6">
                                        <div class="manual-screen-card">
                                            <div class="manual-screen-preview"
                                                style="--screen-accent: {{ $card['accent'] }}; --screen-soft: {{ $card['soft'] }};">
                                                <div class="manual-screen-bar">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                                <div class="manual-screen-body">
                                                    <div class="manual-screen-line short"></div>
                                                    <div class="manual-screen-line"></div>
                                                    <div class="manual-screen-row">
                                                        <span class="manual-screen-pill"
                                                            style="background: {{ $card['soft'] }}; color: {{ $card['accent'] }};"></span>
                                                        <span class="manual-screen-avatar"
                                                            style="background: {{ $card['accent'] }};"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-3">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="manual-screen-icon"
                                                        style="color: {{ $card['accent'] }}; background: {{ $card['soft'] }};">
                                                        <i data-feather="{{ $card['icon'] }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="h6 fw-bold mb-1">{{ $card['title'] }}</h3>
                                                        <div class="small text-muted">{{ $card['description'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="tasks" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Working With Tasks</p>
                                <h2 class="h4 fw-bold mb-1">The normal task flow</h2>
                                <div class="manual-lead">This is the recommended way to move work through the system.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach ($taskSteps as $index => $step)
                                    <div class="col-lg-6">
                                        <div class="manual-flow-card">
                                            <div class="manual-flow-number">{{ $index + 1 }}</div>
                                            <div class="manual-flow-text">{{ $step }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="calendar-guide" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Calendar Guide</p>
                                <h2 class="h4 fw-bold mb-1">How the calendar helps planning</h2>
                                <div class="manual-lead">The calendar is designed to make deadlines easy to understand at a
                                    glance.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3 align-items-stretch">
                                <div class="col-lg-7">
                                    <div class="manual-info-card h-100">
                                        <h3 class="h6 fw-bold mb-3">What to look for</h3>
                                        <div class="d-grid gap-3">
                                            @foreach ($calendarNotes as $note)
                                                <div class="manual-note-item">{{ $note }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="manual-info-card h-100">
                                        <h3 class="h6 fw-bold mb-3">Color meaning</h3>
                                        <div class="d-grid gap-3">
                                            <div class="manual-legend-item"><span class="manual-swatch success"></span>
                                                <div>
                                                    <div class="fw-bold">Green</div>
                                                    <div class="small text-muted">Work is on track.</div>
                                                </div>
                                            </div>
                                            <div class="manual-legend-item"><span class="manual-swatch warning"></span>
                                                <div>
                                                    <div class="fw-bold">Yellow</div>
                                                    <div class="small text-muted">Please review the day.</div>
                                                </div>
                                            </div>
                                            <div class="manual-legend-item"><span class="manual-swatch danger"></span>
                                                <div>
                                                    <div class="fw-bold">Red</div>
                                                    <div class="small text-muted">The day already has overdue work.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="mail-guide" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Mail & Alerts</p>
                                <h2 class="h4 fw-bold mb-1">What the mail tools do</h2>
                                <div class="manual-lead">These tools help you follow up with active users when work is
                                    delayed or when a custom note is needed.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach ($mailItems as $item)
                                    <div class="col-lg-6">
                                        <div class="manual-badge-card">
                                            <i data-feather="mail" class="me-2"></i>
                                            <span>{{ $item }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="exports" class="manual-section mb-4">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Exports</p>
                                <h2 class="h4 fw-bold mb-1">How to download task reports</h2>
                                <div class="manual-lead">Use exports when you need a spreadsheet copy for sharing, reviews,
                                    or offline tracking.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach ($exportItems as $item)
                                    <div class="col-lg-6">
                                        <div class="manual-export-item">
                                            <i data-feather="download" class="me-2"></i>
                                            <span>{{ $item }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="tips" class="manual-section">
                        <div class="manual-section-header">
                            <div>
                                <p class="manual-eyebrow">Tips</p>
                                <h2 class="h4 fw-bold mb-1">Helpful things to remember</h2>
                                <div class="manual-lead">A few small habits will make the system easier for everyone.</div>
                            </div>
                        </div>
                        <div class="manual-section-body">
                            <div class="row g-3">
                                @foreach ($tips as $tip)
                                    <div class="col-lg-6">
                                        <div class="manual-tip-item">
                                            <i data-feather="check-circle" class="me-2"></i>
                                            <span>{{ $tip }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <div class="manual-copyright mt-4">
                        Copyright &copy; Tahmid Ferdous
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        .manual-shell {
            position: relative;
            font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .manual-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .05), rgba(255, 255, 255, 0) 44%),
                linear-gradient(315deg, rgba(37, 99, 235, .05), rgba(255, 255, 255, 0) 36%);
            pointer-events: none;
        }

        .manual-shell>.manual-hero {
            margin-left: clamp(1.5rem, 3vw, 2.5rem) !important;
            margin-right: clamp(1.5rem, 3vw, 2.5rem) !important;
        }

        .manual-shell>.manual-hero>.manual-hero-copy {
            padding-left: clamp(1.75rem, 3vw, 4rem) !important;
            padding-right: clamp(1.75rem, 3vw, 4rem) !important;
            padding-top: clamp(3rem, 4vw, 4.25rem) !important;
            padding-bottom: clamp(3rem, 4vw, 4rem) !important;
        }

        .manual-shell>.container-xl {
            padding-left: clamp(1.75rem, 3vw, 4rem) !important;
            padding-right: clamp(1.75rem, 3vw, 4rem) !important;
            padding-top: 1.25rem;
        }

        .manual-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            background:
                linear-gradient(135deg, #172033 0%, #0f766e 56%, rgba(245, 158, 11, .92) 135%);
            box-shadow: 0 30px 80px rgba(23, 32, 51, .16);
        }

        .manual-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .07) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: .35;
        }

        .manual-hero::after {
            content: "";
            position: absolute;
            inset: auto -80px -120px auto;
            width: 320px;
            height: 320px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .08);
            filter: blur(8px);
        }

        .manual-hero-copy {
            position: relative;
            z-index: 1;
        }

        .manual-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border-radius: 999px;
            padding: .4rem .8rem;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .16);
            color: #fff;
            font-size: .85rem;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .manual-stat {
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 42px rgba(23, 32, 51, .12);
        }

        .manual-stat .value {
            color: #fff;
            font-size: 1.9rem;
            line-height: 1;
            font-weight: 800;
        }

        .manual-stat .label {
            margin-top: .35rem;
            color: rgba(255, 255, 255, .78);
            font-size: .88rem;
        }

        .manual-side-card,
        .manual-section,
        .manual-info-card,
        .manual-role-card,
        .manual-screen-card {
            border-radius: 1.25rem;
            background: #fff;
            border: 1px solid #dfe7f1;
            box-shadow: 0 18px 42px rgba(23, 32, 51, .07);
            overflow: hidden;
        }

        .manual-side-card {
            position: sticky;
            top: 1.25rem;
        }

        .manual-toc-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            border-radius: .9rem;
            padding: .8rem .95rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #172033;
            font-weight: 700;
            text-decoration: none;
            transition: transform .15s ease, background .15s ease, border-color .15s ease;
        }

        .manual-toc-link:hover {
            transform: translateY(-1px);
            background: #fff;
            border-color: #cbd5e1;
            color: #0f766e;
        }

        .manual-section-header {
            padding: 1.2rem 1.25rem 0;
        }

        .manual-eyebrow {
            margin: 0;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 800;
        }

        .manual-lead {
            color: #627084;
            font-size: .96rem;
            margin-top: .25rem;
        }

        .manual-section-body {
            padding: 1.75rem;
        }

        .manual-step,
        .manual-flow-card,
        .manual-note-item,
        .manual-badge-card,
        .manual-export-item,
        .manual-tip-item,
        .manual-legend-item {
            display: flex;
            align-items: flex-start;
            gap: .9rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: .95rem 1rem;
        }

        .manual-step-number,
        .manual-flow-number {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #0f766e;
            color: #fff;
            font-weight: 800;
            font-size: .9rem;
        }

        .manual-step-text,
        .manual-flow-text,
        .manual-note-item,
        .manual-badge-card,
        .manual-export-item,
        .manual-tip-item {
            color: #334155;
            font-weight: 600;
            line-height: 1.55;
        }

        .manual-role-card-top {
            height: .35rem;
            background: linear-gradient(90deg, var(--manual-accent), rgba(255, 255, 255, 0));
        }

        .manual-role-icon,
        .manual-screen-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .manual-bullet-list {
            margin: 0;
            padding-left: 1rem;
            display: grid;
            gap: .7rem;
            color: #475569;
        }

        .manual-bullet-list li {
            line-height: 1.55;
        }

        .manual-screen-preview {
            padding: .9rem .9rem 0;
            background: linear-gradient(180deg, var(--screen-soft), rgba(255, 255, 255, .35));
            border-bottom: 1px solid #e2e8f0;
        }

        .manual-screen-bar {
            display: flex;
            gap: .35rem;
            align-items: center;
            margin-bottom: .75rem;
        }

        .manual-screen-bar span {
            width: .55rem;
            height: .55rem;
            border-radius: 999px;
            background: rgba(23, 32, 51, .22);
        }

        .manual-screen-body {
            padding: .35rem 0 .85rem;
        }

        .manual-screen-line {
            height: .65rem;
            border-radius: 999px;
            background: rgba(23, 32, 51, .14);
            margin-bottom: .55rem;
            width: 100%;
        }

        .manual-screen-line.short {
            width: 56%;
        }

        .manual-screen-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-top: .75rem;
        }

        .manual-screen-pill {
            display: inline-block;
            width: 4rem;
            height: 1.2rem;
            border-radius: 999px;
        }

        .manual-screen-avatar {
            width: 1.85rem;
            height: 1.85rem;
            border-radius: 999px;
            box-shadow: 0 8px 18px rgba(23, 32, 51, .14);
        }

        .manual-info-card {
            padding: 1.25rem;
        }

        .manual-swatch {
            width: .95rem;
            height: .95rem;
            border-radius: 999px;
            flex-shrink: 0;
            margin-top: .3rem;
        }

        .manual-swatch.success {
            background: #0f766e;
        }

        .manual-swatch.warning {
            background: #f59e0b;
        }

        .manual-swatch.danger {
            background: #ef4444;
        }

        .manual-copyright {
            border-radius: 1rem;
            border: 1px solid #dfe7f1;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 14px 30px rgba(23, 32, 51, .05);
            color: #627084;
            font-weight: 700;
            text-align: center;
            padding: 1rem 1.25rem;
        }

        @media (max-width: 991px) {
            .manual-side-card {
                position: static !important;
            }
        }
    </style>
@endpush
