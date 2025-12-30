<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ================= ALERTS ================= --}}
            @if (session('success'))
                <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 animate-fade-in-down"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ================= PAGE HEADER & SEARCH ================= --}}
            <div class="relative overflow-hidden rounded-2xl bg-[#121293] p-8 shadow-2xl">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">E-Learning Course</h1>
                        <p class="mt-2 text-blue-100 max-w-full">
                            Tingkatkan kompetensi Anda dengan akses pelatihan internal yang dikurasi khusus untuk
                            pengembangan karir Anda.
                        </p>
                    </div>

                    <div class="w-full md:w-96">
                        <form method="GET" action="{{ route('employee.courses.index') }}">
                            <div class="relative group">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari pelatihan..."
                                    class="w-full rounded-xl border-0 bg-white/10 py-3 pl-11 pr-4 text-white placeholder-blue-200 backdrop-blur-md focus:bg-white focus:text-slate-900 focus:ring-4 focus:ring-white/20 transition-all">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-200 group-focus-within:text-slate-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Background Pattern Decor -->
                <div class="absolute -right-10 -bottom-10 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
            </div>

            {{-- ================= COURSE CARD GRID ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($courses as $course)
                    @php
                        $isEnrolled = $course
                            ->enrollments()
                            ->where('user_id', auth()->id())
                            ->exists();
                    @endphp

                    <div x-data="{ openDetail: false }"
                        class="group flex flex-col h-full bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                        {{-- Card Body --}}
                        <div class="p-6 flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-[#121293]">
                                    {{ $course->type?->name ?? 'General' }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $course->status === 'published'
                                        ? 'bg-green-50 text-green-700 border border-green-100'
                                        : 'bg-yellow-50 text-yellow-700 border border-yellow-100' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-1.5
                                        {{ $course->status === 'published' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    </span>
                                    {{ ucfirst($course->status) }}
                                </span>
                            </div>
                            {{-- course tyepe --}}
                            <h3
                                class="text-xl font-bold text-slate-900 mb-2 group-hover:text-[#121293] transition-colors line-clamp-2">
                                {{ $course->title }}
                            </h3>

                            {{-- Narasumber --}}
                            <div class="flex items-start text-sm text-slate-600 mb-3">
                                <svg class="w-4 h-4 mr-1.5 mt-0.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                <span class="truncate">
                                    <strong>Narasumber:</strong>
                                    @if ($course->instructors->count())
                                        {{ $course->instructors->pluck('name')->join(', ') }}
                                    @else
                                        <em>Belum ditentukan</em>
                                    @endif
                                </span>
                            </div>


                            <div class="flex items-center text-sm text-slate-500 mb-4">
                                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="truncate">Title:
                                    {{ $course->torSubmission->planEvent?->title ?? 'N/A' }}</span>
                            </div>

                            <div class="flex items-center text-sm text-slate-500 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $course->training_hours }} Jam Pelatihan
                            </div>

                            <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $course->tujuan ?? 'Pelajari lebih dalam mengenai modul ini untuk meningkatkan performa kerja Anda.' }}
                            </p>
                        </div>

                        {{-- Card Footer --}}
                        <div class="px-6 py-5 bg-slate-50 border-t border-slate-100 flex items-center gap-3">
                            <button @click="openDetail = true"
                                class="flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-[#121293] hover:border-[#121293] transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Detail
                            </button>

                            @if ($isEnrolled)
                                <a href="{{ route('employee.courses.show', $course) }}"
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-xl hover:bg-slate-900 transition-all">
                                    Lihat Course
                                </a>
                            @else
                                <a href="{{ route('employee.courses.show', $course) }}"
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold text-white bg-[#121293] rounded-xl hover:bg-opacity-90 shadow-lg shadow-blue-900/20 transition-all">
                                    Enroll Now
                                </a>
                            @endif
                        </div>

                        {{-- ================= MODAL DETAIL ================= --}}
                        <template x-teleport="body">
                            <div x-show="openDetail" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                                x-cloak>

                                <div @click.away="openDetail = false"
                                    class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">

                                    <div
                                        class="sticky top-0 bg-white border-b border-slate-100 px-8 py-5 flex items-center justify-between">
                                        <h2 class="text-xl font-bold text-slate-900 italic tracking-tight">Detail Modul
                                            Pelatihan</h2>
                                        <button @click="openDetail = false"
                                            class="text-slate-400 hover:text-slate-600 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="p-8">
                                        <div class="space-y-6">
                                            <div>
                                                <h1 class="text-3xl font-extrabold text-[#121293] leading-tight mb-2">
                                                    {{ $course->title }}</h1>
                                                <div class="text-sm text-slate-600 mt-2">
                                                    <strong>Narasumber:</strong>
                                                    @if ($course->instructors->count())
                                                        {{ $course->instructors->pluck('name')->join(', ') }}
                                                    @else
                                                        <em>Belum ditentukan</em>
                                                    @endif
                                                </div>

                                                <div class="flex flex-wrap gap-2 mt-4">
                                                    <span
                                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider">
                                                        {{ $course->type?->name ?? 'Standard' }}
                                                    </span>
                                                    <span
                                                        class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold uppercase tracking-wider">
                                                        TOR: {{ $course->torSubmission?->title ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="prose prose-slate max-w-none">
                                                <h4
                                                    class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-2 italic underline underline-offset-4 decoration-blue-500">
                                                    Tentang Course</h4>
                                                <p class="text-slate-600 text-lg leading-relaxed whitespace-pre-line">
                                                    {{ $course->tujuan ?? 'Belum ada deskripsi lengkap.' }}
                                                </p>
                                            </div>

                                            @if (!$isEnrolled)
                                                {{-- ================= BELUM ENROLL ================= --}}
                                                <div
                                                    class="bg-[#121293]/5 border-l-4 border-[#121293] p-4 rounded-r-xl">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0 text-[#121293]">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>

                                                        <div class="ml-3">
                                                            <h3 class="text-sm font-medium text-[#121293]">
                                                                Persyaratan Enrollment
                                                            </h3>

                                                            <div class="mt-2 text-sm text-[#121293]/80">
                                                                <p>
                                                                    Untuk mengikuti pelatihan ini, silakan siapkan
                                                                    <strong>Enrollment Key</strong> yang Anda terima
                                                                    dari
                                                                    departemen terkait.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- ================= SUDAH ENROLL ================= --}}
                                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0 text-green-600">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>

                                                        <div class="ml-3">
                                                            <h3 class="text-sm font-medium text-green-700">
                                                                Anda Sudah Terdaftar
                                                            </h3>

                                                            <div class="mt-2 text-sm text-green-700/80">
                                                                <p>
                                                                    Anda telah berhasil mendaftar pada course ini.
                                                                    Silakan lanjutkan ke modul pembelajaran untuk
                                                                    memulai pelatihan.
                                                                </p>

                                                                <p class="mt-1">
                                                                    Total durasi pelatihan:
                                                                    <strong>{{ $course->training_hours }} jam</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div
                                        class="sticky bottom-0 bg-slate-50 border-t border-slate-100 px-8 py-5 flex flex-col sm:flex-row gap-3 sm:justify-end">
                                        <button @click="openDetail = false"
                                            class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 transition-colors">
                                            Kembali
                                        </button>

                                        @if ($isEnrolled)
                                            <a href="{{ route('employee.courses.show', $course) }}"
                                                class="inline-flex justify-center items-center px-8 py-2.5 text-sm font-bold text-white bg-slate-800 rounded-xl hover:bg-slate-900 transition-all shadow-md">
                                                Lanjutkan Belajar
                                            </a>
                                        @else
                                            <a href="{{ route('employee.courses.show', $course) }}"
                                                class="inline-flex justify-center items-center px-8 py-2.5 text-sm font-bold text-white bg-[#121293] rounded-xl hover:opacity-90 transition-all shadow-lg shadow-blue-900/20">
                                                Mulai Enrollment
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                @empty
                    <div class="col-span-full">
                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-3xl p-16 text-center">
                            <div
                                class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 text-slate-400 mb-6">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Tidak Menemukan Pelatihan</h3>
                            <p class="text-slate-500 max-w-md mx-auto">Kami belum mempublikasikan pelatihan untuk
                                kategori ini. Silakan hubungi tim HR atau Administrator untuk info lebih lanjut.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- ================= PAGINATION ================= --}}
            <div class="pt-8">
                {{ $courses->links() }}
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.4s ease-out;
        }
    </style>


</x-app-layout>
