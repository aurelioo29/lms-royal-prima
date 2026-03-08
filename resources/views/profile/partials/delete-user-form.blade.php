<section class="space-y-6">
    <header>
        <h2 class="text-base font-semibold text-slate-900">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            Once your account is deleted, all of its resources and data will be permanently deleted.
            Please make sure you really want to do this.
        </p>
    </header>

    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
        <div class="text-sm text-red-700">
            Tindakan ini tidak bisa dibatalkan. Semua data akun akan dihapus permanen.
        </div>
    </div>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 shadow-sm">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-2 text-sm text-slate-600">
                Once your account is deleted, all of its resources and data will be permanently deleted.
                Please enter your password to confirm.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-sm font-medium text-slate-700" />

                <x-text-input id="password" name="password" type="password"
                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-sm" />
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 shadow-sm">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
