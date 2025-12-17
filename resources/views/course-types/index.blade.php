<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Course Types</h1>
                        <p class="text-sm text-slate-600 mt-1">Yang dipakai di form Course.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <form method="GET" class="flex flex-wrap items-center gap-2">
                            <input name="q" value="{{ $q }}" placeholder="Cari name/slug..."
                                class="w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <select name="status" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
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
                                <th class="text-left px-4 py-3">Slug</th>
                                <th class="text-left px-4 py-3">Active</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($types as $t)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $t->name }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $t->slug }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold
                                            {{ $t->is_active ? 'bg-green-50 text-green-800 border-green-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $t->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="{{ route('course-types.edit', $t) }}"
                                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('course-types.toggle', $t) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Toggle
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-slate-600">Belum ada type.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">{{ $types->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
