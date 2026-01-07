<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

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

                @php
                    $tor = $course->torSubmission;
                    $event = $tor?->planEvent;
                    $plan = $event?->annualPlan;

                    $dateLabel = '';
                    if ($event?->start_date && $event?->end_date) {
                        if ($event->start_date->isSameDay($event->end_date)) {
                            $dateLabel = $event->start_date->translatedFormat('l, d M Y');
                        } else {
                            $dateLabel =
                                $event->start_date->translatedFormat('l, d M Y') .
                                ' — ' .
                                $event->end_date->translatedFormat('l, d M Y');
                        }
                    }

                    $timeLabel = '';
                    if ($event?->start_time && $event?->end_time) {
                        $timeLabel =
                            \Carbon\Carbon::parse($event->start_time)->format('H:i') .
                            ' – ' .
                            \Carbon\Carbon::parse($event->end_time)->format('H:i') .
                            ' WIB';
                    } elseif ($event?->start_time) {
                        $timeLabel = \Carbon\Carbon::parse($event->start_time)->format('H:i') . ' WIB';
                    } elseif ($event?->end_time) {
                        $timeLabel = \Carbon\Carbon::parse($event->end_time)->format('H:i') . ' WIB';
                    }

                    $modeLabel = $event?->mode ? ucfirst($event->mode) : '';
                @endphp

                {{-- Header --}}
                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-slate-900">Edit Course</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Course mengambil judul & deskripsi dari event (tidak disimpan di Course).
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold bg-slate-50 text-slate-700 border-slate-200">
                                Key:
                                <span id="enrollKey" class="ml-2 font-mono">{{ $course->enrollment_key }}</span>
                                <button type="button"
                                    class="ml-2 rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-50"
                                    onclick="navigator.clipboard.writeText('{{ $course->enrollment_key }}')">
                                    Copy
                                </button>
                            </span>

                            <span
                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold bg-slate-50 text-slate-700 border-slate-200">
                                Status: {{ strtoupper($course->status) }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold bg-slate-50 text-slate-700 border-slate-200">
                                Creator: {{ $course->creator?->name ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('courses.index') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>

                        <form method="POST" action="{{ route('courses.destroy', $course) }}"
                            onsubmit="return confirm('Hapus course ini?');">
                            @csrf
                            @method('DELETE')
                            <button
                                class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Event Snapshot --}}
                <div class="px-5 sm:px-6 pb-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-xs text-slate-500">
                                    {{ $plan ? $plan->year . ' — ' . $plan->title : '—' }}
                                </div>
                                <div class="text-lg font-semibold text-slate-900 leading-snug">
                                    {{ $event?->title ?? '—' }}
                                </div>

                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    @if ($dateLabel)
                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $dateLabel }}
                                        </span>
                                    @endif
                                    @if ($timeLabel)
                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $timeLabel }}
                                        </span>
                                    @endif
                                    @if ($modeLabel)
                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $modeLabel }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                                @if ($event?->location)
                                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                                        <div class="text-xs text-slate-500">Lokasi</div>
                                        <div class="font-semibold">{{ $event->location }}</div>
                                    </div>
                                @endif
                                @if ($event?->target_audience)
                                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                                        <div class="text-xs text-slate-500">Target</div>
                                        <div class="font-semibold">{{ $event->target_audience }}</div>
                                    </div>
                                @endif
                                @if ($event?->meeting_link)
                                    <div class="rounded-xl border border-slate-200 bg-white p-3 sm:col-span-2">
                                        <div class="text-xs text-slate-500">Meeting Link</div>
                                        <div class="font-semibold break-all">{{ $event->meeting_link }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($event?->description)
                            <div class="mt-4">
                                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Deskripsi
                                </div>
                                <div class="mt-1 text-sm text-slate-700 whitespace-pre-line">
                                    {{ $event->description }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Form Update --}}
                <form method="POST" action="{{ route('courses.update', $course) }}"
                    class="p-5 sm:p-6 space-y-6 border-t border-slate-200">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Course Type</label>
                            <select name="course_type_id"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                                <option value="">—</option>
                                @foreach ($courseTypes as $ct)
                                    <option value="{{ $ct->id }}" @selected(old('course_type_id', $course->course_type_id) == $ct->id)>
                                        {{ $ct->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_type_id')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Training Hours</label>
                            <input name="training_hours" type="number" step="0.25" min="0"
                                value="{{ old('training_hours', $course->training_hours) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                            @error('training_hours')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Tujuan (opsional)</label>
                        <textarea name="tujuan" rows="5"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30"
                            placeholder="Tujuan pembelajaran...">{{ old('tujuan', $course->tujuan) }}</textarea>
                        @error('tujuan')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    @include('components.field-select-narasumber', [
                        'eligibleInstructors' => $eligibleInstructors,
                        'selectedInstructors' => $selectedInstructors,
                    ])


                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
                        <div class="w-full md:w-72">
                            <label class="text-sm font-semibold text-slate-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                                <option value="draft" @selected(old('status', $course->status) === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $course->status) === 'published')>Published</option>
                                <option value="archived" @selected(old('status', $course->status) === 'archived')>Archived</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button
                                class="rounded-xl bg-[#121293] px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                                Update Course
                            </button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
