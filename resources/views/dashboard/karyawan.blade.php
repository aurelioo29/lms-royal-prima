<x-app-layout>
    @php
        // ===== Basic user vars =====
        $userName = auth()->user()->name ?? 'Pengguna';

        // ===== Stats mapping (kamu sudah pakai $stats as array) =====
        $courseAktif = (int) ($stats['course_aktif'] ?? 0);
        $courseSelesai = (int) ($stats['course_selesai'] ?? 0);
        $totalJam = (float) ($stats['total_jam'] ?? 0);

        // ===== Target (optional) =====
        $targetJam = (float) ($summary['target_jam'] ?? 20); // default 20 JP/tahun kalau belum ada
        $pctJam = $targetJam > 0 ? (int) round(($totalJam / $targetJam) * 100) : 0;
        $pctJam = max(0, min(100, $pctJam));

        // ===== Progress list (optional) =====
        $progressList = collect($summary['progress'] ?? []);

        // ===== Certificates list (optional) =====
        $certs = collect($summary['certificates'] ?? []);

        // ===== Timeline (optional) =====
        $timeline = collect($activities['timeline'] ?? []);

        // ===== "mini chart" monthly study (optional) =====
        // Ideal: $summary['monthly'] = [ ['label'=>'Jan','value'=>2], ... ]
        $monthly = collect($summary['monthly'] ?? []);
        if ($monthly->isEmpty()) {
            $monthly = collect([
                ['label' => 'Jan', 'value' => 0],
                ['label' => 'Feb', 'value' => 0],
                ['label' => 'Mar', 'value' => 0],
                ['label' => 'Apr', 'value' => 0],
                ['label' => 'Mei', 'value' => 0],
                ['label' => 'Jun', 'value' => 0],
                ['label' => 'Jul', 'value' => 0],
                ['label' => 'Agu', 'value' => 0],
                ['label' => 'Sep', 'value' => 0],
                ['label' => 'Okt', 'value' => 0],
                ['label' => 'Nov', 'value' => 0],
                ['label' => 'Des', 'value' => 0],
            ]);
        }
        $maxMonthly = (int) ($monthly->max('value') ?: 1);

        // ===== Badges / status =====
        $accountStatus = 'Aktif';
        $today = now()->translatedFormat('l, d F Y');
    @endphp

    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= HERO (Personal Learning) ================= --}}
            <section
                class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white/90 backdrop-blur shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>

                {{-- ornaments --}}
                <div
                    class="pointer-events-none absolute -top-24 -right-24 h-56 w-56 rounded-full bg-[#121293]/10 blur-3xl">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-28 -left-28 h-64 w-64 rounded-full bg-slate-200/40 blur-3xl">
                </div>

                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                        {{-- Left --}}
                        <div class="min-w-0">
                            <div class="flex items-start gap-4">
                                <div
                                    class="mt-1 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293] border border-[#121293]/20">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path
                                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">
                                        Dashboard Pembelajaran
                                    </h1>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Selamat datang, <span
                                            class="font-semibold text-slate-900">{{ $userName }}</span>.
                                        Pantau progres pelatihan dan kelola aktivitas belajar Anda secara terstruktur.
                                    </p>

                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-[#121293]/30 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                            Peran: Karyawan / Peserta
                                        </span>

                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $today }}
                                        </span>

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Akun {{ $accountStatus }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Personal Scorecard --}}
                        <div class="shrink-0 w-full lg:w-[420px]">
                            <div class="rounded-2xl border border-slate-200 bg-white/85 p-5">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-slate-900">Ringkasan Progres Pribadi</div>
                                    <span class="text-xs font-semibold text-slate-500">Update
                                        {{ now()->translatedFormat('H:i') }}</span>
                                </div>

                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    {{-- Donut JP --}}
                                    <div
                                        class="rounded-2xl border border-slate-200 bg-slate-50 p-4 flex items-center justify-center">
                                        <div class="relative h-20 w-20">
                                            <svg viewBox="0 0 36 36" class="h-20 w-20">
                                                <path
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                    fill="none" stroke="#e2e8f0" stroke-width="3.5" />
                                                <path
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                    fill="none" stroke="#121293" stroke-width="3.5"
                                                    stroke-linecap="round"
                                                    stroke-dasharray="{{ $pctJam }}, 100" />
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="text-sm font-bold text-slate-900">{{ $pctJam }}%</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Highlights --}}
                                    <div class="sm:col-span-2 grid grid-cols-1 gap-3">
                                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                                            <div
                                                class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                                Jam Pelatihan (JP) Tahun Berjalan
                                            </div>
                                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                                {{ $totalJam }} JP / Target {{ $targetJam }} JP
                                            </div>
                                            <div
                                                class="mt-2 h-2 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                                                <div class="h-2 bg-[#121293] rounded-full"
                                                    style="width: {{ $pctJam }}%"></div>
                                            </div>
                                            <div class="mt-2 text-xs text-slate-500">
                                                Rekomendasi: konsisten menambah JP agar target tercapai lebih cepat.
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                                <div
                                                    class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                                    Course Diikuti
                                                </div>
                                                <div class="mt-1 text-lg font-semibold text-slate-900">
                                                    {{ $courseAktif }}</div>
                                                <div class="text-xs text-slate-500">Sedang berjalan / terdaftar</div>
                                            </div>

                                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                                <div
                                                    class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                                    Course Selesai
                                                </div>
                                                <div class="mt-1 text-lg font-semibold text-slate-900">
                                                    {{ $courseSelesai }}</div>
                                                <div class="text-xs text-slate-500">Tuntas & tercatat</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-xs text-slate-500">
                                    Catatan: progres dihitung dari data penyelesaian course dan perolehan jam pelatihan.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- ================= KPI Personal (ikon + mini spark) ================= --}}
                    @php
                        $userStats = [
                            ['label' => 'Course Diikuti', 'value' => $courseAktif, 'icon' => 'book'],
                            ['label' => 'Course Selesai', 'value' => $courseSelesai, 'icon' => 'check'],
                            ['label' => 'Jam Diklat', 'value' => $totalJam . ' JP', 'icon' => 'clock'],
                            ['label' => 'Status Akun', 'value' => $accountStatus, 'icon' => 'user'],
                        ];
                    @endphp

                    <section class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($userStats as $s)
                            <div
                                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                            {{ $s['label'] }}
                                        </div>
                                        <div class="mt-2 text-2xl font-bold text-slate-900">
                                            <span class="{{ $s['label'] === 'Status Akun' ? 'text-green-700' : '' }}">
                                                {{ $s['value'] }}
                                            </span>
                                        </div>
                                        <div class="mt-2 text-xs text-slate-500">
                                            Snapshot: <span
                                                class="font-semibold text-slate-700">{{ now()->translatedFormat('H:i') }}</span>
                                        </div>
                                    </div>

                                    <div
                                        class="shrink-0 rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-slate-700 group-hover:border-[#121293]/30 group-hover:bg-[#121293]/5 transition">
                                        @if ($s['icon'] === 'book')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                                <path
                                                    d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5V4.5A2.5 2.5 0 0 1 6.5 2z" />
                                            </svg>
                                        @elseif ($s['icon'] === 'check')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M9 12l2 2 4-4" />
                                                <circle cx="12" cy="12" r="10" />
                                            </svg>
                                        @elseif ($s['icon'] === 'clock')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M12 6v6l4 2" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                {{-- mini spark bars --}}
                                <div class="mt-4 flex items-end gap-1.5 h-10">
                                    @for ($i = 0; $i < 10; $i++)
                                        <div class="w-full rounded-md bg-slate-100 overflow-hidden">
                                            <div class="w-full bg-[#121293]/30"
                                                style="height: {{ 20 + (($i * 9 + strlen((string) $s['value'])) % 70) }}%">
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </section>

                    {{-- ================= Mini Chart: JP Bulanan (NO ChartJS) ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
                        x-data="{ hover: null }">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-slate-900 font-semibold">Tren Jam Pelatihan Bulanan</h3>
                                    <p class="text-sm text-slate-500">Gambaran konsistensi belajar Anda per bulan.</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-semibold bg-[#121293]/5 text-[#121293] border border-[#121293]/20">
                                    Tahun {{ now()->year }}
                                </span>
                            </div>

                            <div class="mt-5">
                                <div class="flex items-end gap-2 h-40">
                                    @foreach ($monthly as $idx => $m)
                                        @php
                                            $val = (int) ($m['value'] ?? 0);
                                            $h = $maxMonthly > 0 ? max(6, (int) round(($val / $maxMonthly) * 100)) : 6;
                                        @endphp

                                        <div class="flex-1 min-w-[18px] relative">
                                            <div class="h-32 rounded-xl bg-slate-100 border border-slate-200 flex items-end overflow-hidden"
                                                @mouseenter="hover={{ $idx }}" @mouseleave="hover=null">
                                                <div class="w-full bg-[#121293]/80 rounded-xl transition-all"
                                                    style="height: {{ $h }}%"></div>
                                            </div>

                                            <div x-show="hover === {{ $idx }}" x-transition.opacity
                                                class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-900 shadow">
                                                {{ $val }} JP
                                            </div>

                                            <div class="mt-2 text-[11px] text-slate-500 text-center">
                                                {{ $m['label'] ?? '-' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 text-xs text-slate-500">
                                Total JP tahun berjalan: <span
                                    class="font-semibold text-slate-900">{{ $totalJam }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- ================= Progress Belajar ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-slate-900 font-semibold">Progres Belajar</h2>
                                    <p class="text-sm text-slate-500">Status course dan pencapaian Anda saat ini.</p>
                                </div>

                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                    Terbaru
                                </span>
                            </div>

                            <div class="mt-5 space-y-3">
                                @forelse ($progressList as $p)
                                    @php
                                        $percent = (int) ($p['percent'] ?? 0);
                                        $percent = max(0, min(100, $percent));
                                    @endphp

                                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                                        <div class="flex justify-between items-center">
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-slate-900 truncate">
                                                    {{ $p['title'] ?? 'Course' }}</div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $p['meta'] ?? 'Progres pembelajaran' }}</div>
                                            </div>
                                            <div class="text-xs font-bold text-[#121293]">{{ $percent }}%</div>
                                        </div>

                                        <div
                                            class="mt-3 h-2 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                                            <div class="h-2 bg-[#121293] rounded-full"
                                                style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-slate-200 bg-white p-6 text-center">
                                        <div class="text-sm font-semibold text-slate-900">Belum ada progres course yang
                                            ditampilkan</div>
                                        <div class="mt-1 text-sm text-slate-500">Mulai course untuk melihat progres
                                            Anda di sini.</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </section>

                    {{-- ================= Aktivitas Terakhir ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-slate-900 font-semibold">Aktivitas Terbaru</h2>
                                    <p class="text-sm text-slate-500">Ringkasan interaksi dan kegiatan pelatihan Anda.
                                    </p>
                                </div>

                                <a href="#" class="text-xs font-semibold text-[#121293] hover:underline">
                                    Lihat semua
                                </a>
                            </div>

                            <div class="mt-5 space-y-4">
                                @forelse ($timeline as $a)
                                    <div class="flex items-start gap-4">
                                        <div class="relative flex-none py-1">
                                            <div
                                                class="h-full w-0.5 bg-slate-100 absolute left-1/2 -translate-x-1/2 top-4">
                                            </div>
                                            <div
                                                class="h-3 w-3 rounded-full bg-[#121293] relative z-10 border-2 border-white shadow-sm">
                                            </div>
                                        </div>

                                        <div class="flex-1 pb-4">
                                            <div class="text-sm font-semibold text-slate-900">
                                                {{ $a['title'] ?? 'Aktivitas' }}</div>
                                            <div class="text-xs text-slate-500">
                                                {{ $a['meta'] ?? '-' }} @if (!empty($a['time']))
                                                    • {{ $a['time'] }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-slate-200 bg-white p-6 text-center">
                                        <div class="text-sm font-semibold text-slate-900">Belum ada aktivitas terbaru
                                        </div>
                                        <div class="mt-1 text-sm text-slate-500">Aktivitas akan muncul setelah Anda
                                            mulai mengikuti pelatihan.</div>
                                    </div>
                                @endforelse
                            </div>

                            <a href="#"
                                class="mt-6 block text-center py-2.5 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                                Lihat Pelatihan Saya
                            </a>
                        </div>
                    </section>
                </div>

                {{-- RIGHT --}}
                <aside class="lg:col-span-4 space-y-6">
                    @include('components.online-users')

                    {{-- ================= Certificates ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Sertifikat</div>
                                <div class="text-sm text-slate-500">Dokumen kelulusan yang sudah tersedia.</div>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">{{ $certs->count() }} file</span>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse ($certs as $c)
                                <div
                                    class="flex items-center gap-3 p-3 rounded-xl border border-green-100 bg-green-50/50">
                                    <div
                                        class="h-10 w-10 shrink-0 flex items-center justify-center rounded-lg bg-green-100 text-green-700">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-green-800 truncate">
                                            {{ $c['title'] ?? 'Sertifikat tersedia' }}
                                        </div>
                                        <div class="text-[11px] text-green-700">
                                            {{ $c['meta'] ?? 'Silakan unduh melalui menu sertifikat.' }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-white p-6 text-center">
                                    <div class="text-sm font-semibold text-slate-900">Belum ada sertifikat tersedia
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">Sertifikat muncul setelah course
                                        dinyatakan selesai.</div>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    {{-- ================= Quick Guidance ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                        <div class="text-sm font-semibold text-slate-900">Panduan Singkat</div>
                        <p class="mt-1 text-sm text-slate-500">Langkah yang disarankan untuk menjaga progres tetap
                            stabil.</p>

                        <div class="mt-4 space-y-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Fokus Minggu Ini</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    Selesaikan minimal 1 modul dan tambahkan jam pelatihan secara konsisten.
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Target</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $targetJam }} JP per tahun (indikatif).
                                </div>
                                <div class="mt-2 text-xs text-slate-500">
                                    Catatan: target dapat berbeda sesuai kebijakan institusi.
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>

            </div>
        </div>
    </div>

</x-app-layout>
