<x-app-layout>
    <div class="py-12" x-data="quizManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs / Back Link -->
            <div class="mb-6">
                <a href="{{ route('quizzes.index') }}"
                    class="text-sm text-slate-500 hover:text-indigo-600 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Manajemen Quiz
                </a>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="font-bold">Mohon periksa kembali inputan Anda:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Section A: Informasi Quiz (Sidebar Left) -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="h-1.5 w-full" style="background-color: #121293;"></div>
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-slate-800 mb-4 uppercase tracking-wider">Edit
                                    Informasi Dasar</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Quiz <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="title" value="{{ old('title', $quiz->title) }}"
                                            required
                                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                            placeholder="Contoh: Post-test Kebersihan Tangan">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe Quiz</label>
                                        <select name="quiz_type"
                                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            <option value="pre_test"
                                                {{ old('quiz_type', $quiz->quiz_type) == 'pre_test' ? 'selected' : '' }}>
                                                Pre-Test</option>
                                            <option value="post_test"
                                                {{ old('quiz_type', $quiz->quiz_type) == 'post_test' ? 'selected' : '' }}>
                                                Post-Test</option>
                                            <option value="evaluation"
                                                {{ old('quiz_type', $quiz->quiz_type) == 'evaluation' ? 'selected' : '' }}>
                                                Evaluasi</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                                        <textarea name="description" rows="3"
                                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                            placeholder="Tuliskan petunjuk pengerjaan quiz...">{{ old('description', $quiz->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Lanjut</h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Waktu
                                            (Menit)</label>
                                        <input type="number" name="time_limit"
                                            value="{{ old('time_limit', $quiz->time_limit) }}"
                                            class="w-full rounded-xl border-slate-300">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Passing
                                            Score</label>
                                        <input type="number" name="passing_score"
                                            value="{{ old('passing_score', $quiz->passing_score) }}"
                                            class="w-full rounded-xl border-slate-300">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Batas
                                        Percobaan</label>
                                    <input type="number" name="max_attempts"
                                        value="{{ old('max_attempts', $quiz->max_attempts) }}"
                                        class="w-full rounded-xl border-slate-300">
                                </div>

                                <div class="space-y-3 pt-2">
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <input type="checkbox" name="shuffle_questions" value="1"
                                            {{ old('shuffle_questions', $quiz->shuffle_questions) ? 'checked' : '' }}
                                            class="rounded text-indigo-600 focus:ring-indigo-500 h-5 w-5 border-slate-300">
                                        <span
                                            class="text-sm text-slate-700 group-hover:text-indigo-600 transition-colors">Acak
                                            Pertanyaan</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <input type="checkbox" name="shuffle_options" value="1"
                                            {{ old('shuffle_options', $quiz->shuffle_options) ? 'checked' : '' }}
                                            class="rounded text-indigo-600 focus:ring-indigo-500 h-5 w-5 border-slate-300">
                                        <span
                                            class="text-sm text-slate-700 group-hover:text-indigo-600 transition-colors">Acak
                                            Pilihan Jawaban</span>
                                    </label>
                                </div>

                                <div class="pt-4 border-t border-slate-100">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                                    <div class="flex bg-slate-100 p-1 rounded-xl">
                                        <label
                                            class="flex-1 text-center py-2 rounded-lg cursor-pointer transition-all has-[:checked]:bg-white has-[:checked]:shadow-sm has-[:checked]:text-indigo-600 text-slate-500 font-medium">
                                            <input type="radio" name="status" value="draft" class="hidden"
                                                {{ old('status', $quiz->status) == 'draft' ? 'checked' : '' }}> Draft
                                        </label>
                                        <label
                                            class="flex-1 text-center py-2 rounded-lg cursor-pointer transition-all has-[:checked]:bg-white has-[:checked]:shadow-sm has-[:checked]:text-indigo-600 text-slate-500 font-medium">
                                            <input type="radio" name="status" value="published" class="hidden"
                                                {{ old('status', $quiz->status) == 'published' ? 'checked' : '' }}>
                                            Published
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section B: Manajemen Soal (Main Content) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-full">
                            <div class="h-1.5 w-full" style="background-color: #121293;"></div>

                            <div class="p-8">
                                <div class="flex justify-between items-center mb-8">
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-800">Edit Daftar Pertanyaan</h3>
                                        <p class="text-slate-500 text-sm mt-1">Tambahkan, edit, atau hapus soal untuk
                                            memperbarui quiz ini.</p>
                                    </div>
                                    <button type="button" @click="addQuestion()"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Soal
                                    </button>
                                </div>

                                <!-- Questions Container -->
                                <div class="space-y-8 relative">
                                    <template x-for="(question, qIndex) in questions" :key="question.tempId">
                                        <div
                                            class="p-6 border border-slate-200 rounded-2xl bg-slate-50 relative transition-all hover:border-indigo-300">

                                            <!-- Hidden ID for Existing Records -->
                                            <input type="hidden" :name="'questions[' + qIndex + '][id]'"
                                                x-model="question.id">

                                            <!-- Question Number & Remove -->
                                            <div class="flex justify-between items-start mb-6">
                                                <div class="flex items-center">
                                                    <span
                                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm mr-3">
                                                        <span x-text="qIndex + 1"></span>
                                                    </span>
                                                    <h4 class="font-bold text-slate-700"
                                                        x-text="question.id ? 'Edit Pertanyaan' : 'Pertanyaan Baru'">
                                                    </h4>
                                                </div>
                                                <button type="button" @click="removeQuestion(qIndex)"
                                                    class="text-red-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                                <div class="md:col-span-3">
                                                    <label
                                                        class="block text-xs font-bold text-slate-500 uppercase mb-1">Teks
                                                        Pertanyaan</label>
                                                    <textarea :name="'questions[' + qIndex + '][text]'" x-model="question.text" required
                                                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                                        rows="2" placeholder="Tuliskan pertanyaan Anda..."></textarea>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-slate-500 uppercase mb-1">Score</label>
                                                    <input type="number" :name="'questions[' + qIndex + '][score]'"
                                                        x-model="question.score"
                                                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                                </div>
                                            </div>

                                            <div class="mb-6">
                                                <label
                                                    class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipe
                                                    Pertanyaan</label>
                                                <select x-model="question.type" :name="'questions[' + qIndex + '][type]'"
                                                    class="w-full md:w-1/3 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                                    <option value="multiple_choice">Pilihan Ganda</option>
                                                    <option value="true_false">Benar / Salah</option>
                                                </select>
                                            </div>

                                            <!-- Multiple Choice Options -->
                                            <div x-show="question.type === 'multiple_choice'" x-transition>
                                                <label
                                                    class="block text-xs font-bold text-slate-500 uppercase mb-3">Pilihan
                                                    Jawaban (Centang untuk yang benar)</label>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <template x-for="(optValue, oIndex) in question.options"
                                                        :key="oIndex">
                                                        <div
                                                            class="flex items-center space-x-2 bg-white p-3 border border-slate-200 rounded-xl transition-all has-[:checked]:border-green-300 has-[:checked]:ring-1 has-[:checked]:ring-green-100">
                                                            <input type="radio"
                                                                :name="'questions[' + qIndex + '][is_correct]'"
                                                                :value="oIndex"
                                                                :checked="question.is_correct == oIndex"
                                                                @change="question.is_correct = oIndex"
                                                                class="text-green-600 focus:ring-green-500 h-5 w-5 border-slate-300">
                                                            <input type="text"
                                                                :name="'questions[' + qIndex + '][options][' + oIndex + ']'"
                                                                x-model="question.options[oIndex]" required
                                                                class="flex-1 border-none focus:ring-0 text-sm p-0 placeholder-slate-400"
                                                                placeholder="Opsi...">
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- True/False Options -->
                                            <div x-show="question.type === 'true_false'" x-transition>
                                                <label
                                                    class="block text-xs font-bold text-slate-500 uppercase mb-3">Pilih
                                                    Jawaban Benar</label>
                                                <div class="flex space-x-4">
                                                    <label
                                                        class="flex-1 flex items-center justify-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-white transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                                        <input type="radio"
                                                            :name="'questions[' + qIndex + '][is_correct]'" value="true"
                                                            :checked="question.is_correct === 'true' || question
                                                                .is_correct === true || question.is_correct == 0"
                                                            @change="question.is_correct = 'true'"
                                                            class="text-green-600 focus:ring-green-500 mr-2">
                                                        <span class="font-bold text-slate-700 uppercase">Benar</span>
                                                        <input type="hidden"
                                                            :name="'questions[' + qIndex + '][options][0]'"
                                                            value="Benar">
                                                    </label>
                                                    <label
                                                        class="flex-1 flex items-center justify-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-white transition-all has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                                        <input type="radio"
                                                            :name="'questions[' + qIndex + '][is_correct]'" value="false"
                                                            :checked="question.is_correct === 'false' || question
                                                                .is_correct === false || question.is_correct == 1"
                                                            @change="question.is_correct = 'false'"
                                                            class="text-red-600 focus:ring-red-500 mr-2">
                                                        <span
                                                            class="font-bold text-slate-700 uppercase text-slate-700">Salah</span>
                                                        <input type="hidden"
                                                            :name="'questions[' + qIndex + '][options][1]'"
                                                            value="Salah">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Empty State -->
                                    <div x-show="questions.length === 0"
                                        class="py-12 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 text-slate-400">
                                        <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        <p class="font-medium">Semua pertanyaan telah dihapus. Klik tombol di atas
                                            untuk menambah.</p>
                                    </div>
                                </div>

                                <!-- Sticky Footer Action -->
                                <div
                                    class="mt-12 pt-8 border-t border-slate-100 flex justify-end items-center space-x-4">
                                    <a href="{{ route('quizzes.index') }}"
                                        class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors uppercase tracking-widest">
                                        Batalkan
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 active:bg-indigo-900 focus:outline-none focus:ring ring-indigo-300 transition ease-in-out duration-150">
                                        Perbarui Quiz
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function quizManager() {
                // Helper to parse PHP data safely
                const initialQuestions = @json($quiz->questions ?? []);

                return {
                    // Initialize questions from DB or empty array
                    questions: initialQuestions.map(q => ({
                        tempId: 'id-' + Math.random().toString(36).substr(2, 9),
                        id: q.id,
                        text: q.text,
                        score: q.score,
                        type: q.type,
                        is_correct: q.is_correct,
                        // If multiple choice, ensure 4 options exist, otherwise Benar/Salah
                        options: q.options && q.options.length > 0 ? q.options : ['', '', '', '']
                    })),

                    addQuestion() {
                        const tempId = 'new-' + Date.now();
                        this.questions.push({
                            tempId: tempId,
                            id: null,
                            text: '',
                            score: 10,
                            type: 'multiple_choice',
                            is_correct: null,
                            options: ['', '', '', '']
                        });
                    },

                    removeQuestion(index) {
                        if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
                            this.questions.splice(index, 1);
                        }
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
