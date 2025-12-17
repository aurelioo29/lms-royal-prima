<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-xl font-semibold text-slate-900">TOR Approvals</h1>
                        <p class="mt-1 text-sm text-slate-600">
                            Daftar TOR yang menunggu persetujuan.
                        </p>
                    </div>

                    <div class="shrink-0 text-sm font-semibold text-slate-700">
                        Total: <span class="text-slate-900">{{ $tors->total() }}</span>
                    </div>
                </div>
            </div>

            {{-- List / Table --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-5 py-3 font-semibold">TOR</th>
                                <th class="text-left px-5 py-3 font-semibold">Event</th>
                                <th class="text-left px-5 py-3 font-semibold">Annual Plan</th>
                                <th class="text-left px-5 py-3 font-semibold">Status</th>
                                <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse ($tors as $tor)
                                @php
                                    $status = $tor->status ?? 'pending';
                                    $chip = match ($status) {
                                        'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
                                        'submitted' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'approved' => 'bg-green-50 text-green-800 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-800 border-red-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                                    };
                                @endphp

                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $tor->title }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            Dibuat: {{ optional($tor->created_at)->format('d M Y') ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium">
                                            {{ $tor->planEvent?->title ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium">
                                            {{ $tor->planEvent?->annualPlan?->title ?? '—' }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            Tahun: {{ $tor->planEvent?->annualPlan?->year ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $chip }}">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('tor-submissions.edit', $tor) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Detail
                                            </a>

                                            <form method="POST" action="{{ route('tor-submissions.approve', $tor) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="inline-flex items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                    Approve
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('tor-submissions.reject', $tor) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center text-slate-600">
                                        Belum ada TOR yang menunggu approval.
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
