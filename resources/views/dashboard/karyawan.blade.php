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
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path
                                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                        Ruang Belajar Peserta
                                    </h1>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Selamat datang kembali, <span
                                            class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>!
                                        <span class="text-slate-500 italic block sm:inline">Siap melanjutkan peningkatan
                                            kompetensi hari ini?</span>
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-[#121293]/20 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                            Status: Karyawan / Peserta
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

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT (Focus on Personal Learning) --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Stats - Fokus Pembelajaran --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $userStats = [
                                ['label' => 'Course Diikuti', 'value' => $stats['course_aktif'] ?? 0, 'icon' => 'book'],
                                [
                                    'label' => 'Course Selesai',
                                    'value' => $stats['course_selesai'] ?? 0,
                                    'icon' => 'check',
                                ],
                                [
                                    'label' => 'Jam Diklat',
                                    'value' => ($stats['total_jam'] ?? 0) . ' JP',
                                    'icon' => 'clock',
                                ],
                                ['label' => 'Status Akun', 'value' => 'Aktif', 'icon' => 'user'],
                            ];
                        @endphp

                        @foreach ($userStats as $s)
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                    {{ $s['label'] }}</div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-xl font-bold text-slate-900">
                                        <span class="{{ $s['label'] === 'Status Akun' ? 'text-green-600' : '' }}">
                                            {{ $s['value'] }}
                                        </span>
                                    </div>
                                    <div
                                        class="h-8 w-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                                        @if ($s['icon'] === 'book')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        @elseif($s['icon'] === 'check')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan Progress --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-slate-900 font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Progress Belajar & Sertifikat
                                </h2>
                            </div>

                            <div class="space-y-4">
                                {{-- Mockup Progress --}}
                                @foreach ($summary['progress'] ?? [] as $p)
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-slate-700">{{ $p['title'] }}</span>
                                            <span class="text-xs font-bold text-[#121293]">{{ $p['percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-[#121293] h-2 rounded-full"
                                                style="width: {{ $p['percent'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach


                                {{-- Sertifikat section --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($summary['certificates'] as $c)
                                        <div
                                            class="flex items-center gap-3 p-3 rounded-xl border border-green-100 bg-green-50/50">
                                            <div
                                                class="h-10 w-10 shrink-0 flex items-center justify-center rounded-lg bg-green-100 text-green-600">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-green-800 truncate">Sertifikat
                                                    Tersedia
                                                </div>
                                                <div class="text-[10px] text-green-600">{{ $c['title'] }}</div>
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center justify-center p-3 rounded-xl border border-dashed border-slate-200 text-slate-400 text-xs">
                                            Belum ada sertifikat baru lainnya
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aktivitas Terakhir & Jadwal --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <h2 class="text-slate-900 font-semibold mb-4">Aktivitas & Jadwal</h2>

                            <div class="space-y-4">
                                @foreach ($activities['timeline'] ?? [] as $a)
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
                                            <div class="text-sm font-semibold text-slate-900">{{ $a['title'] }}</div>
                                            <div class="text-xs text-slate-500">{{ $a['meta'] }} •
                                                {{ $a['time'] ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <a href="#"
                                class="mt-6 block text-center py-2 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                                Lihat Semua Pelatihan Saya
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT (Tetap sesuai struktur: Pengguna Online) --}}
                <div class="lg:col-span-4">
                    @include('components.online-users')
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
