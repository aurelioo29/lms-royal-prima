<header class="sticky top-0 z-50">
    <div class="border-b border-slate-200/70 bg-white/70 backdrop-blur-md supports-[backdrop-filter]:bg-white/55">
        <div class="px-4 lg:px-16 py-4 text-sm">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between gap-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-royal.png') }}" class="h-10 w-auto" alt="LMS Royal Prima">
                    </a>

                    <div class="flex items-center gap-2 sm:gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="group relative inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                                      border border-[#121293]/30 text-[#121293] bg-white/70
                                      shadow-sm overflow-hidden
                                      transition-all duration-200 ease-out
                                      hover:-translate-y-0.5 hover:bg-[#121293] hover:text-white hover:shadow-md
                                      focus:outline-none focus-visible:ring-2 focus-visible:ring-[#121293]/40">
                                {{-- subtle shine --}}
                                <span
                                    class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span
                                        class="absolute -left-10 top-0 h-full w-24 rotate-12 bg-white/25 blur-sm group-hover:nav-shine"></span>
                                </span>

                                {{-- icon: dashboard --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                                    <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                                    <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                                    <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                                </svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="group relative inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                                      border border-slate-300/80 text-slate-700 bg-white/60
                                      shadow-sm overflow-hidden
                                      transition-all duration-200 ease-out
                                      hover:-translate-y-0.5 hover:bg-white hover:shadow-md
                                      focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                                {{-- subtle shine --}}
                                <span
                                    class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span
                                        class="absolute -left-10 top-0 h-full w-24 rotate-12 bg-white/35 blur-sm group-hover:nav-shine"></span>
                                </span>

                                {{-- icon: login --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 17l5-5-5-5"></path>
                                    <path d="M15 12H3"></path>
                                    <path d="M21 3v18"></path>
                                </svg>
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="group relative inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                                          bg-[#121293] text-white border border-[#121293]
                                          shadow-sm overflow-hidden
                                          transition-all duration-200 ease-out
                                          hover:-translate-y-0.5 hover:bg-[#0e0e70] hover:shadow-md
                                          focus:outline-none focus-visible:ring-2 focus-visible:ring-[#121293]/40">
                                    {{-- subtle shine --}}
                                    <span
                                        class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <span
                                            class="absolute -left-10 top-0 h-full w-24 rotate-12 bg-white/20 blur-sm group-hover:nav-shine"></span>
                                    </span>

                                    {{-- icon: user-plus --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M19 8v6"></path>
                                        <path d="M22 11h-6"></path>
                                    </svg>
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif
        </div>
    </div>
</header>
