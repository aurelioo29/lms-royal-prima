<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-slate-900">Buat Course Type</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Type ini akan muncul di form pembuatan Course. <span class="font-medium">Slug dibuat
                                otomatis.</span>
                        </p>
                    </div>

                    <a href="{{ route('course-types.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('course-types.store') }}" class="p-5 sm:p-6 space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input name="name" value="{{ old('name') }}" required
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#121293]/25 focus:border-[#121293]/30"
                                placeholder="Contoh: Pelatihan Blended / Workshop / Seminar">
                            @error('name')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                            <div class="text-xs text-slate-500 mt-1">
                                Slug akan otomatis jadi seperti: <span
                                    class="font-semibold">pelatihan-blended-3f8k</span>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start gap-3">
                                <input id="is_active" type="checkbox" name="is_active" value="1"
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-[#121293] focus:ring-[#121293]"
                                    @checked(old('is_active', true))>
                                <div>
                                    <label for="is_active" class="text-sm font-semibold text-slate-800">Active</label>
                                    <div class="text-xs text-slate-600">Jika nonaktif, type tidak muncul di dropdown
                                        course.</div>
                                </div>
                            </div>

                            <button
                                class="inline-flex items-center justify-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
