<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- HEADER --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-start gap-3">
                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900 truncate">
                                        Annual Plan {{ $annualPlan->year }} — {{ $annualPlan->title }}
                                    </h1>

                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $annualPlan->description ?: '—' }}
                                    </p>

                                    @php
                                        $planStatus = $annualPlan->status ?? 'draft';
                                        $planChip = match ($planStatus) {
                                            'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
                                            'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'approved' => 'bg-green-50 text-green-800 border-green-200',
                                            'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200',
                                        };
                                    @endphp

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $planChip }}">
                                            {{ strtoupper($planStatus) }}
                                        </span>

                                        <span class="text-xs text-slate-500">
                                            Tahap berikutnya diproses setelah tahap sebelumnya disetujui.
                                        </span>
                                    </div>

                                    <div
                                        class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                        <span class="font-semibold text-slate-800">Alur:</span>
                                        Annual Plan → Plan Event → TOR → Course
                                    </div>

                                    @if ($annualPlan->isRejected() && $annualPlan->rejected_reason)
                                        <div
                                            class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                            <div class="font-semibold">Catatan Penolakan</div>
                                            <div class="mt-1">{{ $annualPlan->rejected_reason }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS (PLAN) --}}
                        <div class="flex flex-wrap items-center gap-2">
                            @php $user = auth()->user(); @endphp

                            @if ($user->canCreatePlans() && ($annualPlan->isDraft() || $annualPlan->isRejected()))
                                <a href="{{ route('annual-plans.edit', $annualPlan) }}"
                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('annual-plans.submit', $annualPlan) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Ajukan Persetujuan
                                    </button>
                                </form>
                            @endif

                            @if ($user->canApprovePlans())
                                <a href="{{ route('annual-plans.edit', $annualPlan) }}"
                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Edit (Direktur)
                                </a>

                                @if (in_array($annualPlan->status, ['pending', 'rejected'], true))
                                    <form method="POST" action="{{ route('annual-plans.approve', $annualPlan) }}">
                                        @csrf
                                        <button
                                            class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                @if (in_array($annualPlan->status, ['pending', 'approved'], true))
                                    <form method="POST" action="{{ route('annual-plans.reject', $annualPlan) }}"
                                        class="flex items-center gap-2">
                                        @csrf
                                        <input name="rejected_reason" placeholder="Alasan penolakan (opsional)"
                                            class="hidden lg:block w-64 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/10" />
                                        <button
                                            class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                            Reject
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('annual-plans.reopen', $annualPlan) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Reopen (Draft)
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- EVENTS --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-slate-200">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Plan Events</h2>
                        <p class="text-sm text-slate-600 mt-1">
                            Event harus dibuat dan disetujui sebelum TOR dapat diajukan.
                        </p>
                    </div>

                    @if ($user->canCreateEvents())
                        <a href="{{ route('annual-plans.events.create', $annualPlan) }}"
                            class="inline-flex items-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Tambah Event
                        </a>
                    @endif
                </div>

                <div class="p-5 space-y-3">
                    @forelse($annualPlan->events as $event)
                        @php
                            $eventStatus = $event->status ?? 'draft';
                            $eventChip = match ($eventStatus) {
                                'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                'approved' => 'bg-green-50 text-green-800 border-green-200',
                                'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };

                            $tor = $event->torSubmission;
                            $torStatus = $tor?->status;

                            $torChip = match ($torStatus) {
                                'approved' => 'bg-green-50 text-green-800 border-green-200',
                                'submitted' => 'bg-amber-50 text-amber-800 border-amber-200',
                                'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                            };

                            $hasTorReady = $tor && in_array($tor->status, ['submitted', 'approved'], true);

                            $canSubmitEvent =
                                $user->canCreatePlans() &&
                                in_array($eventStatus, ['draft', 'rejected'], true) &&
                                $annualPlan->isApproved();

                            $torCreateUrl = route('tor-submissions.create', $event);
                            $torEditUrl = $tor ? route('tor-submissions.edit', ['torSubmission' => $tor->id]) : null;

                            if (!$tor) {
                                $popupTitle = 'TOR belum dibuat';
                                $popupText = 'Buat TOR dulu sebelum ajukan event.';
                                $popupActionUrl = $torCreateUrl;
                                $popupActionLabel = 'Buat TOR';
                            } elseif (!in_array($tor->status, ['submitted', 'approved'], true)) {
                                $popupTitle = 'TOR belum disubmit';
                                $popupText =
                                    'TOR masih ' . strtoupper($tor->status) . '. Submit TOR dulu sebelum ajukan event.';
                                $popupActionUrl = $torEditUrl;
                                $popupActionLabel = 'Kelola TOR';
                            } else {
                                $popupTitle = '';
                                $popupText = '';
                                $popupActionUrl = null;
                                $popupActionLabel = null;
                            }

                            $torLocked =
                                in_array($eventStatus, ['pending', 'approved'], true) ||
                                $annualPlan->status === 'pending';
                        @endphp

                        <div class="rounded-xl border border-slate-200 bg-white">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-semibold text-slate-900 truncate">{{ $event->title }}</h3>

                                            <span
                                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $eventChip }}">
                                                Event: {{ strtoupper($eventStatus) }}
                                            </span>

                                            <span
                                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $tor ? $torChip : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                                TOR: {{ $tor ? strtoupper($torStatus) : 'BELUM ADA' }}
                                            </span>
                                        </div>

                                        <p class="mt-2 text-sm text-slate-600">
                                            {{ $event->start_date?->format('d M Y') }} →
                                            {{ $event->end_date?->format('d M Y') }}
                                            @if ($event->start_time && $event->end_time)
                                                • {{ $event->start_time }} — {{ $event->end_time }}
                                            @endif
                                            @if ($event->location)
                                                • {{ $event->location }}
                                            @endif
                                        </p>

                                        @if ($event->description)
                                            <p class="mt-2 text-sm text-slate-700">{{ $event->description }}</p>
                                        @endif

                                        {{-- OPTIONAL: inline warning --}}
                                        @if (!$tor)
                                            <div
                                                class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                                TOR belum dibuat. Buat TOR dulu sebelum Ajukan Event.
                                            </div>
                                        @elseif (!in_array($tor->status, ['submitted', 'approved'], true))
                                            <div
                                                class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                                TOR masih <b>{{ strtoupper($tor->status) }}</b>. Submit TOR dulu
                                                sebelum Ajukan Event.
                                            </div>
                                        @endif

                                        @if ($event->isRejected() && $event->rejected_reason)
                                            <div
                                                class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                                <div class="font-semibold">Catatan Penolakan Event</div>
                                                <div class="mt-1">{{ $event->rejected_reason }}</div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                                        @if ($user->canCreatePlans())
                                            @php
                                                $kabidCanEditEvent = in_array(
                                                    $eventStatus,
                                                    ['draft', 'rejected'],
                                                    true,
                                                );
                                            @endphp

                                            {{-- tombol delete hanya muncul kalau event masih draft/rejected --}}
                                            @if ($kabidCanEditEvent)
                                                <form method="POST"
                                                    action="{{ route('annual-plans.events.destroy', [$annualPlan, $event]) }}"
                                                    onsubmit="return confirm('Yakin hapus event ini? Data TOR terkait juga akan ikut terdampak kalau ada.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                        Hapus Event
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('annual-plans.events.edit', [$annualPlan, $event]) }}"
                                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold
                                               {{ $kabidCanEditEvent ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-400 pointer-events-none bg-slate-50' }}">
                                                Edit Event
                                            </a>

                                            @if ($canSubmitEvent)
                                                @if ($hasTorReady)
                                                    <form method="POST"
                                                        action="{{ route('annual-plans.events.submit', [$annualPlan, $event]) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button
                                                            class="inline-flex items-center rounded-lg bg-[#121293] px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                            Ajukan Event
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- ✅ tombol tetap bisa diklik, tapi munculin SweetAlert --}}
                                                    <button type="button"
                                                        class="inline-flex items-center rounded-lg bg-slate-200 px-3 py-2 text-sm font-semibold text-slate-700
                                                               hover:bg-slate-300 cursor-pointer pointer-events-auto relative z-10"
                                                        onclick="showTorGuard(
                                                            @json($popupTitle),
                                                            @json($popupText),
                                                            @json($popupActionUrl),
                                                            @json($popupActionLabel)
                                                        )">
                                                        Ajukan Event
                                                    </button>
                                                @endif
                                            @endif
                                        @endif

                                        {{-- ✅ Direktur: Approve / Reject Event --}}
                                        @if ($user->canApprovePlans())
                                            @if (in_array($eventStatus, ['pending', 'rejected'], true))
                                                <form method="POST"
                                                    action="{{ route('annual-plans.events.approve', [$annualPlan, $event]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        class="inline-flex items-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
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
                                                        class="hidden xl:block w-56 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/10" />

                                                    <button
                                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                        Reject Event
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST"
                                                action="{{ route('annual-plans.events.reopen', [$annualPlan, $event]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                    Reopen
                                                </button>
                                            </form>
                                        @endif

                                        @if ($user->canApprovePlans())
                                            @php
                                                $tor = $event->torSubmission;
                                                $torOkForApprove =
                                                    $tor && in_array($tor->status, ['submitted', 'rejected'], true);

                                                $eventOkForApprove = in_array(
                                                    $eventStatus,
                                                    ['pending', 'rejected'],
                                                    true,
                                                );

                                                // tombol aktif kalau minimal salah satu belum approved dan statusnya memungkinkan
                                                $canApproveAll =
                                                    $annualPlan->isApproved() &&
                                                    (($tor && $tor->status !== 'approved' && $torOkForApprove) ||
                                                        ($eventStatus !== 'approved' && $eventOkForApprove));
                                            @endphp

                                            <form method="POST"
                                                action="{{ route('annual-plans.events.approve-all', [$annualPlan, $event]) }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-white
                                                    {{ $canApproveAll ? 'bg-green-600 hover:opacity-90' : 'bg-slate-200 text-slate-500 cursor-not-allowed' }}"
                                                    {{ $canApproveAll ? '' : 'disabled' }}>
                                                    Approve Semua
                                                </button>
                                            </form>
                                        @endif

                                        {{-- TOR (Kabid) --}}
                                        @if ($user->canCreatePlans())
                                            @if ($torLocked)
                                                <span
                                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-400 cursor-not-allowed">
                                                    {{ $tor ? 'Kelola TOR (Terkunci)' : 'Buat TOR (Terkunci)' }}
                                                </span>
                                            @else
                                                @if (!$tor)
                                                    <a href="{{ route('tor-submissions.create', $event) }}"
                                                        class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                        Buat TOR
                                                    </a>
                                                @else
                                                    <a href="{{ route('tor-submissions.edit', ['torSubmission' => $tor->id]) }}"
                                                        class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                        Kelola TOR
                                                    </a>
                                                @endif
                                            @endif
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-600">
                            Belum ada event. Silakan tambahkan event untuk memulai proses TOR.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ✅ SweetAlert Guard --}}
    <script>
        function showTorGuard(title, text, actionUrl, actionLabel) {
            // fallback kalau sweetalert belum ke-load
            if (typeof Swal === 'undefined') {
                alert((title ? title + "\n" : "") + (text || 'Lengkapi TOR dulu.'));
                if (actionUrl) window.location.href = actionUrl;
                return;
            }

            const hasAction = !!actionUrl;

            Swal.fire({
                icon: 'warning',
                title: title || 'Tidak bisa lanjut',
                text: text || 'Lengkapi TOR dulu.',
                showCancelButton: hasAction, // kalau ada aksi, tampilkan tombol cancel
                confirmButtonText: hasAction ? (actionLabel || 'Buka TOR') : 'Oke',
                cancelButtonText: 'Tutup',
                reverseButtons: true,
                focusConfirm: false,
            }).then((result) => {
                if (result.isConfirmed && hasAction) {
                    window.location.href = actionUrl;
                }
            });
        }
    </script>
</x-app-layout>
