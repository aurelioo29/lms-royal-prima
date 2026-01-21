<x-app-layout>
    <div class="py-12" x-data="quizManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Back -->
            <div class="mb-6">
                <a href="{{ route($routePrefix.'.modules.quiz.questions.index', [$course->id, $module->id]) }}"
                   class="text-sm text-slate-500 hover:text-indigo-600">
                    ← Kembali ke Manajemen Quiz
                </a>
            </div>

            <form
                action="{{ route($routePrefix.'.modules.quiz.questions.store', [$course->id, $module->id]) }}"
                method="POST"
            >
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- LEFT -->
                    <div class="bg-white border rounded-2xl p-6">
                        <h3 class="font-bold mb-4">Informasi Quiz</h3>
                        <div class="text-sm space-y-1 text-slate-600">
                            <div><b>Judul:</b> {{ $quiz->title }}</div>
                            <div><b>Passing:</b> {{ $quiz->passing_score }}%</div>
                            <div><b>Durasi:</b> {{ $quiz->time_limit }} menit</div>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="lg:col-span-2 bg-white border rounded-2xl p-8">

                        <div class="flex justify-between mb-6">
                            <h3 class="text-xl font-bold">Daftar Soal</h3>
                            <button type="button"
                                    @click="addQuestion"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl">
                                + Tambah Soal
                            </button>
                        </div>

                        <!-- QUESTIONS -->
                        <template x-for="(q, qIndex) in questions" :key="q.id">
                            <div class="mb-8 p-6 bg-slate-50 rounded-xl border">

                                <div class="flex justify-between mb-4">
                                    <b>Soal <span x-text="qIndex + 1"></span></b>
                                    <button type="button"
                                            @click="removeQuestion(qIndex)"
                                            class="text-red-500 text-sm">
                                        Hapus
                                    </button>
                                </div>

                                <!-- QUESTION -->
                                <textarea
                                    class="w-full rounded-xl border mb-4"
                                    rows="2"
                                    :name="`questions[${qIndex}][question]`"
                                    placeholder="Tulis pertanyaan..."
                                ></textarea>

                                <!-- SCORE -->
                                <input type="number"
                                       class="w-24 rounded-xl border mb-4"
                                       :name="`questions[${qIndex}][score]`"
                                       value="10">

                                <!-- TYPE -->
                                <select x-model="q.type"
                                        @change="onTypeChange(q)"
                                        class="rounded-xl border mb-4"
                                        :name="`questions[${qIndex}][type]`">
                                    <option value="mcq">Pilihan Ganda</option>
                                    <option value="true_false">Benar / Salah</option>
                                    <option value="essay">Essay</option>
                                </select>

                                <!-- MCQ -->
                                <template x-if="q.type === 'mcq'">
                                    <div>
                                        <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                            <div class="flex items-center gap-2 mb-2">
                                                <input type="radio"
                                                       :name="`questions[${qIndex}][correct_index]`"
                                                       :value="oIndex">
                                                <input type="text"
                                                       class="flex-1 rounded-xl border"
                                                       :name="`questions[${qIndex}][options][${oIndex}][text]`"
                                                       placeholder="Jawaban">
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- TRUE FALSE -->
                                <template x-if="q.type === 'true_false'">
                                    <div class="space-x-6">
                                        <input type="hidden"
                                               :name="`questions[${qIndex}][options][0][text]`"
                                               value="Benar">
                                        <input type="hidden"
                                               :name="`questions[${qIndex}][options][1][text]`"
                                               value="Salah">

                                        <label>
                                            <input type="radio"
                                                   :name="`questions[${qIndex}][correct_index]`"
                                                   value="0"> Benar
                                        </label>

                                        <label>
                                            <input type="radio"
                                                   :name="`questions[${qIndex}][correct_index]`"
                                                   value="1"> Salah
                                        </label>
                                    </div>
                                </template>

                                <!-- ESSAY -->
                                <template x-if="q.type === 'essay'">
                                    <div class="text-sm text-slate-500">
                                        Jawaban essay akan dinilai secara manual.
                                    </div>
                                </template>

                            </div>
                        </template>

                        <div x-show="questions.length === 0"
                             class="text-center text-slate-400 py-10 border border-dashed rounded-xl">
                            Belum ada soal
                        </div>

                        <div class="text-right mt-8">
                            <button type="submit"
                                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl"
                                    :disabled="questions.length === 0">
                                Simpan Soal
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function quizManager() {
            return {
                questions: [],

                addQuestion() {
                    this.questions.push({
                        id: Date.now(),
                        type: 'mcq',
                        options: [{},{},{},{}]
                    })
                },

                removeQuestion(i) {
                    this.questions.splice(i, 1)
                },

                onTypeChange(q) {
                    if (q.type === 'mcq') {
                        q.options = [{},{},{},{}]
                    } else if (q.type === 'true_false') {
                        q.options = []
                    } else {
                        q.options = []
                    }
                }
            }
        }
    </script>
</x-app-layout>
