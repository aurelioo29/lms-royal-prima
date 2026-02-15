<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO / TOP SUMMARY --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="min-w-0 flex items-start gap-4">
                            <div
                                class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h1 class="text-xl font-bold text-slate-900 truncate">
                                    {{ $course->torSubmission->planEvent?->title ?? 'Nama Course Tidak Tersedia' }}
                                </h1>
                                <p class="text-sm text-slate-500 mt-0.5">Manajemen Modul Course</p>

                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        {{ $modules->count() ?? 0 }} Modul
                                    </span>

                                    @php
                                        $isPublished = ($course->status ?? '') === 'published';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $isPublished ? 'bg-green-50 text-green-700 border-green-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                        {{ ucfirst($course->status ?? 'Draft') }}
                                    </span>

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a4 4 0 00-5-4m-4 6h4m-4 0v-2a4 4 0 014-4m-4 6H7m4 0v-2a4 4 0 00-4-4H2v2h5" />
                                        </svg>
                                        {{ $enrolledCount }} Peserta
                                    </span>

                                    <a href="{{ route($routePrefix . '.enrollments.index', $course->id) }}"
                                        class="inline-flex items-center gap-2 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 px-3 py-1 text-xs font-semibold hover:bg-indigo-100 transition">
                                        Kelola Peserta
                                    </a>


                                </div>
                            </div>
                        </div>

                        <div class="shrink-0">
                            <a href="{{ route($routePrefix . '.modules.create', $course->id) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#121293] text-white hover:bg-[#121293]/90 transition-all shadow-sm text-sm font-semibold">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Modul
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT: TABLE --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-20">
                                    Urutan</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Judul
                                    Modul</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Wajib</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($modules ?? [] as $module)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-sm font-bold text-slate-700 border border-slate-200">
                                            {{ $module->sort_order }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-slate-900">
                                                    {{ $module->title }}
                                                </span>

                                                @if ($module->quiz)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-0.5">
                                                        Quiz
                                                    </span>
                                                @endif

                                                @if ($module->quiz && ($module->quiz->essay_questions_count ?? 0) > 0)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold px-2 py-0.5">
                                                        Essay
                                                    </span>
                                                @endif


                                            </div>

                                            @if ($module->quiz)
                                                @php
                                                    $questionCount = $module->quiz?->questions_count ?? 0;
                                                @endphp

                                                <span title="Tambahkan soal quiz agar bisa di"
                                                    class="inline-flex w-fit items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                    {{ $questionCount === 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                    {{ $questionCount === 0 ? 'Belum Ada Soal' : $questionCount . ' Soal' }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $module->is_required ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $module->is_required ? 'Wajib' : 'Opsional' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($module->quiz && $module->quiz->questions_count === 0)
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                     bg-amber-100 text-amber-800">
                                                Draft
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                {{ $module->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $module->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">

                                            @if ($module->quiz && $module->quiz->questions->where('type', 'essay')->count() > 0)
                                                <a href="{{ route($routePrefix . '.modules.quiz.review.index', [
                                                    'course' => $course->id,
                                                    'module' => $module->id,
                                                ]) }}"
                                                    title="Review Jawaban Essay"
                                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                                    {{-- Icon clipboard / review --}}
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5v2m6-2v2M9 14h6M9 18h6" />
                                                    </svg>
                                                </a>
                                            @endif



                                            @if ($module->quiz)
                                                <a href="{{ route($routePrefix . '.modules.quiz.questions.index', [$course->id, $module->id]) }}"
                                                    title="Kelola Soal Quiz"
                                                    class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5v2m6-2v2" />
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Preview Modul --}}
                                            <a href="{{ route($routePrefix . '.modules.show', [$course->id, $module->id]) }}"
                                                title="Preview Modul"
                                                class="p-2 text-slate-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                        c4.478 0 8.268 2.943 9.542 7
                                                        -1.274 4.057-5.064 7-9.542 7
                                                        -4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            {{-- Toggle Status --}}
                                            @if ($module->quiz && $module->quiz->questions_count === 0)
                                                <span title="Quiz belum memiliki soal"
                                                    class="p-2 text-slate-300 cursor-not-allowed rounded-lg">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </span>
                                            @else
                                                <form
                                                    action="{{ route($routePrefix . '.modules.toggle', [$course->id, $module->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit" title="Toggle Status"
                                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route($routePrefix . '.modules.edit', [$course->id, $module->id]) }}"
                                                class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                title="Edit Modul">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <button type="button" title="Hapus Modul"
                                                x-on:click="
                                                document.getElementById('deleteForm').action =
                                                '{{ route($routePrefix . '.modules.destroy', [$course->id, ':id']) }}'
                                                    .replace(':id', {{ $module->id }});
                                                $dispatch('open-modal', 'confirm-delete-module');"
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>


                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-slate-200 mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-sm font-medium">Belum ada modul yang ditambahkan.</p>
                                            <a href="{{ route($routePrefix . '.modules.create', $course->id) }}"
                                                class="mt-2 text-[#121293] hover:underline text-sm font-semibold">Klik
                                                di sini untuk membuat modul pertama.</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="relative z-[9999]">
        <x-modal name="confirm-delete-module" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Hapus Modul
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Apakah kamu yakin ingin menghapus modul ini?
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close-modal', 'confirm-delete-module')">
                        Batal
                    </x-secondary-button>

                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')

                        <x-danger-button type="submit">
                            Ya, Hapus
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </x-modal>
    </div>

</x-app-layout>
