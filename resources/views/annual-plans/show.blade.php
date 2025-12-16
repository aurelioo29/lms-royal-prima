<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- TOP HERO / HEADER (NO x-slot) --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path
                                            d="M4 3h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1zM4 13h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1zm10 0h6c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1h-6c-.55 0-1-.45-1-1v-6c0-.55.45-1 1-1z"
                                            fill="currentColor" />
                                    </svg>
                                </div>

                                <div class="min-w-0 w-full">
                                    <h2
                                        class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight truncate">
                                        {{ $annualPlan->title }}
                                    </h2>

                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path
                                                    d="M8 2v3M16 2v3M3 9h18M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Tahun <span
                                                class="font-medium text-slate-700">{{ $annualPlan->year }}</span>
                                        </span>

                                        <span class="text-slate-300">•</span>

                                        @php
                                            $status = strtoupper($annualPlan->status);
                                            $badge = match ($annualPlan->status) {
                                                'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'approved' => 'bg-green-50 text-green-700 border-green-200',
                                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };

                                            $dot = match ($annualPlan->status) {
                                                'draft' => 'bg-slate-400',
                                                'pending' => 'bg-amber-500',
                                                'approved' => 'bg-green-600',
                                                'rejected' => 'bg-red-600',
                                                default => 'bg-slate-400',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $badge }}">
                                            <span class="h-2 w-2 rounded-full {{ $dot }}"></span>
                                            {{ $status }}
                                        </span>
                                    </div>

                                    {{-- REJECTED REASON --}}
                                    @if ($annualPlan->isRejected())
                                        <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-4">
                                            <div class="flex items-start gap-3">
                                                <div class="mt-0.5 text-red-600">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        aria-hidden="true">
                                                        <path
                                                            d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 0 0 4.6 20h14.8a2 2 0 0 0 1.72-3.32l-7.4-12.82a2 2 0 0 0-3.43 0Z"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-red-700">Ditolak</p>
                                                    <p class="mt-0.5 text-sm text-red-700/90 break-words">
                                                        {{ $annualPlan->rejected_reason }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- PLAN DESCRIPTION (preview + expand) --}}
                                    @php $planDesc = trim($annualPlan->description ?? ''); @endphp
                                    @if ($planDesc !== '')
                                        <div x-data="{ openDesc: false }" class="mt-4">
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 max-w-xl">
                                                <p class="text-sm font-semibold text-slate-800">Deskripsi Plan</p>

                                                <p class="mt-1 text-sm text-slate-600 whitespace-pre-line"
                                                    :class="openDesc ? '' : 'max-h-12 overflow-hidden'">
                                                    {{ $annualPlan->description }}
                                                </p>

                                                <button type="button" @click="openDesc = !openDesc"
                                                    class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-[#121293] hover:underline">
                                                    <span x-text="openDesc ? 'Tutup' : 'Lihat lengkap'"></span>
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            @if (auth()->user()->canCreatePlans() && ($annualPlan->isDraft() || $annualPlan->isRejected()))
                                <a href="{{ route('annual-plans.edit', $annualPlan) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 20h9" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Edit
                                </a>

                                <a href="{{ route('annual-plans.events.create', $annualPlan) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#121293] focus:ring-offset-2">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                    Tambah Event
                                </a>

                                <form method="POST" action="{{ route('annual-plans.submit', $annualPlan) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M22 2L11 13" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M22 2L15 22l-4-9-9-4 20-7Z" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Submit Approval
                                    </button>
                                </form>
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
                                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="text-sm font-medium">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            {{-- EVENTS TABLE + MODAL --}}
            <div x-data="{ modalOpen: false, event: null }"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 sm:p-6 flex justify-between items-center">
                    <div>
                        <div class="text-lg font-semibold text-slate-900">Plan Events</div>
                        <div class="text-sm text-slate-500">Daftar jadwal pada plan ini.</div>
                    </div>

                    <div class="text-sm text-slate-500">
                        Total:
                        <span class="font-semibold text-slate-800">{{ $annualPlan->events->count() }}</span>
                        event
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr class="border-b border-slate-200">
                                <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tanggal</th>
                                <th class="px-5 py-3 text-left font-semibold">Judul</th>
                                <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Waktu</th>

                                {{-- NEW --}}
                                <th class="px-5 py-3 text-left font-semibold">Course</th>
                                <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Mode</th>

                                <th class="px-5 py-3 text-left font-semibold">Lokasi</th>
                                <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                                <th class="px-5 py-3 text-right font-semibold whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($annualPlan->events as $e)
                                @php
                                    $time =
                                        ($e->start_time ? substr($e->start_time, 0, 5) : '-') .
                                        ' — ' .
                                        ($e->end_time ? substr($e->end_time, 0, 5) : '-');

                                    $modeLabel = match ($e->mode) {
                                        'online' => 'Online',
                                        'offline' => 'Offline',
                                        'blended' => 'Blended',
                                        default => '-',
                                    };

                                    $courseTitle = $e->course?->title ?? '-';
                                @endphp

                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4 text-slate-700 align-middle whitespace-nowrap">
                                        {{ $e->date?->format('d M Y') }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-900 font-semibold align-middle">
                                        <div class="min-w-0">
                                            <div class="truncate">{{ $e->title }}</div>
                                            @if (!empty($e->meeting_link))
                                                <div class="mt-1">
                                                    <a href="{{ $e->meeting_link }}" target="_blank" rel="noopener"
                                                        class="text-xs font-semibold text-[#121293] hover:underline">
                                                        Meeting Link
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-500 align-middle whitespace-nowrap">
                                        {{ $time }}
                                    </td>

                                    {{-- NEW: Course --}}
                                    <td class="px-5 py-4 text-slate-500 align-middle">
                                        <div class="max-w-[220px] truncate">
                                            {{ $courseTitle }}
                                        </div>
                                    </td>

                                    {{-- NEW: Mode --}}
                                    <td class="px-5 py-4 text-slate-500 align-middle whitespace-nowrap">
                                        {{ $modeLabel }}
                                    </td>

                                    <td class="px-5 py-4 text-slate-500 align-middle">
                                        {{ $e->location ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 align-middle">
                                        <span
                                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">
                                            {{ strtoupper($e->status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right align-middle">
                                        <div class="inline-flex items-center gap-2 whitespace-nowrap">
                                            {{-- DETAIL MODAL BUTTON --}}
                                            <button type="button"
                                                @click="
                                                    event = {
                                                        title: @js($e->title),
                                                        date: @js(optional($e->date)->format('d M Y')),
                                                        time: @js($time),
                                                        location: @js($e->location ?? '-'),
                                                        audience: @js($e->target_audience ?? '-'),
                                                        status: @js(strtoupper($e->status)),
                                                        description: @js($e->description ?? '-'),

                                                        course_title: @js($e->course?->title ?? null),
                                                        mode: @js($modeLabel),
                                                        meeting_link: @js($e->meeting_link ?? null),
                                                    };
                                                    modalOpen = true;
                                                "
                                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-[#121293] hover:bg-slate-50">
                                                Detail
                                            </button>

                                            @if (auth()->user()->canCreatePlans() && ($annualPlan->isDraft() || $annualPlan->isRejected()))
                                                <a href="{{ route('annual-plans.events.edit', [$annualPlan, $e]) }}"
                                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                    Edit
                                                </a>

                                                <form method="POST"
                                                    action="{{ route('annual-plans.events.destroy', [$annualPlan, $e]) }}"
                                                    onsubmit="return confirm('Hapus event ini?')" class="m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                                        Belum ada event.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 p-4 sm:p-5 flex items-center justify-between">
                    <a href="{{ route('annual-plans.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#121293] hover:underline">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Kembali
                    </a>
                </div>

                {{-- MODAL --}}
                <div x-show="modalOpen" x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
                    @click.self="modalOpen = false" @keydown.escape.window="modalOpen = false">
                    <div
                        class="w-full max-w-lg rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">
                        <div class="h-1 w-full bg-[#121293]"></div>

                        <div class="p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-900" x-text="event?.title"></h3>
                                    <div class="mt-1 text-sm text-slate-500">
                                        <span x-text="event?.date"></span>
                                        <span class="text-slate-300">•</span>
                                        <span x-text="event?.status"></span>
                                    </div>
                                </div>

                                <button type="button" @click="modalOpen = false"
                                    class="rounded-lg p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                                {{-- NEW: Course --}}
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 sm:col-span-2"
                                    x-show="event?.course_title">
                                    <div class="text-xs font-semibold text-slate-500">Course</div>
                                    <div class="mt-1 font-medium text-slate-800" x-text="event?.course_title"></div>
                                </div>

                                {{-- NEW: Mode --}}
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="text-xs font-semibold text-slate-500">Mode</div>
                                    <div class="mt-1 font-medium text-slate-800" x-text="event?.mode ?? '-'"></div>
                                </div>

                                {{-- NEW: Meeting Link --}}
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3"
                                    x-show="event?.meeting_link">
                                    <div class="text-xs font-semibold text-slate-500">Meeting Link</div>
                                    <a :href="event?.meeting_link" target="_blank" rel="noopener"
                                        class="mt-1 inline-flex font-semibold text-[#121293] hover:underline break-all"
                                        x-text="event?.meeting_link"></a>
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="text-xs font-semibold text-slate-500">Target Audience</div>
                                    <div class="mt-1 font-medium text-slate-800" x-text="event?.audience"></div>
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="text-xs font-semibold text-slate-500">Lokasi</div>
                                    <div class="mt-1 font-medium text-slate-800" x-text="event?.location"></div>
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 sm:col-span-2">
                                    <div class="text-xs font-semibold text-slate-500">Waktu</div>
                                    <div class="mt-1 font-medium text-slate-800" x-text="event?.time"></div>
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-white p-3 sm:col-span-2">
                                    <div class="text-xs font-semibold text-slate-500">Deskripsi</div>
                                    <div class="mt-1 text-slate-700 whitespace-pre-line leading-relaxed"
                                        x-text="event?.description"></div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-2">
                                <button type="button" @click="modalOpen = false"
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- END MODAL --}}
            </div>

        </div>
    </div>
</x-app-layout>
