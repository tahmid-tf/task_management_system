@extends('layouts.admin')

@section('content')
    @php
        $metricCards = [
            [
                'label' => 'Visible Tasks',
                'value' => $visibleTaskCount,
                'icon' => 'layers',
                'accent' => '#172033',
                'soft' => '#e8edf4',
                'note' => 'Tasks visible to your role',
            ],
            [
                'label' => 'Today',
                'value' => $todayTaskCount,
                'icon' => 'calendar',
                'accent' => '#2563eb',
                'soft' => '#dbeafe',
                'note' => 'Tasks due today',
            ],
            [
                'label' => 'Due Soon',
                'value' => $dueSoonTaskCount,
                'icon' => 'clock',
                'accent' => '#f59e0b',
                'soft' => '#fef3c7',
                'note' => 'Due in the next 7 days',
            ],
            [
                'label' => 'Overdue',
                'value' => $overdueTaskCount,
                'icon' => 'alert-triangle',
                'accent' => '#ef4444',
                'soft' => '#fee2e2',
                'note' => 'Needs immediate attention',
            ],
            [
                'label' => 'Completed',
                'value' => $completedTaskCount,
                'icon' => 'check-circle',
                'accent' => '#0f766e',
                'soft' => '#d9f0ed',
                'note' => 'Done tasks',
            ],
        ];

        $legendItems = [
            [
                'label' => 'Green',
                'title' => 'On track',
                'description' => 'Done work is keeping pace with the day.',
                'class' => 'success',
            ],
            [
                'label' => 'Yellow',
                'title' => 'Needs attention',
                'description' => 'The day has open tasks that should be watched closely.',
                'class' => 'warning',
            ],
            [
                'label' => 'Red',
                'title' => 'Delayed',
                'description' => 'Open tasks are already past their due date.',
                'class' => 'danger',
            ],
            [
                'label' => 'Gray',
                'title' => 'Empty day',
                'description' => 'No visible tasks are scheduled here.',
                'class' => 'neutral',
            ],
        ];
    @endphp

    <main class="calendar-shell">
        <section class="calendar-hero mx-4 mt-4">
            <div class="calendar-hero-copy container-xl px-4 py-5">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-8">
                        <div class="calendar-chip">
                            <i data-feather="calendar" class="me-1"></i>
                            Professional task calendar
                        </div>
                        <h1 class="mt-3 mb-3 display-5 fw-bold text-white">
                            See deadlines and workload without leaving the board.
                        </h1>
                        <p class="mb-4 lead" style="max-width: 56rem; color: rgba(255, 255, 255, .78);">
                            Hover any date to see total To Do, In Progress, and Done counts.
                            Dates are tinted with a traffic-signal style so overdue work stands out instantly.
                            Admins see the full calendar, while Team Members see only their assigned tasks.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.tasks.board') }}" class="btn btn-light">
                                <i data-feather="columns" class="me-1"></i>
                                Task Board
                            </a>
                            <a href="{{ route('admin.tasks.table') }}" class="btn btn-outline-light">
                                <i data-feather="table" class="me-1"></i>
                                Task Table
                            </a>
                            <a href="{{ route('admin.user-manual') }}" class="btn btn-outline-light">
                                <i data-feather="book-open" class="me-1"></i>
                                User Manual
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="d-grid gap-3">
                            <div class="calendar-stat">
                                <div class="value">{{ $overdueTaskCount }}</div>
                                <div class="label">Overdue tasks visible to your role</div>
                            </div>
                            <div class="calendar-stat">
                                <div class="value">{{ $dueSoonTaskCount }}</div>
                                <div class="label">Tasks due in the next seven days</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container-xl px-4 mt-n4 pb-4">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-5 g-3 mb-4">
                @foreach ($metricCards as $card)
                    <div class="col">
                        <div class="calendar-stat-card h-100" style="--accent-color: {{ $card['accent'] }}; --accent-soft: {{ $card['soft'] }};">
                            <div class="calendar-stat-card-top"></div>
                            <div class="p-4">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div>
                                        <div class="small text-muted fw-semibold">{{ $card['label'] }}</div>
                                        <div class="mt-1 h2 mb-1 fw-bold text-dark">{{ $card['value'] }}</div>
                                        <div class="small text-muted">{{ $card['note'] }}</div>
                                    </div>
                                    <div class="calendar-metric-icon" style="color: {{ $card['accent'] }}; background: {{ $card['soft'] }};">
                                        <i data-feather="{{ $card['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4">
                <div class="col-xl-9">
                    <div class="calendar-panel">
                        <div class="calendar-panel-header d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3">
                            <div>
                                <h2 class="h5 fw-bold mb-1">Task Calendar</h2>
                                <div class="calendar-note">
                                    Hover a date to preview task totals. Click a task card to see the full task summary.
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="calendar-legend-pill"><span class="calendar-dot bg-success"></span>On track</span>
                                <span class="calendar-legend-pill"><span class="calendar-dot bg-warning"></span>Needs attention</span>
                                <span class="calendar-legend-pill"><span class="calendar-dot bg-danger"></span>Delayed</span>
                            </div>
                        </div>

                        <div class="p-3 p-lg-4 calendar-calendar">
                            <div id="taskCalendar"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="calendar-side-card mb-4">
                        <div class="p-4">
                            <h3 class="h6 fw-bold mb-3">Traffic Signal Guide</h3>
                            <div class="d-grid gap-3">
                                @foreach ($legendItems as $item)
                                    <div class="calendar-legend-item">
                                        <span class="calendar-swatch calendar-swatch-{{ $item['class'] }}"></span>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item['title'] }}</div>
                                            <div class="small text-muted">{{ $item['description'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="calendar-side-card">
                        <div class="p-4">
                            <h3 class="h6 fw-bold mb-3">How It Works</h3>
                            <ul class="calendar-help-list">
                                <li>Admins see all visible tasks in the calendar.</li>
                                <li>Team Members see only their assigned tasks.</li>
                                <li>Hover a date to check the To Do, In Progress, and Done totals.</li>
                                <li>Click a task card to inspect the task summary.</li>
                                <li>Red dates mean the day already has overdue work.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="calendarTooltip" class="calendar-tooltip"></div>
@endsection

@push('styles')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        .calendar-shell {
            position: relative;
            font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .calendar-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .05), rgba(255, 255, 255, 0) 44%),
                linear-gradient(315deg, rgba(245, 158, 11, .06), rgba(255, 255, 255, 0) 35%);
            pointer-events: none;
        }

        .calendar-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            background:
                linear-gradient(135deg, #172033 0%, #0f766e 56%, rgba(245, 158, 11, .94) 135%);
            box-shadow: 0 30px 80px rgba(23, 32, 51, .16);
        }

        .calendar-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .07) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: .35;
        }

        .calendar-hero::after {
            content: "";
            position: absolute;
            inset: auto -80px -120px auto;
            width: 320px;
            height: 320px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .08);
            filter: blur(8px);
        }

        .calendar-hero-copy {
            position: relative;
            z-index: 1;
        }

        .calendar-chip {
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

        .calendar-stat {
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 42px rgba(23, 32, 51, .12);
        }

        .calendar-stat .value {
            color: #fff;
            font-size: 1.9rem;
            line-height: 1;
            font-weight: 800;
        }

        .calendar-stat .label {
            margin-top: .35rem;
            color: rgba(255, 255, 255, .78);
            font-size: .88rem;
        }

        .calendar-stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            background: #fff;
            border: 1px solid #dfe7f1;
            box-shadow: 0 16px 38px rgba(23, 32, 51, .08);
        }

        .calendar-stat-card-top {
            height: .35rem;
            background: linear-gradient(90deg, var(--accent-color), rgba(255, 255, 255, 0));
        }

        .calendar-metric-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .calendar-panel,
        .calendar-side-card {
            background: #fff;
            border: 1px solid #dfe7f1;
            border-radius: 1.25rem;
            box-shadow: 0 18px 42px rgba(23, 32, 51, .07);
            overflow: hidden;
        }

        .calendar-panel-header {
            background: linear-gradient(135deg, rgba(15, 118, 110, .09), rgba(37, 99, 235, .05));
            border-bottom: 1px solid #dfe7f1;
        }

        .calendar-note {
            color: #627084;
            font-size: .95rem;
        }

        .calendar-legend-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem .7rem;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #172033;
            font-size: .78rem;
            font-weight: 800;
        }

        .calendar-dot {
            width: .6rem;
            height: .6rem;
            border-radius: 999px;
        }

        .calendar-help-list {
            margin: 0;
            padding-left: 1.05rem;
            color: #475569;
            display: grid;
            gap: .75rem;
        }

        .calendar-help-list li {
            line-height: 1.6;
        }

        .calendar-calendar .fc {
            font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .calendar-calendar .fc .fc-toolbar {
            margin-bottom: 1rem;
        }

        .calendar-calendar .fc .fc-toolbar-title {
            color: #172033;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .calendar-calendar .fc .fc-button {
            background: #fff;
            border: 1px solid #dfe7f1;
            color: #172033;
            text-transform: none;
            box-shadow: none;
            border-radius: .75rem;
            padding: .45rem .85rem;
            font-weight: 700;
        }

        .calendar-calendar .fc .fc-button:hover {
            background: #f5f7fb;
            border-color: #cbd5e1;
        }

        .calendar-calendar .fc .fc-button-primary:not(:disabled).fc-button-active,
        .calendar-calendar .fc .fc-button-primary:not(:disabled):active {
            background: #0f766e;
            border-color: #0f766e;
            color: #fff;
        }

        .calendar-calendar .fc-theme-standard td,
        .calendar-calendar .fc-theme-standard th {
            border-color: #dfe7f1;
        }

        .calendar-calendar .fc .fc-col-header-cell-cushion {
            color: #627084;
            text-decoration: none;
            font-weight: 800;
            text-transform: uppercase;
            font-size: .78rem;
            letter-spacing: .08em;
        }

        .calendar-calendar .fc .fc-daygrid-day-number {
            color: #172033;
            font-weight: 800;
            text-decoration: none;
        }

        .calendar-calendar .fc .fc-day-other {
            background: rgba(245, 247, 251, .72);
        }

        .calendar-calendar .fc .fc-day-today {
            background: rgba(15, 118, 110, .08);
        }

        .calendar-day-cell {
            position: relative;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .calendar-day-cell:hover {
            transform: translateY(-1px);
            box-shadow: inset 0 0 0 1px rgba(15, 118, 110, .12);
        }

        .calendar-signal-success {
            background: linear-gradient(180deg, rgba(15, 118, 110, .09), rgba(15, 118, 110, .02));
        }

        .calendar-signal-warning {
            background: linear-gradient(180deg, rgba(245, 158, 11, .12), rgba(245, 158, 11, .03));
        }

        .calendar-signal-danger {
            background: linear-gradient(180deg, rgba(239, 68, 68, .13), rgba(239, 68, 68, .04));
        }

        .calendar-day-summary {
            position: absolute;
            right: .45rem;
            bottom: .45rem;
            padding: .2rem .55rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .calendar-summary-success {
            background: #d9f0ed;
            color: #0f766e;
        }

        .calendar-summary-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .calendar-summary-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .calendar-event-wrap {
            padding: .15rem .1rem;
            overflow: hidden;
        }

        .calendar-event-title {
            font-size: .74rem;
            font-weight: 800;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .calendar-event-meta {
            margin-top: .15rem;
            font-size: .64rem;
            line-height: 1.2;
            opacity: .92;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .calendar-event-overdue {
            box-shadow: 0 8px 18px rgba(239, 68, 68, .22) !important;
        }

        .calendar-tooltip {
            position: fixed;
            z-index: 1080;
            display: none;
            width: min(340px, calc(100vw - 24px));
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(23, 32, 51, .96);
            color: #fff;
            box-shadow: 0 24px 60px rgba(23, 32, 51, .22);
            backdrop-filter: blur(16px);
            pointer-events: none;
        }

        .calendar-tooltip .tooltip-label {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .62);
        }

        .calendar-tooltip .tooltip-title {
            margin-top: .2rem;
            font-size: 1.02rem;
            font-weight: 800;
            color: #fff;
        }

        .calendar-tooltip .tooltip-list {
            display: grid;
            gap: .55rem;
            margin-top: .85rem;
        }

        .calendar-tooltip .tooltip-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .6rem .75rem;
            border-radius: .85rem;
            background: rgba(255, 255, 255, .06);
        }

        .calendar-tooltip .tooltip-item strong,
        .calendar-tooltip .tooltip-item span {
            font-size: .88rem;
            font-weight: 700;
        }

        .calendar-tooltip .tooltip-footer {
            margin-top: .8rem;
            font-size: .78rem;
            color: rgba(255, 255, 255, .72);
        }

        @media (max-width: 991px) {
            .calendar-tooltip {
                display: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        $(function () {
            const calendarEvents = @json($calendarEvents);
            const dateSummaries = @json($dateSummaries);
            const eventsByDate = calendarEvents.reduce(function (acc, event) {
                if (!acc[event.start]) {
                    acc[event.start] = [];
                }

                acc[event.start].push(event);

                return acc;
            }, {});
            const tooltip = $('#calendarTooltip');

            const escapeHtml = function (value) {
                return $('<div>').text(value ?? '').html();
            };

            const toDateKey = function (date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            };

            const statusTone = function (signal) {
                if (signal === 'danger') {
                    return {
                        title: 'Delayed day',
                        accent: '#ef4444',
                        soft: '#fee2e2',
                    };
                }

                if (signal === 'warning') {
                    return {
                        title: 'Needs attention',
                        accent: '#f59e0b',
                        soft: '#fef3c7',
                    };
                }

                return {
                    title: 'On track',
                    accent: '#0f766e',
                    soft: '#d9f0ed',
                };
            };

            const positionTooltip = function (event) {
                const margin = 18;
                const width = tooltip.outerWidth() || 320;
                const height = tooltip.outerHeight() || 200;
                let left = event.clientX + margin;
                let top = event.clientY + margin;

                if (left + width > window.innerWidth - margin) {
                    left = event.clientX - width - margin;
                }

                if (top + height > window.innerHeight - margin) {
                    top = event.clientY - height - margin;
                }

                tooltip.css({
                    left: `${Math.max(margin, left)}px`,
                    top: `${Math.max(margin, top)}px`,
                });
            };

            const showTooltip = function (summary, event) {
                const tone = statusTone(summary.signal);

                tooltip.html(`
                    <div class="tooltip-label">${escapeHtml(summary.label)}</div>
                    <div class="tooltip-title">${tone.title}</div>
                    <div class="tooltip-list">
                        <div class="tooltip-item">
                            <strong>To Do</strong>
                            <span>${summary.todo}</span>
                        </div>
                        <div class="tooltip-item">
                            <strong>In Progress</strong>
                            <span>${summary.in_progress}</span>
                        </div>
                        <div class="tooltip-item">
                            <strong>Done</strong>
                            <span>${summary.done}</span>
                        </div>
                    </div>
                    <div class="tooltip-footer">
                        ${summary.open > 0 ? `${summary.open} open task${summary.open === 1 ? '' : 's'} on this date.` : 'No open tasks on this date.'}
                    </div>
                `).show();

                positionTooltip(event);
            };

            const hideTooltip = function () {
                tooltip.hide();
            };

            const renderDateModal = function (summary, dateKey) {
                const dayEvents = eventsByDate[dateKey] || [];
                const tone = statusTone(summary.signal);
                const taskList = dayEvents.length
                    ? dayEvents.map(function (event) {
                        const eventTone = event.backgroundColor || tone.accent;

                        return `
                            <div class="border rounded-4 p-3 mb-2" style="background:#f8fafc;border-color:#e2e8f0!important;">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="fw-bold text-dark">${escapeHtml(event.extendedProps.full_title || event.title)}</div>
                                    <span class="badge text-white" style="background:${eventTone};">${escapeHtml(event.extendedProps.status_label || event.extendedProps.status)}</span>
                                </div>
                                <div class="small text-muted mt-2">
                                    Priority: ${escapeHtml(event.extendedProps.priority_label || event.extendedProps.priority)}
                                </div>
                                <div class="small text-muted">
                                    Assignee: ${escapeHtml(event.extendedProps.assignee || 'Unassigned')}
                                </div>
                                <div class="small text-muted">
                                    Category: ${escapeHtml(event.extendedProps.category || '-')}
                                </div>
                            </div>
                        `;
                    }).join('')
                    : '<div class="text-muted">No visible tasks were scheduled for this date.</div>';

                Swal.fire({
                    title: summary.label,
                    html: `
                        <div class="text-start">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge rounded-pill" style="background:${tone.soft}; color:${tone.accent};">To Do: ${summary.todo}</span>
                                <span class="badge rounded-pill" style="background:#dbeafe; color:#2563eb;">In Progress: ${summary.in_progress}</span>
                                <span class="badge rounded-pill" style="background:#d9f0ed; color:#0f766e;">Done: ${summary.done}</span>
                            </div>
                            <div class="mb-3 small text-muted">
                                ${summary.open > 0 ? `${summary.open} open task${summary.open === 1 ? '' : 's'} are still on this date.` : 'All visible tasks on this date are already done.'}
                            </div>
                            <div class="d-grid gap-2">${taskList}</div>
                        </div>
                    `,
                    width: 760,
                    confirmButtonText: 'Close',
                    confirmButtonColor: tone.accent,
                    customClass: {
                        popup: 'shadow-lg',
                    },
                });
            };

            const renderEventModal = function (event) {
                const tone = event.extendedProps.is_overdue
                    ? {
                        title: 'Overdue task',
                        accent: '#ef4444',
                    }
                    : {
                        title: 'Scheduled task',
                        accent: event.backgroundColor || '#0f766e',
                    };

                Swal.fire({
                    title: event.extendedProps.full_title || event.title,
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <span class="badge rounded-pill me-1" style="background:${event.backgroundColor || tone.accent};">${escapeHtml(event.extendedProps.status_label || event.extendedProps.status)}</span>
                                <span class="badge rounded-pill" style="background:#e8edf4; color:#172033;">${escapeHtml(event.extendedProps.priority_label || event.extendedProps.priority)}</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <div class="small text-uppercase text-muted fw-bold">Due Date</div>
                                        <div class="fw-bold text-dark mt-1">${escapeHtml(event.extendedProps.due_date || '-')}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <div class="small text-uppercase text-muted fw-bold">Category</div>
                                        <div class="fw-bold text-dark mt-1">${escapeHtml(event.extendedProps.category || '-')}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <div class="small text-uppercase text-muted fw-bold">Assignee</div>
                                        <div class="fw-bold text-dark mt-1">${escapeHtml(event.extendedProps.assignee || 'Unassigned')}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <div class="small text-uppercase text-muted fw-bold">Assigned By</div>
                                        <div class="fw-bold text-dark mt-1">${escapeHtml(event.extendedProps.creator || 'System')}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 small text-muted">
                                ${tone.title}
                            </div>
                        </div>
                    `,
                    width: 640,
                    confirmButtonText: 'Close',
                    confirmButtonColor: tone.accent,
                    customClass: {
                        popup: 'shadow-lg',
                    },
                });
            };

            const calendarEl = document.getElementById('taskCalendar');

            if (calendarEl && typeof FullCalendar !== 'undefined') {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    firstDay: 1,
                    fixedWeekCount: false,
                    height: 'auto',
                    expandRows: true,
                    dayMaxEventRows: 3,
                    navLinks: false,
                    nowIndicator: false,
                    events: calendarEvents,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: '',
                    },
                    eventDisplay: 'block',
                    displayEventTime: false,
                    eventContent: function (arg) {
                        return {
                            html: `
                                <div class="calendar-event-wrap">
                                    <div class="calendar-event-title">${escapeHtml(arg.event.extendedProps.full_title || arg.event.title)}</div>
                                    <div class="calendar-event-meta">${escapeHtml(arg.event.extendedProps.status_label || arg.event.extendedProps.status)} · ${escapeHtml(arg.event.extendedProps.assignee || 'Unassigned')}</div>
                                </div>
                            `,
                        };
                    },
                    eventDidMount: function (arg) {
                        arg.el.title = `${arg.event.extendedProps.full_title || arg.event.title} | ${arg.event.extendedProps.status_label || arg.event.extendedProps.status}`;
                    },
                    eventClick: function (info) {
                        info.jsEvent.preventDefault();
                        info.jsEvent.stopPropagation();
                        renderEventModal(info.event);
                    },
                    dayCellDidMount: function (arg) {
                        const dateKey = toDateKey(arg.date);
                        const summary = dateSummaries[dateKey];

                        if (!summary) {
                            return;
                        }

                        arg.el.classList.add('calendar-day-cell', `calendar-signal-${summary.signal}`);
                        arg.el.dataset.calendarDate = dateKey;

                        const frame = arg.el.querySelector('.fc-daygrid-day-frame');
                        if (frame && !frame.querySelector('.calendar-day-summary')) {
                            const summaryBadge = document.createElement('span');
                            summaryBadge.className = `calendar-day-summary calendar-summary-${summary.signal}`;
                            summaryBadge.textContent = `${summary.total} task${summary.total === 1 ? '' : 's'}`;
                            frame.appendChild(summaryBadge);
                        }

                        arg.el.addEventListener('mouseenter', function (event) {
                            showTooltip(summary, event);
                        });

                        arg.el.addEventListener('mousemove', function (event) {
                            if (tooltip.is(':visible')) {
                                positionTooltip(event);
                            }
                        });

                        arg.el.addEventListener('mouseleave', hideTooltip);

                        arg.el.addEventListener('click', function (event) {
                            if (event.target.closest('.fc-event')) {
                                return;
                            }

                            renderDateModal(summary, dateKey);
                        });
                    },
                    datesSet: function () {
                        hideTooltip();
                    },
                });

                calendar.render();
            }
        });
    </script>
@endpush
