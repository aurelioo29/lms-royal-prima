<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="min-w-0">
                            <h1 class="text-lg font-semibold text-slate-900">
                                Annual Plan Approvals
                            </h1>
                            <p class="mt-1 text-sm text-slate-600">
                                Hanya menampilkan Annual Plan dengan status <span
                                    class="font-semibold text-slate-800">PENDING</span>.
                            </p>

                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-600">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1">
                                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                    Total Pending: <span
                                        class="font-semibold text-slate-900">{{ $plans->total() }}</span>
                                </span>
                                <span class="text-slate-500">
                                    Klik “Review” untuk melihat detail dan melakukan keputusan.
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('annual-plans.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-5 py-3 font-semibold">Tahun</th>
                                <th class="text-left px-5 py-3 font-semibold">Judul</th>
                                <th class="text-left px-5 py-3 font-semibold">Creator</th>
                                <th class="text-left px-5 py-3 font-semibold">Status</th>
                                <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($plans as $p)
                                <tr class="hover:bg-slate-50/70 odd:bg-white even:bg-slate-50/30">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $p->year }}</div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $p->title }}
                                        </div>
                                        <div class="mt-1 text-slate-600 text-sm line-clamp-1">
                                            {{ $p->description ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium">{{ $p->creator?->name ?: '—' }}</div>
                                        <div class="text-xs text-slate-500">
                                            Dibuat: {{ optional($p->created_at)->format('d M Y') ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <x-status-badge :status="$p->status" label="Plan" />
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('annual-plans.show', $p) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Review
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14">
                                        <div class="text-center">
                                            <div
                                                class="mx-auto mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path fill="currentColor"
                                                        d="M7 3h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z" />
                                                </svg>
                                            </div>
                                            <div class="text-slate-900 font-semibold">Tidak ada data pending</div>
                                            <div class="text-slate-600 text-sm mt-1">
                                                Semua Annual Plan sudah diproses atau belum diajukan.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                    {{ $plans->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
