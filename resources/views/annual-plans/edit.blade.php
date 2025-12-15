<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO (NO x-slot) --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    {{-- edit icon --}}
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 20h9" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                        Edit Annual Plan
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Perubahan hanya diizinkan saat status <span class="font-semibold">Draft</span> /
                                        <span class="font-semibold">Rejected</span>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            <a href="{{ route('annual-plans.show', $annualPlan) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    {{-- Soft notice --}}
                    <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path
                                        d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 0 0 4.6 20h14.8a2 2 0 0 0 1.72-3.32l-7.4-12.82a2 2 0 0 0-3.43 0Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-sm">
                                Kalau status sudah <span class="font-semibold">Pending/Approved</span>, jangan harap
                                bisa edit ya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM CARD --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 sm:p-6">
                    <div class="text-sm text-slate-500">
                        Field bertanda <span class="text-red-500">*</span> wajib diisi.
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <form method="POST" action="{{ route('annual-plans.update', $annualPlan) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Tahun --}}
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-800">
                                    Tahun <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M8 2v3M16 2v3M3 9h18" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path
                                                d="M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>

                                    <input name="year" type="number" required
                                        value="{{ old('year', $annualPlan->year) }}"
                                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                                               focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                                        placeholder="Contoh: 2026">
                                </div>
                                <p class="text-xs text-slate-500">Format tahun (YYYY).</p>
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
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 20h9" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>

                                    <input name="title" required value="{{ old('title', $annualPlan->title) }}"
                                        class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                                               focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                                        placeholder="Contoh: Kalender Tahunan Kegiatan 2026">
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
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 6h16M4 12h10M4 18h16" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </span>

                                <textarea name="description" rows="5"
                                    class="w-full rounded-xl border-slate-200 bg-white pl-10 pr-3 py-2.5 text-slate-900
                                           focus:border-[#121293] focus:ring-[#121293] placeholder:text-slate-400"
                                    placeholder="Tambahkan catatan, target, atau detail penting...">{{ old('description', $annualPlan->description) }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <a href="{{ route('annual-plans.show', $annualPlan) }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#121293] focus:ring-offset-2">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M17 21v-8H7v8" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7 3v5h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Update
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
