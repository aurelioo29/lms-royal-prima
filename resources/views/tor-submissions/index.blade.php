<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 mt-0.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor"
                                d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20Zm-1 14l-4-4l1.4-1.4L11 13.2l5.6-5.6L18 9l-7 7Z" />
                        </svg>
                        <div class="text-sm font-semibold leading-relaxed">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 mt-0.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor"
                                d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20Zm1 13h-2v-2h2v2Zm0-4h-2V7h2v4Z" />
                        </svg>
                        <div class="text-sm font-semibold leading-relaxed">{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-slate-900">TOR Submissions</h1>
                        <div class="mt-1 text-sm text-slate-600">
                            Total: <span class="font-semibold text-slate-900">{{ $tors->total() }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if (auth()->user()->canApprovePlans())
                            <a href="{{ route('tor-submissions.approvals') }}"
                                class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Approvals
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 sticky top-0 z-10">
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
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900 line-clamp-1">
                                            {{ $tor->title }}
                                        </div>

                                        @if ($status === 'rejected' && $tor->review_notes)
                                            <div
                                                class="mt-2 rounded-xl border border-red-200 bg-red-50 p-3 text-xs text-red-800">
                                                <div class="font-semibold">Catatan</div>
                                                <div class="mt-1 line-clamp-3">{{ $tor->review_notes }}</div>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        @if ($plan)
                                            <div class="font-medium text-slate-900 line-clamp-1">
                                                {{ $plan->year }} — {{ $plan->title }}
                                            </div>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium text-slate-900 line-clamp-1">
                                            {{ $event?->title ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium">{{ $tor->created_at?->format('d M Y') ?? '—' }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $tor->created_at?->format('H:i') ?? '' }}</div>
                                    </td>

                                    <td class="px-5 py-4">
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

                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $chip }}">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            @if (auth()->user()->canCreateTOR())
                                                <a href="{{ route('tor-submissions.edit', $tor) }}"
                                                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                    Kelola
                                                </a>
                                            @endif

                                            @if (auth()->user()->canApprovePlans() && in_array($status, ['submitted', 'rejected'], true))
                                                <form method="POST"
                                                    action="{{ route('tor-submissions.approve', $tor) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        class="inline-flex items-center rounded-xl bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-14">
                                        <div class="text-center text-slate-600">
                                            Belum ada TOR.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 p-4">
                    {{ $tors->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
