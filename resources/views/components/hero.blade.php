<section class="relative">
    {{-- soft blur only (clean) --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-28 right-0 h-80 w-80 rounded-full bg-[#121293]/12 blur-3xl"></div>
        <div class="absolute top-32 -left-20 h-80 w-80 rounded-full bg-sky-500/10 blur-3xl"></div>
    </div>

    <div class="mx-auto px-4 lg:px-16 pt-10 pb-10 lg:pt-14 lg:pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            {{-- LEFT --}}
            <div class="lg:col-span-7">
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-[#121293]"></span>
                    Sistem Pelatihan RSU Royal Prima
                </div>

                <h1 class="mt-4 text-4xl lg:text-5xl font-semibold tracking-tight text-slate-900 leading-tight">
                    <span class="block">Jadwal Pelatihan Yang Rapi.</span>

                    <span class="block text-[#121293]">
                        <span id="typed-hero"></span>
                        <span class="typed-cursor" aria-hidden="true"></span>
                    </span>
                </h1>

                <p class="mt-4 text-base lg:text-lg text-slate-600 max-w-2xl">
                    Pantau kalender kegiatan, ikut pelatihan, dan catat jam pelatihan dalam satu sistem yang cepat,
                    jelas, dan mudah digunakan.
                </p>

                {{-- CTAs --}}
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}"
                        class="group relative inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl
                              bg-[#121293] text-white text-sm font-semibold shadow-sm overflow-hidden
                              transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#0e0e70]">
                        <span
                            class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <span class="absolute -left-10 top-0 h-full w-24 rotate-12 bg-white/20 blur-sm"></span>
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 17l5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M21 3v18"></path>
                        </svg>
                        Masuk
                    </a>

                    <a href="#calendar-wrap"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl
                              border border-slate-300 bg-white/70 text-slate-800 text-sm font-semibold
                              shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <path d="M16 2v4"></path>
                            <path d="M8 2v4"></path>
                            <path d="M3 10h18"></path>
                        </svg>
                        Lihat Kalender
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-2 text-sm">
                    <span
                        class="rounded-full bg-white/70 border border-slate-200 px-3 py-1 text-slate-700">Terpusat</span>
                    <span
                        class="rounded-full bg-white/70 border border-slate-200 px-3 py-1 text-slate-700">Terukur</span>
                    <span class="rounded-full bg-white/70 border border-slate-200 px-3 py-1 text-slate-700">Mudah
                        dipakai</span>
                </div>
            </div>

            {{-- RIGHT (bigger, professional) --}}
            <div class="lg:col-span-5">
                <div class="group relative w-full hover:cursor-pointer">
                    <div
                        class="rounded-[2rem] border  backdrop-blur shadow-sm overflow-hidden
                                transition-all duration-300 ease-out
                                group-hover:-translate-y-1 group-hover:shadow-md">
                        {{-- fixed ratio so it always looks tidy --}}
                        <div class="relative aspect-[4/3]">
                            <img src="{{ asset('images/hero_image.webp') }}" alt="LMS Royal Prima"
                                class="absolute inset-0 h-full w-full object-contain transition-transform duration-500 ease-out group-hover:scale-[1.03]">
                        </div>

                        {{-- tiny bottom info bar (more “product UI”) --}}
                        <div class="flex items-center justify-between px-5 py-4 backdrop-blur border-t ">
                            <div>
                                <p class="text-xs text-slate-500">Akses cepat</p>
                                <p class="text-sm font-semibold text-slate-900">Cek jadwal & kegiatan terbaru</p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full bg-[#121293]/10 text-[#121293] px-3 py-1 text-xs font-semibold">
                                Live Calendar
                            </span>
                        </div>
                    </div>

                    {{-- glass shine on hover --}}
                    <div class="pointer-events-none absolute inset-0 rounded-[2rem] overflow-hidden">
                        <span
                            class="hero-shine absolute -left-1/2 top-0 h-full w-1/3 rotate-12 bg-white/0 blur-sm opacity-0"></span>
                    </div>

                    {{-- run shine only on hover --}}
                    <style>
                        .group:hover .hero-shine {
                            opacity: 1;
                            background: rgba(255, 255, 255, .22);
                            animation: heroShine 900ms ease-out;
                        }

                        @keyframes heroShine {
                            0% {
                                transform: translateX(-60%) rotate(12deg);
                            }

                            100% {
                                transform: translateX(240%) rotate(12deg);
                            }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</section>
