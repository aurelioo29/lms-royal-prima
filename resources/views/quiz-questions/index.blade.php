<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Card -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <!-- Header Branding -->
                <div class="h-1 w-full bg-[#121293]"></div>

                <!-- Page Header & Actions -->
                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Quiz Management</h1>
                        <p class="text-sm text-slate-600 mt-1">
                            Kelola daftar kuis, ambang batas nilai, dan monitoring jumlah soal.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Search & Filter Form -->
                        <form method="GET" action="" class="flex flex-wrap items-center gap-2">
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari judul kuis..."
                                    class="w-64 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#121293] focus:ring-1 focus:ring-[#121293]">
                            </div>

                            <select name="status"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#121293] focus:ring-1 focus:ring-[#121293]">
                                <option value="">Semua Status</option>
                                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                                <option value="published" @selected(request('status') === 'published')>Published</option>
                                <option value="archived" @selected(request('status') === 'archived')>Archived</option>
                            </select>

                            <button type="submit"
                                class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:bg-blue-900 transition-colors">
                                Filter
                            </button>
                        </form>

                        <a href="{{ route($routePrefix . '.modules.quiz.questions.create', [
                                'course' => $course->id,
                                'module' => $module->id
                            ]) }}"
                            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Soal
                        </a>

                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 border-y border-slate-100">
                            <tr>
                                <th class="text-left px-6 py-4 font-semibold">Pertanyaan</th>
                                <th class="text-left px-4 py-4 font-semibold">Tipe</th>
                                <th class="text-left px-4 py-4 font-semibold">Opsi / Jawaban</th>
                                <th class="text-center px-4 py-4 font-semibold">Skor</th>
                                <th class="text-right px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($questions as $question)
                                <tr>
                                    {{-- Question --}}
                                    <td class="px-6 py-4">
                                        {{ $question->question }}
                                    </td>

                                    {{-- Type --}}
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold">
                                            {{ strtoupper($question->type) }}
                                        </span>
                                    </td>

                                    {{-- Options --}}
                                    <td class="px-4 py-4">
                                        @if ($question->type === 'essay')
                                            <span class="italic text-slate-500">
                                                Jawaban essay
                                            </span>
                                        @else
                                            <ul class="space-y-1">
                                                @foreach ($question->options as $option)
                                                    <li class="flex items-center gap-2">
                                                        <span>{{ $option->option_text }}</span>
                                                        @if ($option->is_correct)
                                                            <span class="text-green-600 font-bold">✔</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    {{-- Score --}}
                                    <td class="px-4 py-4 text-center">
                                        {{ $question->score }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">

                                            <!-- EDIT -->
                                            <a href="{{ route($routePrefix . '.modules.quiz.questions.edit',
                                                [$course->id, $module->id, $question->id]) }}"
                                                title="Edit Soal"
                                                class="inline-flex items-center justify-center
                                                    w-9 h-9 rounded-lg
                                                    text-slate-500 hover:text-amber-600
                                                    hover:bg-amber-50
                                                    transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- DELETE -->
                                            <button type="button"
                                                title="Hapus Soal"
                                                x-on:click="
                                                    document.getElementById('deleteForm').action =
                                                    '{{ route($routePrefix . '.modules.quiz.questions.destroy', [$course->id, $module->id, '__id__']) }}'
                                                        .replace('__id__', {{ $question->id }});
                                                    $dispatch('open-modal', 'confirm-delete-question');
                                                "
                                                class="inline-flex items-center justify-center
                                                    w-9 h-9 rounded-lg
                                                    text-slate-500 hover:text-red-600
                                                    hover:bg-red-50
                                                    transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
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
                                    <td colspan="5" class="text-center px-6 py-8 text-slate-500">
                                        Belum ada soal untuk quiz ini.
                                    </td>
                                </tr>
                            @endforelse

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

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
