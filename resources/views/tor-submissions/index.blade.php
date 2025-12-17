<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Alerts --}}
            @if (session('success'))
                <div
                    class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h1 class="text-lg font-semibold text-slate-900">TOR Submissions</h1>
                            <div class="mt-1 text-sm text-slate-600">
                                Total: <span class="font-semibold text-slate-900">{{ $tors->total() }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if (auth()->user()->canApprovePlans())
                                <a href="{{ route('tor-submissions.approvals') }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Approvals
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    {{-- FIX: jangan pakai sticky + z tinggi, itu biang kerok nabrak modal --}}
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-5 py-3 font-semibold">TOR</th>
                                <th class="text-left px-5 py-3 font-semibold">Plan</th>
                                <th class="text-left px-5 py-3 font-semibold">Event</th>
                                <th class="text-left px-5 py-3 font-semibold">Created</th>
                                <th class="text-left px-5 py-3 font-semibold">File</th>
                                <th class="text-left px-5 py-3 font-semibold">Status</th>
                                <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($tors as $tor)
                                @php
                                    $status = $tor->status ?? 'draft';
                                    $chip = match ($status) {
                                        'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'submitted' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'approved' => 'bg-green-50 text-green-800 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };

                                    $plan = $tor->planEvent?->annualPlan;
                                    $event = $tor->planEvent;
                                @endphp

                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4 align-top">
                                        <div class="font-semibold text-slate-900 line-clamp-1">
                                            {{ $tor->title }}
                                        </div>

                                        @if ($status === 'rejected' && $tor->review_notes)
                                            <div
                                                class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-800">
                                                <div class="font-semibold">Catatan</div>
                                                <div class="mt-1 line-clamp-3">{{ $tor->review_notes }}</div>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 align-top text-slate-700">
                                        @if ($plan)
                                            <div class="font-medium text-slate-900 line-clamp-1">
                                                {{ $plan->year }} — {{ $plan->title }}
                                            </div>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 align-top text-slate-700">
                                        <div class="font-medium text-slate-900 line-clamp-1">
                                            {{ $event?->title ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 align-top text-slate-700">
                                        <div class="font-medium">{{ $tor->created_at?->format('d M Y') ?? '—' }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $tor->created_at?->format('H:i') ?? '' }}</div>
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        @if ($tor->file_path)
                                            <a class="inline-flex items-center gap-2 text-[#121293] font-semibold hover:underline"
                                                href="{{ asset('storage/' . $tor->file_path) }}" target="_blank"
                                                rel="noreferrer">
                                                Lihat
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path fill="currentColor"
                                                        d="M14 3h7v7h-2V6.41l-9.29 9.3l-1.42-1.42l9.3-9.29H14V3ZM5 5h6v2H7v10h10v-4h2v6H5V5Z" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-slate-500">—</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $chip }}">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        <div class="flex items-center justify-end gap-2">
                                            @if (auth()->user()->canCreateTOR())
                                                <a href="{{ route('tor-submissions.edit', $tor) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                    Kelola
                                                </a>
                                            @endif

                                            @if (auth()->user()->canApprovePlans() && in_array($status, ['submitted', 'rejected'], true))
                                                <form method="POST"
                                                    action="{{ route('tor-submissions.approve', $tor) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        class="inline-flex items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-14 text-center text-slate-600">
                                        Tidak ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-4 sm:px-6 border-t border-slate-200">
                    {{ $tors->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
