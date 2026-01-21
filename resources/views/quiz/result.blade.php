<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-6">
                <h1 class="text-xl font-bold">
                    {{ $quiz->title }}
                </h1>

                <div class="mt-2 text-sm text-slate-600">
                    Skor:
                    <strong>{{ $attempt->score }}</strong> /
                    {{ $attempt->max_score }}
                </div>

                <div class="mt-1 text-sm">
                    Status:
                    @if($attempt->is_passed)
                        <span class="text-green-600 font-semibold">LULUS</span>
                    @else
                        <span class="text-red-600 font-semibold">TIDAK LULUS</span>
                    @endif
                </div>
            </div>

            <!-- QUESTIONS -->
            <div class="space-y-8">

                @foreach ($quiz->questions as $index => $question)
                    @php
                        $answer = $attempt->answers
                            ->firstWhere('quiz_question_id', $question->id);
                    @endphp

                    <div class="bg-white border rounded-2xl p-6">

                        <div class="mb-4">
                            <div class="font-semibold text-slate-900">
                                Soal {{ $index + 1 }}
                            </div>

                            <div class="mt-1 text-slate-700">
                                {{ $question->question }}
                            </div>

                            <div class="text-xs text-slate-400 mt-1">
                                Skor: {{ $question->score }}
                            </div>
                        </div>

                        <div class="space-y-3">

                            {{-- MCQ & TRUE/FALSE --}}
                            @if(in_array($question->type, ['mcq', 'true_false']))
                                @foreach ($question->options as $option)
                                    <div class="flex items-center gap-3">
                                        <input
                                            type="radio"
                                            disabled
                                            {{ optional($answer)->option_id === $option->id ? 'checked' : '' }}
                                        >
                                        <span class="{{ $option->is_correct ? 'text-green-700 font-semibold' : '' }}">
                                            {{ $option->option_text }}
                                        </span>
                                    </div>
                                @endforeach
                            @endif

                            {{-- ESSAY --}}
                            @if ($question->type === 'essay')
                                <div class="p-3 bg-slate-50 border rounded-xl">
                                    {{ $answer->essay_answer ?? '-' }}
                                </div>

                                <div class="text-xs text-slate-400 mt-1">
                                    Dinilai manual oleh instruktur.
                                </div>
                            @endif

                        </div>

                    </div>
                @endforeach

            </div>

            <!-- ACTION -->
            <div class="mt-10 flex justify-end">
                <a
                    href="{{ route('employee.courses.show', $course) }}"
                    class="px-6 py-3 bg-slate-600 text-white rounded-xl hover:bg-slate-700"
                >
                    Kembali ke Course
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
