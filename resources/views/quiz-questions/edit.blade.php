<x-app-layout>
<div class="py-12" x-data="quizManager({{ Js::from($quiz->questions ?? []) }})"
    @confirm-delete-question-yes.window="confirmDelete()">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Back -->
        <div class="mb-6">
            <a href="{{ route($routePrefix.'.modules.quiz.questions.index', [$course->id, $module->id]) }}"
               class="text-sm text-slate-500 hover:text-indigo-600">
                ← Kembali ke Manajemen Quiz
            </a>
        </div>

        <form
            action="{{ route(
                $routePrefix.'.modules.quiz.questions.bulk-update',
                [$course->id, $module->id]
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

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
                        <h3 class="text-xl font-bold">Edit Daftar Soal</h3>
                        <button type="button"
                                @click="addQuestion"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-xl">
                            + Tambah Soal
                        </button>
                    </div>

                    <!-- QUESTIONS -->
                    <template x-for="(q, qIndex) in questions" :key="q.id || q.temp_id">
                        <div class="mb-8 p-6 bg-slate-50 rounded-xl border">

                            <div class="flex justify-between mb-4">
                                <b>Soal <span x-text="qIndex + 1"></span></b>
                                <button type="button"
                                class="text-red-500 text-sm hover:underline"
                                @click="
                                    deleteIndex = qIndex;
                                    $dispatch('open-modal', 'confirm-delete-question');
                                ">
                                Hapus
                            </button>
                            </div>

                            <!-- Hidden ID for Existing Question -->
                            <input type="hidden" :name="`questions[${qIndex}][id]`" x-model="q.id">

                            <!-- QUESTION -->
                            <textarea
                                class="w-full rounded-xl border mb-4"
                                rows="2"
                                x-model="q.question"
                                :name="`questions[${qIndex}][question]`"
                                placeholder="Tulis pertanyaan..."
                            ></textarea>

                            <!-- SCORE -->
                            <div class="flex flex-col mb-4">
                                <label class="text-xs text-slate-500 mb-1">Skor</label>
                                <input type="number"
                                       class="w-24 rounded-xl border"
                                       x-model="q.score"
                                       :name="`questions[${qIndex}][score]`">
                            </div>

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
                                                   :value="oIndex"
                                                   :checked="q.correct_index == oIndex"
                                                   @change="q.correct_index = oIndex">
                                            <input type="text"
                                                   class="flex-1 rounded-xl border"
                                                   x-model="opt.text"
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
                                               value="0"
                                               :checked="q.correct_index == 0"
                                               @change="q.correct_index = 0"> Benar
                                    </label>

                                    <label>
                                        <input type="radio"
                                               :name="`questions[${qIndex}][correct_index]`"
                                               value="1"
                                               :checked="q.correct_index == 1"
                                               @change="q.correct_index = 1"> Salah
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
                            Perbarui Soal
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- DELETE MODAL -->
    <x-modal name="confirm-delete-question" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Hapus Soal Quiz
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                Apakah kamu yakin ingin menghapus soal quiz ini?
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'confirm-delete-question')">
                    Batal
                </x-secondary-button>

                <x-danger-button
                    x-on:click="
                        $dispatch('confirm-delete-question-yes');
                        $dispatch('close-modal', 'confirm-delete-question');
                    ">
                    Ya, Hapus
                </x-danger-button>
            </div>
        </div>
    </x-modal>
</div>

<script>
function quizManager(initialData = []) {
    return {
        questions: initialData.map(q => {
            let correctIndex = null;

            if (q.type === 'mcq' && q.options) {
                correctIndex = q.options.findIndex(o => o.is_correct);
            }

            if (q.type === 'true_false') {
                correctIndex = q.options?.findIndex(o => o.is_correct) ?? 0;
            }

            return {
                id: q.id,
                type: q.type,
                question: q.question,
                score: q.score ?? 10,
                correct_index: correctIndex,
                options: q.type === 'mcq'
                    ? (q.options?.map(o => ({ text: o.option_text })) ?? [
                        { text: '' }, { text: '' }, { text: '' }, { text: '' }
                    ])
                    : q.type === 'true_false'
                        ? [{ text: 'Benar' }, { text: 'Salah' }]
                        : []
            };
        }),
        deleteIndex: null,

        confirmDelete() {
            if (this.deleteIndex !== null) {
                this.questions.splice(this.deleteIndex, 1)
                this.deleteIndex = null
            }
        },

        addQuestion() {
            this.questions.push({
                temp_id: Date.now(),
                id: null,
                type: 'mcq',
                question: '',
                score: 10,
                correct_index: 0,
                options: [
                    { text: '' },
                    { text: '' },
                    { text: '' },
                    { text: '' }
                ]
            });
        },

        onTypeChange(q) {
            if (q.type === 'mcq') {
                q.options = [
                    { text: '' },
                    { text: '' },
                    { text: '' },
                    { text: '' }
                ];
                q.correct_index = 0;
            }

            if (q.type === 'true_false') {
                q.options = [{ text: 'Benar' }, { text: 'Salah' }];
                q.correct_index = 0;
            }

            if (q.type === 'essay') {
                q.options = [];
                q.correct_index = null;
            }
        }
    }
}
</script>


</x-app-layout>