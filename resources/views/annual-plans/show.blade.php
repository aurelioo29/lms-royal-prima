<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
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

            {{-- HEADER / HERO --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill="currentColor"
                                            d="M7 3h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-xl font-semibold text-slate-900">
                                        Annual Plan {{ $annualPlan->year }} — {{ $annualPlan->title }}
                                    </h1>
                                    <p class="text-sm text-slate-600 mt-1">
                                        {{ $annualPlan->description ?: '—' }}
                                    </p>

                                    {{-- STATUS + FLOW --}}
                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        @php
                                            $planStatus = $annualPlan->status;
                                            $chip = match ($planStatus) {
                                                'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'approved' => 'bg-green-50 text-green-800 border-green-200',
                                                'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                            $planApproved = $annualPlan->isApproved();
                                        @endphp

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium {{ $chip }}">
                                            <span class="h-2 w-2 rounded-full bg-current opacity-60"></span>
                                            Status Annual Plan: {{ strtoupper($planStatus) }}
                                        </span>

                                        <span class="text-xs text-slate-500">
                                            Alur: Annual Plan → Plan Event → (ACC Event) → TOR → (ACC TOR) → Course
                                        </span>
                                    </div>

                                    @if ($annualPlan->isRejected() && $annualPlan->rejected_reason)
                                        <div
                                            class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                                            <div class="font-semibold">Catatan Penolakan:</div>
                                            <div>{{ $annualPlan->rejected_reason }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS (Annual Plan) --}}
                        <div class="flex flex-wrap items-center gap-2">
                            @php $user = auth()->user(); @endphp

                            @if ($user->canCreatePlans() && ($annualPlan->isDraft() || $annualPlan->isRejected()))
                                <a href="{{ route('annual-plans.edit', $annualPlan) }}"
                                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('annual-plans.submit', $annualPlan) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Ajukan ACC
                                    </button>
                                </form>
                            @endif

                            @if ($user->canApprovePlans())
                                <a href="{{ route('annual-plans.edit', $annualPlan) }}"
                                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Direktur Edit
                                </a>

                                @if (in_array($annualPlan->status, ['pending', 'rejected'], true))
                                    <form method="POST" action="{{ route('annual-plans.approve', $annualPlan) }}">
                                        @csrf
                                        <button
                                            class="inline-flex items-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                @if (in_array($annualPlan->status, ['pending', 'approved'], true))
                                    <form method="POST" action="{{ route('annual-plans.reject', $annualPlan) }}"
                                        class="flex items-center gap-2">
                                        @csrf
                                        <input name="rejected_reason" placeholder="Alasan (opsional)"
                                            class="hidden sm:block w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                                        <button
                                            class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                            Reject
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('annual-plans.reopen', $annualPlan) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Reopen (Draft)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- EVENTS --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center justify-between gap-4 p-4 sm:p-5 border-b border-slate-200">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Plan Events</h2>
                        <p class="text-sm text-slate-600">
                            Buat event dulu. TOR baru kebuka setelah Annual Plan & Event sudah approved.
                        </p>
                    </div>

                    @if ($user->canCreatePlans())
                        <a href="{{ route('annual-plans.events.create', $annualPlan) }}"
                            class="inline-flex items-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            + Tambah Event
                        </a>
                    @endif
                </div>

                <div class="p-4 sm:p-5 space-y-3">
                    @forelse($annualPlan->events as $event)
                        @php
                            $eventStatus = $event->status ?? 'draft';
                            $eventChip = match ($eventStatus) {
                                'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                'approved' => 'bg-green-50 text-green-800 border-green-200',
                                'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };

                            $tor = $event->torSubmission;
                            $torStatus = $tor?->status;
                            $canTor = $annualPlan->isApproved() && $event->isApproved();
                        @endphp

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-semibold text-slate-900">{{ $event->title }}</h3>

                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium {{ $eventChip }}">
                                            Event: {{ strtoupper($eventStatus) }}
                                        </span>

                                        @if ($tor)
                                            <span
                                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium
                                                {{ $torStatus === 'approved'
                                                    ? 'bg-green-50 text-green-800 border-green-200'
                                                    : ($torStatus === 'submitted'
                                                        ? 'bg-amber-50 text-amber-800 border-amber-200'
                                                        : ($torStatus === 'rejected'
                                                            ? 'bg-red-50 text-red-800 border-red-200'
                                                            : 'bg-slate-100 text-slate-700 border-slate-200')) }}">
                                                TOR: {{ strtoupper($torStatus) }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium bg-slate-50 text-slate-600 border-slate-200">
                                                TOR: BELUM ADA
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-slate-600 mt-1">
                                        {{ $event->start_date?->format('d M Y') }} →
                                        {{ $event->end_date?->format('d M Y') }}
                                        @if ($event->start_time && $event->end_time)
                                            • {{ $event->start_time }}—{{ $event->end_time }}
                                        @endif
                                        @if ($event->location)
                                            • {{ $event->location }}
                                        @endif
                                    </p>

                                    @if ($event->description)
                                        <p class="text-sm text-slate-700 mt-2">{{ $event->description }}</p>
                                    @endif

                                    @if ($event->isRejected() && $event->rejected_reason)
                                        <div
                                            class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                                            <div class="font-semibold">Catatan Penolakan Event:</div>
                                            <div>{{ $event->rejected_reason }}</div>
                                        </div>
                                    @endif
                                </div>

                                {{-- ACTIONS (Event + TOR + Course) --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($user->canCreatePlans())
                                        <a href="{{ route('annual-plans.events.edit', [$annualPlan, $event]) }}"
                                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Edit Event
                                        </a>

                                        @if (in_array($eventStatus, ['draft', 'rejected'], true))
                                            <form method="POST"
                                                action="{{ route('annual-plans.events.submit', [$annualPlan, $event]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="inline-flex items-center rounded-xl bg-[#121293] px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                    Ajukan Event
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if ($user->canApprovePlans())
                                        @if (in_array($eventStatus, ['pending', 'rejected'], true))
                                            <form method="POST"
                                                action="{{ route('annual-plans.events.approve', [$annualPlan, $event]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="inline-flex items-center rounded-xl bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                    Approve Event
                                                </button>
                                            </form>
                                        @endif

                                        @if (in_array($eventStatus, ['pending', 'approved'], true))
                                            <form method="POST"
                                                action="{{ route('annual-plans.events.reject', [$annualPlan, $event]) }}"
                                                class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input name="rejected_reason" placeholder="Alasan (opsional)"
                                                    class="hidden xl:block w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                                                <button
                                                    class="inline-flex items-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                    Reject Event
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST"
                                            action="{{ route('annual-plans.events.reopen', [$annualPlan, $event]) }}">
                                            @csrf
                                            <button
                                                class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Reopen Event
                                            </button>
                                        </form>
                                    @endif

                                    {{-- TOR button (Kabid) --}}
                                    @if ($user->canCreatePlans())
                                        @if ($canTor)
                                            @if (!$tor)
                                                <a href="{{ route('tor-submissions.create', $event) }}"
                                                    class="inline-flex items-center rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                    Buat TOR
                                                </a>
                                            @else
                                                <a href="{{ route('tor-submissions.edit', ['torSubmission' => $event->torSubmission->id]) }}"
                                                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                    Kelola TOR
                                                </a>
                                            @endif
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-500">
                                                TOR terkunci
                                            </span>
                                        @endif
                                    @endif

                                    {{-- Course button (Admin) --}}
                                    @if (auth()->user()->canCreateCourses() && $tor && $tor->status === 'approved')
                                        <a href="{{ route('courses.create', ['tor_submission_id' => $tor->id]) }}"
                                            class="inline-flex items-center rounded-xl bg-[#121293] px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                            Buat Course
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if (!$annualPlan->isApproved())
                                <div class="mt-3 text-xs text-slate-500">
                                    🧱 TOR terkunci karena Annual Plan belum <span
                                        class="font-semibold">APPROVED</span>.
                                </div>
                            @elseif(!$event->isApproved())
                                <div class="mt-3 text-xs text-slate-500">
                                    🧱 TOR terkunci karena Plan Event belum <span class="font-semibold">APPROVED</span>.
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-600">
                            Belum ada event. Kabid bisa mulai dari “Tambah Event”.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
