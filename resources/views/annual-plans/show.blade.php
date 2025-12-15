<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">{{ $annualPlan->title }}</h2>
                <p class="text-sm text-slate-500">
                    Tahun {{ $annualPlan->year }} · Status:
                    <span class="font-medium text-[#121293]">{{ strtoupper($annualPlan->status) }}</span>
                </p>

                @if ($annualPlan->isRejected())
                    <p class="mt-2 text-sm text-red-600">
                        Alasan ditolak: {{ $annualPlan->rejected_reason }}
                    </p>
                @endif
            </div>

            <div class="flex flex-wrap gap-2 justify-end">
                @if (auth()->user()->canCreatePlans() && ($annualPlan->isDraft() || $annualPlan->isRejected()))
                    <a href="{{ route('annual-plans.edit', $annualPlan) }}"
                        class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
                        Edit
                    </a>

                    <a href="{{ route('annual-plans.events.create', $annualPlan) }}"
                        class="px-3 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90">
                        + Tambah Event
                    </a>

                    <form method="POST" action="{{ route('annual-plans.submit', $annualPlan) }}">
                        @csrf
                        <button class="px-3 py-2 rounded-lg bg-amber-500 text-white hover:opacity-90">
                            Submit Approval
                        </button>
                    </form>
                @endif

                @if (auth()->user()->canApprovePlans() && $annualPlan->isPending())
                    <form method="POST" action="{{ route('annual-plans.approve', $annualPlan) }}">
                        @csrf
                        <button class="px-3 py-2 rounded-lg bg-green-600 text-white hover:opacity-90">
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('annual-plans.reject', $annualPlan) }}"
                        class="flex items-center gap-2">
                        @csrf
                        <input name="rejected_reason" placeholder="Alasan reject..."
                            class="px-3 py-2 rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">
                        <button class="px-3 py-2 rounded-lg bg-red-600 text-white hover:opacity-90">
                            Reject
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-800">Plan Events</div>
                        <div class="text-sm text-slate-500">Daftar jadwal pada plan ini.</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Tanggal</th>
                                <th class="text-left px-4 py-3">Judul</th>
                                <th class="text-left px-4 py-3">Waktu</th>
                                <th class="text-left px-4 py-3">Lokasi</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($annualPlan->events as $e)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">{{ $e->date?->format('d M Y') }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $e->title }}</td>
                                    <td class="px-4 py-3 text-slate-500">
                                        {{ $e->start_time ? substr($e->start_time, 0, 5) : '-' }}
                                        -
                                        {{ $e->end_time ? substr($e->end_time, 0, 5) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">{{ $e->location ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-700">
                                            {{ strtoupper($e->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if (auth()->user()->canCreatePlans() && ($annualPlan->isDraft() || $annualPlan->isRejected()))
                                            <a class="text-[#121293] hover:underline"
                                                href="{{ route('annual-plans.events.edit', [$annualPlan, $e]) }}">
                                                Edit
                                            </a>
                                            <form class="inline" method="POST"
                                                action="{{ route('annual-plans.events.destroy', [$annualPlan, $e]) }}"
                                                onsubmit="return confirm('Hapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ml-3 text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                                        Belum ada event.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <a href="{{ route('annual-plans.index') }}" class="text-[#121293] hover:underline">
                ← Kembali
            </a>
        </div>
    </div>
</x-app-layout>
