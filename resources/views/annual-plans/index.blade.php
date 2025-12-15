<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Annual Plans</h2>
                <p class="text-sm text-slate-500">Rencana training tahunan.</p>
            </div>

            @if (auth()->user()->canCreatePlans())
                <a href="{{ route('annual-plans.create') }}"
                    class="px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90">
                    + Buat Plan
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Tahun</th>
                                <th class="text-left px-4 py-3">Judul</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Dibuat</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($plans as $p)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium">{{ $p->year }}</td>
                                    <td class="px-4 py-3">{{ $p->title }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $badge = match ($p->status) {
                                                'draft' => 'bg-slate-100 text-slate-700',
                                                'pending' => 'bg-amber-100 text-amber-800',
                                                'approved' => 'bg-green-100 text-green-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs {{ $badge }}">
                                            {{ strtoupper($p->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">
                                        {{ $p->creator?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('annual-plans.show', $p) }}"
                                            class="text-[#121293] hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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
