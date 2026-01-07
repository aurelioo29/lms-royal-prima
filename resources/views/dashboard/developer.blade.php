<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO / TOP SUMMARY (NO x-slot) --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill="currentColor"
                                            d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.8V17a2 2 0 0 1-2 2h-1v2h-2v-2H10a2 2 0 0 1-2-2v-2.2c-1.8-1.3-3-3.4-3-5.8a7 7 0 0 1 7-7Zm-2 19h4v-2h-4v2Z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                        Dashboard
                                    </h1>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Selamat datang, <span
                                            class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>.
                                        <span class="text-slate-500">Ringkasan cepat hari ini.</span>
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        {{-- Role badge (customize if you want) --}}
                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                            Role: {{ auth()->user()->role->name ?? '—' }}
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

            {{-- MOT CARD (FULL WIDTH) --}}
            @if (auth()->user()->isNarasumber())
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
                                    <p class="text-sm text-slate-600 mt-2">
                                        Upload MOT dulu supaya bisa mengajar.
                                    </p>
                                @elseif($mot->status === 'pending')
                                    <p class="text-sm text-slate-600 mt-2">Menunggu approval Admin.</p>
                                @elseif($mot->status === 'rejected')
                                    <p class="text-sm text-slate-600 mt-2">Dokumen ditolak, mohon revisi dan upload
                                        ulang.</p>
                                    @if ($mot->rejected_reason)
                                        <p class="text-sm text-slate-500 mt-1">
                                            Alasan: <span
                                                class="text-slate-700 font-medium">{{ $mot->rejected_reason }}</span>
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm text-slate-600 mt-2">Dokumen valid dan siap digunakan.</p>
                                @endif
                            </div>

                            <a href="{{ route('instructor.mot.show') }}"
                                class="inline-flex justify-center shrink-0 px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90 text-sm font-semibold">
                                {{ $mot ? 'Lihat / Upload Ulang' : 'Upload MOT' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Stats --}}
                    {{-- <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($stats ?? [] as $s)
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                                <div class="text-xs text-slate-500">{{ $s['label'] }}</div>

                                <div class="mt-2 flex items-end justify-between gap-3">
                                    <div class="text-2xl font-semibold text-slate-900">
                                        @if ($s['label'] === 'Status Akun')
                                            <span
                                                class="{{ $s['value'] === 'Aktif' ? 'text-green-600' : 'text-amber-600' }}">
                                                {{ $s['value'] }}
                                            </span>
                                        @else
                                            {{ $s['value'] }}
                                        @endif
                                    </div>

                                    <div
                                        class="h-10 w-10 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill="currentColor"
                                                d="M4 19V5h2v14H4Zm14 0V9h2v10h-2ZM9 19V12h2v7H9Zm5 0V7h2v12h-2Z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}

                    {{-- Ringkasan --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-slate-900 font-semibold">Ringkasan</div>
                                    <div class="text-sm text-slate-600 mt-1">
                                        Nanti isi sesuai role (Direktur/Kabid/Narasumber/Karyawan/Admin/Developer).
                                    </div>
                                </div>

                                <span
                                    class="shrink-0 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                    Overview
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                    ✅ Quick wins: taruh info penting per role di sini.
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                    📌 Next: progres jam diklat, course aktif, approval pending.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aktivitas --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <div class="text-slate-900 font-semibold">Aktivitas</div>
                            <div class="text-sm text-slate-600 mt-1">
                                Slot untuk: kalender, course yang diikuti, progress jam diklat, approval, dsb.
                            </div>

                            <div
                                class="mt-4 border border-dashed border-slate-200 rounded-2xl p-6 text-sm text-slate-400">
                                Coming soon. (alias: nanti kamu isi 😄)
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

</x-app-layout>
