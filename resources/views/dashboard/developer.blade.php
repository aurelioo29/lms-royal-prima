<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO / TOP SUMMARY --}}
            <div class="relative overflow-hidden rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm hover:shadow-md transition"
                x-data="{ tipOpen: false }">

                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-600 via-amber-500 to-rose-600"></div>
                <div
                    class="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-rose-500/10 blur-2xl">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-amber-400/10 blur-3xl">
                </div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                        {{-- Left --}}
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-11 w-11 items-center justify-center rounded-2xl
                                           bg-gradient-to-br from-rose-600/15 to-amber-500/15
                                           text-rose-700 border border-rose-200/40">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill="currentColor"
                                            d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.8V17a2 2 0 0 1-2 2h-1v2h-2v-2H10a2 2 0 0 1-2-2v-2.2c-1.8-1.3-3-3.4-3-5.8a7 7 0 0 1 7-7Zm-2 19h4v-2h-4v2Z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900">Dashboard</h1>

                                    <p class="mt-1 text-sm text-slate-600">
                                        Selamat datang, <span
                                            class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>.
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-rose-200/60 bg-white/70 px-3 py-1 text-xs font-semibold text-rose-800">
                                            Role: {{ auth()->user()->role->name ?? '—' }}
                                        </span>

                                        <span
                                            class="inline-flex items-center rounded-full border border-amber-200/60 bg-white/70 px-3 py-1 text-xs font-semibold text-amber-800">
                                            {{ now()->translatedFormat('l, d F Y') }}
                                        </span>

                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-3 py-1 text-xs font-semibold text-slate-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Optional info --}}
                            <div class="mt-4">
                                <button @click="tipOpen = !tipOpen"
                                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white/70">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill="currentColor"
                                                d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20Zm1 14h-2v-2h2v2Zm0-4h-2V6h2v6Z" />
                                        </svg>
                                    </span>
                                    Informasi
                                    <svg class="h-4 w-4 transition-transform" :class="tipOpen ? 'rotate-180' : ''"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                                    </svg>
                                </button>

                                <div x-show="tipOpen" x-collapse.duration.200ms
                                    class="mt-2 rounded-xl border border-slate-200/70 bg-white/70 p-4 text-sm text-slate-700">
                                    Ringkasan menampilkan status utama, pintasan menu, dan aktivitas terbaru.
                                </div>
                            </div>
                        </div>

                        {{-- Right: Quick Actions --}}
                        <div class="shrink-0 w-full lg:w-[360px]">
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-slate-900">Akses Cepat</div>
                                    <span class="text-xs text-slate-500">Shortcut</span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <a href="{{ route('calendar.index') }}"
                                        class="group rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800
                                               hover:-translate-y-0.5 hover:shadow-sm transition">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-700">
                                                📅
                                            </span>
                                            <div class="min-w-0">
                                                <div class="truncate">Kalender</div>
                                                <div class="text-xs text-slate-500 font-medium">Agenda</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('annual-plans.index') }}"
                                        class="group rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800
                                               hover:-translate-y-0.5 hover:shadow-sm transition">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 border border-rose-200 text-rose-800">
                                                🗓️
                                            </span>
                                            <div class="min-w-0">
                                                <div class="truncate">Annual Plan</div>
                                                <div class="text-xs text-slate-500 font-medium">Daftar</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ route('courses.index') }}"
                                        class="group rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800
                                               hover:-translate-y-0.5 hover:shadow-sm transition">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 border border-amber-200 text-amber-900">
                                                📚
                                            </span>
                                            <div class="min-w-0">
                                                <div class="truncate">Courses</div>
                                                <div class="text-xs text-slate-500 font-medium">Manajemen</div>
                                            </div>
                                        </div>
                                    </a>

                                    @if (auth()->user()->isNarasumber())
                                        <a href="{{ route('instructor.mot.show') }}"
                                            class="group rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800
                                                   hover:-translate-y-0.5 hover:shadow-sm transition">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900">
                                                    📄
                                                </span>
                                                <div class="min-w-0">
                                                    <div class="truncate">MOT</div>
                                                    <div class="text-xs text-slate-500 font-medium">Dokumen</div>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div
                                            class="rounded-xl border border-dashed border-slate-200 bg-white/60 px-3 py-3 text-sm text-slate-600">
                                            Menu sesuai role
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- MOT CARD --}}
            @if (auth()->user()->isNarasumber())
                <div
                    class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm overflow-hidden hover:shadow-md transition">
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
                                    <p class="text-sm text-slate-600 mt-2">Silakan unggah dokumen MOT.</p>
                                @elseif($mot->status === 'pending')
                                    <p class="text-sm text-slate-600 mt-2">Menunggu persetujuan Admin.</p>
                                @elseif($mot->status === 'rejected')
                                    <p class="text-sm text-slate-600 mt-2">Dokumen ditolak. Silakan revisi dan unggah
                                        ulang.</p>
                                    @if ($mot->rejected_reason)
                                        <p class="text-sm text-slate-500 mt-1">
                                            Alasan: <span
                                                class="text-slate-700 font-medium">{{ $mot->rejected_reason }}</span>
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm text-slate-600 mt-2">Dokumen valid.</p>
                                @endif
                            </div>

                            <a href="{{ route('instructor.mot.show') }}"
                                class="inline-flex justify-center shrink-0 px-4 py-2 rounded-xl
                                       bg-gradient-to-r from-rose-600 to-amber-500 text-white
                                       hover:opacity-90 text-sm font-semibold transition">
                                {{ $mot ? 'Lihat / Unggah Ulang' : 'Unggah MOT' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Ringkasan (collapsible) --}}
                    <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm overflow-hidden"
                        x-data="{ open: true }">
                        <button type="button" @click="open = !open"
                            class="w-full p-5 sm:p-6 flex items-start justify-between gap-3 hover:bg-white/50 transition">
                            <div class="min-w-0 text-left">
                                <div class="text-slate-900 font-semibold">Ringkasan</div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Informasi utama sesuai peran pengguna.
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span
                                    class="inline-flex items-center rounded-full border border-slate-200 bg-white/70 px-3 py-1 text-xs font-semibold text-slate-700">
                                    Overview
                                </span>
                                <svg class="w-4 h-4 text-slate-600 transition-transform"
                                    :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="currentColor"
                                        d="m12 15l-4.243-4.242l1.415-1.414L12 12.172l2.828-2.828l1.415 1.414L12 15.001Z" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" x-collapse.duration.250ms class="px-5 sm:px-6 pb-5 sm:pb-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-slate-200 bg-white/70 p-4 text-sm text-slate-700">
                                    Status pelatihan, jam JPL, dan course aktif.
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white/70 p-4 text-sm text-slate-700">
                                    Persetujuan, TOR, dan item yang membutuhkan tindak lanjut.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aktivitas (tabs) --}}
                    <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm overflow-hidden"
                        x-data="{ tab: 'today' }">
                        <div class="p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-slate-900 font-semibold">Aktivitas</div>
                                    <div class="text-sm text-slate-600 mt-1">
                                        Aktivitas terbaru dan agenda.
                                    </div>
                                </div>

                                <div class="shrink-0 inline-flex rounded-xl border border-slate-200 bg-white/70 p-1">
                                    <button @click="tab='today'"
                                        :class="tab === 'today' ? 'bg-slate-900 text-white' :
                                            'text-slate-700 hover:bg-white'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                                        Hari ini
                                    </button>
                                    <button @click="tab='week'"
                                        :class="tab === 'week' ? 'bg-slate-900 text-white' :
                                            'text-slate-700 hover:bg-white'"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                                        Minggu ini
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div x-show="tab==='today'" x-transition.opacity>
                                    <div
                                        class="rounded-2xl border border-dashed border-slate-200 bg-white/60 p-6 text-sm text-slate-600">
                                        Tidak ada aktivitas untuk ditampilkan.
                                    </div>
                                </div>

                                <div x-show="tab==='week'" x-transition.opacity>
                                    <div
                                        class="rounded-2xl border border-dashed border-slate-200 bg-white/60 p-6 text-sm text-slate-600">
                                        Tidak ada agenda untuk ditampilkan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="lg:col-span-4 space-y-6">
                    @include('components.online-users')

                    <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm p-5">
                        <div class="text-sm font-semibold text-slate-900">Pengumuman</div>
                        <div class="mt-2 text-sm text-slate-600">
                            Informasi sistem dan pemberitahuan penting.
                        </div>
                        <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                            Status: Normal
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
