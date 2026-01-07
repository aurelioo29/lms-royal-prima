<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= HERO ================= --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                Dashboard
                            </h1>
                            <p class="mt-1 text-sm text-slate-600">
                                Selamat datang kembali,
                                <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>.
                                Pantau performa pengembangan SDM institusi.
                            </p>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-full border border-[#121293]/20 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                    Jabatan: Admin
                                </span>

                                <span
                                    class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ now()->translatedFormat('l, d F Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= KPI ================= --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($stats as $stat)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">
                            {{ $stat['label'] }}
                        </div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $stat['value'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ================= MAIN GRID ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- ================= RINGKASAN STRATEGIS ================= --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6 space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-slate-900 font-bold">
                                        Ringkasan Strategis
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        Progress pencapaian JPL institusi
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-full tracking-widest">
                                    Real-time
                                </span>
                            </div>

                            {{-- Progress --}}
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-700 font-medium">
                                        Progress JPL Tahunan
                                    </span>
                                    <span class="text-[#121293] font-bold">
                                        {{ $summary['progress_percent'] }}%
                                    </span>
                                </div>

                                <div class="w-full bg-slate-100 rounded-full h-3">
                                    <div class="bg-[#121293] h-3 rounded-full transition-all"
                                        style="width: {{ $summary['progress_percent'] }}%">
                                    </div>
                                </div>

                                <p class="mt-2 text-xs text-slate-500">
                                    {{ $summary['total_jpl'] }} /
                                    {{ $summary['target_jpl'] }} JPL
                                    (Target {{ $summary['target_per_employee'] }} JPL / Karyawan / Tahun)
                                </p>
                            </div>

                            {{-- Summary cards --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <div class="text-xs text-slate-500 mb-1">
                                        Total JPL Saat Ini
                                    </div>
                                    <div class="text-lg font-semibold text-slate-900">
                                        {{ $summary['total_jpl'] }} Jam
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <div class="text-xs text-slate-500 mb-1">
                                        Rata-rata JPL / Karyawan
                                    </div>
                                    <div class="text-lg font-semibold text-slate-900">
                                        {{ $summary['avg_jpl'] }} Jam
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <div class="text-xs text-slate-500 mb-1">
                                        Target Institusi
                                    </div>
                                    <div class="text-lg font-semibold text-slate-900">
                                        {{ $summary['target_jpl'] }} Jam
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= JPL PER KARYAWAN ================= --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6 space-y-4">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h3 class="text-slate-900 font-bold">
                                        Capaian JPL Karyawan
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        Target {{ $summary['target_per_employee'] }} JPL / Tahun
                                    </p>
                                </div>

                                <input type="text" id="employeeSearch" placeholder="Cari nama karyawan..."
                                    class="w-full sm:w-64 rounded-xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/40">
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b text-left text-slate-500">
                                            <th class="py-2">Nama</th>
                                            <th class="py-2">Total JPL</th>
                                            <th class="py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="employeeTable">
                                        @forelse ($summary['jpl_per_employee'] as $row)
                                            <tr class="border-b employee-row">
                                                <td class="py-2 font-medium text-slate-900 employee-name">
                                                    {{ $row->name ?? '-' }}
                                                </td>
                                                <td class="py-2">
                                                    {{ $row->total_jpl }} JPL
                                                </td>
                                                <td class="py-2">
                                                    @if ($row->total_jpl >= $summary['target_per_employee'])
                                                        <span class="text-green-600 font-semibold">✔ Tercapai</span>
                                                    @else
                                                        <span class="text-amber-600 font-semibold">⏳ Belum</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-6 text-center text-slate-400">
                                                    Belum ada data JPL karyawan
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="lg:col-span-4">
                    @include('components.online-users')
                </div>

            </div>
        </div>
    </div>

    {{-- ================= SEARCH SCRIPT ================= --}}
    <script>
        document.getElementById('employeeSearch')?.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#employeeTable .employee-row');

            rows.forEach(row => {
                const name = row.querySelector('.employee-name').innerText.toLowerCase();
                row.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
