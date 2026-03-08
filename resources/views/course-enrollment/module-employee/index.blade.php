<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- BREADCRUMB & HEADER --}}
            <nav class="flex text-sm text-slate-500 mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-[#121293]">Dashboard</a></li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z">
                                </path>
                            </svg>
                            <a href="{{ route('employee.courses.show', $course) }}"
                                class="hover:text-[#121293]">Course</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z">
                                </path>
                            </svg>
                            <span class="font-medium text-slate-700">Modul</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-[#121293] mb-1">
                                {{ $course->title }}
                            </h2>
                            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">
                                {{ $module->title }}
                            </h1>
                        </div>

                        {{-- STATUS BADGE --}}
                        <div>
                            @if ($progress->status === 'completed')
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700 border border-green-200">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Selesai
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-700 border border-amber-200">
                                    <svg class="h-3.5 w-3.5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Sedang Dipelajari
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- KONTEN UTAMA --}}
                <div class="lg:col-span-12 space-y-6">

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        {{-- MODULE CONTENT VIEWER --}}
                        <div class="bg-slate-50 border-b border-slate-200 p-4 min-h-[400px] flex flex-col">


                            @if ($module->type === 'video')
                                <div
                                    class="aspect-video w-full max-w-4xl mx-auto overflow-hidden rounded-xl shadow-lg bg-black">

                                    {{-- ================= VIDEO UPLOAD ================= --}}
                                    @if ($module->file_path)
                                        <video controls class="w-full h-full rounded-xl">
                                            <source src="{{ asset('storage/' . $module->file_path) }}" type="video/mp4">
                                            Browser Anda tidak mendukung video.
                                        </video>

                                        {{-- ================= VIDEO LINK ================= --}}
                                    @elseif ($module->content)
                                        @php
                                            $embedUrl = youtube_embed_url($module->content);
                                        @endphp

                                        <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    @else
                                        <div class="flex items-center justify-center h-full text-white">
                                            Video tidak tersedia.
                                        </div>
                                    @endif

                                </div>
                            @elseif($module->type === 'pdf')
                                <div
                                    class="flex-1 w-full h-[600px] rounded-xl overflow-hidden border border-slate-200 bg-white">
                                    <iframe
                                        src="{{ asset('storage/' . $module->file_path) }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                                        class="w-full h-[80vh]" frameborder="0">
                                    </iframe>
                                    <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank"
                                        class="text-xs font-semibold text-[#121293] hover:underline">
                                        Download PDF
                                    </a>

                                </div>
                                <div class="mt-3 text-center">
                                    <p class="text-xs text-slate-500">Gunakan kontrol pada browser jika PDF tidak muncul
                                        otomatis.</p>
                                </div>
                            @elseif($module->type === 'link')
                                <div class="flex flex-col items-center justify-center py-20 text-center">
                                    <div
                                        class="h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center text-[#121293] mb-4">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-slate-900">Materi Eksternal</h3>
                                    <p class="text-slate-600 mb-6 max-w-sm">Klik tombol di bawah untuk membuka materi
                                        pada tab baru.</p>
                                    <a href="{{ $module->content }}" target="_blank"
                                        class="inline-flex items-center px-6 py-3 rounded-xl bg-[#121293] text-white font-semibold hover:bg-opacity-90 transition-all">
                                        Buka Materi
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            @elseif($module->type === 'quiz')
                                <div class="flex flex-col items-center justify-center py-20 text-center">
                                    <div
                                        class="h-16 w-16 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 mb-4">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-slate-900">Modul Quiz</h3>
                                    <p class="text-slate-600 mb-2">Fitur ujian/quiz sedang dalam tahap pengembangan.</p>
                                    <p class="text-sm text-slate-400 italic">(Placeholder logic quiz akan diletakkan di
                                        sini)</p>
                                </div>
                            @endif

                        </div>

                        {{-- DESKRIPSI & FOOTER --}}
                        <div class="p-5 sm:p-6 bg-white">
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="flex-1">
                                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">
                                        Deskripsi
                                        Modul</h2>
                                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                                        {!! $module->description ?? '<span class="italic text-slate-400">Tidak ada deskripsi untuk modul ini.</span>' !!}
                                    </div>

                                    @if ($module->is_required)
                                        <div class="mt-4 flex items-center gap-2 text-xs font-medium text-red-600">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Wajib diselesaikan untuk lanjut ke modul berikutnya.
                                        </div>
                                    @endif
                                </div>

                                <div class="shrink-0 flex flex-col justify-end items-center md:items-end">
                                    <form action="{{ route('employee.courses.modules.complete', [$course, $module]) }}"
                                        method="POST">
                                        @csrf
                                        @if ($progress->status === 'completed')
                                            <button type="button" disabled
                                                class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-slate-100 text-slate-400 font-bold cursor-not-allowed border border-slate-200 transition-all">
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Sudah Selesai
                                            </button>
                                        @else
                                            <button type="submit"
                                                class="group inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-[#121293] text-white font-bold hover:shadow-lg hover:shadow-blue-900/20 active:scale-95 transition-all">
                                                Tandai Selesai
                                                <svg class="h-5 w-5 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </form>

                                    @if ($progress->status === 'completed')
                                        <p class="mt-2 text-xs text-slate-500">Diselesaikan pada:
                                            {{ $progress->updated_at->translatedFormat('d F Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- NAVIGASI SEBELUMNYA/SELANJUTNYA (Opsional tapi berguna) --}}
                    <div class="flex items-center justify-between gap-4">
                        <a href="{{ route('employee.courses.show', $course) }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-[#121293] transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar Modul
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS Khusus untuk menyesuaikan Iframe Video agar responsif */
        iframe {
            width: 100%;
            height: 100%;
        }

        .prose {
            color: #475569;
        }

        .prose strong {
            color: #1e293b;
        }
    </style>


</x-app-layout>
