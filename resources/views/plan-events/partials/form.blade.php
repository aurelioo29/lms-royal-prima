@php
    use Illuminate\Support\Carbon;

    $e = $planEvent ?? new \App\Models\PlanEvent();

    $F = [
        'title' => 'title',
        'description' => 'description',

        // RANGE DATE
        'start_date' => 'start_date',
        'end_date' => 'end_date',

        'start_time' => 'start_time',
        'end_time' => 'end_time',

        'mode' => 'mode',
        'meeting_link' => 'meeting_link',
        'location' => 'location',
        'target_audience' => 'target_audience',

        // approval flow
        'status' => 'status',
    ];

    $modeOptions = [
        '' => '— pilih —',
        'offline' => 'Offline',
        'online' => 'Online',
        'blended' => 'Blended',
    ];

    // status sesuai migration kamu
    $statusOptions = [
        'draft' => 'Draft',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    // helper ambil value dari model + old()
    $val = function (string $key, $default = '') use ($e, $F) {
        $attr = $F[$key] ?? $key;
        return old($attr, data_get($e, $attr, $default));
    };

    // helper format date ke Y-m-d (buat input type=date)
    $fmtDate = function ($raw) {
        if (!$raw) {
            return '';
        }
        if ($raw instanceof \Carbon\CarbonInterface) {
            return $raw->format('Y-m-d');
        }
        try {
            return Carbon::parse($raw)->format('Y-m-d');
        } catch (\Throwable $th) {
            return '';
        }
    };

    $startDateValue = old($F['start_date'], $fmtDate(data_get($e, $F['start_date'])));
    $endDateValue = old($F['end_date'], $fmtDate(data_get($e, $F['end_date'])));

    // default mode (kalau kosong -> offline biar UI gak ngambang)
    $modeValue = old($F['mode'], data_get($e, $F['mode'], 'offline'));

    // status default -> draft
    $statusValue = old($F['status'], data_get($e, $F['status'], 'draft'));
@endphp

@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <div class="font-semibold mb-2">Form masih ada yang salah:</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div x-data="{
    mode: @js($modeValue),

    startDate: @js($startDateValue),
    endDate: @js($endDateValue),

    syncEnd() {
        if (!this.endDate || this.endDate < this.startDate) this.endDate = this.startDate;
    }
}" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- LEFT --}}
    <div class="space-y-6">

        {{-- Judul --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">
                Judul <span class="text-red-500">*</span>
            </label>

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>

                <input name="{{ $F['title'] }}" value="{{ $val('title') }}" required
                    class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                           focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                    placeholder="Contoh: Pelatihan K3 / Webinar Internal / Workshop SOP">
            </div>

            <p class="text-xs text-slate-500">Bikin singkat, jelas, dan tidak puitis.</p>
            <x-input-error :messages="$errors->get($F['title'])" class="mt-2" />
        </div>

        {{-- Deskripsi --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Deskripsi</label>

            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-3 text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 6h16M4 12h10M4 18h16" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                </span>

                <textarea name="{{ $F['description'] }}" rows="6"
                    class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                           focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                    placeholder="Tujuan event, materi, PIC, catatan penting...">{{ $val('description') }}</textarea>
            </div>

            <x-input-error :messages="$errors->get($F['description'])" class="mt-2" />
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="space-y-6">

        {{-- Range Tanggal --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Start Date --}}
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">
                    Mulai Tanggal <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M8 2v3M16 2v3M3 9h18" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input type="date" name="{{ $F['start_date'] }}" required x-model="startDate" @change="syncEnd()"
                        value="{{ $startDateValue }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293]">
                </div>

                <x-input-error :messages="$errors->get($F['start_date'])" class="mt-2" />
            </div>

            {{-- End Date --}}
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">
                    Sampai Tanggal <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M8 2v3M16 2v3M3 9h18" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input type="date" name="{{ $F['end_date'] }}" required x-model="endDate" :min="startDate"
                        value="{{ $endDateValue }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293]">
                </div>

                <p class="text-xs text-slate-500">Kalau cuma 1 hari, isi sama dengan mulai tanggal.</p>
                <x-input-error :messages="$errors->get($F['end_date'])" class="mt-2" />
            </div>
        </div>

        {{-- Jam --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Mulai Jam</label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="currentColor"
                                stroke-width="2" />
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input type="text" name="{{ $F['start_time'] }}" value="{{ $val('start_time') }}"
                        data-timepicker placeholder="HH:MM"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
             focus:border-[#121293] focus:ring-[#121293]">
                </div>
                <p class="text-xs text-slate-500">Format 24 jam (WIB).</p>
                <x-input-error :messages="$errors->get($F['start_time'])" class="mt-2" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Selesai Jam</label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="currentColor"
                                stroke-width="2" />
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input type="text" name="{{ $F['end_time'] }}" value="{{ $val('end_time') }}"
                        data-timepicker placeholder="HH:MM"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
             focus:border-[#121293] focus:ring-[#121293]">
                </div>
                <p class="text-xs text-slate-500">Format 24 jam (WIB).</p>
                <x-input-error :messages="$errors->get($F['end_time'])" class="mt-2" />
            </div>
        </div>

        {{-- Mode + Meeting Link --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Mode (opsional)</label>
                <select name="{{ $F['mode'] }}" x-model="mode"
                    class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                           focus:border-[#121293] focus:ring-[#121293]">
                    @foreach ($modeOptions as $k => $label)
                        <option value="{{ $k }}" @selected($val('mode', 'offline') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get($F['mode'])" class="mt-2" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">
                    Meeting Link
                    <span class="text-red-500" x-show="mode === 'online' || mode === 'blended'">*</span>
                </label>

                <input name="{{ $F['meeting_link'] }}" value="{{ $val('meeting_link') }}"
                    :required="mode === 'online' || mode === 'blended'"
                    class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                           focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                    placeholder="https://zoom.us/j/.... / Google Meet link / Teams link">

                <p class="text-xs text-slate-500" x-show="mode === 'online' || mode === 'blended'">
                    Wajib diisi kalau mode online/blended.
                </p>

                <x-input-error :messages="$errors->get($F['meeting_link'])" class="mt-2" />
            </div>
        </div>

        {{-- Lokasi + Target --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Lokasi</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input name="{{ $F['location'] }}" value="{{ $val('location') }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                        placeholder="Contoh: Aula Lt.2 / Zoom / Ruang Training">
                </div>
                <x-input-error :messages="$errors->get($F['location'])" class="mt-2" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Target Audience</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </span>

                    <input name="{{ $F['target_audience'] }}" value="{{ $val('target_audience') }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                        placeholder="Contoh: Perawat IGD / Semua karyawan / Dokter umum">
                </div>
                <x-input-error :messages="$errors->get($F['target_audience'])" class="mt-2" />
            </div>
        </div>

        {{-- Status --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">
                Status <span class="text-red-500">*</span>
            </label>

            <select name="{{ $F['status'] }}" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]">
                @foreach ($statusOptions as $k => $label)
                    <option value="{{ $k }}" @selected($statusValue === $k)>{{ $label }}</option>
                @endforeach
            </select>

            <p class="text-xs text-slate-500">
                Draft = belum diajukan, Pending = menunggu approval, Approved = disetujui, Rejected = ditolak.
            </p>

            <x-input-error :messages="$errors->get($F['status'])" class="mt-2" />
        </div>

    </div>
</div>

{{-- ACTIONS --}}
<div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
    <a href="{{ route('annual-plans.show', $annualPlan) }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>

    <button
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#121293] focus:ring-offset-2">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M17 21v-8H7v8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
            <path d="M7 3v5h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        {{ $submitText ?? 'Simpan' }}
    </button>
</div>
