@csrf

<div class="space-y-8">

    {{-- Basic Info --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-slate-200">
            <p class="text-sm font-semibold text-slate-900">Role Details</p>
            <p class="text-xs text-slate-500 mt-0.5">Atur nama, slug, dan level role.</p>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Name --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-slate-700">
                        Name <span class="text-red-500">*</span>
                    </label>

                    <input name="name" value="{{ old('name', $role->name ?? '') }}"
                        placeholder="Contoh: Kabid Diklat" required
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                               shadow-sm outline-none transition
                               focus:border-[#121293]/40 focus:ring-4 focus:ring-[#121293]/10">

                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-slate-700">
                        Slug <span class="text-red-500">*</span>
                    </label>

                    <div class="mt-2 relative">
                        <input name="slug" value="{{ old('slug', $role->slug ?? '') }}"
                            placeholder="contoh: head_training" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                                   shadow-sm outline-none transition
                                   focus:border-[#121293]/40 focus:ring-4 focus:ring-[#121293]/10">
                    </div>

                    <p class="mt-2 text-xs text-slate-500">
                        Gunakan huruf kecil, angka, underscore. (contoh: <span class="font-mono">head_training</span>)
                    </p>

                    @error('slug')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Level --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Level <span class="text-red-500">*</span>
                    </label>

                    <div class="mt-2 flex items-center gap-3">
                        <input type="number" name="level" value="{{ old('level', $role->level ?? 0) }}"
                            placeholder="Contoh: 70" required
                            class="w-full md:w-64 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                                   shadow-sm outline-none transition
                                   focus:border-[#121293]/40 focus:ring-4 focus:ring-[#121293]/10">

                        <span class="text-xs text-slate-500">
                            Semakin tinggi, semakin “berkuasa” (secara sistem, bukan secara drama).
                        </span>
                    </div>

                    @error('level')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Capabilities --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-900">Capabilities</p>
                <p class="text-xs text-slate-500 mt-0.5">Centang izin yang dimiliki role ini.</p>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                Permissions
            </span>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @php
                    $caps = [
                        [
                            'name' => 'can_manage_users',
                            'title' => 'Manage Users',
                            'desc' => 'Kelola user & role assignment.',
                        ],
                        [
                            'name' => 'can_create_plans',
                            'title' => 'Create Plans (Kabid)',
                            'desc' => 'Buat rencana tahunan / event pelatihan.',
                        ],
                        [
                            'name' => 'can_approve_plans',
                            'title' => 'Approve Plans (Direktur)',
                            'desc' => 'Setujui rencana sebelum tampil ke publik.',
                        ],
                        [
                            'name' => 'can_create_courses',
                            'title' => 'Create Courses',
                            'desc' => 'Buat course & modul pembelajaran.',
                        ],
                        [
                            'name' => 'can_approve_courses',
                            'title' => 'Approve Courses',
                            'desc' => 'Publish course setelah dicek/ACC.',
                        ],
                    ];
                @endphp

                @foreach ($caps as $cap)
                    <label
                        class="group flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4
                               hover:bg-slate-50 transition cursor-pointer">
                        <input type="checkbox" name="{{ $cap['name'] }}" value="1"
                            class="mt-0.5 h-5 w-5 rounded-md border-slate-300 text-[#121293]
                                   focus:ring-[#121293]/20 focus:ring-4"
                            {{ old($cap['name'], $role->{$cap['name']} ?? false) ? 'checked' : '' }}>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-slate-950">
                                {{ $cap['title'] }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $cap['desc'] }}
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
        <p class="text-xs text-slate-500">
            Pastikan slug unik. Kalau nggak unik, database bakal ngamuk (dan itu wajar).
        </p>

        <div class="flex gap-2">
            <button
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                       bg-[#121293] text-white text-sm font-semibold shadow-sm
                       hover:opacity-90 active:opacity-80 transition">
                Simpan
            </button>

            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                       border border-slate-200 bg-white text-slate-700 text-sm font-semibold
                       hover:bg-slate-50 transition">
                Batal
            </a>
        </div>
    </div>

</div>
