{{-- SECTION FORM QUIZ (TAMBAHAN) --}}
<div x-show="has_quiz" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    class="mt-6 rounded-2xl border-2 border-orange-100 bg-orange-50/30 shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-orange-100 bg-orange-50 flex items-center gap-3">
        <div class="p-2 bg-orange-500 rounded-lg text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
            </svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-orange-900 tracking-wide uppercase">
                Pengaturan Quiz Modul
            </h3>
            <p class="text-[11px] text-orange-700">
                Quiz akan muncul setelah modul selesai dipelajari.
            </p>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Judul Quiz --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Judul Quiz <span class="text-red-500">*</span>
                </label>
                <input type="text" name="quiz[title]" value="{{ old('quiz.title', $module->quiz->title ?? '') }}"
                    class="w-full rounded-xl border-slate-200 focus:border-orange-500 focus:ring focus:ring-orange-500/10"
                    placeholder="Contoh: Quiz Evaluasi Modul 1">
            </div>

            {{-- Deskripsi Quiz --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Deskripsi Quiz
                </label>
                <textarea name="quiz[description]" rows="3"
                    class="w-full rounded-xl border-slate-200 focus:border-orange-500 focus:ring focus:ring-orange-500/10"
                    placeholder="Penjelasan singkat tentang quiz ini (opsional)">{{ old('quiz.description', $module->quiz->description ?? '') }}</textarea>
            </div>


            {{-- Passing Score --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Passing Score
                </label>
                <input type="number" name="quiz[passing_score]" min="0"
                    value="{{ old('quiz.passing_score', $module->quiz->passing_score ?? 70) }}"
                    class="w-full rounded-xl border-slate-200 focus:border-orange-500 focus:ring focus:ring-orange-500/10">
                <p class="text-[11px] text-slate-500 mt-1">
                    Nilai minimum untuk lulus quiz
                </p>
            </div>

            {{-- Time Limit --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Batas Waktu (Menit)
                </label>
                <input type="number" name="quiz[time_limit]" min="1"
                    value="{{ old('quiz.time_limit', $module->quiz->time_limit ?? '') }}"
                    class="w-full rounded-xl border-slate-200 focus:border-orange-500 focus:ring focus:ring-orange-500/10"
                    placeholder="Opsional">
            </div>

            {{-- Mandatory Quiz --}}
            <div class="md:col-span-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="quiz[is_mandatory]" value="1"
                        class="w-5 h-5 rounded border-slate-300 text-orange-500 focus:ring-orange-500/20"
                        {{ old('quiz.is_mandatory', $module->quiz->is_mandatory ?? true) ? 'checked' : '' }}>
                    <div class="ms-3">
                        <span class="text-sm font-semibold text-slate-700">
                            Quiz Wajib Lulus
                        </span>
                        <p class="text-[10px] text-slate-500">
                            Modul tidak dianggap selesai jika quiz belum lulus
                        </p>
                    </div>
                </label>
            </div>

            {{-- status --}}
            <input type="hidden" name="quiz[status]"
                value="{{ old('quiz.status', $module->quiz->status ?? 'draft') }}">

        </div>
    </div>
</div>
