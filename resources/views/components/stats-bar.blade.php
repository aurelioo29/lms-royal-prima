<section class="w-full">
    {{-- full width, transparent feel --}}
    <div class="w-full py-10">
        <div class="mx-auto max-w-7xl px-4 lg:px-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- 1: Mode --}}
                <a href="#calendar-wrap" data-aos="fade-up" data-aos-delay="0"
                    class="group relative rounded-3xl p-6
                          border border-slate-200/70
                          bg-transparent
                          transition-all duration-300 ease-out
                          hover:-translate-y-1 hover:border-slate-200 hover:shadow-[0_12px_40px_-18px_rgba(18,18,147,0.45)]">

                    {{-- hover glow (subtle) --}}
                    <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        style="background: radial-gradient(600px circle at 30% 20%, rgba(18,18,147,.12), transparent 55%);">
                    </div>

                    {{-- shine sweep --}}
                    <div class="pointer-events-none absolute inset-0 rounded-3xl overflow-hidden">
                        <span
                            class="absolute -left-1/2 top-0 h-full w-1/3 rotate-12 bg-white/0 blur-sm opacity-0 group-hover:opacity-100 group-hover:stats-shine"></span>
                    </div>

                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]
                                    transition-all duration-300 group-hover:bg-[#121293]/15 group-hover:scale-[1.02]">
                            {{-- icon: layers --}}
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2 2 7l10 5 10-5-10-5Z"></path>
                                <path d="m2 17 10 5 10-5"></path>
                                <path d="m2 12 10 5 10-5"></path>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 leading-tight">3 Mode Pelatihan</p>
                            <p class="mt-1 text-sm text-slate-500">Online • Offline • Blended</p>

                            <p class="mt-4 text-2xl font-semibold text-[#121293] tabular-nums">
                                <span class="countup" data-to="3" data-suffix=" Mode">0</span>
                            </p>
                        </div>
                    </div>
                </a>

                {{-- 2: Jam tercatat otomatis --}}
                <a href="#calendar-wrap" data-aos="fade-up" data-aos-delay="80"
                    class="group relative rounded-3xl p-6
                          border border-slate-200/70 bg-transparent
                          transition-all duration-300 ease-out
                          hover:-translate-y-1 hover:border-slate-200 hover:shadow-[0_12px_40px_-18px_rgba(18,18,147,0.45)]">
                    <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        style="background: radial-gradient(600px circle at 30% 20%, rgba(18,18,147,.12), transparent 55%);">
                    </div>
                    <div class="pointer-events-none absolute inset-0 rounded-3xl overflow-hidden">
                        <span
                            class="absolute -left-1/2 top-0 h-full w-1/3 rotate-12 bg-white/0 blur-sm opacity-0 group-hover:opacity-100 group-hover:stats-shine"></span>
                    </div>

                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]
                                    transition-all duration-300 group-hover:bg-[#121293]/15 group-hover:scale-[1.02]">
                            {{-- icon: clock --}}
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 leading-tight">Jam tercatat otomatis</p>
                            <p class="mt-1 text-sm text-slate-500">Tracking cepat & rapi</p>

                            <p class="mt-4 text-2xl font-semibold text-[#121293] tabular-nums">
                                <span class="countup" data-to="100" data-suffix="%">0</span>
                            </p>
                        </div>
                    </div>
                </a>

                {{-- 3: Enrollment key --}}
                <a href="#calendar-wrap" data-aos="fade-up" data-aos-delay="160"
                    class="group relative rounded-3xl p-6
                          border border-slate-200/70 bg-transparent
                          transition-all duration-300 ease-out
                          hover:-translate-y-1 hover:border-slate-200 hover:shadow-[0_12px_40px_-18px_rgba(18,18,147,0.45)]">
                    <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        style="background: radial-gradient(600px circle at 30% 20%, rgba(18,18,147,.12), transparent 55%);">
                    </div>
                    <div class="pointer-events-none absolute inset-0 rounded-3xl overflow-hidden">
                        <span
                            class="absolute -left-1/2 top-0 h-full w-1/3 rotate-12 bg-white/0 blur-sm opacity-0 group-hover:opacity-100 group-hover:stats-shine"></span>
                    </div>

                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]
                                    transition-all duration-300 group-hover:bg-[#121293]/15 group-hover:scale-[1.02]">
                            {{-- icon: key --}}
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 2l-2 2"></path>
                                <path d="M7 13a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z"></path>
                                <path d="M15 13h3l2-2"></path>
                                <path d="M17 11v2"></path>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 leading-tight">Enrollment key</p>
                            <p class="mt-1 text-sm text-slate-500">Akses lebih aman</p>

                            <p class="mt-4 text-2xl font-semibold text-[#121293] tabular-nums">
                                <span class="countup" data-to="1" data-suffix=" Kunci">0</span>
                            </p>
                        </div>
                    </div>
                </a>

                {{-- 4: Jadwal terpusat --}}
                <a href="#calendar-wrap" data-aos="fade-up" data-aos-delay="240"
                    class="group relative rounded-3xl p-6
                          border border-slate-200/70 bg-transparent
                          transition-all duration-300 ease-out
                          hover:-translate-y-1 hover:border-slate-200 hover:shadow-[0_12px_40px_-18px_rgba(18,18,147,0.45)]">
                    <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        style="background: radial-gradient(600px circle at 30% 20%, rgba(18,18,147,.12), transparent 55%);">
                    </div>
                    <div class="pointer-events-none absolute inset-0 rounded-3xl overflow-hidden">
                        <span
                            class="absolute -left-1/2 top-0 h-full w-1/3 rotate-12 bg-white/0 blur-sm opacity-0 group-hover:opacity-100 group-hover:stats-shine"></span>
                    </div>

                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]
                                    transition-all duration-300 group-hover:bg-[#121293]/15 group-hover:scale-[1.02]">
                            {{-- icon: calendar --}}
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                <path d="M16 2v4"></path>
                                <path d="M8 2v4"></path>
                                <path d="M3 10h18"></path>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 leading-tight">Jadwal terpusat</p>
                            <p class="mt-1 text-sm text-slate-500">Selalu update & mudah dicari</p>

                            <p class="mt-4 text-2xl font-semibold text-[#121293] tabular-nums">
                                <span class="countup" data-to="24" data-suffix="/7">0</span>
                            </p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>

    {{-- local styles for shine sweep --}}
    <style>
        .group:hover .stats-shine {
            background: rgba(255, 255, 255, .22);
            animation: statsShine 900ms ease-out;
        }

        @keyframes statsShine {
            0% {
                transform: translateX(-60%) rotate(12deg);
                opacity: 0;
            }

            15% {
                opacity: 1;
            }

            100% {
                transform: translateX(240%) rotate(12deg);
                opacity: 0;
            }
        }
    </style>
</section>
