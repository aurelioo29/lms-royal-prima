<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-semibold text-slate-900">{{ $planEvent->title }}</h1>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <x-status-badge :status="$annualPlan->status" label="Plan" />
                                <x-status-badge :status="$planEvent->status" label="Event" />
                                @if ($planEvent->torSubmission)
                                    <x-status-badge :status="$planEvent->torSubmission->status" label="TOR" />
                                @endif
                            </div>

                            @if ($planEvent->isRejected() && $planEvent->rejected_reason)
                                <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                                    <div class="font-semibold">Catatan Penolakan Event:</div>
                                    <div>{{ $planEvent->rejected_reason }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('annual-plans.show', $annualPlan) }}"
                                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Kembali
                            </a>

                            @if (auth()->user()->canCreatePlans() || auth()->user()->canApprovePlans())
                                <a href="{{ route('annual-plans.events.edit', [$annualPlan, $planEvent]) }}"
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="text-slate-500">Tanggal</div>
                            <div class="font-semibold text-slate-900">
                                {{ $planEvent->start_date?->format('d M Y') }} —
                                {{ $planEvent->end_date?->format('d M Y') }}
                            </div>
                            <div class="text-slate-500 mt-2">Waktu</div>
                            <div class="font-semibold text-slate-900">
                                {{ $planEvent->start_time ?? '—' }} — {{ $planEvent->end_time ?? '—' }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="text-slate-500">Lokasi</div>
                            <div class="font-semibold text-slate-900">{{ $planEvent->location ?: '—' }}</div>

                            <div class="text-slate-500 mt-2">Target Audience</div>
                            <div class="font-semibold text-slate-900">{{ $planEvent->target_audience ?: '—' }}</div>

                            <div class="text-slate-500 mt-2">Mode</div>
                            <div class="font-semibold text-slate-900">{{ $planEvent->mode ?: '—' }}</div>

                            @if ($planEvent->meeting_link)
                                <div class="text-slate-500 mt-2">Meeting Link</div>
                                <a href="{{ $planEvent->meeting_link }}" target="_blank"
                                    class="text-[#121293] underline break-all">
                                    {{ $planEvent->meeting_link }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="text-slate-500 text-sm">Deskripsi</div>
                        <div class="text-slate-900 mt-1 whitespace-pre-line">{{ $planEvent->description ?: '—' }}</div>
                    </div>

                    {{-- ACTIONS (dikunci sesuai flow) --}}
                    <div class="flex flex-wrap gap-2 pt-2">
                        {{-- Kabid submit event hanya kalau AnnualPlan approved --}}
                        @if (auth()->user()->canCreatePlans())
                            @if ($annualPlan->isApproved() && in_array($planEvent->status, ['draft', 'rejected'], true))
                                <form method="POST"
                                    action="{{ route('annual-plans.events.submit', [$annualPlan, $planEvent]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Ajukan Event
                                    </button>
                                </form>
                            @elseif(!$annualPlan->isApproved())
                                <button disabled
                                    class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 cursor-not-allowed">
                                    Ajukan Event (Plan belum approved)
                                </button>
                            @endif
                        @endif

                        {{-- Direktur approve/reject event --}}
                        @if (auth()->user()->canApprovePlans())
                            @if (in_array($planEvent->status, ['pending', 'rejected'], true) && $annualPlan->isApproved())
                                <form method="POST"
                                    action="{{ route('annual-plans.events.approve', [$annualPlan, $planEvent]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Approve Event
                                    </button>
                                </form>
                            @endif

                            @if (in_array($planEvent->status, ['pending', 'approved'], true))
                                <form method="POST"
                                    action="{{ route('annual-plans.events.reject', [$annualPlan, $planEvent]) }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input name="rejected_reason" placeholder="Alasan (opsional)"
                                        class="w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                                    <button
                                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Reject Event
                                    </button>
                                </form>
                            @endif

                            <form method="POST"
                                action="{{ route('annual-plans.events.reopen', [$annualPlan, $planEvent]) }}">
                                @csrf
                                <button
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Reopen (Draft)
                                </button>
                            </form>
                        @endif

                        {{-- TOR: Kabid hanya bisa buat kalau Event APPROVED --}}
                        @php
                            $tor = $planEvent->torSubmission;
                            $canCreateTor = auth()->user()->canCreateTOR() && $planEvent->isApproved();
                        @endphp

                        @if ($tor)
                            <a href="{{ route('tor-submissions.edit', ['torSubmission' => $event->torSubmission->id]) }}"
                                class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Lihat TOR
                            </a>
                        @else
                            @if ($canCreateTor)
                                <a href="{{ route('tor-submissions.create', $planEvent) }}"
                                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                    + Buat TOR
                                </a>
                            @else
                                <button disabled
                                    class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 cursor-not-allowed">
                                    Buat TOR (Event belum approved)
                                </button>
                            @endif
                        @endif

                        {{-- Course: Admin hanya dari TOR APPROVED --}}
                        @if (auth()->user()->canCreateCourses() && $tor && $tor->isApproved())
                            <a href="{{ route('courses.create', ['tor_submission_id' => $tor->id]) }}"
                                class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Buat Course
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
