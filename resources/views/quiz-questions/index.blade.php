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

                        <a href="{{ route('courses.modules.quiz.questions.create', ['course' => $course->id, 'module' => $module->id]) }}"
                            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Quiz Baru
                        </a>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 border-y border-slate-100">
                            <tr>
                                <th class="text-left px-6 py-4 font-semibold">Judul Quiz</th>
                                <th class="text-left px-4 py-4 font-semibold">Course</th>
                                <th class="text-left px-4 py-4 font-semibold">Tipe</th>
                                <th class="text-center px-4 py-4 font-semibold">Soal</th>
                                <th class="text-center px-4 py-4 font-semibold">Passing Score</th>
                                <th class="text-left px-4 py-4 font-semibold">Status</th>
                                <th class="text-right px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($questions as $question)
                                <tr>
                                    <td>{{ $question->question }}</td>
                                    <td>
                                        @foreach ($question->options as $option)
                                            <div>{{ $option->option_text }} @if ($option->is_correct)
                                                    ✔
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('courses.modules.quiz.questions.edit', ['course' => $course->id, 'module' => $module->id, 'question' => $question->id]) }}">Edit</a>
                                        <form
                                            action="{{ route('courses.modules.quiz.questions.destroy', ['course' => $course->id, 'module' => $module->id, 'question' => $question->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada soal untuk quiz ini</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Pagination Section -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{-- {{ $quizzes->appends(request()->query())->links() }} --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
