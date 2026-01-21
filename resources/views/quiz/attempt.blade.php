<x-app-layout>
    <div
        class="py-12"
        x-data="quizAttempt({
            timeLimit: {{ $quiz->time_limit ?? 'null' }},
            startedAt: '{{ $attempt->started_at->toIso8601String() }}'
        })"
        x-init="initTimer"
    >
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-xl font-bold">
                    {{ $quiz->title }}
                </h1>

                <!-- TIMER -->
                @if($quiz->time_limit)
                    <div class="px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-red-700 font-semibold"
                         x-text="timeText">
                    </div>
                @endif
            </div>

            <form
                method="POST"
                action="{{ route(
                    'employee.courses.modules.quiz.submit',
                    [$course->id, $module->id, $attempt->id]
                ) }}"
                @submit.prevent="submitForm($event)"
            >
                @csrf

                <!-- QUESTIONS -->
                <div class="space-y-8">

                    @foreach ($quiz->questions as $index => $question)
                        <div class="bg-white border rounded-2xl p-6">

                            <!-- QUESTION HEADER -->
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

                            <!-- ANSWERS -->
                            <div class="space-y-3">

                                {{-- MCQ --}}
                                @if ($question->type === 'mcq')
                                    @foreach ($question->options as $option)
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $option->id }}"
                                                class="text-indigo-600"
                                            >
                                            <span>{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                @endif

                                {{-- TRUE / FALSE --}}
                                @if ($question->type === 'true_false')
                                    @foreach ($question->options as $option)
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $option->id }}"
                                                class="text-indigo-600"
                                            >
                                            <span>{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                @endif

                                {{-- ESSAY --}}
                                @if ($question->type === 'essay')
                                    <textarea
                                        name="answers[{{ $question->id }}]"
                                        rows="4"
                                        class="w-full rounded-xl border border-slate-300"
                                        placeholder="Tulis jawaban Anda..."
                                    ></textarea>

                                    <div class="text-xs text-slate-400 mt-1">
                                        Jawaban essay akan dinilai secara manual.
                                    </div>
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- SUBMIT -->
                <div class="mt-10 flex justify-end">
                    <button
                        type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700"
                        :disabled="submitting"
                        x-text="submitting ? 'Mengirim...' : 'Kumpulkan Quiz'"
                    >
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function quizAttempt({ timeLimit, startedAt }) {
            return {
                submitting: false,
                timeText: '',
                remainingSeconds: null,

                initTimer() {
                    if (!timeLimit || !startedAt) return;

                    const start = new Date(startedAt);

                    // ⛔ PROTEKSI PENTING
                    if (isNaN(start.getTime())) {
                        console.error('Invalid startedAt:', startedAt);
                        this.timeText = 'Timer error';
                        return;
                    }

                    const end = new Date(start.getTime() + timeLimit * 60000);

                    this.updateTimer(end);
                    setInterval(() => this.updateTimer(end), 1000);
                },


                updateTimer(end) {
                    const now = new Date();
                    const diff = Math.floor((end - now) / 1000);

                    if (diff <= 0) {
                        this.timeText = 'Waktu habis';
                        this.autoSubmit();
                        return;
                    }

                    const minutes = Math.floor(diff / 60);
                    const seconds = diff % 60;

                    this.timeText =
                        String(minutes).padStart(2, '0') +
                        ':' +
                        String(seconds).padStart(2, '0');
                },

                submitForm(event) {
                    if (this.submitting) return;

                    if (!confirm('Apakah Anda yakin ingin mengumpulkan quiz?')) {
                        return;
                    }

                    this.submitting = true;
                    event.target.submit();
                },

                autoSubmit() {
                    if (this.submitting) return;

                    this.submitting = true;
                    document.querySelector('form').submit();
                }

                
            }
        }
        console.log('startedAt:', startedAt);
console.log('timeLimit:', timeLimit);
    </script>
</x-app-layout>
