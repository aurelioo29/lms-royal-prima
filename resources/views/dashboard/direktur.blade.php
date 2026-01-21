<x-app-layout>
    @php
        // ===== Chart data (optional) =====
        // Ideal: $charts['monthly_jpl'] = [ ['label'=>'Jan','value'=>10], ... ]
        $monthly = collect(data_get($charts ?? [], 'monthly_jpl', []));
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
        $yearLabel = data_get($charts ?? [], 'year', now()->year);

        // ===== Donut progress =====
        $progress = (int) ($summary['progress_percent'] ?? 0);
        $progress = max(0, min(100, $progress));

        // ===== KPI Icons mapping =====
        $kpiIcons = [
            'Total Karyawan Aktif' => 'users',
            'Course Aktif' => 'book',
            'Total JPL Tahun Ini' => 'clock',
            'Rata-rata JPL / Karyawan' => 'chart',
        ];
        $getIcon = fn($key) => $kpiIcons[$key] ?? 'dot';

        // ===== Insights =====
        $targetPerEmployee = (int) ($summary['target_per_employee'] ?? 0);
        $jplRows = collect($summary['jpl_per_employee'] ?? []);
        $achievedCount =
            $targetPerEmployee > 0
                ? $jplRows->filter(fn($r) => (int) ($r->total_jpl ?? 0) >= $targetPerEmployee)->count()
                : 0;

        $employeeCount = (int) data_get(collect($stats)->firstWhere('label', 'Total Karyawan Aktif'), 'value', 0);
        $achievedPct = $employeeCount > 0 ? (int) round(($achievedCount / $employeeCount) * 100) : 0;
        $achievedPct = max(0, min(100, $achievedPct));

        // ===== Top performer (optional) =====
        $topPerformer = $jplRows->first();
        $topName = data_get($topPerformer, 'name', '-');
        $topJpl = (int) data_get($topPerformer, 'total_jpl', 0);

        // ===== Bottom (optional) =====
        $bottomPerformer = $jplRows->sortBy('total_jpl')->first();
        $bottomName = data_get($bottomPerformer, 'name', '-');
        $bottomJpl = (int) data_get($bottomPerformer, 'total_jpl', 0);
    @endphp

    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= EXECUTIVE HERO ================= --}}
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
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">
                                        Executive Dashboard
                                    </h1>

                                    <p class="mt-1 text-sm text-slate-600">
                                        Ringkasan kinerja pengembangan SDM dan capaian pelatihan institusi untuk
                                        mendukung pengambilan keputusan.
                                    </p>

                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-[#121293]/30 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                            Jabatan: Direktur
                                        </span>

                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ now()->translatedFormat('l, d F Y') }}
                                        </span>

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Sistem Berjalan Normal
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Executive Scorecard --}}
                        <div class="shrink-0 w-full lg:w-[440px]">
                            <div class="rounded-2xl border border-slate-200 bg-white/85 p-5">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-slate-900">Executive Scorecard</div>
                                    <span class="text-xs font-semibold text-slate-500">Tahun {{ $yearLabel }}</span>
                                </div>

                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    {{-- Donut --}}
                                    <div
                                        class="sm:col-span-1 rounded-2xl border border-slate-200 bg-slate-50 p-4 flex items-center justify-center">
                                        <div class="relative h-20 w-20">
                                            <svg viewBox="0 0 36 36" class="h-20 w-20">
                                                <path
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                    fill="none" stroke="#e2e8f0" stroke-width="3.5" />
                                                <path
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                    fill="none" stroke="#121293" stroke-width="3.5"
                                                    stroke-linecap="round"
                                                    stroke-dasharray="{{ $progress }}, 100" />
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="text-sm font-bold text-slate-900">{{ $progress }}%</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Highlights --}}
                                    <div class="sm:col-span-2 grid grid-cols-1 gap-3">
                                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                                            <div
                                                class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                                Total JPL Tercatat / Target Institusi
                                            </div>
                                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                                {{ $summary['total_jpl'] ?? 0 }} / {{ $summary['target_jpl'] ?? 0 }} JPL
                                            </div>
                                            <div
                                                class="mt-2 h-2 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                                                <div class="h-2 bg-[#121293] rounded-full"
                                                    style="width: {{ $progress }}%"></div>
                                            </div>
                                            <div class="mt-2 text-xs text-slate-500">
                                                Target individu: {{ $summary['target_per_employee'] ?? 0 }} JPL per
                                                karyawan per tahun
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                                <div
                                                    class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                                    Rata-rata JPL / Karyawan
                                                </div>
                                                <div class="mt-1 text-lg font-semibold text-slate-900">
                                                    {{ $summary['avg_jpl'] ?? 0 }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    Tahun {{ $yearLabel }}
                                                </div>
                                            </div>

                                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                                <div
                                                    class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                                    Karyawan Memenuhi Target
                                                </div>
                                                <div class="mt-1 text-lg font-semibold text-slate-900">
                                                    {{ $achievedCount }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $achievedPct }}% dari karyawan aktif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-xs text-slate-500">
                                    Pembaruan: <span
                                        class="font-semibold text-slate-700">{{ now()->translatedFormat('H:i') }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- ================= KPI (ikon + spark bars) ================= --}}
            <section class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($stats as $stat)
                    @php $icon = $getIcon($stat['label']); @endphp

                    <div
                        class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                                    {{ $stat['label'] }}
                                </div>
                                <div class="mt-2 text-2xl font-bold text-slate-900">
                                    {{ $stat['value'] }}
                                </div>
                                <div class="mt-2 text-xs text-slate-500">
                                    Snapshot: <span
                                        class="font-semibold text-slate-700">{{ now()->translatedFormat('H:i') }}</span>
                                </div>
                            </div>

                            <div
                                class="shrink-0 rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-slate-700 group-hover:border-[#121293]/30 group-hover:bg-[#121293]/5 transition">
                                @if ($icon === 'users')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                @elseif ($icon === 'book')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5V4.5A2.5 2.5 0 0 1 6.5 2z" />
                                    </svg>
                                @elseif ($icon === 'clock')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                @elseif ($icon === 'chart')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M3 3v18h18" />
                                        <path d="M7 14l3-3 4 4 5-6" />
                                    </svg>
                                @else
                                    <span class="text-sm font-black">•</span>
                                @endif
                            </div>
                        </div>

                        {{-- mini spark bars --}}
                        <div class="mt-4 flex items-end gap-1.5 h-10">
                            @for ($i = 0; $i < 10; $i++)
                                <div class="w-full rounded-md bg-slate-100 overflow-hidden">
                                    <div class="w-full bg-[#121293]/30"
                                        style="height: {{ 20 + (($i * 7 + strlen((string) $stat['value'])) % 70) }}%">
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </section>

            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- ================= CHART: JPL BULANAN (NO ChartJS) ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
                        x-data="{ hover: null }">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-slate-900 font-semibold">Tren Capaian JPL Bulanan</h3>
                                    <p class="text-sm text-slate-500">
                                        Visualisasi kontribusi jam pelatihan per bulan untuk memantau konsistensi
                                        peningkatan kompetensi.
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-semibold bg-[#121293]/5 text-[#121293] border border-[#121293]/20">
                                    Tahun {{ $yearLabel }}
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
                                                {{ $val }} JPL
                                            </div>

                                            <div class="mt-2 text-[11px] text-slate-500 text-center">
                                                {{ $m['label'] ?? '-' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 text-xs text-slate-500">
                                Total JPL tahun berjalan: <span
                                    class="font-semibold text-slate-900">{{ $summary['total_jpl'] ?? 0 }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- ================= JPL PER KARYAWAN ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-6 space-y-4">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h3 class="text-slate-900 font-semibold">Capaian JPL per Karyawan</h3>
                                    <p class="text-sm text-slate-500">
                                        Monitoring pencapaian individu terhadap target tahunan
                                        ({{ $summary['target_per_employee'] ?? 0 }} JPL).
                                    </p>
                                </div>

                                <input type="text" id="employeeSearch" placeholder="Cari karyawan (nama)..."
                                    class="w-full sm:w-72 rounded-xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/40">
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b text-left text-slate-500">
                                            <th class="py-2">Nama</th>
                                            <th class="py-2">Total JPL</th>
                                            <th class="py-2">Status</th>
                                            <th class="py-2">Progres</th>
                                        </tr>
                                    </thead>
                                    <tbody id="employeeTable">
                                        @forelse ($summary['jpl_per_employee'] as $row)
                                            @php
                                                $target = (int) ($summary['target_per_employee'] ?? 1);
                                                $val = (int) ($row->total_jpl ?? 0);
                                                $pct = $target > 0 ? min(100, (int) round(($val / $target) * 100)) : 0;
                                            @endphp

                                            <tr class="border-b employee-row hover:bg-slate-50/70 transition">
                                                <td class="py-3 font-medium text-slate-900 employee-name">
                                                    {{ $row->name ?? '-' }}
                                                </td>
                                                <td class="py-3">
                                                    {{ $val }} JPL
                                                </td>
                                                <td class="py-3">
                                                    @if ($val >= $target)
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-green-50 text-green-700 px-2.5 py-1 text-xs font-semibold">
                                                            Memenuhi Target
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-2.5 py-1 text-xs font-semibold">
                                                            Belum Memenuhi
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    <div class="w-44 max-w-full">
                                                        <div
                                                            class="h-2 rounded-full bg-slate-100 border border-slate-200 overflow-hidden">
                                                            <div class="h-2 bg-[#121293] rounded-full"
                                                                style="width: {{ $pct }}%"></div>
                                                        </div>
                                                        <div class="mt-1 text-[11px] text-slate-500">
                                                            {{ $pct }}%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-8 text-center text-slate-400">
                                                    Data JPL karyawan belum tersedia.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </section>

                </div>

                {{-- RIGHT --}}
                <aside class="lg:col-span-4 space-y-6">
                    @include('components.online-users')

                    {{-- ================= EXECUTIVE SUMMARY ================= --}}
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                        <div class="text-sm font-semibold text-slate-900">Ringkasan Eksekutif</div>
                        <p class="mt-1 text-sm text-slate-500">
                            Indikator utama untuk pemantauan dan arah kebijakan pelatihan.
                        </p>

                        <div class="mt-4 space-y-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Capaian Institusi</div>
                                <div class="mt-1 text-lg font-semibold text-slate-900">{{ $progress }}%</div>
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $summary['total_jpl'] ?? 0 }} dari {{ $summary['target_jpl'] ?? 0 }} JPL
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Karyawan Memenuhi Target</div>
                                <div class="mt-1 text-lg font-semibold text-slate-900">{{ $achievedCount }}</div>
                                <div class="text-xs text-slate-500">{{ $achievedPct }}% dari karyawan aktif</div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Performa Tertinggi</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">{{ $topName }}</div>
                                <div class="text-xs text-slate-500">{{ $topJpl }} JPL</div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Perlu Perhatian</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">{{ $bottomName }}</div>
                                <div class="text-xs text-slate-500">{{ $bottomJpl }} JPL</div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs text-slate-500">Arah Tindak Lanjut</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    Prioritaskan percepatan pelatihan pada karyawan dengan progres rendah agar capaian
                                    institusi sesuai target tahunan.
                                </div>
                                <div class="mt-2 text-xs text-slate-500">
                                    Fokus: kelompok &lt; 50% target dan unit dengan gap terbesar.
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>

            </div>
        </div>
    </div>

    {{-- ================= SEARCH SCRIPT ================= --}}
    <script>
        document.getElementById('employeeSearch')?.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('#employeeTable .employee-row').forEach(row => {
                const name = row.querySelector('.employee-name')?.innerText?.toLowerCase() || '';
                row.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
