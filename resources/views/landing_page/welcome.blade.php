@php
    $productName = config('app.name', 'Task Management');
    $productName = $productName === 'Laravel' ? 'Task Management' : $productName;
    $loginUrl = Route::has('login') ? route('login') : '#';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $productName }} keeps teams aligned with task boards, role-based access, exports, reminders, attachments, comments, and activity tracking.">

    <title>{{ $productName }} | Organized Task Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #172033;
            --muted: #627084;
            --line: #dfe7f1;
            --panel: #ffffff;
            --soft: #f5f7fb;
            --teal: #0f766e;
            --blue: #2563eb;
            --amber: #f59e0b;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--soft);
            color: var(--ink);
            font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .surface-grid {
            background-image:
                linear-gradient(rgba(37, 99, 235, .07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 118, 110, .07) 1px, transparent 1px);
            background-size: 44px 44px;
        }

        .hero-wash {
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .11), rgba(255, 255, 255, 0) 42%),
                linear-gradient(315deg, rgba(245, 158, 11, .12), rgba(255, 255, 255, 0) 38%);
        }

        .glass-line {
            background: rgba(255, 255, 255, .78);
            backdrop-filter: blur(18px);
        }

        .task-preview-shadow {
            box-shadow: 0 26px 80px rgba(23, 32, 51, .14);
        }

        .status-dot {
            width: .65rem;
            height: .65rem;
            border-radius: 999px;
            display: inline-block;
        }

        .hero-shell {
            min-height: calc(100vh - 34px);
        }

        @media (max-width: 767px) {
            .hero-shell {
                min-height: auto;
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen overflow-hidden">
        <header class="fixed inset-x-0 top-0 z-40 border-b border-white/70 glass-line">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="Main navigation">
                <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="{{ $productName }} home">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-bold text-white shadow-sm">
                        TM
                    </span>
                    <span class="text-base font-bold text-slate-950 sm:text-lg">{{ $productName }}</span>
                </a>

                <div class="hidden items-center gap-8 text-sm font-semibold text-slate-600 md:flex">
                    <a href="#platform" class="transition hover:text-slate-950">Platform</a>
                    <a href="#workflow" class="transition hover:text-slate-950">Workflow</a>
                    <a href="#control" class="transition hover:text-slate-950">Control</a>
                </div>

                <a
                    href="{{ $loginUrl }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2"
                >
                    <i data-feather="log-in" class="h-4 w-4" aria-hidden="true"></i>
                    <span>Login</span>
                </a>
            </nav>
        </header>

        <main>
            <section class="hero-shell surface-grid relative flex items-center px-4 pb-14 pt-28 sm:px-6 lg:px-8">
                <div class="absolute inset-0 hero-wash"></div>
                <div class="relative mx-auto grid w-full max-w-7xl gap-10 lg:grid-cols-[.9fr_1.1fr] lg:items-center">
                    <div class="max-w-2xl">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-md border border-teal-200 bg-white px-3 py-2 text-sm font-semibold text-teal-800 shadow-sm">
                            <span class="status-dot bg-teal-500"></span>
                            Built for accountable team execution
                        </div>

                        <h1 class="text-4xl font-extrabold leading-tight text-slate-950 sm:text-5xl lg:text-6xl">
                            Manage tasks, teams, and deadlines from one elegant workspace.
                        </h1>

                        <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                            {{ $productName }} brings task boards, table views, user roles, assignment mail, files, comments, activity history, and exports into a focused system your team can trust every day.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="{{ $loginUrl }}"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-teal-700 px-6 py-3 text-base font-bold text-white shadow-lg shadow-teal-700/20 transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2"
                            >
                                <i data-feather="log-in" class="h-5 w-5" aria-hidden="true"></i>
                                <span>Login to Workspace</span>
                            </a>
                            <a
                                href="#platform"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-3 text-base font-bold text-slate-800 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2"
                            >
                                <i data-feather="layout" class="h-5 w-5" aria-hidden="true"></i>
                                <span>Explore Platform</span>
                            </a>
                        </div>

                        <div class="mt-10 grid max-w-xl grid-cols-3 gap-3">
                            <div class="rounded-lg border border-white bg-white/80 p-4 shadow-sm">
                                <div class="text-2xl font-extrabold text-slate-950">4</div>
                                <div class="mt-1 text-sm font-medium text-slate-600">Task states</div>
                            </div>
                            <div class="rounded-lg border border-white bg-white/80 p-4 shadow-sm">
                                <div class="text-2xl font-extrabold text-slate-950">3</div>
                                <div class="mt-1 text-sm font-medium text-slate-600">Access roles</div>
                            </div>
                            <div class="rounded-lg border border-white bg-white/80 p-4 shadow-sm">
                                <div class="text-2xl font-extrabold text-slate-950">360</div>
                                <div class="mt-1 text-sm font-medium text-slate-600">Task context</div>
                            </div>
                        </div>
                    </div>

                    <div class="task-preview-shadow overflow-hidden rounded-lg border border-slate-200 bg-white">
                        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-950 px-4 py-3 text-white">
                            <div class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                                <span class="h-3 w-3 rounded-full bg-amber"></span>
                                <span class="h-3 w-3 rounded-full bg-teal-400"></span>
                            </div>
                            <div class="hidden text-sm font-semibold text-slate-300 sm:block">Task Board Overview</div>
                            <div class="h-8 w-8 rounded-md bg-white/10"></div>
                        </div>

                        <div class="grid bg-white lg:grid-cols-[190px_1fr]">
                            <aside class="hidden border-r border-slate-200 bg-slate-50 p-4 lg:block">
                                <div class="mb-5 h-9 rounded-md bg-slate-900"></div>
                                <div class="space-y-2">
                                    <div class="rounded-md bg-white px-3 py-2 text-sm font-bold text-blue-700 shadow-sm">Dashboard</div>
                                    <div class="rounded-md px-3 py-2 text-sm font-semibold text-slate-500">Send Mail</div>
                                    <div class="rounded-md px-3 py-2 text-sm font-semibold text-slate-500">Export Tasks</div>
                                    <div class="rounded-md bg-teal-50 px-3 py-2 text-sm font-bold text-teal-800">Task Board</div>
                                    <div class="rounded-md px-3 py-2 text-sm font-semibold text-slate-500">Users</div>
                                </div>
                            </aside>

                            <div class="p-4 sm:p-5">
                                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <div class="h-4 w-28 rounded-md bg-slate-200"></div>
                                        <div class="mt-3 h-8 w-48 rounded-md bg-slate-900"></div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-9 w-24 rounded-md border border-slate-200 bg-white"></div>
                                        <div class="h-9 w-28 rounded-md bg-teal-700"></div>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="text-sm font-bold text-slate-700">Backlog</span>
                                            <span class="rounded-md bg-slate-200 px-2 py-1 text-xs font-bold text-slate-700">6</span>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="rounded-md border border-slate-200 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-24 rounded bg-slate-800"></div>
                                                <div class="mt-3 h-2 w-full rounded bg-slate-200"></div>
                                                <div class="mt-2 h-2 w-3/4 rounded bg-slate-200"></div>
                                                <div class="mt-3 flex gap-2">
                                                    <span class="h-5 w-14 rounded-md bg-blue-100"></span>
                                                    <span class="h-5 w-12 rounded-md bg-amber-100"></span>
                                                </div>
                                            </div>
                                            <div class="rounded-md border border-slate-200 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-28 rounded bg-slate-700"></div>
                                                <div class="mt-3 h-2 w-5/6 rounded bg-slate-200"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-lg border border-blue-100 bg-blue-50 p-3">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="text-sm font-bold text-blue-900">To Do</span>
                                            <span class="rounded-md bg-blue-200 px-2 py-1 text-xs font-bold text-blue-900">8</span>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="rounded-md border border-blue-100 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-32 rounded bg-slate-800"></div>
                                                <div class="mt-3 h-2 w-full rounded bg-slate-200"></div>
                                                <div class="mt-3 flex items-center justify-between">
                                                    <span class="h-5 w-16 rounded-md bg-teal-100"></span>
                                                    <span class="h-7 w-7 rounded-full bg-blue-600"></span>
                                                </div>
                                            </div>
                                            <div class="rounded-md border border-blue-100 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-20 rounded bg-slate-700"></div>
                                                <div class="mt-3 h-2 w-4/5 rounded bg-slate-200"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-3">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="text-sm font-bold text-amber-900">In Progress</span>
                                            <span class="rounded-md bg-amber-200 px-2 py-1 text-xs font-bold text-amber-900">4</span>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="rounded-md border border-amber-100 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-28 rounded bg-slate-800"></div>
                                                <div class="mt-3 h-2 w-full rounded bg-slate-200"></div>
                                                <div class="mt-2 h-2 w-2/3 rounded bg-slate-200"></div>
                                                <div class="mt-3 h-2 rounded bg-amber-400"></div>
                                            </div>
                                            <div class="rounded-md border border-amber-100 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-24 rounded bg-slate-700"></div>
                                                <div class="mt-3 flex gap-2">
                                                    <span class="h-5 w-12 rounded-md bg-red-100"></span>
                                                    <span class="h-5 w-14 rounded-md bg-blue-100"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-lg border border-teal-100 bg-teal-50 p-3">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="text-sm font-bold text-teal-900">Done</span>
                                            <span class="rounded-md bg-teal-200 px-2 py-1 text-xs font-bold text-teal-900">12</span>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="rounded-md border border-teal-100 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-24 rounded bg-slate-800"></div>
                                                <div class="mt-3 h-2 w-full rounded bg-slate-200"></div>
                                                <div class="mt-3 flex items-center justify-between">
                                                    <span class="h-5 w-16 rounded-md bg-teal-100"></span>
                                                    <i data-feather="check-circle" class="h-6 w-6 text-teal-700" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="rounded-md border border-teal-100 bg-white p-3 shadow-sm">
                                                <div class="h-3 w-20 rounded bg-slate-700"></div>
                                                <div class="mt-3 h-2 w-5/6 rounded bg-slate-200"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-lg border border-slate-200 bg-white p-4">
                                        <div class="text-sm font-semibold text-slate-500">Completion</div>
                                        <div class="mt-2 text-2xl font-extrabold text-slate-950">74%</div>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-4">
                                        <div class="text-sm font-semibold text-slate-500">Due soon</div>
                                        <div class="mt-2 text-2xl font-extrabold text-amber-700">9</div>
                                    </div>
                                    <div class="rounded-lg border border-slate-200 bg-white p-4">
                                        <div class="text-sm font-semibold text-slate-500">Overdue</div>
                                        <div class="mt-2 text-2xl font-extrabold text-red-600">3</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="platform" class="bg-white px-4 py-20 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div class="max-w-3xl">
                        <p class="text-sm font-bold uppercase text-teal-700">Platform</p>
                        <h2 class="mt-3 text-3xl font-extrabold leading-tight text-slate-950 sm:text-4xl">
                            Everything your team needs to move work from request to result.
                        </h2>
                        <p class="mt-4 text-lg leading-8 text-slate-600">
                            Designed around the system already inside your project, the landing page highlights the operational pieces that make the dashboard useful for admins, team members, and viewers.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-teal-50 text-teal-700">
                                <i data-feather="columns" class="h-5 w-5" aria-hidden="true"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-950">Kanban task board</h3>
                            <p class="mt-3 leading-7 text-slate-600">Backlog, To Do, In Progress, and Done columns keep priorities visible and movement simple.</p>
                        </article>

                        <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                                <i data-feather="table" class="h-5 w-5" aria-hidden="true"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-950">Responsive table view</h3>
                            <p class="mt-3 leading-7 text-slate-600">Scan tasks by category, status, priority, assignee, due date, and labels in a clean data view.</p>
                        </article>

                        <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                <i data-feather="paperclip" class="h-5 w-5" aria-hidden="true"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-950">Complete task context</h3>
                            <p class="mt-3 leading-7 text-slate-600">Files, labels, comments, assignment details, time estimates, and activity logs stay attached to the task.</p>
                        </article>

                        <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                <i data-feather="mail" class="h-5 w-5" aria-hidden="true"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-950">Built-in mail actions</h3>
                            <p class="mt-3 leading-7 text-slate-600">Assignment emails, delayed-task reminders, and custom messages support timely follow-up.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section id="workflow" class="bg-slate-950 px-4 py-20 text-white sm:px-6 lg:px-8">
                <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[.95fr_1.05fr] lg:items-center">
                    <div>
                        <p class="text-sm font-bold uppercase text-teal-300">Workflow</p>
                        <h2 class="mt-3 text-3xl font-extrabold leading-tight sm:text-4xl">
                            A clear operating rhythm for daily task execution.
                        </h2>
                        <p class="mt-4 text-lg leading-8 text-slate-300">
                            Plan the work, assign ownership, monitor delivery, and export progress without forcing your team to jump between disconnected tools.
                        </p>

                        <div class="mt-8 space-y-4">
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-teal-500 text-sm font-extrabold text-white">1</div>
                                <div>
                                    <h3 class="font-bold">Create and categorize</h3>
                                    <p class="mt-1 leading-7 text-slate-300">Add tasks with category, priority, due date, assignee, labels, estimated time, and attachments.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500 text-sm font-extrabold text-white">2</div>
                                <div>
                                    <h3 class="font-bold">Move work across stages</h3>
                                    <p class="mt-1 leading-7 text-slate-300">Use board movement and table controls to keep status accurate from backlog through completion.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-500 text-sm font-extrabold text-white">3</div>
                                <div>
                                    <h3 class="font-bold">Review and communicate</h3>
                                    <p class="mt-1 leading-7 text-slate-300">Track comments, activity, overdue items, assignment notifications, and delayed-task mail in one place.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-white/10 bg-white text-slate-950 shadow-2xl">
                        <div class="border-b border-slate-200 bg-slate-50 p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-sm font-bold text-slate-500">Today’s Operations</div>
                                    <div class="mt-1 text-2xl font-extrabold">Delivery Health</div>
                                </div>
                                <div class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-bold text-white">Export Ready</div>
                            </div>
                        </div>
                        <div class="grid gap-0 md:grid-cols-2">
                            <div class="border-b border-slate-200 p-5 md:border-b-0 md:border-r">
                                <div class="mb-4 flex items-center justify-between">
                                    <span class="font-bold">Recent activity</span>
                                    <i data-feather="activity" class="h-5 w-5 text-teal-700" aria-hidden="true"></i>
                                </div>
                                <div class="space-y-4">
                                    <div class="border-l-4 border-teal-500 pl-4">
                                        <div class="font-bold">Task moved</div>
                                        <div class="text-sm leading-6 text-slate-600">Website launch checklist moved to In Progress.</div>
                                    </div>
                                    <div class="border-l-4 border-blue-500 pl-4">
                                        <div class="font-bold">Comment added</div>
                                        <div class="text-sm leading-6 text-slate-600">Design review notes attached to the task thread.</div>
                                    </div>
                                    <div class="border-l-4 border-amber-500 pl-4">
                                        <div class="font-bold">Reminder queued</div>
                                        <div class="text-sm leading-6 text-slate-600">Delayed task email ready for the assigned user.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="mb-4 flex items-center justify-between">
                                    <span class="font-bold">Task export</span>
                                    <i data-feather="download" class="h-5 w-5 text-blue-700" aria-hidden="true"></i>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                                        <span class="text-sm font-semibold text-slate-600">Open tasks</span>
                                        <span class="font-extrabold">27</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                                        <span class="text-sm font-semibold text-slate-600">Archived</span>
                                        <span class="font-extrabold">14</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                                        <span class="text-sm font-semibold text-slate-600">Overdue</span>
                                        <span class="font-extrabold text-red-600">3</span>
                                    </div>
                                    <div class="mt-5 h-3 overflow-hidden rounded-md bg-slate-100">
                                        <div class="h-full w-3/4 rounded-md bg-teal-600"></div>
                                    </div>
                                    <div class="text-sm font-semibold text-slate-600">74% completion rate</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="control" class="bg-white px-4 py-20 sm:px-6 lg:px-8">
                <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[1fr_.9fr] lg:items-center">
                    <div class="order-2 lg:order-1">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
                                <i data-feather="shield" class="h-7 w-7 text-teal-700" aria-hidden="true"></i>
                                <h3 class="mt-4 text-lg font-bold text-slate-950">Admin control</h3>
                                <p class="mt-3 leading-7 text-slate-600">Manage users, roles, active or inactive access, categories, task creation, archive actions, and mail settings.</p>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
                                <i data-feather="user-check" class="h-7 w-7 text-blue-700" aria-hidden="true"></i>
                                <h3 class="mt-4 text-lg font-bold text-slate-950">Team focus</h3>
                                <p class="mt-3 leading-7 text-slate-600">Team members can work from assigned tasks and keep statuses updated without extra admin noise.</p>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
                                <i data-feather="eye" class="h-7 w-7 text-amber-700" aria-hidden="true"></i>
                                <h3 class="mt-4 text-lg font-bold text-slate-950">Viewer clarity</h3>
                                <p class="mt-3 leading-7 text-slate-600">Viewer access supports transparent tracking for people who need status insight without changing work.</p>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
                                <i data-feather="archive" class="h-7 w-7 text-red-600" aria-hidden="true"></i>
                                <h3 class="mt-4 text-lg font-bold text-slate-950">Clean history</h3>
                                <p class="mt-3 leading-7 text-slate-600">Archive, duplicate, restore, and review tasks while preserving useful operational history.</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-1 lg:order-2">
                        <p class="text-sm font-bold uppercase text-teal-700">Control</p>
                        <h2 class="mt-3 text-3xl font-extrabold leading-tight text-slate-950 sm:text-4xl">
                            Professional governance without slowing the work down.
                        </h2>
                        <p class="mt-4 text-lg leading-8 text-slate-600">
                            The experience is built around role-aware access, account status, mail controls, and clear task ownership, so operations stay organized as the team grows.
                        </p>
                        <div class="mt-8 rounded-lg border border-slate-200 bg-slate-50 p-5">
                            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                                <img
                                    src="{{ asset('assets/img/illustrations/data-report.svg') }}"
                                    alt="Task reporting illustration"
                                    class="h-24 w-24 shrink-0"
                                >
                                <div>
                                    <h3 class="text-lg font-bold text-slate-950">Built for reporting and follow-through</h3>
                                    <p class="mt-2 leading-7 text-slate-600">Use dashboard metrics, exports, and task activity to understand what is moving, what is late, and who needs support.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-slate-100 px-4 py-16 sm:px-6 lg:px-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-8 rounded-lg border border-slate-200 bg-white p-8 shadow-sm md:flex-row md:items-center md:justify-between">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-extrabold leading-tight text-slate-950">
                            Ready to get back into your workspace?
                        </h2>
                        <p class="mt-3 text-lg leading-8 text-slate-600">
                            Sign in to manage tasks, review assignments, send reminders, and keep team execution visible.
                        </p>
                    </div>
                    <a
                        href="{{ $loginUrl }}"
                        class="inline-flex min-h-12 shrink-0 items-center justify-center gap-2 rounded-lg bg-slate-950 px-6 py-3 text-base font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2"
                    >
                        <i data-feather="log-in" class="h-5 w-5" aria-hidden="true"></i>
                        <span>Login</span>
                    </a>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 bg-white px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <div class="font-semibold">&copy; {{ date('Y') }} {{ $productName }}. All rights reserved.</div>
                <div class="flex flex-wrap gap-4">
                    <span>Task Board</span>
                    <span>Mail Center</span>
                    <span>Role Access</span>
                    <span>Exports</span>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    <script>
        if (window.feather) {
            window.feather.replace();
        }
    </script>
</body>
</html>
