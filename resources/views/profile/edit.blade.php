@php
    $productName = config('app.name', 'Task Management');
    $productName = $productName === 'Laravel' ? 'Task Management' : $productName;
@endphp

<x-app-layout>
    <style>
        .profile-surface-grid {
            background-image:
                linear-gradient(rgba(37, 99, 235, .07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 118, 110, .07) 1px, transparent 1px);
            background-size: 44px 44px;
        }

        .profile-hero-wash {
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .11), rgba(255, 255, 255, 0) 42%),
                linear-gradient(315deg, rgba(245, 158, 11, .12), rgba(255, 255, 255, 0) 38%);
        }

        .profile-panel-shadow {
            box-shadow: 0 26px 80px rgba(23, 32, 51, .14);
        }
    </style>

    <div class="profile-surface-grid relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 profile-hero-wash"></div>

        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[.82fr_1.18fr] lg:items-start lg:px-8">
            <aside class="lg:sticky lg:top-24">
                <div class="profile-panel-shadow overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <div class="bg-slate-950 px-6 py-7 text-white">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-teal-700 text-xl font-extrabold text-white shadow-lg shadow-teal-700/20">
                                {{ strtoupper(\Illuminate\Support\Str::substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-300">{{ $productName }} account</div>
                                <h1 class="truncate text-2xl font-extrabold leading-tight">{{ $user->name }}</h1>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <p class="text-sm leading-6 text-slate-600">
                            Keep your identity and security details current so your workspace, task assignments, reminders, and activity history stay connected to the right account.
                        </p>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-50 text-teal-700">
                                    <i data-feather="mail" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold uppercase text-slate-500">Email</div>
                                    <div class="truncate text-sm font-bold text-slate-950">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                                    <i data-feather="shield" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <div class="text-xs font-bold uppercase text-slate-500">Security</div>
                                    <div class="text-sm font-bold text-slate-950">Password protected</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                    <i data-feather="activity" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <div class="text-xs font-bold uppercase text-slate-500">Workspace</div>
                                    <div class="text-sm font-bold text-slate-950">Ready for daily task flow</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="space-y-6">
                <div class="profile-panel-shadow rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="profile-panel-shadow rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- <div class="profile-panel-shadow rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div> --}}
            </main>
        </div>
    </div>
</x-app-layout>
