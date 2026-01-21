<x-app-layout>
    @php
        $name = auth()->user()->name ?? 'Narasumber';
        $today = now()->translatedFormat('l, d F Y');

        // MOT
        $mot = $summary['mot'] ?? null;

        // Badge MOT
        $badgeClass = 'bg-slate-50 text-slate-700 border-slate-200';
        $badgeText = 'Belum Diunggah';
        if ($mot) {
            if ($mot->status === 'pending') {
                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                $badgeText = 'Menunggu Verifikasi';
            } elseif ($mot->status === 'rejected') {
                $badgeClass = 'bg-red-50 text-red-700 border-red-200';
                $badgeText = 'Perlu Revisi';
            } else {
                $badgeClass = 'bg-green-50 text-green-700 border-green-200';
                $badgeText = 'Disetujui';
            }
        }

        // Stats (fallback)
        $totalCourse = (int) ($stats['total_course'] ?? 0);
        $totalHours = (float) ($stats['total_hours'] ?? 0);
        $activeStudents = (int) ($stats['active_students'] ?? 0);
        $accountStatus = $stats['account_status'] ?? 'Aktif';

        // Optional: monthly teaching hours chart (no ChartJS)
        // Ideal: $summary['monthly_hours'] = [ ['label'=>'Jan','value'=>2], ... ]
        $monthly = collect($summary['monthly_hours'] ?? []);
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

        // Upcoming schedule (optional)
        $schedule = $summary['upcoming_schedule'] ?? null;

        // MOT action label
        $motActionLabel = $mot ? 'Lihat / Unggah Ulang' : 'Unggah MOT';
        $motHintTitle = 'Status Dokumen MOT';
        $motHintText = 'Dokumen diperlukan untuk proses penugasan mengajar dan validasi administrasi.';
    @endphp

    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= HERO ================= --}}
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
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">
                                        Dashboard Narasumber
                                    </h1>

                                    <p class="mt-1 text-sm text-slate-600">
                                        Selamat datang, <span
                                            class="font-semibold text-slate-900">{{ $name }}</span>.
                                        Kelola dokumen, pantau jadwal, dan lihat ringkasan aktivitas mengajar Anda.
                                    </p>

                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-[#121293]/30 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                            Peran: Narasumber
                                        </span>

                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $today }}
                                        </span>

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Sistem Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Quick Scorecard --}}
                        <div class="shrink-0 w-full lg:w-[420px]">
                            <div class="rounded-2xl border border-slate-200 bg-white/85 p-5">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-slate-900">Ringkasan Cepat</div>
                                    <span class="text-xs font-semibold text-slate-500">Update
                                        {{ now()->translatedFormat('H:i') }}</span>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                            Course Diajar</div>
                                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $totalCourse }}</div>
                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                            Jam Mengajar</div>
                                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $totalHours }}h</div>
                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                            Peserta Aktif</div>
                                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $activeStudents }}</div>
                                    </div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                            Status Akun</div>
                                        <div class="mt-1 text-sm font-bold text-green-700">{{ $accountStatus }}</div>
                                        <div
                                            class="mt-2 h-2 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                                            <div class="h-2 bg-[#121293] rounded-full" style="width: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-xs text-slate-500">
                                    Catatan: ringkasan menampilkan indikator operasional narasumber secara singkat.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- ================= MOT CARD (TETAP, tapi lebih rapi) ================= --}}
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="text-slate-900 font-semibold">{{ $motHintTitle }}</div>
                                <span
                                    class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ $badgeText }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-slate-600">
                                {{ $motHintText }}
                            </p>

                            @if (!$mot)
                                <p class="mt-2 text-sm text-slate-600">
                                    Silakan unggah dokumen MOT untuk mengaktifkan proses penjadwalan dan penugasan
                                    mengajar.
                                </p>
                            @elseif ($mot->status === 'pending')
                                <p class="mt-2 text-sm text-slate-600">
                                    Dokumen sedang menunggu verifikasi. Anda akan mendapatkan pembaruan setelah proses
                                    selesai.
                                </p>
                            @elseif ($mot->status === 'rejected')
                                <p class="mt-2 text-sm text-slate-600">
                                    Dokumen memerlukan perbaikan. Silakan lakukan revisi dan unggah ulang.
                                </p>
                                @if (!empty($mot->rejected_reason))
                                    <p class="mt-1 text-sm text-slate-500">
                                        Catatan verifikator: <span
                                            class="text-slate-700 font-medium">{{ $mot->rejected_reason }}</span>
                                    </p>
                                @endif
                            @else
                                <p class="mt-2 text-sm text-slate-600">
                                    Dokumen telah disetujui dan dapat digunakan sebagai dasar penugasan mengajar.
                                </p>
                            @endif
                        </div>

                        <div class="shrink-0 flex flex-col gap-2 w-full lg:w-auto">
                            <a href="{{ route('instructor.mot.show') }}"
                                class="inline-flex justify-center px-4 py-2.5 rounded-xl bg-[#121293] text-white hover:opacity-90 text-sm font-semibold">
                                {{ $motActionLabel }}
                            </a>

                            <div class="text-xs text-slate-500">
                                Pastikan format dokumen sesuai ketentuan.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- ================= TEACHING TREND (NO ChartJS) ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
                        x-data="{ hover: null }">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-slate-900 font-semibold">Tren Jam Mengajar Bulanan</h3>
                                    <p class="text-sm text-slate-500">Indikasi konsistensi aktivitas mengajar per bulan.
                                    </p>
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
                                                {{ $val }} jam
                                            </div>

                                            <div class="mt-2 text-[11px] text-slate-500 text-center">
                                                {{ $m['label'] ?? '-' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 text-xs text-slate-500">
                                Total jam mengajar: <span
                                    class="font-semibold text-slate-900">{{ $totalHours }}</span> jam
                            </div>
                        </div>
                    </section>

                    {{-- ================= RINGKASAN MENGAJAR ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-slate-900 font-semibold flex items-center gap-2">
                                        <svg class="h-5 w-5 text-[#121293]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Ringkasan Mengajar
                                    </h3>
                                    <p class="text-sm text-slate-500">Pantau jadwal terdekat dan akses daftar course.
                                    </p>
                                </div>

                                <a href="{{ route('instructor.courses.index') }}"
                                    class="text-xs font-semibold text-[#121293] hover:underline">
                                    Kelola Course
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Jadwal Terdekat --}}
                                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                                    <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                        Jadwal Terdekat</div>

                                    @if ($schedule)
                                        <div class="mt-3 flex items-center gap-3">
                                            <div
                                                class="flex flex-col items-center justify-center h-12 w-12 rounded-xl bg-white border border-slate-200">
                                                <span class="text-lg font-bold text-slate-900">
                                                    {{ $schedule->start_date->format('d') }}
                                                </span>
                                                <span class="text-[11px] font-semibold uppercase text-slate-500">
                                                    {{ $schedule->start_date->translatedFormat('M') }}
                                                </span>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-slate-900 truncate">
                                                    {{ $schedule->title ?? 'Sesi Mengajar' }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $schedule->start_date->format('H:i') }} –
                                                    {{ $schedule->end_date->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 text-sm text-slate-500 italic">
                                            Belum ada jadwal mengajar dalam waktu dekat.
                                        </div>
                                    @endif
                                </div>

                                {{-- Status MOT mini --}}
                                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                                    <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                        Kesiapan Administrasi</div>

                                    <div class="mt-3 flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-slate-900">Dokumen MOT</div>
                                            <div class="text-xs text-slate-500">
                                                Pastikan dokumen valid untuk kelancaran proses mengajar.
                                            </div>
                                        </div>

                                        <span
                                            class="shrink-0 inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    </div>

                                    <a href="{{ route('instructor.mot.show') }}"
                                        class="mt-3 inline-flex text-xs font-semibold text-[#121293] hover:underline">
                                        Buka halaman MOT
                                    </a>
                                </div>

                            </div>
                        </div>
                    </section>

                </div>

                {{-- RIGHT --}}
                <aside class="lg:col-span-4 space-y-6">
                    @include('components.online-users')

                    {{-- ================= QUICK ACTIONS ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                        <div class="text-sm font-semibold text-slate-900">Aksi Cepat</div>
                        <p class="mt-1 text-sm text-slate-500">Akses fitur yang paling sering digunakan.</p>

                        <div class="mt-4 space-y-3">
                            <a href="{{ route('instructor.courses.index') }}"
                                class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100 transition">
                                <div class="text-sm font-semibold text-slate-900">Kelola Course</div>
                                <span class="text-xs font-semibold text-slate-500">Buka</span>
                            </a>

                            <a href="{{ route('instructor.mot.show') }}"
                                class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100 transition">
                                <div class="text-sm font-semibold text-slate-900">Dokumen MOT</div>
                                <span class="text-xs font-semibold text-slate-500">Buka</span>
                            </a>

                            <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                                <div class="text-xs text-slate-500">Catatan</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    Pastikan dokumen dan jadwal terkonfirmasi sebelum sesi dimulai.
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
