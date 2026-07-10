<section>
    <header class="flex items-start gap-4">
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-teal-700">
            <i data-feather="user" class="h-6 w-6" aria-hidden="true"></i>
        </span>
        <div>
            <h2 class="text-2xl font-extrabold leading-tight text-slate-950">
                {{ __('Profile Information') }}
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-slate-800">{{ __('Name') }}</label>
            <div class="relative mt-2">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-feather="user" class="h-5 w-5" aria-hidden="true"></i>
                </span>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-slate-800">{{ __('Email') }}</label>
            <div class="relative mt-2">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-feather="mail" class="h-5 w-5" aria-hidden="true"></i>
                </span>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="block min-h-12 w-full rounded-lg border-slate-300 bg-white pl-11 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-teal-600 focus:ring-teal-600"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                >
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
                    <p class="text-sm leading-6 text-amber-900">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="font-bold text-amber-900 underline underline-offset-4 transition hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-bold text-teal-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <button
                type="submit"
                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-teal-700 px-6 py-3 text-base font-bold text-white shadow-lg shadow-teal-700/20 transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2"
            >
                <i data-feather="save" class="h-5 w-5" aria-hidden="true"></i>
                <span>{{ __('Save Profile') }}</span>
            </button>

            @if (session('status') === 'profile-updated')
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
