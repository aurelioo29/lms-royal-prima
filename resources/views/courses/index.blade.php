<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Courses</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Data course mengikuti event yang TOR-nya sudah approved.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <form method="GET" class="flex flex-wrap items-center gap-2">
                            <input name="q" value="{{ $q }}" placeholder="Cari event / plan / key..."
                                class="w-64 rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <select name="status" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="">All</option>
                                <option value="draft" @selected($status === 'draft')>Draft</option>
                                <option value="published" @selected($status === 'published')>Published</option>
                                <option value="archived" @selected($status === 'archived')>Archived</option>
                            </select>
                            <button
                                class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Filter
                            </button>
                        </form>

                        <a href="{{ route('courses.create') }}"
                            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            + New Course
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Event</th>
                                <th class="text-left px-4 py-3">Type</th>
                                <th class="text-left px-4 py-3">Hours</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Key</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($courses as $c)
                                @php
                                    $event = $c->torSubmission?->planEvent;
                                    $plan = $event?->annualPlan;

                                    $dateLabel = '';
                                    if ($event?->start_date && $event?->end_date) {
                                        if ($event->start_date->isSameDay($event->end_date)) {
                                            $dateLabel = $event->start_date->translatedFormat('l, d M Y');
                                        } else {
                                            $dateLabel =
                                                $event->start_date->translatedFormat('d M Y') .
                                                ' — ' .
                                                $event->end_date->translatedFormat('d M Y');
                                        }
                                    }

                                    $timeLabel = '';
                                    if ($event?->start_time && $event?->end_time) {
                                        $timeLabel =
                                            \Carbon\Carbon::parse($event->start_time)->format('H:i') .
                                            '–' .
                                            \Carbon\Carbon::parse($event->end_time)->format('H:i') .
                                            ' WIB';
                                    }
                                @endphp

                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">
                                            {{ $event?->title ?? '—' }}
                                        </div>

                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-600">
                                            <span class="rounded-full border border-slate-200 bg-white px-2 py-0.5">
                                                {{ $plan ? $plan->year . ' — ' . $plan->title : '—' }}
                                            </span>

                                            @if ($dateLabel)
                                                <span class="rounded-full border border-slate-200 bg-white px-2 py-0.5">
                                                    {{ $dateLabel }}
                                                </span>
                                            @endif

                                            @if ($timeLabel)
                                                <span class="rounded-full border border-slate-200 bg-white px-2 py-0.5">
                                                    {{ $timeLabel }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $c->type?->name ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $c->training_hours }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold
                                            {{ $c->status === 'published'
                                                ? 'bg-green-50 text-green-800 border-green-200'
                                                : ($c->status === 'archived'
                                                    ? 'bg-slate-100 text-slate-700 border-slate-200'
                                                    : 'bg-amber-50 text-amber-800 border-amber-200') }}">
                                            {{ strtoupper($c->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-xs rounded-lg border border-slate-200 bg-white px-2 py-1">
                                                {{ $c->enrollment_key }}
                                            </span>
                                            <button type="button"
                                                class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-50"
                                                onclick="navigator.clipboard.writeText('{{ $c->enrollment_key }}')">
                                                Copy
                                            </button>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            {{-- Button Modul --}}
                                            <a href="{{ route('courses.modules.index', $c) }}"
                                                class="inline-flex items-center rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                                                Modul
                                            </a>

                                            {{-- Button Edit --}}
                                            <a href="{{ route('courses.edit', $c) }}"
                                                class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-600">
                                        Belum ada Course.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $courses->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
