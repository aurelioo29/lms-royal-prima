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
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                            Halo, Narasumber!
                                        </h1>
                                        <p class="mt-1 text-sm text-slate-600">
                                            Selamat datang kembali, <span
                                                class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>.
                                            Siap untuk berbagi ilmu hari ini?
                                        </p>

                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full border border-[#121293]/20 bg-[#121293]/5 px-3 py-1 text-xs font-semibold text-[#121293]">
                                                Role: Narasumber
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

                {{-- MOT CARD (TETAP ASLI) --}}
                @php
                    $mot = $summary['mot'] ?? null;
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="text-slate-900 font-semibold">Dokumen MOT</div>

                                    @php
                                        $badge = 'bg-slate-50 text-slate-700 border-slate-200';
                                        $badgeText = 'Belum Upload';
                                        if ($mot) {
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

                                @if (!$mot)
                                    <p class="text-sm text-slate-600 mt-2">Upload MOT dulu supaya bisa mengajar.</p>
                                @elseif($mot->status === 'pending')
                                    <p class="text-sm text-slate-600 mt-2">Menunggu approval Admin.</p>
                                @elseif($mot->status === 'rejected')
                                    <p class="text-sm text-slate-600 mt-2">Dokumen ditolak, mohon revisi dan upload
                                        ulang.</p>
                                    @if ($mot->rejected_reason)
                                        <p class="text-sm text-slate-500 mt-1">Alasan: <span
                                                class="text-slate-700 font-medium">{{ $mot->rejected_reason }}</span>
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm text-slate-600 mt-2">Dokumen valid dan siap digunakan.</p>
                                @endif
                            </div>

                            <a href="{{ route('instructor.mot.show') }}"
                                class="inline-flex justify-center shrink-0 px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90 text-sm font-semibold">
                                {{ isset($mot) ? 'Lihat / Upload Ulang' : 'Upload MOT' }}
                            </a>
                        </div>
                    </div>
                </div>


                {{-- MAIN GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    {{-- LEFT --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- Stats - Narasumber Focused --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            {{-- Course Diajar --}}
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Course Diajar
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-2xl font-bold text-slate-900">{{ $stats['total_course'] ?? '0' }}
                                    </div>
                                    <div class="text-[#121293] bg-[#121293]/5 p-2 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            {{-- Jam Mengajar --}}
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Jam Mengajar
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-2xl font-bold text-slate-900">{{ $stats['total_hours'] ?? '0' }}h
                                    </div>
                                    <div class="text-amber-600 bg-amber-50 p-2 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            {{-- Peserta Aktif --}}
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Peserta Aktif
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-2xl font-bold text-slate-900">
                                        {{ $stats['active_students'] ?? '0' }}
                                    </div>
                                    <div class="text-green-600 bg-green-50 p-2 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            {{-- Status Akun --}}
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                                <div class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Status Akun
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-sm font-bold text-green-600">
                                        {{ $stats['account_status'] ?? '-' }}
                                    </div>
                                    <div class="text-slate-400 bg-slate-50 p-2 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.040L3 14.535a11.955 11.955 0 001.382 5.33l1.243 1.109l.643-.548l.643.548l1.243-1.109a11.955 11.955 0 001.382-5.33L9 12.016z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ringkasan Mengajar --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-slate-900 font-bold flex items-center gap-2">
                                        <svg class="h-5 w-5 text-[#121293]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Ringkasan Mengajar
                                    </h3>
                                    <a href="#"
                                        class="text-xs font-semibold text-[#121293] hover:underline">Lihat
                                        Semua</a>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Jadwal Terdekat --}}
                                    @php
                                        $schedule = $summary['upcoming_schedule'] ?? null;
                                    @endphp

                                    <div
                                        class="flex flex-col gap-3 p-4 rounded-xl border border-slate-100 bg-slate-50">
                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-tight">
                                            Jadwal
                                            Terdekat</div>
                                        @if ($schedule)
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex flex-col items-center justify-center h-12 w-12 rounded-lg bg-white border border-slate-200 text-[#121293]">
                                                    <span class="text-lg font-bold text-slate-800">
                                                        {{ $schedule->planEvent->start_date->format('d') }}
                                                    </span>
                                                    <span class="text-xs font-semibold uppercase text-slate-500">
                                                        {{ $schedule->planEvent->start_date->format('M') }}
                                                    </span>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-bold text-slate-800">
                                                        {{-- {{ $schedule->planEvent->title }} --}}
                                                    </div>
                                                    <div class="text-sm text-slate-500">
                                                        {{ $schedule->planEvent->start_date->format('H:i') }}
                                                        –
                                                        {{ $schedule->planEvent->end_date->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-sm text-slate-500 italic">Belum ada jadwal dalam waktu
                                                dekat.
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Aktivitas & Progress --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-slate-900 font-bold">Progress Mengajar</h3>
                                    <span
                                        class="px-2 py-1 rounded bg-slate-100 text-[10px] font-bold text-slate-500">REALTIME</span>
                                </div>

                                <div class="space-y-6">
                                    {{-- Progress Item --}}
                                    @forelse ($activities['teaching_progress'] as $progress)
                                        <div>
                                            <div class="flex justify-between items-end mb-2">
                                                <span class="text-sm font-bold text-slate-700">
                                                    {{-- {{ $progress->planEvent->title }} --}}
                                                </span>
                                                <span class="text-xs font-semibold text-slate-500">
                                                    {{ ucfirst($progress->status) }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-2">
                                                <div class="bg-indigo-600 h-2 rounded-full"
                                                    style="width: {{ $progress->progress_percent }}%">
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <p class="text-sm text-slate-500 italic">
                                            Belum ada aktivitas mengajar.
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT (WIDGET ONLINE TETAP ASLI) --}}
                    <div class="lg:col-span-4">
                        @include('components.online-users')
                    </div>

                </div>
            </div>
        </div>

    </x-app-layout>
