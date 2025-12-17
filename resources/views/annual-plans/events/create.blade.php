<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h1 class="text-lg font-semibold text-slate-900">Tambah Plan Event</h1>
                            <p class="mt-1 text-sm text-slate-600">
                                Event akan dibuat dengan status <span class="font-semibold text-slate-800">DRAFT</span>.
                                Persetujuan dilakukan setelah pengajuan.
                            </p>
                        </div>

                        <a href="{{ route('annual-plans.show', $annualPlan) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('annual-plans.events.store', $annualPlan) }}"
                    class="px-5 py-5 sm:px-6 space-y-5">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700">Judul Event</label>
                        <p class="mt-1 text-xs text-slate-500">Contoh: Pelatihan BLS untuk Dokter Jaga.</p>
                        <input id="title" name="title" value="{{ old('title') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                        @error('title')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                        <p class="mt-1 text-xs text-slate-500">Opsional. Jelaskan tujuan, materi singkat, atau catatan
                            penting.</p>
                        <textarea id="description" name="description" rows="5"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-slate-700">Tanggal
                                Mulai</label>
                            <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('start_date')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-slate-700">Tanggal
                                Selesai</label>
                            <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('end_date')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Times --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-semibold text-slate-700">Jam Mulai</label>
                            <p class="mt-1 text-xs text-slate-500">Opsional. Format: HH:MM</p>
                            <input id="start_time" type="text" inputmode="numeric" placeholder="08:00"
                                name="start_time" value="{{ old('start_time') }}" data-timepicker
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('start_time')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-semibold text-slate-700">Jam Selesai</label>
                            <p class="mt-1 text-xs text-slate-500">Opsional. Format: HH:MM</p>
                            <input id="end_time" type="text" inputmode="numeric" placeholder="12:00" name="end_time"
                                value="{{ old('end_time') }}" data-timepicker
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('end_time')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Location & Audience --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="location" class="block text-sm font-semibold text-slate-700">Lokasi</label>
                            <input id="location" name="location" value="{{ old('location') }}"
                                placeholder="Contoh: Ruang Training"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('location')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="target_audience" class="block text-sm font-semibold text-slate-700">Target
                                Peserta</label>
                            <input id="target_audience" name="target_audience" value="{{ old('target_audience') }}"
                                placeholder="Contoh: Dokter Jaga"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('target_audience')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Mode & Link --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mode" class="block text-sm font-semibold text-slate-700">Mode</label>
                            <select id="mode" name="mode"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                                <option value="">—</option>
                                <option value="offline" @selected(old('mode') === 'offline')>Offline</option>
                                <option value="online" @selected(old('mode') === 'online')>Online</option>
                                <option value="blended" @selected(old('mode') === 'blended')>Blended</option>
                            </select>
                            @error('mode')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="meeting_link" class="block text-sm font-semibold text-slate-700">Meeting
                                Link</label>
                            <p id="meeting_help" class="mt-1 text-xs text-slate-500">
                                Opsional. Isi jika mode Online/Blended.
                            </p>

                            <input id="meeting_link" name="meeting_link" value="{{ old('meeting_link') }}"
                                placeholder="https://"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400
                   focus:outline-none focus:ring-2 focus:ring-[#121293]/20
                   disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed">

                            @error('meeting_link')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer actions --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 pt-3 border-t border-slate-200">
                        <a href="{{ route('annual-plans.show', $annualPlan) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Simpan Event
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Helper: end_date >= start_date --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const start = document.getElementById('start_date');
            const end = document.getElementById('end_date');

            const mode = document.getElementById('mode');
            const link = document.getElementById('meeting_link');
            const help = document.getElementById('meeting_help');

            if (!start || !end) return;

            if (!mode || !link) return;

            function syncMeetingLink() {
                const v = (mode.value || '').toLowerCase();
                const enabled = (v === 'online' || v === 'blended');

                link.disabled = !enabled;

                if (enabled) {
                    link.placeholder = 'https://';
                    if (help) help.textContent = 'Wajib diisi jika event Online/Blended.';
                } else {
                    link.placeholder = 'Tidak diperlukan untuk Offline';
                    // opsional: kosongin biar aman (ga nyangkut link)
                    link.value = '';
                    if (help) help.textContent = 'Tidak diperlukan untuk mode Offline.';
                }
            }

            function syncMinEndDate() {
                if (start.value) {
                    end.min = start.value;
                    if (end.value && end.value < start.value) end.value = start.value;
                } else {
                    end.removeAttribute('min');
                }
            }
            syncMeetingLink();
            mode.addEventListener('change', syncMeetingLink);

            syncMinEndDate();
            start.addEventListener('change', syncMinEndDate);
        });
    </script>
</x-app-layout>
