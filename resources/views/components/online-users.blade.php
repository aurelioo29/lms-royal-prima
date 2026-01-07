<div x-data="onlineUsersWidget()" x-init="init()"
    class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden lg:sticky lg:top-6">

    <div class="p-4 border-b border-slate-200">
        <div class="font-semibold text-slate-900">Pengguna Online</div>
        <div class="text-sm text-slate-600 mt-1">
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
                <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50">
                    <div
                        class="h-9 w-9 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-semibold border border-slate-200">
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

{{-- SCRIPT TETAP ASLI --}}
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
