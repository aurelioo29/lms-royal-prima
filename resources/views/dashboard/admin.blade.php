<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO / TOP SUMMARY --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-red-600"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                        </path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h1 class="text-xl font-bold text-slate-900">System Administrator Control</h1>
                                    <p class="text-sm text-slate-500">Pusat kendali operasional dan pengawasan sistem
                                        LMS.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700 uppercase tracking-wider">
                                High Access Level
                            </span>
                            <span
                                class="text-sm font-medium text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT: Monitoring & Actions --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @php
                            $adminStats = [
                                [
                                    'label' => 'Total User',
                                    'value' => $stats['total_users'] ?? '1,240',
                                    'icon' => 'users',
                                    'color' => 'blue',
                                ],
                                [
                                    'label' => 'Aktif Hari Ini',
                                    'value' => $stats['active_today'] ?? '85',
                                    'icon' => 'chart-bar',
                                    'color' => 'green',
                                ],
                                [
                                    'label' => 'Course Aktif',
                                    'value' => $stats['active_courses'] ?? '42',
                                    'icon' => 'book-open',
                                    'color' => 'indigo',
                                ],
                                [
                                    'label' => 'Pending Approval',
                                    'value' => $stats['pending_approval'] ?? '12',
                                    'icon' => 'clock',
                                    'color' => 'amber',
                                ],
                            ];
                        @endphp

                        @foreach ($adminStats as $s)
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                    {{ $s['label'] }}</div>
                                <div class="mt-1 flex items-center justify-between">
                                    <div class="text-xl font-bold text-slate-900">{{ $s['value'] }}</div>
                                    <div class="text-{{ $s['color'] }}-500">
                                        {{-- Simple Bullet to represent color --}}
                                        <div class="h-2 w-2 rounded-full bg-current"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- System Status & Critical Data --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden font-sans">
                        <div
                            class="border-b border-slate-100 bg-slate-50/50 px-5 py-4 flex justify-between items-center">
                            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Integritas Sistem & Ringkasan
                            </h2>
                            <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Real-time
                                Check</span>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Status Server --}}
                                <div class="flex items-center p-3 rounded-xl bg-green-50 border border-green-100">
                                    <div class="flex-1 text-sm font-medium text-green-800">Server & Database Status
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                                        <span class="text-xs font-bold text-green-600 uppercase">Optimal</span>
                                    </div>
                                </div>
                                {{-- Critical Action --}}
                                <div class="flex items-center p-3 rounded-xl bg-amber-50 border border-amber-100">
                                    <div class="flex-1 text-sm font-medium text-amber-800">Verifikasi MOT Tertunda</div>
                                    <div class="text-xs font-bold text-amber-600 underline cursor-pointer">
                                        {{ $stats['pending_approval'] ?? '12' }} Butuh Tindakan</div>
                                </div>
                            </div>

                            <div class="mt-4 p-4 rounded-xl border border-slate-200 bg-slate-50">
                                <h3 class="text-xs font-bold text-slate-500 uppercase mb-3 tracking-widest">Catatan
                                    Operasional</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                        <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                        Terdapat 5 narasumber baru yang belum melengkapi dokumen profil.
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                        <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                        Backup sistem otomatis terakhir dijalankan:
                                        {{ now()->subHours(2)->format('H:i') }} WIB.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Management Activities --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="font-bold text-slate-800">Antrian Manajemen & Aktivitas</h2>
                            <div class="flex gap-2">
                                <button
                                    class="text-xs font-semibold px-3 py-1 rounded-md bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition">Filter</button>
                            </div>
                        </div>
                        <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-[10px] uppercase text-slate-500 font-bold">
                                    <tr>
                                        <th class="px-5 py-3 border-b border-slate-100">Kategori</th>
                                        <th class="px-5 py-3 border-b border-slate-100">Subjek</th>
                                        <th class="px-5 py-3 border-b border-slate-100">Status/Waktu</th>
                                        <th class="px-5 py-3 border-b border-slate-100 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    {{-- Row: Approval MOT --}}
                                    <tr>
                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 uppercase">MOT
                                                Approval</span>
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            <div class="font-semibold text-slate-900">Dr. Andi Wijaya</div>
                                            <div class="text-xs text-slate-500">Narasumber Baru - Sertifikat MOT</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="text-xs font-medium text-amber-600">Pending Approval</div>
                                            <div class="text-[10px] text-slate-400">10 menit yang lalu</div>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <button
                                                class="text-xs font-bold text-blue-600 hover:underline">Tinjau</button>
                                        </td>
                                    </tr>
                                    {{-- Row: User Management --}}
                                    <tr>
                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">User
                                                Access</span>
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            <div class="font-semibold text-slate-900">Regulasi Baru LMS</div>
                                            <div class="text-xs text-slate-500">Penyesuaian Role: Narasumber Madya</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="text-xs font-medium text-green-600">Completed</div>
                                            <div class="text-[10px] text-slate-400">1 jam yang lalu</div>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <button
                                                class="text-xs font-bold text-slate-600 hover:underline">Log</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100">
                            <a href="#" class="text-xs font-bold text-[#121293] hover:underline">Lihat semua log
                                aktivitas sistem &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Online Users (RESTRICTED - DO NOT MODIFY WIDGET LOGIC) --}}
                <div class="lg:col-span-4">
                    <div x-data="onlineUsersWidget()" x-init="init()"
                        class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden lg:sticky lg:top-6">

                        <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                            <div class="font-bold text-slate-900">Pengguna Online</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Monitoring trafik 5 menit terakhir
                            </div>
                        </div>

                        <div class="p-2">
                            <template x-if="loading">
                                <div class="p-4 text-sm text-slate-500">Menghubungkan ke server...</div>
                            </template>

                            <template x-if="!loading && users.length === 0">
                                <div class="p-4 text-sm text-slate-500 text-center italic">Tidak ada sesi aktif.</div>
                            </template>

                            <div class="max-h-[500px] overflow-y-auto pr-1">
                                <template x-for="u in users" :key="u.id">
                                    <div
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                                        <div
                                            class="h-9 w-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-sm font-bold border border-white shadow-sm">
                                            <span x-text="(u.name || '?').trim().charAt(0).toUpperCase()"></span>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold text-slate-800 truncate"
                                                x-text="u.name"></div>
                                            <div class="text-[10px] text-slate-400 flex items-center gap-1">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Aktif <span x-text="formatAgo(u.last_seen_at)"></span>
                                            </div>
                                        </div>

                                        <button
                                            class="p-1.5 rounded-lg hover:bg-white border border-transparent hover:border-slate-200 text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div
                            class="p-3 bg-white border-t border-slate-100 flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                            <span>Total Sesi: <span x-text="count"
                                    class="text-slate-900 font-extrabold"></span></span>
                            <span class="text-green-500">Live Updates</span>
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
                    if (min < 60) return min + ' mnt';
                    const hr = Math.floor(min / 60);
                    return hr + ' jam';
                }
            }
        }
    </script>
</x-app-layout>
