<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-6">
                <h1 class="text-xl font-bold">
                    Review Jawaban Quiz
                </h1>
                <p class="text-slate-600">
                    {{ $attempt->quiz->title }} — {{ $attempt->user->name }}
                </p>
            </div>

            <div class="space-y-6">

                @foreach ($attempt->quiz->questions as $index => $question)
                    @php
                        $answer = $attempt->answers
                            ->where('quiz_question_id', $question->id)
                            ->first();
                    @endphp

                    <div class="bg-white border rounded-2xl p-6">

                        <!-- QUESTION -->
                        <div class="mb-4">
                            <div class="font-semibold">
                                Soal {{ $index + 1 }}
                            </div>
                            <div class="text-slate-700 mt-1">
                                {{ $question->question }}
                            </div>
                        </div>

                        <!-- ANSWER -->
                        <div class="space-y-2 text-sm">

                            {{-- MCQ & TRUE FALSE --}}
                            @if (in_array($question->type, ['mcq', 'true_false']))
                                @foreach ($question->options as $option)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="w-4 h-4 rounded-full border
                                            {{ $option->is_correct ? 'bg-green-500' : '' }}">
                                        </span>

                                        <span
                                            class="
                                                {{ $answer && $answer->quiz_question_option_id === $option->id
                                                    ? 'font-bold text-indigo-600'
                                                    : '' }}
                                            ">
                                            {{ $option->option_text }}
                                        </span>

                                        @if ($option->is_correct)
                                            <span class="text-green-600 font-semibold">
                                                ✔ Kunci
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            {{-- ESSAY --}}
                            @if ($question->type === 'essay')
                                <div class="p-4 rounded-xl bg-slate-50 border">
                                    <div class="text-slate-500 mb-1">Jawaban Peserta:</div>
                                    <div class="whitespace-pre-line">
                                        {{ $answer->answer_text ?? '-' }}
                                    </div>
                                </div>

                                <div class="text-xs text-amber-600">
                                    ⚠ Essay perlu penilaian manual
                                </div>
                            @endif

                        </div>

                        <!-- SCORE -->
                        <div class="mt-4 text-sm">
                            Skor:
                            <span class="font-bold">
                                {{ $answer->score ?? 0 }} / {{ $question->score }}
                            </span>
                        </div>

                    </div>
                @endforeach

            </div>

            <!-- FOOTER -->
            <div class="mt-8 flex justify-end">
                <a href="{{ url()->previous() }}"
                   class="px-5 py-2 rounded-xl bg-slate-200 text-slate-700 hover:bg-slate-300">
                    Kembali
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
