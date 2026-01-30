<footer class="mt-auto w-full">
    {{-- Top --}}
    <div class="bg-gradient-to-b from-[#0b0f2a] via-[#070a1c] to-[#050617] text-white">
        <div class="mx-auto max-w-7xl px-4 lg:px-0 py-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                {{-- Brand --}}
                <div class="md:col-span-5">
                    <div class="inline-flex items-center gap-4 bg-white rounded-2xl px-5 py-3 shadow-lg">
                        <img src="{{ asset('images/logo-royal.png') }}" class="h-10 w-auto" alt="RSU Royal Prima">
                        <div class="w-px h-8 bg-slate-300"></div>
                        <img src="{{ asset('images/logo-fast.png') }}" class="h-10 w-auto" alt="FAST">
                    </div>

                    <p class="mt-4 text-sm text-white/70 max-w-md leading-relaxed">
                        Learning Management System untuk mendukung pelatihan, pencatatan jam, dan pengembangan
                        kompetensi tenaga kesehatan RSU Royal Prima.
                    </p>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-white/10 border border-white/10 px-3 py-1 text-xs text-white/80">
                            Terpusat
                        </span>
                        <span class="rounded-full bg-white/10 border border-white/10 px-3 py-1 text-xs text-white/80">
                            Terukur
                        </span>
                        <span class="rounded-full bg-white/10 border border-white/10 px-3 py-1 text-xs text-white/80">
                            Mudah dipakai
                        </span>
                    </div>
                </div>

                {{-- Links --}}
                <div class="md:col-span-3">
                    <p class="text-sm font-semibold tracking-wide">Tautan</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li>
                            <a href="{{ url('/') }}"
                               class="text-white/70 hover:text-white transition-colors">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="#calendar-wrap"
                               class="text-white/70 hover:text-white transition-colors">
                                Kalender
                            </a>
                        </li>
                        @if (Route::has('login'))
                            <li>
                                <a href="{{ route('login') }}"
                                   class="text-white/70 hover:text-white transition-colors">
                                    Masuk
                                </a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li>
                                <a href="{{ route('register') }}"
                                   class="text-white/70 hover:text-white transition-colors">
                                    Daftar
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                {{-- Contact + Social --}}
                <div class="md:col-span-4">
                    <p class="text-sm font-semibold tracking-wide">Kontak</p>

                    <ul class="mt-4 space-y-2 text-sm text-white/70">
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 text-white/60"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>RSU Royal Prima, Medan, Indonesia</span>
                        </li>

                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white/60"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M4 4h16v16H4z" opacity=".2"></path>
                                <path d="m22 6-10 7L2 6"></path>
                                <path d="M2 6v12h20V6"></path>
                            </svg>
                            <span>Email:
                                <a href="mailto:contact@royalprima.com"
                                   class="text-white/80 hover:text-white transition-colors">
                                    contact@royalprima.com
                                </a>
                            </span>
                        </li>

                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white/60"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3 5.18 2 2 0 0 1 5 3h3a2 2 0 0 1 2 1.72c.12.86.3 1.7.54 2.5a2 2 0 0 1-.45 2.11L9.91 10.91a16 16 0 0 0 3.18 3.18l1.58-1.58a2 2 0 0 1 2.11-.45c.8.24 1.64.42 2.5.54A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span>Call:
                                <span class="text-white/80">
                                    (061) 88813182 - 88813183
                                </span>
                            </span>
                        </li>

                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white/60"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M10.3 3.2 1.9 18a2 2 0 0 0 1.7 3h16.8a2 2 0 0 0 1.7-3L13.7 3.2a2 2 0 0 0-3.4 0Z"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                            <span>Emergency:
                                <span class="text-white/80">
                                    (061) 88813773
                                </span>
                            </span>
                        </li>
                    </ul>

                    <div class="mt-5">
                        <p class="text-xs text-white/60">Social</p>
                        <div class="mt-2 flex items-center gap-3">
                            <!-- Social icons tetap sama seperti sebelumnya -->
                        </div>
                    </div>
                </div>

            </div>

            {{-- Divider --}}
            <div class="mt-10 h-px w-full bg-white/10"></div>

            {{-- Bottom bar --}}
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/60">
                <p>&copy; {{ now()->year }} RSU Royal Prima — Learning Management System.</p>

                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms &amp; Conditions</a>
                </div>
            </div>

        </div>
    </div>
</footer>
