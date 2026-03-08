<section>
    <header>
        <h2 class="text-base font-semibold text-slate-900">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <x-input-label for="update_password_current_password" :value="__('Current Password')"
                    class="text-sm font-medium text-slate-700" />
                <x-text-input id="update_password_current_password" name="current_password" type="password"
                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-[#121293] focus:ring-[#121293]"
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')"
                    class="text-sm font-medium text-slate-700" />
                <x-text-input id="update_password_password" name="password" type="password"
                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-[#121293] focus:ring-[#121293]"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')"
                    class="text-sm font-medium text-slate-700" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-[#121293] focus:ring-[#121293]"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm" />
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90 shadow-sm">
                Save Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium">
                    Password updated.
                </p>
            @endif
        </div>
    </form>
</section>
