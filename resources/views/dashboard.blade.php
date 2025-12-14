<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Dashboard</h2>
                <p class="text-sm text-slate-500">Selamat datang, {{ auth()->user()->name }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8 space-y-6">

            {{-- MOT CARD (FULL WIDTH) --}}
            @if (auth()->user()->isNarasumber())
                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <div class="text-slate-800 font-semibold">Dokumen MOT</div>

                            @if (!$mot)
                                <p class="text-sm text-red-600 mt-1">Belum upload MOT.</p>
                                <p class="text-sm text-slate-500 mt-1">Upload dulu supaya bisa mengajar.</p>
                            @elseif($mot->status === 'pending')
                                <p class="text-sm text-amber-600 mt-1">Status: Pending approval Admin.</p>
                            @elseif($mot->status === 'rejected')
                                <p class="text-sm text-red-600 mt-1">Status: Ditolak.</p>
                                @if ($mot->rejected_reason)
                                    <p class="text-sm text-slate-500 mt-1">Alasan: {{ $mot->rejected_reason }}</p>
                                @endif
                            @else
                                <p class="text-sm text-green-600 mt-1">Status: Approved ✅</p>
                            @endif
                        </div>

                        <a href="{{ route('instructor.mot.show') }}"
                            class="inline-flex justify-center shrink-0 px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90 text-sm">
                            {{ $mot ? 'Lihat / Upload Ulang' : 'Upload MOT' }}
                        </a>
                    </div>
                </div>
            @endif

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white border border-slate-200 rounded-xl p-6">
                        <div class="text-slate-800 font-semibold">Ringkasan</div>
                        <div class="text-sm text-slate-500 mt-1">
                            Nanti isi sesuai role (Direktur/Kabid/Narasumber/Karyawan/Admin/Developer).
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($stats ?? [] as $s)
                            <div class="bg-white border border-slate-200 rounded-xl p-5">
                                <div class="text-xs text-slate-500">{{ $s['label'] }}</div>
                                <div class="mt-1 text-lg font-semibold text-slate-800">
                                    @if ($s['label'] === 'Status Akun')
                                        <span
                                            class="{{ $s['value'] === 'Aktif' ? 'text-green-600' : 'text-amber-600' }}">
                                            {{ $s['value'] }}
                                        </span>
                                    @else
                                        {{ $s['value'] }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-6">
                        <div class="text-slate-800 font-semibold">Aktivitas</div>
                        <div class="text-sm text-slate-500 mt-1">
                            Slot untuk: kalender, course yang diikuti, progress jam diklat, approval, dsb.
                        </div>
                        <div class="mt-4 border border-dashed border-slate-200 rounded-xl p-6 text-sm text-slate-400">
                            Coming soon. (alias: nanti kamu isi 😄)
                        </div>
                    </div>
                </div>

                {{-- RIGHT (FIX: 4 COLS, NOT 3) --}}
                <div class="lg:col-span-4">
                    <div x-data="onlineUsersWidget()" x-init="init()"
                        class="bg-white border border-slate-200 rounded-xl overflow-hidden lg:sticky lg:top-6">

                        <div class="p-4 border-b border-slate-200">
                            <div class="font-semibold text-slate-800">Pengguna Online</div>
                            <div class="text-sm text-slate-500 mt-1">
                                <span x-text="count"></span> pengguna daring (5 menit terakhir)
                            </div>
                        </div>

                        <div class="p-2">
                            <template x-if="loading">
                                <div class="p-4 text-sm text-slate-500">Loading...</div>
                            </template>

                            <template x-if="!loading && users.length === 0">
                                <div class="p-4 text-sm text-slate-500">Belum ada yang online.</div>
                            </template>

                            <div class="max-h-[420px] overflow-y-auto pr-1">
                                <template x-for="u in users" :key="u.id">
                                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50">
                                        <div
                                            class="h-8 w-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-semibold">
                                            <span x-text="(u.name || '?').trim().charAt(0).toUpperCase()"></span>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm text-[#121293] truncate" x-text="u.name"></div>
                                            <div class="text-xs text-slate-400">
                                                aktif: <span x-text="formatAgo(u.last_seen_at)"></span>
                                            </div>
                                        </div>

                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function onlineUsersWidget() {
            return {
                loading: true,
                count: @js($onlineCount ?? 0),
                users: @js(
    ($onlineUsers ?? collect())
        ->map(
            fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'last_seen_at' => optional($u->last_seen_at)->toIso8601String(),
            ],
        )
        ->values(),
),
                timer: null,

                init() {
                    this.loading = false;
                    this.refresh();
                    this.timer = setInterval(() => this.refresh(), 10000);
                },

                async refresh() {
                    try {
                        const res = await fetch(@js(route('dashboard.online')), {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.count = data.count ?? 0;
                        this.users = data.users ?? [];
                    } catch (e) {}
                },

                formatAgo(iso) {
                    if (!iso) return '-';
                    const t = new Date(iso).getTime();
                    const diff = Math.max(0, Date.now() - t);
                    const sec = Math.floor(diff / 1000);
                    if (sec < 60) return sec + ' detik';
                    const min = Math.floor(sec / 60);
                    if (min < 60) return min + ' menit';
                    const hr = Math.floor(min / 60);
                    return hr + ' jam';
                }
            }
        }
    </script>
</x-app-layout>
