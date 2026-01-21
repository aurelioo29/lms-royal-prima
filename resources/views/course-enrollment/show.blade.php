<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= HEADER COURSE ================= --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:justify-between gap-4">

                    <div>
                        <h1 class="text-2xl font-semibold text-slate-900">
                            {{ $course->event_title }}
                        </h1>

                        <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <span class="font-semibold">Narasumber:</span>

                            @if ($course->instructors->count())
                                @foreach ($course->instructors as $instructor)
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">
                                        {{ $instructor->name }}
                                        <span class="text-slate-400">
                                            ({{ ucfirst($instructor->pivot->role) }})
                                        </span>
                                    </span>
                                @endforeach
                            @else
                                <em class="text-slate-400">Belum ditentukan</em>
                            @endif
                        </div>


                        <p class="mt-2 text-sm text-slate-600 max-w-3xl">
                            {{ $course->event_description ?? 'Tidak ada deskripsi course.' }}
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="rounded-lg bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Enrolled
                            </span>

                            <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs text-slate-600">
                                {{ $course->type?->name ?? 'Umum' }}
                            </span>

                            @if ($progress['is_completed'])
                                <span class="rounded-lg bg-green-600 px-3 py-1 text-xs font-semibold text-white">
                                    ✔ Course Selesai
                                </span>
                            @endif

                        </div>
                    </div>

                    <div class="flex gap-2 items-start">
                        <a href="{{ route('employee.courses.index') }}"
                            class="rounded-lg border px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            ← Kembali
                        </a>

                        @if ($progress['is_completed'])
                            <span class="rounded-lg bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                                🎉 Course Telah Diselesaikan
                            </span>
                        @elseif ($progress['next_module'])
                            <a href="{{ route('employee.courses.modules.show', [$course, $progress['next_module']]) }}"
                                class="rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Lanjutkan Belajar
                            </a>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ================= INFO COURSE ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-info-card label="Jenis Course" :value="$course->type?->name ?? '-'" />
                <x-info-card label="TOR" :value="$course->torSubmission?->title ?? '-'" />
                <x-info-card label="Durasi" :value="$course->training_hours . ' Jam'" />
                <x-info-card label="Jumlah Modul" :value="$progress['total'] . ' Modul'" />
            </div>

            {{-- ================= PROGRESS ================= --}}
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="flex justify-between mb-3">
                    <h2 class="font-semibold text-slate-900">Progress Belajar</h2>
                    <span class="text-sm text-slate-600">
                        {{ $progress['completed'] }} / {{ $progress['total'] }} modul
                    </span>
                </div>

                <div class="h-3 rounded-full bg-slate-200 overflow-hidden">
                    <div class="h-full bg-[#121293] transition-all duration-300"
                        style="width: {{ $progress['percent'] }}%"></div>
                </div>

                <div class="mt-2 text-xs text-slate-500">
                    {{ $progress['percent'] }}% selesai
                </div>
            </div>

            {{-- ================= DAFTAR MODUL ================= --}}
            <div class="rounded-xl border bg-white shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="font-semibold text-slate-900">Daftar Modul</h2>
                </div>

                <div class="divide-y">
                    @forelse ($modules as $module)
                        @php
                            $progressModule = $module->progresses->first();
                            
                        @endphp

                        @php
                            $quiz = $module->quiz;

                            $attempts = $quiz?->attempts ?? collect();

                            $lastAttempt = $attempts->first();

                            $quizPassed = $lastAttempt?->is_passed;

                            $quizDone = $lastAttempt && $lastAttempt->submitted_at;

                            $usedAttempts = $attempts->count();

                            $maxAttempts = $quiz?->max_attempts; // null = unlimited

                            $attemptsLeft = is_null($maxAttempts)
                                ? null
                                : max(0, $maxAttempts - $usedAttempts);

                            $attemptsExhausted = ! is_null($maxAttempts) && $attemptsLeft <= 0;
                        @endphp


                        <div class="p-5 flex justify-between items-center hover:bg-slate-50">
                            <div>
                                <div class="font-medium text-slate-900">
                                    {{ $module->title }}
                                </div>
                                <div class="text-sm text-slate-500">
                                    {{ ucfirst($module->type) }}
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div>
                                @if ($progressModule?->status === 'completed')
                                    <span class="text-green-600 font-semibold flex items-center gap-1">
                                        ✔ Selesai
                                    </span>
                                    {{-- ✅ BUTTON BUKA ULANG --}}
                                    <a href="{{ route('employee.courses.modules.show', [$course, $module]) }}"
                                        class="rounded-lg border px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                        Buka Ulang
                                    </a>
                                @elseif ($progressModule)
                                    <a href="{{ route('employee.courses.modules.show', [$course, $module]) }}"
                                        class="rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white">
                                        Lanjutkan
                                    </a>
                                @elseif ($module->is_required)
                                    <span class="text-slate-400 text-sm flex items-center gap-1">
                                        🔒 Terkunci
                                    </span>
                                @else
                                    <a href="{{ route('employee.courses.modules.show', [$course, $module]) }}"
                                        class="rounded-lg border px-4 py-2 text-sm font-semibold text-slate-700">
                                        Mulai
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- ================= QUIZ ACTION ================= --}}
                        @if ($quiz)
                            <div class="mt-4 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#121293]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-700">
                                        Quiz Modul
                                    </span>
                                </div>

                                {{-- ================= STATUS / ACTION ================= --}}
                                <div class="flex items-center gap-2">

                                    {{-- ✅ SUDAH LULUS --}}
                                    @if ($quizPassed)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            ✔ Lulus
                                        </span>

                                    {{-- ❌ ATTEMPT HABIS --}}
                                    @elseif ($attemptsExhausted)
                                        <button
                                            @click="openQuizLimitModal = true"
                                            class="inline-flex items-center rounded-xl bg-red-500 px-4 py-2 text-xs font-bold text-white hover:bg-red-600 transition">
                                            Quiz Tidak Bisa Dikerjakan
                                        </button>

                                    {{-- 🔁 BISA ULANG --}}
                                    @elseif ($quizDone)
                                        <a href="{{ route('employee.courses.modules.quiz.start', [$course, $module]) }}"
                                            class="inline-flex items-center rounded-xl bg-yellow-500 px-4 py-2 text-xs font-bold text-white hover:bg-yellow-600 transition">
                                            Ulangi Quiz
                                            @if (!is_null($attemptsLeft))
                                                <span class="ml-2 text-[10px] opacity-80">
                                                    (Sisa {{ $attemptsLeft }}x)
                                                </span>
                                            @endif
                                        </a>

                                    {{-- ▶️ BELUM DIKERJAKAN --}}
                                    @else
                                        <a href="{{ route('employee.courses.modules.quiz.start', [$course, $module]) }}"
                                            class="inline-flex items-center rounded-xl bg-[#121293] px-4 py-2 text-xs font-bold text-white hover:opacity-90 transition">
                                            Kerjakan Quiz
                                        </a>
                                    @endif

                                </div>

                                <div
                                        x-data="{ openQuizLimitModal: false }"
                                        x-show="openQuizLimitModal"
                                        x-transition
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

                                        <div
                                            @click.away="openQuizLimitModal = false"
                                            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="rounded-full bg-red-100 p-3 text-red-600">
                                                    ⚠
                                                </div>
                                                <h2 class="text-lg font-bold text-slate-800">
                                                    Percobaan Quiz Habis
                                                </h2>
                                            </div>

                                            <p class="text-sm text-slate-600 mb-4">
                                                Anda telah menggunakan seluruh kesempatan pengerjaan quiz untuk modul ini.
                                                Silakan hubungi admin atau narasumber jika memerlukan reset percobaan.
                                            </p>

                                            <div class="flex justify-end gap-2">
                                                <button
                                                    @click="openQuizLimitModal = false"
                                                    class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                            </div>
                        @endif

                    @empty
                        <div class="p-6 text-center text-slate-500">
                            Belum ada modul pada course ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
