<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO (NO x-slot) --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    {{-- approval icon --}}
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20 6v6a8 8 0 1 1-16 0V6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M7 6V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                        Approvals
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Daftar Annual Plan yang menunggu persetujuan.
                                    </p>

                                    <div class="mt-3 text-sm text-slate-500">
                                        Pending: <span class="font-semibold text-slate-800">{{ $plans->total() }}</span>
                                        plan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            {{-- optional: could add filter/search later --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 sm:p-6 flex items-center justify-between">
                    <div>
                        <div class="text-lg font-semibold text-slate-900">Pending Plans</div>
                        <div class="text-sm text-slate-500">Approve atau reject rencana tahunan.</div>
                    </div>

                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        PENDING
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr class="border-b border-slate-200">
                                <th class="px-5 py-3 text-left font-semibold">Judul</th>
                                <th class="px-5 py-3 text-left font-semibold">Tahun</th>
                                <th class="px-5 py-3 text-left font-semibold">Dibuat</th>
                                <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($plans as $p)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4 align-middle min-w-[320px]">
                                        <div class="font-semibold text-slate-900">
                                            {{ $p->title }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            ID: {{ $p->id }} {{-- optional, hapus kalau gak perlu --}}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 align-middle whitespace-nowrap text-slate-700 font-semibold">
                                        {{ $p->year }}
                                    </td>

                                    <td class="px-5 py-4 align-middle whitespace-nowrap text-slate-500">
                                        {{ $p->created_at?->format('d M Y') }}
                                    </td>

                                    <td class="px-5 py-4 align-middle text-right">
                                        <div class="w-full flex items-center justify-end gap-2 whitespace-nowrap">
                                            <form method="POST" action="{{ route('annual-plans.approve', $p) }}"
                                                class="m-0 p-0">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                        aria-hidden="true">
                                                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('annual-plans.reject', $p) }}"
                                                class="m-0 p-0" onsubmit="return confirm('Yakin reject plan ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                        aria-hidden="true">
                                                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            </form>

                                            <a href="{{ route('annual-plans.show', $p) }}"
                                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Detail
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                    aria-hidden="true">
                                                    <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                        Tidak ada yang pending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 p-4">
                    {{ $plans->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
