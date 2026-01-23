<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Main Card -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <!-- Header -->
                <div class="p-5 sm:p-6">
                    <h1 class="text-xl font-semibold text-slate-900">
                        Review Essay Quiz
                    </h1>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $module->title }} – {{ $course->title }}
                    </p>
                </div>



                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 border-y border-slate-100">
                            <tr>
                                <th class="text-left px-6 py-4 font-semibold">Peserta</th>
                                <th class="text-center px-4 py-4 font-semibold">Nilai</th>
                                <th class="text-center px-4 py-4 font-semibold">Status</th>
                                <th class="text-right px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($attempts as $attempt)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">
                                            {{ $attempt->user->name }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $attempt->user->email }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        {{ $attempt->score ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @switch($attempt->status)
                                            @case('submitted')
                                                <span
                                                    class="inline-flex rounded-full bg-yellow-100 px-3 py-1
                text-xs font-semibold text-yellow-700">
                                                    ⏳ Menunggu Review
                                                </span>
                                            @break

                                            @case('reviewed_passed')
                                                <span
                                                    class="inline-flex rounded-full bg-green-100 px-3 py-1
                text-xs font-semibold text-green-700">
                                                    ✔ Lulus
                                                </span>
                                            @break

                                            @case('reviewed_failed')
                                                <span
                                                    class="inline-flex rounded-full bg-red-100 px-3 py-1
                text-xs font-semibold text-red-700">
                                                    ✖ Tidak Lulus
                                                </span>
                                            @break
                                        @endswitch
                                    </td>



                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route($routePrefix . '.modules.quiz.review.show', [
                                            'course' => $course->id,
                                            'module' => $module->id,
                                            'attempt' => $attempt->id,
                                        ]) }}"
                                            class="inline-flex items-center gap-2 rounded-xl
                                            bg-[#121293] px-4 py-2 text-sm font-semibold
                                            text-white hover:bg-blue-900 transition">
                                            Review Essay
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                            Tidak ada attempt dengan soal essay.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </x-app-layout>
