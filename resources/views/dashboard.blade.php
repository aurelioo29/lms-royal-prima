<x-app-layout>
    <div class="space-y-6">

        {{-- Welcome --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800">
                Dashboard
            </h2>
            <p class="text-sm text-slate-500">
                Selamat datang, {{ Auth::user()->name }}.
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="text-sm text-slate-500">Kalender Tahunan</div>
                <div class="mt-2 text-xl font-semibold">—</div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="text-sm text-slate-500">Jam Diklat</div>
                <div class="mt-2 text-xl font-semibold">—</div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="text-sm text-slate-500">Status Akun</div>
                <div class="mt-2 text-xl font-semibold text-green-600">
                    Aktif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
