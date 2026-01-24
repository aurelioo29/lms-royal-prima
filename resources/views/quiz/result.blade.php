<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-8">
                <h1 class="text-xl font-bold text-slate-900">
                    {{ $quiz->title }}
                </h1>

                {{-- HASIL BELUM BISA DITAMPILKAN --}}
                @if (!$canShowResult)
                    <div class="mt-4 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                        <div class="font-semibold text-yellow-700">
                            ⏳ Quiz Telah Dikerjakan
                        </div>
                        <div class="text-sm text-yellow-600 mt-1">
                            Quiz ini mengandung soal essay yang memerlukan penilaian manual oleh instruktur.
                        </div>
                        <div class="text-sm text-yellow-600 mt-1">
                            Nilai akhir dan status kelulusan akan ditampilkan setelah proses penilaian selesai.
                        </div>

                    </div>

                    {{-- HASIL SUDAH FINAL --}}
                @else
                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm text-slate-700">
                            Skor Akhir:
                            <strong>{{ $attempt->score }}</strong> /
                            {{ $attempt->max_score }}
                        </div>

                        <div class="mt-1 text-sm">
                            Status:
                            @if ($attempt->is_passed)
                                <span class="font-semibold text-green-600">
                                    ✔ LULUS
                                </span>
                            @else
                                <span class="font-semibold text-red-600">
                                    ✖ TIDAK LULUS
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <!-- ACTION -->
            <div class="mt-10 flex justify-end">
                <a href="{{ route('employee.courses.show', $course) }}"
                    class="px-6 py-3 bg-slate-600 text-white rounded-xl hover:bg-slate-700">
                    Kembali ke Course
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
