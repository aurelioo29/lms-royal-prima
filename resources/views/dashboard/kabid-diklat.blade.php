<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO: Sambutan & Role Kabid --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold text-slate-900">Kontrol Operasional Bidang</h1>
                                    <p class="text-sm text-slate-600">
                                        Selamat datang, <span
                                            class="font-semibold text-[#121293]">{{ auth()->user()->name }}</span>.
                                        Anda login sebagai <span
                                            class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-medium text-xs">Kepala
                                            Bidang</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm text-slate-500 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- STATS: Operasional Bidang --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Total Course
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-2xl font-bold text-slate-900">24</span>
                                <span class="text-blue-600 bg-blue-50 p-1.5 rounded-lg"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg></span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Narasumber Aktif
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-2xl font-bold text-slate-900">12</span>
                                <span class="text-green-600 bg-green-50 p-1.5 rounded-lg"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg></span>
                            </div>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm ring-2 ring-amber-500/20">
                            <div class="text-[10px] uppercase tracking-wider font-bold text-amber-600">Approval Pending
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-2xl font-bold text-amber-600">08</span>
                                <span class="text-amber-600 bg-amber-50 p-1.5 rounded-lg"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg></span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Jam Diklat (JP)
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-2xl font-bold text-slate-900">140</span>
                                <span class="text-purple-600 bg-purple-50 p-1.5 rounded-lg"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg></span>
                            </div>
                        </div>
                    </div>

                    {{-- RINGKASAN: Kontrol & Monitoring --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Monitoring Course & Validasi
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4">
                                {{-- Course butuh perhatian --}}
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                                    <div class="flex items-start gap-3">
                                        <div class="shrink-0 text-red-600 mt-1">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-red-800">Perhatian: Diklat Kepemimpinan
                                                Batch III</p>
                                            <p class="text-xs text-red-700 mt-0.5">Progress materi di bawah 40%. Harap
                                                cek ketersediaan narasumber.</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Approval --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div class="p-3 border border-slate-100 rounded-xl bg-slate-50">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase">Pengajuan Narasumber
                                            Baru</p>
                                        <p class="text-sm font-semibold text-slate-800 mt-1">4 Belum divalidasi</p>
                                        <a href="#"
                                            class="text-xs text-[#121293] hover:underline mt-2 inline-block font-medium">Buka
                                            Menu Approval &rarr;</a>
                                    </div>
                                    <div class="p-3 border border-slate-100 rounded-xl bg-slate-50">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase">Verifikasi Sertifikat
                                        </p>
                                        <p class="text-sm font-semibold text-slate-800 mt-1">12 Dokumen antri</p>
                                        <a href="#"
                                            class="text-xs text-[#121293] hover:underline mt-2 inline-block font-medium">Lihat
                                            Semua &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AKTIVITAS: Approval MOT & Jadwal --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 border-b border-slate-100">
                            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Log Operasional & Jadwal Berjalan
                            </h3>
                        </div>
                        <div class="p-0">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase">
                                    <tr>
                                        <th class="px-6 py-3 font-bold">Aktivitas / Course</th>
                                        <th class="px-6 py-3 font-bold">Status/MOT</th>
                                        <th class="px-6 py-3 font-bold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">Pelatihan Teknis IT</div>
                                            <div class="text-[11px] text-slate-500">Mulai: 28 Des 2023</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold">Budi
                                                Santoso (MOT)</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button class="text-[#121293] font-bold hover:underline">Detail</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">Approval Sertifikat Workshop</div>
                                            <div class="text-[11px] text-slate-500">Oleh: Admin Bidang</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold">Menunggu
                                                Validasi Kabid</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button
                                                class="bg-[#121293] text-white px-3 py-1 rounded text-[11px] font-bold">Validasi</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-4">
                    @include('components.online-users')
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
