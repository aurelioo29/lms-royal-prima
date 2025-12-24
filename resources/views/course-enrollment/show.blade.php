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
                        </div>
                    </div>

                    <div class="flex gap-2 items-start">
                        <a href="{{ route('employee.courses.index') }}"
                            class="rounded-lg border px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            ← Kembali
                        </a>

                        @if ($progress['next_module'])
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
