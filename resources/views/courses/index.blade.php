<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                Courses
                            </h1>
                            <p class="mt-1 text-sm text-slate-500">
                                Kelola course yang dibuat dari TOR (approved) + tipe course.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            <a href="{{ route('course-types.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Course Types
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FLASH --}}
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

            {{-- FILTER --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-6">
                            <label class="text-sm font-semibold text-slate-800">Search</label>
                            <input name="q" value="{{ $q }}"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                                       focus:border-[#121293] focus:ring-[#121293]"
                                placeholder="Cari judul course...">
                        </div>

                        <div class="md:col-span-3">
                            <label class="text-sm font-semibold text-slate-800">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                                       focus:border-[#121293] focus:ring-[#121293]">
                                <option value="">— semua —</option>
                                <option value="draft" @selected($status === 'draft')>Draft</option>
                                <option value="published" @selected($status === 'published')>Published</option>
                                <option value="archived" @selected($status === 'archived')>Archived</option>
                            </select>
                        </div>

                        <div class="md:col-span-3 flex items-end gap-2">
                            <button
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                Filter
                            </button>

                            <a href="{{ route('courses.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 sm:p-6 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        Total: <span class="font-semibold text-slate-800">{{ $courses->total() }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Course</th>
                                <th class="text-left px-4 py-3">Type</th>
                                <th class="text-left px-4 py-3">Training Hours</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($courses as $c)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $c->title }}</div>
                                        <div class="text-xs text-slate-500">
                                            TOR: {{ $c->tor_submission_id ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                            {{ $c->type?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $c->training_hours ?? 0 }} jam
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $badge = match ($c->status) {
                                                'published' => 'bg-green-50 text-green-700 border-green-200',
                                                'archived' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                default => 'bg-yellow-50 text-yellow-800 border-yellow-200',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badge }}">
                                            {{ strtoupper($c->status ?? 'draft') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('courses.edit', $c) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('courses.destroy', $c) }}"
                                                onsubmit="return confirm('Hapus course ini?')">
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
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                                        Tidak ada course.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $courses->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
