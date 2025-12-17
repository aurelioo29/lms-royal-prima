<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-semibold text-slate-900">Annual Plan Approvals</h1>
                            <p class="text-sm text-slate-600 mt-1">Yang muncul di sini cuma status <b>pending</b>.</p>
                        </div>
                        <a href="{{ route('annual-plans.index') }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Tahun</th>
                                <th class="text-left px-4 py-3">Judul</th>
                                <th class="text-left px-4 py-3">Creator</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($plans as $p)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->year }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $p->title }}</div>
                                        <div class="text-slate-600 line-clamp-1">{{ $p->description ?: '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $p->creator?->name ?: '—' }}</td>
                                    <td class="px-4 py-3"><x-status-badge :status="$p->status" label="Plan" /></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="{{ route('annual-plans.show', $p) }}"
                                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-600">Tidak ada pending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">{{ $plans->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
