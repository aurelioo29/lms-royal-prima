<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                Course Types
                            </h1>
                            <p class="mt-1 text-sm text-slate-500">
                                Tipe course yang bisa dipilih saat membuat course.
                            </p>
                        </div>

                        <a href="{{ route('course-types.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                            + Tambah Type
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-7">
                            <label class="text-sm font-semibold text-slate-800">Search</label>
                            <input name="q" value="{{ $q }}"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                                       focus:border-[#121293] focus:ring-[#121293]"
                                placeholder="Cari name/slug...">
                        </div>

                        <div class="md:col-span-3">
                            <label class="text-sm font-semibold text-slate-800">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                                       focus:border-[#121293] focus:ring-[#121293]">
                                <option value="">— semua —</option>
                                <option value="active" @selected($status === 'active')>Active</option>
                                <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 flex items-end gap-2">
                            <button
                                class="w-full rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Type</th>
                                <th class="text-left px-4 py-3">Slug</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($types as $t)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $t->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $t->description ?? '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $t->slug }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold
                                            {{ $t->is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $t->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <form method="POST" action="{{ route('course-types.toggle', $t) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                    Toggle
                                                </button>
                                            </form>

                                            <a href="{{ route('course-types.edit', $t) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('course-types.destroy', $t) }}"
                                                onsubmit="return confirm('Hapus course type ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-slate-500">
                                        Tidak ada course type.
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
