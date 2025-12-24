<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER: Navigation & Module Info --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('courses.modules.index', $course) }}"
                                class="group inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-all hover:bg-slate-50 hover:text-[#121293]">
                                <svg class="h-5 w-5 transition-transform group-hover:-translate-x-0.5" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                </svg>
                            </a>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="inline-flex items-center rounded-md bg-[#121293]/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#121293]">
                                        {{ $module->type }}
                                    </span>
                                    @if ($module->is_required)
                                        <span
                                            class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700 border border-amber-100">
                                            Wajib
                                        </span>
                                    @endif
                                </div>
                                <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ $module->title }}</h1>
                                <p class="text-sm text-slate-500 truncate max-w-md">{{ $course->title }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('courses.modules.edit', [$course, $module]) }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                                <svg class="mr-2 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Modul
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- MAIN CONTENT: Preview Area --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Content Display --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden min-h-[400px]">
                        @if ($module->type === 'video')
                            {{-- Case Video --}}
                            <div class="aspect-video bg-black w-full">
                                @if ($module->content)
                                    <iframe src="{{ youtube_embed_url($module->content) }}" class="w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <div class="flex h-full items-center justify-center text-slate-500 text-sm italic">
                                        URL Video belum diset.
                                    </div>
                                @endif
                            </div>
                        @elseif($module->type === 'pdf')
                            {{-- Case PDF --}}
                            <div class="p-12 flex flex-col items-center justify-center text-center">
                                <div class="mb-6 rounded-3xl bg-red-50 p-6 text-red-600">
                                    <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900">Dokumen PDF</h3>
                                <p class="mt-2 text-slate-500 max-w-sm">Preview langsung PDF dinonaktifkan untuk
                                    menghemat bandwidth. Klik tombol di bawah untuk melihat file.</p>

                                @if ($module->file_path)
                                    <a href="{{ Storage::url($module->file_path) }}" target="_blank"
                                        class="mt-8 inline-flex items-center rounded-xl bg-[#121293] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition-all hover:scale-105 active:scale-95">
                                        Lihat File PDF
                                        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <div
                                        class="mt-8 rounded-lg bg-slate-50 px-4 py-2 text-xs font-medium text-slate-400 border border-slate-200">
                                        File belum diunggah
                                    </div>
                                @endif
                            </div>
                        @elseif($module->type === 'link')
                            {{-- Case Link --}}
                            <div class="p-12 flex flex-col items-center justify-center text-center">
                                <div class="mb-6 rounded-3xl bg-blue-50 p-6 text-blue-600">
                                    <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900">Eksternal Link</h3>
                                <p
                                    class="mt-2 text-slate-600 break-all max-w-md font-mono text-sm bg-slate-50 p-2 rounded border">
                                    {{ $module->content ?? 'URL Kosong' }}</p>

                                <a href="{{ $module->content }}" target="_blank"
                                    class="mt-8 inline-flex items-center rounded-xl bg-white border border-slate-200 px-6 py-3 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 active:scale-95">
                                    Buka Link
                                    <svg class="ml-2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        @elseif($module->type === 'quiz')
                            {{-- Case Quiz --}}
                            <div class="p-12 flex flex-col items-center justify-center text-center">
                                <div class="mb-6 animate-pulse rounded-3xl bg-amber-50 p-6 text-amber-500">
                                    <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 text-[#121293]">Modul Kuis</h3>
                                <p class="mt-2 text-slate-500 italic">Fitur interaksi kuis sedang dalam tahap
                                    pengembangan (Coming Soon).</p>
                            </div>
                        @endif
                    </div>

                    {{-- Description Card --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Deskripsi Modul</h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                            {!! $module->description ?? '<span class="italic text-slate-400">Tidak ada deskripsi untuk modul ini.</span>' !!}
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR: Module List --}}
                <div class="lg:col-span-4 space-y-6">
                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden lg:sticky lg:top-6">
                        <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                            <div class="font-bold text-slate-900">Kurikulum Course</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $allModules->count() }} Modul Tersedia</div>
                        </div>

                        <div class="p-2 max-h-[calc(100vh-250px)] overflow-y-auto custom-scrollbar">
                            <div class="space-y-1">
                                @foreach ($allModules as $m)
                                    <a href="{{ route('courses.modules.show', [$course, $m]) }}"
                                        class="flex items-start gap-3 px-3 py-3 rounded-xl transition-all {{ $m->id === $module->id ? 'bg-[#121293]/5 border border-[#121293]/10' : 'hover:bg-slate-50 border border-transparent' }}">

                                        {{-- Icon Based on Type --}}
                                        <div class="mt-0.5 shrink-0">
                                            @if ($m->type === 'video')
                                                <svg class="h-5 w-5 {{ $m->id === $module->id ? 'text-[#121293]' : 'text-slate-400' }}"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($m->type === 'pdf')
                                                <svg class="h-5 w-5 {{ $m->id === $module->id ? 'text-[#121293]' : 'text-slate-400' }}"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @elseif($m->type === 'link')
                                                <svg class="h-5 w-5 {{ $m->id === $module->id ? 'text-[#121293]' : 'text-slate-400' }}"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 {{ $m->id === $module->id ? 'text-[#121293]' : 'text-slate-400' }}"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div
                                                class="text-sm font-semibold truncate {{ $m->id === $module->id ? 'text-[#121293]' : 'text-slate-700' }}">
                                                {{ $m->title }}
                                            </div>
                                            <div class="mt-0.5 flex items-center gap-2">
                                                <span
                                                    class="text-[10px] uppercase font-bold text-slate-400">{{ $m->type }}</span>
                                                @if (!$m->is_active)
                                                    <span
                                                        class="text-[10px] text-red-500 font-bold italic">Draft</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($m->id === $module->id)
                                            <div class="shrink-0">
                                                <div class="h-2 w-2 rounded-full bg-[#121293]"></div>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="p-4 border-t border-slate-200">
                            <a href="{{ route('courses.modules.index', $course) }}"
                                class="flex items-center justify-center gap-2 text-xs font-bold text-[#121293] hover:underline">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Kelola Semua Modul
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</x-app-layout>
