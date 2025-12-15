<x-app-layout>
    <div class="py-8">
        <div class="max-w-full px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                        Buat Annual Plan
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Isi informasi rencana tahunan. Field bertanda <span class="text-red-500">*</span> wajib diisi.
                    </p>
                </div>

                <a href="{{ route('annual-plans.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    {{-- icon arrow-left --}}
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Card --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                {{-- subtle top accent --}}
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('annual-plans.store') }}" class="space-y-6">
                        @csrf

                        {{-- Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Tahun --}}
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-800">
                                    Tahun <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        {{-- icon calendar --}}
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path
                                                d="M8 2v3M16 2v3M3 9h18M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>

                                    <input name="year" type="number" value="{{ old('year', date('Y')) }}" required
                                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                                               focus:border-[#121293] focus:ring-[#121293]
                                               placeholder:text-slate-400"
                                        placeholder="Contoh: 2026">
                                </div>

                                <p class="text-xs text-slate-500">Pakai format tahun (YYYY).</p>
                                <x-input-error :messages="$errors->get('year')" class="mt-2" />
                            </div>

                            {{-- Judul --}}
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-800">
                                    Judul <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        {{-- icon edit --}}
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 20h9" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>

                                    <input name="title" value="{{ old('title') }}" required
                                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                                               focus:border-[#121293] focus:ring-[#121293]
                                               placeholder:text-slate-400"
                                        placeholder="Contoh: Annual Plan Pelatihan 2026">
                                </div>

                                <p class="text-xs text-slate-500">Judul singkat tapi jelas.</p>
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-slate-800">Deskripsi</label>

                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-3 text-slate-400">
                                    {{-- icon align-left --}}
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 6h16M4 12h10M4 18h16" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </span>

                                <textarea name="description" rows="5"
                                    class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                                           focus:border-[#121293] focus:ring-[#121293]
                                           placeholder:text-slate-400"
                                    placeholder="Tambahkan konteks, target, atau catatan penting...">{{ old('description') }}</textarea>
                            </div>

                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <p class="text-xs text-slate-500">
                                Dengan menekan <span class="font-semibold">Simpan</span>, data akan masuk ke sistem.
                            </p>

                            <div class="flex gap-3">
                                <a href="{{ route('annual-plans.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Batal
                                </a>

                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#121293] focus:ring-offset-2">
                                    {{-- icon save --}}
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M17 21v-8H7v8" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M7 3v5h8" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
