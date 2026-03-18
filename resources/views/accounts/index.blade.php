<x-app-layout>
    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 p-4">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="font-semibold text-slate-800">All Accounts</div>
                            <div class="text-sm text-slate-500">Kelola semua akun user dalam sistem.</div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <form method="GET" action="{{ route('accounts.index') }}" class="w-full sm:w-auto">
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Search nama / email / NIK / phone..."
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">

                                    <select name="role"
                                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
                                        <option value="">Semua Role</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->slug }}" @selected(request('role') === $item->slug)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="status"
                                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
                                        <option value="">Semua Status</option>
                                        <option value="1" @selected(request('status') === '1')>Aktif</option>
                                        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
                                    </select>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                            class="rounded-xl bg-[#121293] px-4 py-2.5 text-sm text-white hover:opacity-90">
                                            Search
                                        </button>

                                        @if (request('q') || request('role') || request('status') !== null)
                                            <a href="{{ route('accounts.index') }}"
                                                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
                                                Clear
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <a href="{{ route('accounts.create') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                                Tambah Account
                            </a>
                        </div>
                    </div>

                    @if (request('q'))
                        <div class="mt-2 text-xs text-slate-500">
                            Menampilkan hasil untuk:
                            <span class="font-medium text-slate-700">"{{ request('q') }}"</span>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Role</th>
                                <th class="px-4 py-3 text-left">Phone</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse ($accounts as $account)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800">{{ $account->name }}</div>
                                        <div class="text-xs text-slate-500">
                                            NIK: {{ $account->nik ?: '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $account->email }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-2 py-1 text-xs text-slate-700">
                                            {{ $account->role?->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $account->phone ?: '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        @if ($account->is_active)
                                            <span
                                                class="inline-flex rounded-full border border-green-200 bg-green-50 px-2 py-1 text-xs text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full border border-red-200 bg-red-50 px-2 py-1 text-xs text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('accounts.edit', $account) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('accounts.destroy', $account) }}"
                                                class="js-delete-account">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100"
                                                    data-name="{{ $account->name }}">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center text-slate-600">
                                        Tidak ada account ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.js-delete-account button[type="button"]');
            if (!btn) return;

            const form = btn.closest('form');
            const name = btn.dataset.name || 'account ini';

            const result = await Swal.fire({
                title: 'Hapus account?',
                html: `Account <b>${name}</b> akan dihapus permanen.<br/>Lanjut?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });
    </script>
</x-app-layout>
