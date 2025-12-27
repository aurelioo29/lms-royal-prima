<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO / TOP SUMMARY --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <polyline points="9 22 9 12 15 12 15 22" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                        @if (auth()->user()->hasRole('direktur'))
                                            Executive Dashboard
                                        @else
                                            Dashboard
                                        @endif
                                    </h1>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Selamat datang kembali, <span
                                            class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>.
                                        <span class="text-slate-500">
                                            @if (auth()->user()->hasRole('direktur'))
                                                Pantau perkembangan dan performa institusi Anda hari ini.
                                            @else
                                                Ringkasan cepat aktivitas Anda hari ini.
                                            @endif
                                        </span>
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-[#121293]/20 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                            Jabatan: {{ auth()->user()->role->name ?? 'Direktur' }}
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
                </div>
            </div>

            {{-- MOT CARD (Hanya untuk Narasumber) --}}
            @if (auth()->user()->isNarasumber())
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    {{-- ... (Konten MOT Anda tetap sama) ... --}}
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="text-slate-900 font-semibold">Dokumen MOT</div>
                                    @php
                                        $badge = 'bg-slate-50 text-slate-700 border-slate-200';
                                        $badgeText = 'Belum Upload';
                                        if (isset($mot)) {
                                            if ($mot->status === 'pending') {
                                                $badge = 'bg-amber-50 text-amber-700 border-amber-200';
                                                $badgeText = 'Pending';
                                            } elseif ($mot->status === 'rejected') {
                                                $badge = 'bg-red-50 text-red-700 border-red-200';
                                                $badgeText = 'Rejected';
                                            } else {
                                                $badge = 'bg-green-50 text-green-700 border-green-200';
                                                $badgeText = 'Approved';
                                            }
                                        }
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badge }}">
                                        {{ $badgeText }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 mt-2">Kelola kelengkapan administrasi mengajar Anda di
                                    sini.</p>
                            </div>
                            <a href="#"
                                class="inline-flex justify-center shrink-0 px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90 text-sm font-semibold">
                                Lihat Dokumen
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Stats Section --}}
                    @if (auth()->user()->hasRole('direktur'))
                        {{-- DIREKTUR STATS --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Total Peserta
                                </div>
                                <div class="mt-2 text-2xl font-bold text-slate-900">1,284</div>
                                <div class="text-xs text-green-600 mt-1">↑ 12% bln ini</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Narasumber
                                </div>
                                <div class="mt-2 text-2xl font-bold text-slate-900">42</div>
                                <div class="text-xs text-slate-400 mt-1">Pakar Aktif</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Course Aktif
                                </div>
                                <div class="mt-2 text-2xl font-bold text-slate-900">18</div>
                                <div class="text-xs text-blue-600 mt-1">8 Sedang Jalan</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Jam Diklat
                                </div>
                                <div class="mt-2 text-2xl font-bold text-slate-900">3,450</div>
                                <div class="text-xs text-slate-400 mt-1">Kumulatif</div>
                            </div>
                        </div>
                    @else
                        {{-- DEFAULT STATS --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach ($stats ?? [] as $s)
                                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                                    <div class="text-xs text-slate-500">{{ $s['label'] }}</div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <div class="text-2xl font-semibold text-slate-900">
                                            {{ $s['value'] }}
                                        </div>
                                        <div
                                            class="h-10 w-10 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M4 19V5h2v14H4Zm14 0V9h2v10h-2ZM9 19V12h2v7H9Zm5 0V7h2v12h-2Z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- STRATEGIC SUMMARY / RINGKASAN --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-slate-900 font-bold">Ringkasan Strategis</h3>
                                    <p class="text-sm text-slate-500">Status pencapaian target institusi</p>
                                </div>
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-full tracking-widest">
                                    Real-time
                                </span>
                            </div>

                            @if (auth()->user()->hasRole('direktur'))
                                <div class="space-y-6">
                                    {{-- Progres Pelatihan --}}
                                    <div>
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="text-slate-700 font-medium">Progress Pelatihan Tahunan</span>
                                            <span class="text-[#121293] font-bold">75%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-3">
                                            <div class="bg-[#121293] h-3 rounded-full" style="width: 75%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                            <div class="text-xs text-slate-500 mb-1">Course Paling Aktif</div>
                                            <div class="text-sm font-semibold text-slate-900">Sertifikasi Manajemen
                                                Risiko TI</div>
                                            <div class="text-xs text-slate-400 mt-1">120 Peserta Terdaftar</div>
                                        </div>
                                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                            <div class="text-xs text-slate-500 mb-1">Rata-rata Jam/Karyawan</div>
                                            <div class="text-sm font-semibold text-slate-900">32.5 Jam / Tahun</div>
                                            <div class="text-xs text-green-600 mt-1">Mencapai Target KPI</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                        ✅ Quick wins: taruh info penting per role di sini.
                                    </div>
                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                        📌 Next: progres jam diklat, course aktif, approval pending.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- AKTIVITAS & APPROVAL --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-slate-900 font-bold">Aktivitas & Prioritas</h3>
                                @if (auth()->user()->hasRole('direktur'))
                                    <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded">3
                                        Approval Menunggu</span>
                                @endif
                            </div>

                            <div class="space-y-4">
                                @if (auth()->user()->hasRole('direktur'))
                                    {{-- Approval List for Director --}}
                                    <div
                                        class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-10 w-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">Pengajuan Anggaran
                                                    Diklat Q3</div>
                                                <div class="text-xs text-slate-500">Diajukan oleh: Kabid SDM • 2 jam
                                                    yang lalu</div>
                                            </div>
                                        </div>
                                        <button
                                            class="text-xs font-bold text-[#121293] hover:underline">Tinjau</button>
                                    </div>

                                    <div
                                        class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 2v-6m10 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">Laporan Bulanan
                                                    Keaktifan Peserta</div>
                                                <div class="text-xs text-slate-500">Sistem • Otomatis • Kemarin</div>
                                            </div>
                                        </div>
                                        <button class="text-xs font-bold text-[#121293] hover:underline">Unduh
                                            PDF</button>
                                    </div>
                                @else
                                    <div
                                        class="border border-dashed border-slate-200 rounded-2xl p-6 text-sm text-slate-400 text-center">
                                        Belum ada aktivitas baru untuk ditampilkan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN (TIDAK BERUBAH) --}}
                <div class="lg:col-span-4">
                    @include('components.online-users')
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
