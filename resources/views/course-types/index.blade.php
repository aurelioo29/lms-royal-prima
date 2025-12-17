<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-slate-900">Course Types</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Master data untuk dropdown Course. Slug dibuat otomatis & unik.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                        <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:items-center">
                            <div class="relative">
                                <input name="q" value="{{ $q }}" placeholder="Cari name..."
                                    class="w-full sm:w-64 rounded-xl border border-slate-200 px-3 py-2 text-sm pr-9 focus:outline-none focus:ring-2 focus:ring-[#121293]/25">
                                <div
                                    class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill="currentColor"
                                            d="M10 18a8 8 0 1 1 5.293-14.293A8 8 0 0 1 10 18Zm11 3l-6-6l1.414-1.414l6 6L21 21Z" />
                                    </svg>
                                </div>
                            </div>

                            <select name="status"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/25">
                                <option value="">All</option>
                                <option value="active" @selected($status === 'active')>Active</option>
                                <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                            </select>

                            <button
                                class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Filter
                            </button>
                        </form>

                        <a href="{{ route('course-types.create') }}"
                            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            + New Type
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Name</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($types as $t)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $t->name }}</div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold
                                            {{ $t->is_active ? 'bg-green-50 text-green-800 border-green-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            <span class="h-2 w-2 rounded-full bg-current opacity-60"></span>
                                            {{ $t->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('course-types.edit', $t) }}"
                                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('course-types.toggle', $t) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                {{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-slate-600">
                                        Belum ada course type.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $types->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
