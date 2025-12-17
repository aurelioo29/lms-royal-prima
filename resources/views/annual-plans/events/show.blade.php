<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h1 class="text-lg sm:text-xl font-semibold text-slate-900 truncate">
                                    {{ $planEvent->title }}
                                </h1>

                                <div class="mt-2 flex flex-wrap gap-2">
                                    <x-status-badge :status="$annualPlan->status" label="Plan" />
                                    <x-status-badge :status="$planEvent->status" label="Event" />
                                    @if ($planEvent->torSubmission)
                                        <x-status-badge :status="$planEvent->torSubmission->status" label="TOR" />
                                    @endif
                                </div>

                                @if ($planEvent->isRejected() && $planEvent->rejected_reason)
                                    <div
                                        class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                        <div class="font-semibold">Catatan Penolakan Event</div>
                                        <div class="mt-1">{{ $planEvent->rejected_reason }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('annual-plans.show', $annualPlan) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Kembali
                                </a>

                                @if (auth()->user()->canCreatePlans() || auth()->user()->canApprovePlans())
                                    <a href="{{ route('annual-plans.events.edit', [$annualPlan, $planEvent]) }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="text-xs text-slate-500">
                            Detail event untuk alur: Annual Plan → Event → TOR → Course
                        </div>
                    </div>
                </div>

                <div class="px-5 py-5 sm:px-6 space-y-5">

                    {{-- Info Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                        {{-- Schedule --}}
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Jadwal</div>

                            <div class="mt-2">
                                <div class="text-slate-500">Tanggal</div>
                                <div class="font-semibold text-slate-900">
                                    {{ $planEvent->start_date?->format('d M Y') ?? '—' }}
                                    @if ($planEvent->end_date)
                                        — {{ $planEvent->end_date?->format('d M Y') }}
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-slate-500">Waktu</div>
                                <div class="font-semibold text-slate-900">
                                    {{ $planEvent->start_time ?: '—' }} — {{ $planEvent->end_time ?: '—' }}
                                </div>
                            </div>
                        </div>

                        {{-- Location & Audience --}}
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Informasi</div>

                            <div class="mt-2">
                                <div class="text-slate-500">Lokasi</div>
                                <div class="font-semibold text-slate-900">{{ $planEvent->location ?: '—' }}</div>
                            </div>

                            <div class="mt-3">
                                <div class="text-slate-500">Target Peserta</div>
                                <div class="font-semibold text-slate-900">{{ $planEvent->target_audience ?: '—' }}
                                </div>
                            </div>
                        </div>

                        {{-- Mode & Link --}}
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Mode</div>

                            <div class="mt-2">
                                <div class="text-slate-500">Tipe</div>
                                <div class="font-semibold text-slate-900">{{ $planEvent->mode ?: '—' }}</div>
                            </div>

                            <div class="mt-3">
                                <div class="text-slate-500">Meeting Link</div>
                                @if ($planEvent->meeting_link)
                                    <a href="{{ $planEvent->meeting_link }}" target="_blank" rel="noopener"
                                        class="mt-2 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-[#121293] hover:bg-slate-50">
                                        Buka Link
                                    </a>
                                    <div class="mt-2 text-xs text-slate-400 break-all">
                                        {{ $planEvent->meeting_link }}
                                    </div>
                                @else
                                    <div class="font-semibold text-slate-900">—</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="rounded-lg border border-slate-200 bg-white p-4">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Deskripsi</div>
                        <div class="mt-2 text-slate-900 whitespace-pre-line">
                            {{ $planEvent->description ?: '—' }}
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    @php
                        $tor = $planEvent->torSubmission;
                        $user = auth()->user();

                        $canSubmitEvent =
                            $user->canCreatePlans() &&
                            $annualPlan->isApproved() &&
                            in_array($planEvent->status, ['draft', 'rejected'], true);
                        $canApproveEvent =
                            $user->canApprovePlans() &&
                            $annualPlan->isApproved() &&
                            in_array($planEvent->status, ['pending', 'rejected'], true);
                        $canRejectEvent =
                            $user->canApprovePlans() && in_array($planEvent->status, ['pending', 'approved'], true);

                        $canCreateTor = $user->canCreateTOR() && $planEvent->isApproved();
                    @endphp

                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="text-sm font-semibold text-slate-900">Aksi</div>

                            <div class="flex flex-wrap gap-2">
                                {{-- Kabid submit event --}}
                                @if ($user->canCreatePlans())
                                    @if ($canSubmitEvent)
                                        <form method="POST"
                                            action="{{ route('annual-plans.events.submit', [$annualPlan, $planEvent]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                Ajukan Event
                                            </button>
                                        </form>
                                    @elseif(!$annualPlan->isApproved())
                                        <button disabled
                                            class="inline-flex items-center justify-center rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 cursor-not-allowed">
                                            Ajukan Event (Plan belum approved)
                                        </button>
                                    @endif
                                @endif

                                {{-- Direktur approve --}}
                                @if ($user->canApprovePlans())
                                    @if ($canApproveEvent)
                                        <form method="POST"
                                            action="{{ route('annual-plans.events.approve', [$annualPlan, $planEvent]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                Approve Event
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Direktur reject (pakai input alasan) --}}
                                    @if ($canRejectEvent)
                                        <form method="POST"
                                            action="{{ route('annual-plans.events.reject', [$annualPlan, $planEvent]) }}"
                                            class="flex flex-wrap items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input name="rejected_reason" placeholder="Alasan (opsional)"
                                                class="w-full sm:w-64 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/10" />
                                            <button
                                                class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST"
                                        action="{{ route('annual-plans.events.reopen', [$annualPlan, $planEvent]) }}">
                                        @csrf
                                        <button
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Reopen (Draft)
                                        </button>
                                    </form>
                                @endif

                                {{-- TOR --}}
                                @if ($tor)
                                    <a href="{{ route('tor-submissions.edit', ['torSubmission' => $tor->id]) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Lihat TOR
                                    </a>
                                @else
                                    @if ($canCreateTor)
                                        <a href="{{ route('tor-submissions.create', $planEvent) }}"
                                            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                            + Buat TOR
                                        </a>
                                    @else
                                        <button disabled
                                            class="inline-flex items-center justify-center rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 cursor-not-allowed">
                                            Buat TOR (Event belum approved)
                                        </button>
                                    @endif
                                @endif

                                {{-- Course --}}
                                @if ($user->canCreateCourses() && $tor && $tor->isApproved())
                                    <a href="{{ route('courses.create', ['tor_submission_id' => $tor->id]) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Buat Course
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if (!$annualPlan->isApproved())
                            <div class="mt-3 text-xs text-slate-500">
                                Catatan: beberapa aksi terkunci karena Annual Plan belum berstatus <span
                                    class="font-semibold">APPROVED</span>.
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
