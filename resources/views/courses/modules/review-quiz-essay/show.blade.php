<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h1 class="text-xl font-semibold text-slate-900">
                    Review Essay
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    {{ $attempt->user->name }} – {{ $module->title }}
                </p>
            </div>

            @foreach ($attempt->answers as $answer)
                @if ($answer->question->type === 'essay')

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4">
                            <h3 class="font-semibold text-slate-900">
                                Pertanyaan
                            </h3>
                            <p class="mt-1 text-slate-700">
                                {{ $answer->question->question }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <h3 class="font-semibold text-slate-900">
                                Jawaban Peserta
                            </h3>
                            <div class="mt-2 rounded-xl border bg-slate-50 p-4 text-slate-700">
                                {{ $answer->answer_text}}
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('courses.modules.quiz.review.store', [
                                'course' => $course->id,
                                'module' => $module->id,
                                'answer' => $answer->id,
                            ]) }}"
                        >
                            @csrf

                            <!-- Penilaian Benar / Salah -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Penilaian
                                </label>

                                <div class="flex items-center gap-6">
                                    <label class="flex items-center gap-2">
                                        <input type="radio"
                                            name="is_correct"
                                            value="1"
                                            {{ optional($answer->review)->score > 0 ? 'checked' : '' }}
                                            required>
                                        <span class="text-sm text-slate-700">
                                            Benar (+{{ $answer->question->score }} poin)
                                        </span>
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio"
                                            name="is_correct"
                                            value="0"
                                            {{ optional($answer->review)->score === 0 ? 'checked' : '' }}>
                                        <span class="text-sm text-slate-700">
                                            Salah (0 poin)
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">
                                    Catatan (Opsional)
                                </label>
                                <textarea name="note"
                                    rows="3"
                                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2
                                        text-sm focus:border-[#121293] focus:ring-[#121293]">{{ $answer->review->note ?? '' }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="rounded-xl bg-green-600 px-6 py-2
                                        text-sm font-semibold text-white
                                        hover:bg-green-700 transition">
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>

                @endif
            @endforeach

        </div>
    </div>
</x-app-layout>
