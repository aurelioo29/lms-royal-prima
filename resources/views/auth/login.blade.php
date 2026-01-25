<x-guest-layout>
    @php
        $loginPics = ['images/pic-login-1.jpg', 'images/pic-login-2.jpg', 'images/pic-login-3.jpg'];
        $pickedPic = $loginPics[array_rand($loginPics)];

        // Fallback URL kalau route password.request belum ada
        $resetUrl = Route::has('password.request') ? route('password.request') : url('/forgot-password');
    @endphp

    <div class="min-h-dvh w-full bg-slate-50 relative overflow-hidden">
        {{-- Soft background gradient + blobs --}}
        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute -top-40 -left-40 h-[520px] w-[520px] rounded-full blur-3xl opacity-40
                       bg-gradient-to-br from-indigo-300 via-sky-200 to-white">
            </div>

            <div
                class="absolute -bottom-48 -right-48 h-[620px] w-[620px] rounded-full blur-3xl opacity-40
                       bg-gradient-to-br from-blue-300 via-indigo-200 to-white">
            </div>

            {{-- subtle grid pattern --}}
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 22px 22px;">
            </div>
        </div>

        <div class="relative min-h-dvh flex">
            {{-- Left: Form (scrollable, center stable) --}}
            <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-10 lg:pr-12 overflow-y-auto">
                <div class="w-full max-w-md">
                    {{-- Logo + Title --}}
                    <div class="flex flex-col items-center text-center mb-7">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/logo-royal.png') }}" alt="RSU Royal Prima"
                                class="h-20 w-auto select-none" draggable="false" />
                        </div>

                        <h1 class="mt-5 text-2xl font-semibold tracking-tight text-slate-900">
                            Selamat datang kembali 👋
                        </h1>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Masuk untuk mengakses LMS Royal Prima dan lanjutkan progress pelatihanmu.
                        </p>
                    </div>

                    {{-- Glass Card --}}
                    <div
                        class="rounded-2xl border border-white/60 bg-white/70 backdrop-blur-xl shadow-xl shadow-slate-200/60">
                        <div class="p-6 sm:p-7">
                            {{-- Session Status --}}
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf

                                {{-- Email --}}
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <div class="relative mt-1">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 6l8 6 8-6"></path>
                                                <path d="M4 18h16"></path>
                                            </svg>
                                        </span>

                                        <x-text-input id="email" class="block w-full pl-10" type="email"
                                            name="email" :value="old('email')" required autofocus autocomplete="username"
                                            placeholder="Masukan Email Anda" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                {{-- Password --}}
                                <div>
                                    <x-input-label for="password" :value="__('Password')" />

                                    <div class="relative mt-1">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M7 10V7a5 5 0 0 1 10 0v3"></path>
                                                <path d="M5 10h14v10H5z"></path>
                                            </svg>
                                        </span>

                                        <x-text-input id="password" class="block w-full pl-10 pr-12" type="password"
                                            name="password" required autocomplete="current-password"
                                            placeholder="Masukan Kata Sandi" />

                                        <button type="button" id="togglePassword"
                                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                                            aria-label="Tampilkan/Sembunyikan password">
                                            {{-- Eye --}}
                                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>

                                            {{-- Eye off --}}
                                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-6.5 0-10-7-10-7a21.8 21.8 0 0 1 5.06-6.94">
                                                </path>
                                                <path d="M1 1l22 22"></path>
                                                <path
                                                    d="M9.9 4.24A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a21.7 21.7 0 0 1-3.43 5.1">
                                                </path>
                                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                {{-- Remember + Reset Password --}}
                                <div class="flex items-center justify-between pt-1 gap-3">
                                    <label for="remember_me"
                                        class="inline-flex items-center cursor-pointer select-none">
                                        <input id="remember_me" type="checkbox"
                                            class="rounded border-slate-300 text-[#121293] shadow-sm focus:ring-[#121293]"
                                            name="remember">
                                        <span class="ms-2 text-sm text-slate-600">Remember me</span>
                                    </label>

                                    {{-- ALWAYS show reset link (route fallback) --}}
                                    <a href="{{ $resetUrl }}"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/60 px-3 py-2
                                              text-xs font-semibold text-[#121293] hover:bg-white hover:border-slate-300
                                              focus:outline-none focus:ring-2 focus:ring-[#121293]/30 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 6V4"></path>
                                            <path d="M12 20v-2"></path>
                                            <path d="M6 12H4"></path>
                                            <path d="M20 12h-2"></path>
                                            <path d="M7.8 7.8 6.4 6.4"></path>
                                            <path d="M17.6 17.6 16.2 16.2"></path>
                                            <path d="M16.2 7.8 17.6 6.4"></path>
                                            <path d="M6.4 17.6 7.8 16.2"></path>
                                        </svg>
                                        Reset Password
                                    </a>
                                </div>

                                {{-- Button --}}
                                <div class="pt-2">
                                    <x-primary-button
                                        class="w-full justify-center !rounded-xl !py-3 !text-[15px]
                                                            bg-[#121293] hover:bg-[#0e0e75] focus:ring-[#121293]/40">
                                        {{ __('Masuk') }}
                                    </x-primary-button>
                                </div>

                                {{-- Divider --}}
                                <div class="pt-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-px flex-1 bg-slate-200"></div>
                                        <div class="text-xs text-slate-400">atau</div>
                                        <div class="h-px flex-1 bg-slate-200"></div>
                                    </div>
                                </div>

                                {{-- Register link --}}
                                @if (Route::has('register'))
                                    <div class="text-center text-sm text-slate-600">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}"
                                            class="text-[#121293] hover:text-indigo-700 font-semibold">
                                            Daftar Sekarang
                                        </a>
                                    </div>
                                @endif

                                {{-- Version --}}
                                <div class="text-center text-xs text-slate-400 pt-3">
                                    Version {{ config('app.version') }}
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tiny trust footer --}}
                    <div class="mt-6 text-center text-xs text-slate-500">
                        Dengan masuk, kamu menyetujui kebijakan penggunaan sistem internal RSU Royal Prima.
                    </div>
                </div>
            </div>

            {{-- Right: Illustration --}}
            <div class="hidden lg:block lg:w-1/2 p-8">
                <div
                    class="h-full w-full rounded-3xl overflow-hidden relative shadow-2xl shadow-slate-200/70 border border-white/60">
                    <img src="{{ asset($pickedPic) }}" alt="Login Illustration" class="h-full w-full object-cover"
                        draggable="false" loading="lazy" />

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-slate-950/15 to-transparent">
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="max-w-md">
                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-white/90 text-xs backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                                Sistem Pelatihan Aktif
                            </div>

                            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white">
                                Belajar. Naik level. Jadi lebih siap.
                            </h2>
                            <p class="mt-3 text-sm text-white/80 leading-relaxed">
                                Akses jadwal, modul, dan progress pelatihan dalam satu tempat—cepat, jelas, dan rapi.
                            </p>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/85 backdrop-blur">📚
                                    Modul Terstruktur</span>
                                <span
                                    class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/85 backdrop-blur">🗓️
                                    Kalender Tahunan</span>
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/85 backdrop-blur">📈
                                    Tracking JPL</span>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const btn = document.getElementById('togglePassword');
            const input = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            const eyeOff = document.getElementById('eyeOffIcon');

            if (!btn || !input || !eye || !eyeOff) return;

            btn.addEventListener('click', () => {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                eye.classList.toggle('hidden', show);
                eyeOff.classList.toggle('hidden', !show);
            });
        })();
    </script>
</x-guest-layout>
