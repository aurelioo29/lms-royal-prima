<x-app-layout>
    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <div class="mb-6">
                    <h1 class="text-xl font-semibold text-slate-800">Tambah Account</h1>
                    <p class="mt-1 text-sm text-slate-500">Buat akun baru untuk user.</p>
                </div>

                <form method="POST" action="{{ route('accounts.store') }}" class="space-y-6">
                    @include('accounts._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
