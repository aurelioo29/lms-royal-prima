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

                <div class="p-5 sm:p-6 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-slate-900">Buat Course</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Pilih event yang TOR-nya sudah <span class="font-semibold">Approved</span>.
                        </p>
                    </div>

                    <a href="{{ route('courses.index') }}"
                        class="shrink-0 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('courses.store') }}" class="p-5 sm:p-6 space-y-6"
                    x-data="{
                        selected: '{{ old('tor_submission_id', $prefillTorId) }}',
                        meta: {},
                        tzLabel: 'WIB',
                        setMeta(el) {
                            const opt = el.options[el.selectedIndex];
                    
                            this.meta = opt ? {
                                plan: opt.dataset.plan || '',
                                event: opt.dataset.event || '',
                                startDate: opt.dataset.startDate || '',
                                endDate: opt.dataset.endDate || '',
                                startTime: opt.dataset.startTime || '',
                                endTime: opt.dataset.endTime || '',
                                location: opt.dataset.location || '',
                                audience: opt.dataset.audience || '',
                                mode: opt.dataset.mode || '',
                                link: opt.dataset.link || '',
                                desc: opt.dataset.desc || '',
                            } : {};
                    
                            this.meta.dateText = this.formatDateRange(this.meta.startDate, this.meta.endDate);
                            this.meta.timeText = this.formatTimeRange(this.meta.startTime, this.meta.endTime);
                        },
                        formatDate(d) {
                            if (!d) return '';
                            // d expected: YYYY-MM-DD
                            const [y, m, day] = d.split('-').map(Number);
                            const dt = new Date(y, (m - 1), day);
                            return new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: '2-digit', month: 'short', year: 'numeric' }).format(dt);
                        },
                        formatDateShort(d) {
                            if (!d) return '';
                            const [y, m, day] = d.split('-').map(Number);
                            const dt = new Date(y, (m - 1), day);
                            return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).format(dt);
                        },
                        formatDateRange(s, e) {
                            if (!s && !e) return '';
                            if (s && !e) return this.formatDate(s);
                            if (!s && e) return this.formatDate(e);
                            if (s === e) return this.formatDate(s);
                            // multi day: show both with weekday for clarity
                            return this.formatDate(s) + ' — ' + this.formatDate(e);
                        },
                        trimSeconds(t) {
                            // from 12:26:00 => 12:26
                            if (!t) return '';
                            return t.toString().slice(0, 5);
                        },
                        formatTimeRange(s, e) {
                            const a = this.trimSeconds(s);
                            const b = this.trimSeconds(e);
                            if (!a && !b) return '';
                            if (a && !b) return a + ' ' + this.tzLabel;
                            if (!a && b) return b + ' ' + this.tzLabel;
                            return a + ' – ' + b + ' ' + this.tzLabel;
                        },
                        niceMode(m) {
                            if (!m) return '';
                            const map = { online: 'Online', offline: 'Offline', blended: 'Blended' };
                            return map[m] || m;
                        }
                    }" x-init="$nextTick(() => { const el = $refs.torSelect; if (el) { setMeta(el); } })">
                    @csrf

                    {{-- Select --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">
                            Event (TOR Approved) <span class="text-red-500">*</span>
                        </label>

                        <select x-ref="torSelect" name="tor_submission_id" required x-model="selected"
                            @change="setMeta($event.target)"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                            <option value="">— Pilih Event —</option>

                            @foreach ($torOptions as $tor)
                                @php
                                    $event = $tor->planEvent;
                                    $plan = $event?->annualPlan;

                                    $planLabel = $plan ? $plan->year . ' — ' . $plan->title : '';
                                    $eventLabel = $event?->title ?? '—';
                                    $desc = $event?->description ?? '';
                                @endphp

                                <option value="{{ $tor->id }}" @selected(old('tor_submission_id', $prefillTorId) == $tor->id)
                                    data-plan="{{ $planLabel }}" data-event="{{ $eventLabel }}"
                                    data-start-date="{{ optional($event?->start_date)->format('Y-m-d') }}"
                                    data-end-date="{{ optional($event?->end_date)->format('Y-m-d') }}"
                                    data-start-time="{{ $event?->start_time ?? '' }}"
                                    data-end-time="{{ $event?->end_time ?? '' }}"
                                    data-location="{{ $event?->location ?? '' }}"
                                    data-audience="{{ $event?->target_audience ?? '' }}"
                                    data-mode="{{ $event?->mode ?? '' }}"
                                    data-link="{{ $event?->meeting_link ?? '' }}" data-desc="{{ e($desc) }}">
                                    {{ $eventLabel }}
                                </option>
                            @endforeach
                        </select>

                        @error('tor_submission_id')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror

                        @if ($torOptions->isEmpty())
                            <div class="mt-2 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl p-3">
                                Belum ada TOR Approved yang siap dibuat Course.
                            </div>
                        @endif
                    </div>

                    {{-- Preview Event --}}
                    <div x-show="selected" x-cloak class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-xs text-slate-500" x-text="meta.plan"></div>
                                <div class="text-lg font-semibold text-slate-900 leading-snug" x-text="meta.event">
                                </div>

                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700"
                                        x-show="meta.dateText">
                                        <span x-text="meta.dateText"></span>
                                    </span>

                                    <span
                                        class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700"
                                        x-show="meta.timeText">
                                        <span x-text="meta.timeText"></span>
                                    </span>

                                    <span
                                        class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700"
                                        x-show="meta.mode">
                                        <span x-text="niceMode(meta.mode)"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                                <div class="rounded-xl border border-slate-200 bg-white p-3" x-show="meta.location">
                                    <div class="text-xs text-slate-500">Lokasi</div>
                                    <div class="font-semibold" x-text="meta.location"></div>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white p-3" x-show="meta.audience">
                                    <div class="text-xs text-slate-500">Target</div>
                                    <div class="font-semibold" x-text="meta.audience"></div>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white p-3 sm:col-span-2"
                                    x-show="meta.link">
                                    <div class="text-xs text-slate-500">Meeting Link</div>
                                    <div class="font-semibold break-all" x-text="meta.link"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4" x-show="meta.desc">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Deskripsi</div>
                            <div class="mt-1 text-sm text-slate-700 whitespace-pre-line" x-text="meta.desc"></div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Course Type</label>
                            <select name="course_type_id"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                                <option value="">—</option>
                                @foreach ($courseTypes as $ct)
                                    <option value="{{ $ct->id }}" @selected(old('course_type_id') == $ct->id)>
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
                                value="{{ old('training_hours', 0) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                            @error('training_hours')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Tujuan (opsional)</label>
                        <textarea name="tujuan" rows="4"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30"
                            placeholder="Contoh: Setelah pelatihan, peserta mampu...">{{ old('tujuan') }}</textarea>
                        @error('tujuan')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
                        <div class="w-full md:w-72">
                            <label class="text-sm font-semibold text-slate-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                                <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                                <option value="published" @selected(old('status') === 'published')>Published</option>
                                <option value="archived" @selected(old('status') === 'archived')>Archived</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button
                                class="rounded-xl bg-[#121293] px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                                Simpan Course
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
