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

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Annual Plans</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Alur ketat: Annual Plan → Event → (ACC Event) → TOR → (ACC TOR) → Course.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if (auth()->user()->canApprovePlans())
                            <a href="{{ route('annual-plans.approvals') }}"
                                class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Approvals
                            </a>
                        @endif

                        @if (auth()->user()->canCreatePlans())
                            <a href="{{ route('annual-plans.create') }}"
                                class="inline-flex items-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                + Buat Annual Plan
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Tahun</th>
                                <th class="text-left px-4 py-3">Judul</th>
                                <th class="text-left px-4 py-3">Creator</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Approver</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($plans as $p)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->year }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $p->title }}</div>
                                        <div class="text-slate-600 line-clamp-1">{{ $p->description ?: '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $p->creator?->name ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <x-status-badge :status="$p->status" label="Plan" />
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $p->approver?->name ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('annual-plans.show', $p) }}"
                                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-600">
                                        Belum ada Annual Plan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $plans->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
