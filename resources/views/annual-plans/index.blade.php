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
                                    {{-- icon --}}
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path
                                            d="M4 3h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zM4 13h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1z"
                                            fill="currentColor" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                        Annual Plans
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Rencana training tahunan.
                                    </p>

                                    <div class="mt-3 text-sm text-slate-500">
                                        Total: <span class="font-semibold text-slate-800">{{ $plans->total() }}</span>
                                        plan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            @if (auth()->user()->canCreatePlans())
                                <a href="{{ route('annual-plans.create') }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#121293] focus:ring-offset-2">
                                    {{-- plus icon --}}
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                    Buat Plan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- FLASH --}}
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-700">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="text-sm font-medium">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            {{-- TABLE CARD --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr class="border-b border-slate-200">
                                <th class="px-5 py-3 text-left font-semibold">Tahun</th>
                                <th class="px-5 py-3 text-left font-semibold">Judul</th>
                                <th class="px-5 py-3 text-left font-semibold">Status</th>
                                <th class="px-5 py-3 text-left font-semibold">Pembuat</th>
                                <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($plans as $p)
                                @php
                                    $badge = match ($p->status) {
                                        'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'approved' => 'bg-green-50 text-green-700 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp

                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4 font-semibold text-slate-900 whitespace-nowrap align-middle">
                                        {{ $p->year }}
                                    </td>

                                    <td class="px-5 py-4 align-middle min-w-[280px]">
                                        <div class="font-semibold text-slate-900">
                                            {{ $p->title }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            Status: {{ strtoupper($p->status) }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 align-middle">
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $badge }}">
                                            <span
                                                class="h-2 w-2 rounded-full
                                                @if ($p->status === 'draft') bg-slate-400
                                                @elseif($p->status === 'pending') bg-amber-500
                                                @elseif($p->status === 'approved') bg-green-600
                                                @elseif($p->status === 'rejected') bg-red-600
                                                @else bg-slate-400 @endif
                                            "></span>
                                            {{ strtoupper($p->status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600 align-middle">
                                        {{ $p->creator?->name ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 text-right align-middle">
                                        <div class="w-full flex items-center justify-end">
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
                                    <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                        Belum ada Annual Plan.
                                        @if (auth()->user()->canCreatePlans())
                                            <div class="mt-3">
                                                <a href="{{ route('annual-plans.create') }}"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                        aria-hidden="true">
                                                        <path d="M12 5v14M5 12h14" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" />
                                                    </svg>
                                                    Buat Plan Pertama
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="border-t border-slate-200 p-4">
                    {{ $plans->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
