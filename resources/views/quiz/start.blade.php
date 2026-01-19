    <x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- BACK -->
            <div class="mb-6">
                <a href="{{ url()->previous() }}"
                   class="text-sm text-slate-500 hover:text-indigo-600">
                    ← Kembali
                </a>
            </div>

            <div class="bg-white border rounded-2xl p-8">

                <h1 class="text-2xl font-bold mb-6">
                    {{ $quiz->title }}
                </h1>

                <!-- QUIZ INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-700 mb-8">

                    <div>
                        <div class="font-semibold text-slate-900 mb-1">
                            Jumlah Soal
                        </div>
                        <div>
                            {{ $quiz->questions->count() }} soal
                        </div>
                    </div>

                    <div>
                        <div class="font-semibold text-slate-900 mb-1">
                            Nilai Kelulusan
                        </div>
                        <div>
                            {{ $quiz->passing_score }}%
                        </div>
                    </div>

                    <div>
                        <div class="font-semibold text-slate-900 mb-1">
                            Durasi
                        </div>
                        <div>
                            @if($quiz->time_limit)
                                {{ $quiz->time_limit }} menit
                            @else
                                Tidak dibatasi waktu
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="font-semibold text-slate-900 mb-1">
                            Tipe Penilaian
                        </div>
                        <div>
                            Pilihan ganda otomatis, Essay dinilai manual
                        </div>
                    </div>

                </div>

                <!-- WARNING -->
                <div class="mb-8 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-sm text-yellow-800">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Quiz hanya dapat dikerjakan satu kali.</li>
                        <li>Jika waktu habis, quiz akan otomatis dikumpulkan.</li>
                        <li>Pastikan koneksi internet stabil sebelum memulai.</li>
                    </ul>
                </div>

                <!-- START BUTTON -->
                <form method="POST" action="{{ route('quiz.start', $quiz) }}">
                    @csrf

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                            Mulai Quiz
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
