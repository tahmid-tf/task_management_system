@php
    $productName = config('app.name', 'Task Management');
    $productName = $productName === 'Laravel' ? 'Task Management' : $productName;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | {{ $productName }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #172033;
            --soft: #f5f7fb;
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

        .panel-shadow {
            box-shadow: 0 26px 80px rgba(23, 32, 51, .14);
        }

        .status-dot {
            width: .65rem;
            height: .65rem;
            border-radius: 999px;
            display: inline-block;
        }
    </style>
</head>
<body class="antialiased">
    <div class="surface-grid relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 hero-wash"></div>

        <header class="relative z-10 border-b border-white/70 glass-line">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="Login navigation">
                <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="{{ $productName }} home">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-bold text-white shadow-sm">
                        TM
                    </span>
                    <span class="text-base font-bold text-slate-950 sm:text-lg">{{ $productName }}</span>
                </a>

                <a
                    href="{{ url('/') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-800 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2"
                >
                    <i data-feather="arrow-left" class="h-4 w-4" aria-hidden="true"></i>
                    <span>Home</span>
                </a>
            </nav>
        </header>

        <main class="relative z-10 mx-auto grid min-h-[calc(100vh-68px)] max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_.86fr] lg:items-center lg:px-8">
            <section class="hidden max-w-2xl lg:block">
                <div class="mb-6 inline-flex items-center gap-2 rounded-md border border-teal-200 bg-white px-3 py-2 text-sm font-semibold text-teal-800 shadow-sm">
                    <span class="status-dot bg-teal-500"></span>
                    Secure workspace access
                </div>

                <h1 class="text-5xl font-extrabold leading-tight text-slate-950">
                    Welcome back to your task command center.
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                    Sign in to review dashboard metrics, manage task boards, track assignments, send reminders, export work, and keep your team aligned.
                </p>

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

                <div class="panel-shadow mt-10 overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-950 px-4 py-3 text-white">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-red-500"></span>
                            <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                            <span class="h-3 w-3 rounded-full bg-teal-400"></span>
                        </div>
                        <div class="text-sm font-semibold text-slate-300">Workspace Preview</div>
                    </div>

                    <div class="grid gap-4 p-5 sm:grid-cols-3">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-600">Open</span>
                                <i data-feather="layers" class="h-5 w-5 text-teal-700" aria-hidden="true"></i>
                            </div>
                            <div class="mt-4 text-3xl font-extrabold text-slate-950">27</div>
                            <div class="mt-2 h-2 rounded-md bg-slate-200">
                                <div class="h-full w-2/3 rounded-md bg-teal-600"></div>
                            </div>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-600">Due Soon</span>
                                <i data-feather="clock" class="h-5 w-5 text-amber-700" aria-hidden="true"></i>
                            </div>
                            <div class="mt-4 text-3xl font-extrabold text-amber-700">9</div>
                            <div class="mt-2 h-2 rounded-md bg-slate-200">
                                <div class="h-full w-1/2 rounded-md bg-amber-500"></div>
                            </div>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-600">Complete</span>
                                <i data-feather="check-circle" class="h-5 w-5 text-blue-700" aria-hidden="true"></i>
                            </div>
                            <div class="mt-4 text-3xl font-extrabold text-blue-700">74%</div>
                            <div class="mt-2 h-2 rounded-md bg-slate-200">
                                <div class="h-full w-3/4 rounded-md bg-blue-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="panel-shadow mx-auto w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
                <div class="mb-8">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-950 text-sm font-bold text-white shadow-sm">
                        TM
                    </div>
                    <h2 class="mt-5 text-3xl font-extrabold text-slate-950">Login</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Enter your credentials to continue to the dashboard.
                    </p>
                </div>

                <x-auth-session-status class="mb-4 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-semibold text-teal-800" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-800">Email address</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i data-feather="mail" class="h-5 w-5" aria-hidden="true"></i>
                            </span>
                            <input
                                id="email"
                                class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                required
                                autofocus
                                autocomplete="username"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label for="password" class="block text-sm font-bold text-slate-800">Password</label>
                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm font-bold text-teal-700 transition hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2"
                                >
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i data-feather="lock" class="h-5 w-5" aria-hidden="true"></i>
                            </span>
                            <input
                                id="password"
                                class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                                type="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label for="remember_me" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-slate-300 text-teal-700 shadow-sm focus:ring-teal-600"
                            name="remember"
                        >
                        <span class="text-sm font-semibold text-slate-700">Remember me on this device</span>
                    </label>

                    <button
                        type="submit"
                        class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-teal-700 px-6 py-3 text-base font-bold text-white shadow-lg shadow-teal-700/20 transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2"
                    >
                        <i data-feather="log-in" class="h-5 w-5" aria-hidden="true"></i>
                        <span>Login to Workspace</span>
                    </button>
                </form>
            </section>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    <script>
        if (window.feather) {
            window.feather.replace();
        }
    </script>
</body>
</html>
