<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Courses</h1>
                        <p class="text-sm text-slate-600 mt-1">Course hanya dibuat dari TOR yang sudah <b>approved</b>.
                        </p>
                    </div>

                    <form method="GET" class="flex flex-wrap items-center gap-2">
                        <input name="q" value="{{ $q }}" placeholder="Cari judul..."
                            class="w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        <select name="status" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">All status</option>
                            <option value="draft" @selected($status === 'draft')>Draft</option>
                            <option value="published" @selected($status === 'published')>Published</option>
                            <option value="archived" @selected($status === 'archived')>Archived</option>
                        </select>
                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Filter
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Title</th>
                                <th class="text-left px-4 py-3">Type</th>
                                <th class="text-left px-4 py-3">Hours</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($courses as $c)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $c->title }}</div>
                                        <div class="text-slate-600 line-clamp-1">{{ $c->description ?: '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $c->type?->name ?: '—' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $c->training_hours }}</td>
                                    <td class="px-4 py-3"><x-status-badge :status="$c->status" label="Course" /></td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('courses.edit', $c) }}"
                                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-600">Belum ada course.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">{{ $courses->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
