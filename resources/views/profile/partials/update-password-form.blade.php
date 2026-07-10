<section>
    <header class="flex items-start gap-4">
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
            <i data-feather="lock" class="h-6 w-6" aria-hidden="true"></i>
        </span>
        <div>
            <h2 class="text-2xl font-extrabold leading-tight text-slate-950">
                {{ __('Update Password') }}
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                {{ __('Keep your account secure with a strong password that is not reused elsewhere.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-slate-800">{{ __('Current Password') }}</label>
            <div class="relative mt-2">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-feather="shield" class="h-5 w-5" aria-hidden="true"></i>
                </span>
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                    autocomplete="current-password"
                >
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="update_password_password" class="block text-sm font-bold text-slate-800">{{ __('New Password') }}</label>
                <div class="relative mt-2">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-feather="key" class="h-5 w-5" aria-hidden="true"></i>
                    </span>
                    <input
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                        autocomplete="new-password"
                    >
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-bold text-slate-800">{{ __('Confirm Password') }}</label>
                <div class="relative mt-2">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-feather="check-circle" class="h-5 w-5" aria-hidden="true"></i>
                    </span>
                    <input
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                        autocomplete="new-password"
                    >
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <button
                type="submit"
                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-slate-950 px-6 py-3 text-base font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2"
            >
                <i data-feather="lock" class="h-5 w-5" aria-hidden="true"></i>
                <span>{{ __('Update Password') }}</span>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-bold text-teal-800"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
