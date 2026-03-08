<section>
    <header>
        <h2 class="text-base font-semibold text-slate-900">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-slate-700" />
                <x-text-input id="name" name="name" type="text"
                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-[#121293] focus:ring-[#121293]"
                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2 text-sm" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-700" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-[#121293] focus:ring-[#121293]"
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2 text-sm" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm text-amber-800">
                            Your email address is unverified.
                        </p>

                        <button form="send-verification"
                            class="mt-2 inline-flex items-center rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm font-medium text-amber-700 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-400">
                            Click here to re-send the verification email
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-3 text-sm font-medium text-green-600">
                                A new verification link has been sent to your email address.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90 shadow-sm">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium">
                    Profile updated.
                </p>
            @endif
        </div>
    </form>
</section>
