@php
    $e = $planEvent;
@endphp

{{-- GRID MAIN --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- LEFT --}}
    <div class="space-y-6">

        {{-- Judul --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Judul <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    {{-- icon --}}
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <input name="title" value="{{ old('title', $e->title ?? '') }}" required
                    class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                           focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                    placeholder="Contoh: Pelatihan K3 / Webinar Internal / Workshop SOP">
            </div>
            <p class="text-xs text-slate-500">Bikin singkat, jelas, dan tidak puitis.</p>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
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
                <textarea name="description" rows="5"
                    class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                           focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                    placeholder="Tujuan event, materi, PIC, catatan penting...">{{ old('description', $e->description ?? '') }}</textarea>
            </div>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="space-y-6">

        {{-- Tanggal + Jam --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1 md:col-span-1">
                <label class="text-sm font-semibold text-slate-800">Tanggal <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M8 2v3M16 2v3M3 9h18" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                            <path d="M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <input type="date" name="date" required
                        value="{{ old('date', optional($e?->date)->format('Y-m-d')) }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293]">
                </div>
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time', $e->start_time ?? '') }}"
                    class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                           focus:border-[#121293] focus:ring-[#121293]">
                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-800">Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time', $e->end_time ?? '') }}"
                    class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                           focus:border-[#121293] focus:ring-[#121293]">
                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
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
                    <input name="location" value="{{ old('location', $e->location ?? '') }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                        placeholder="Contoh: Aula Lt.2 / Zoom / Ruang Training">
                </div>
                <x-input-error :messages="$errors->get('location')" class="mt-2" />
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
                    <input name="target_audience" value="{{ old('target_audience', $e->target_audience ?? '') }}"
                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5
                               focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                        placeholder="Contoh: Perawat IGD / Semua karyawan / Dokter umum">
                </div>
                <x-input-error :messages="$errors->get('target_audience')" class="mt-2" />
            </div>
        </div>

        {{-- Status --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Status <span class="text-red-500">*</span></label>
            @php $status = old('status', $e->status ?? 'scheduled'); @endphp

            <select name="status" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]">
                <option value="scheduled" @selected($status === 'scheduled')>Scheduled</option>
                <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                <option value="done" @selected($status === 'done')>Done</option>
            </select>

            <p class="text-xs text-slate-500">Scheduled = rencana, Done = selesai, Cancelled = dibatalkan.</p>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
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
        {{-- save icon --}}
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
