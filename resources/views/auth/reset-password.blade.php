<x-guest-layout>
    @php
        $emailValue = old('email', $request->email ?? '');
        $tokenValue = $request->route('token');
    @endphp

    <div class="min-h-dvh w-full bg-slate-50 relative overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute -top-40 -left-40 h-[520px] w-[520px] rounded-full blur-3xl opacity-40 bg-gradient-to-br from-indigo-300 via-sky-200 to-white">
            </div>

            <div
                class="absolute -bottom-48 -right-48 h-[620px] w-[620px] rounded-full blur-3xl opacity-40 bg-gradient-to-br from-blue-300 via-indigo-200 to-white">
            </div>

            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 22px 22px;">
            </div>
        </div>

        <div class="relative min-h-dvh flex items-center justify-center px-6 py-10">
            <div class="w-full max-w-md">
                {{-- Header --}}
                <div class="flex flex-col items-center text-center mb-7">
                    <img src="{{ asset('images/logo-royal.png') }}" alt="RSU Royal Prima" class="h-16 w-auto select-none"
                        draggable="false" />

                    <h1 class="mt-5 text-2xl font-semibold tracking-tight text-slate-900">
                        Buat Password Baru ✨
                    </h1>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Masukkan password baru yang aman, lalu konfirmasi. Setelah itu kamu bisa login lagi.
                    </p>
                </div>

                {{-- Card --}}
                <div
                    class="rounded-2xl border border-white/60 bg-white/70 backdrop-blur-xl shadow-xl shadow-slate-200/60">
                    <div class="p-6 sm:p-7">
                        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                            @csrf

                            {{-- Token --}}
                            <input type="hidden" name="token" value="{{ $tokenValue }}">

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
                                        name="email" value="{{ $emailValue }}" required autofocus
                                        autocomplete="username" placeholder="contoh: user@royalprima.id" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            {{-- Password --}}
                            <div>
                                <x-input-label for="password" :value="__('Password Baru')" />

                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M7 10V7a5 5 0 0 1 10 0v3"></path>
                                            <path d="M5 10h14v10H5z"></path>
                                        </svg>
                                    </span>

                                    <x-text-input id="password" class="block w-full pl-10 pr-12" type="password"
                                        name="password" required autocomplete="new-password"
                                        placeholder="Minimal 8 karakter" />

                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                                        aria-label="Tampilkan/Sembunyikan password">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

                                {{-- Strength meter --}}
                                <div class="mt-2">
                                    <div class="h-2 w-full rounded-full bg-slate-200 overflow-hidden">
                                        <div id="pwBar" class="h-full w-0 bg-slate-400 transition-all"></div>
                                    </div>
                                    <div class="mt-1 flex items-center justify-between text-xs">
                                        <span id="pwLabel" class="text-slate-500">Kekuatan password: —</span>
                                        <span id="pwHint" class="text-slate-400">Gunakan huruf besar + angka</span>
                                    </div>
                                </div>

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M7 10V7a5 5 0 0 1 10 0v3"></path>
                                            <path d="M5 10h14v10H5z"></path>
                                        </svg>
                                    </span>

                                    <x-text-input id="password_confirmation" class="block w-full pl-10 pr-12"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" placeholder="Ulangi password" />

                                    <button type="button" id="togglePassword2"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                                        aria-label="Tampilkan/Sembunyikan konfirmasi password">
                                        <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg id="eyeOffIcon2" xmlns="http://www.w3.org/2000/svg"
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

                                {{-- Match indicator --}}
                                <div class="mt-2 text-xs">
                                    <span id="matchLabel" class="text-slate-500">Konfirmasi: —</span>
                                </div>

                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            {{-- Buttons --}}
                            <div class="pt-2 space-y-3">
                                <x-primary-button
                                    class="w-full justify-center !rounded-xl !py-3 !text-[15px]
                                           bg-[#121293] hover:bg-[#0e0e75] focus:ring-[#121293]/40">
                                    {{ __('Reset Password') }}
                                </x-primary-button>

                                <a href="{{ route('login') }}"
                                    class="w-full inline-flex justify-center rounded-xl border border-slate-200 bg-white/70 py-3
                                           text-sm font-semibold text-slate-700 hover:bg-white hover:border-slate-300
                                           focus:outline-none focus:ring-2 focus:ring-[#121293]/30 transition">
                                    Kembali ke Login
                                </a>
                            </div>

                            <div class="text-center text-xs text-slate-400 pt-2">
                                Version {{ config('app.version') }}
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-6 text-center text-xs text-slate-500">
                    Tips: gunakan password yang tidak sama dengan password lama. Iya, kamu yang itu 😄
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Toggle visibility helper
            function setupToggle(btnId, inputId, eyeId, eyeOffId) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                const eye = document.getElementById(eyeId);
                const eyeOff = document.getElementById(eyeOffId);
                if (!btn || !input || !eye || !eyeOff) return;

                btn.addEventListener('click', () => {
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    eye.classList.toggle('hidden', show);
                    eyeOff.classList.toggle('hidden', !show);
                });
            }

            setupToggle('togglePassword', 'password', 'eyeIcon', 'eyeOffIcon');
            setupToggle('togglePassword2', 'password_confirmation', 'eyeIcon2', 'eyeOffIcon2');

            // Strength meter (simple)
            const pw = document.getElementById('password');
            const pw2 = document.getElementById('password_confirmation');
            const bar = document.getElementById('pwBar');
            const label = document.getElementById('pwLabel');
            const hint = document.getElementById('pwHint');
            const matchLabel = document.getElementById('matchLabel');

            if (!pw || !bar || !label || !hint || !matchLabel) return;

            function scorePassword(v) {
                let s = 0;
                if (!v) return 0;

                // length
                if (v.length >= 8) s += 25;
                if (v.length >= 12) s += 15;

                // variety
                if (/[a-z]/.test(v)) s += 15;
                if (/[A-Z]/.test(v)) s += 15;
                if (/[0-9]/.test(v)) s += 15;
                if (/[^A-Za-z0-9]/.test(v)) s += 15;

                return Math.min(s, 100);
            }

            function setStrengthUI(score) {
                bar.style.width = score + '%';

                // no custom colors rule? this is inline style-free but uses classes; still ok.
                // We'll only switch tailwind bg classes, not hardcode CSS.
                bar.classList.remove('bg-red-400', 'bg-amber-400', 'bg-emerald-500', 'bg-slate-400');

                let text = '—';
                let hintText = 'Gunakan huruf besar + angka';

                if (score === 0) {
                    bar.classList.add('bg-slate-400');
                    text = '—';
                    hintText = 'Minimal 8 karakter';
                } else if (score < 45) {
                    bar.classList.add('bg-red-400');
                    text = 'Lemah';
                    hintText = 'Tambah panjang & variasi karakter';
                } else if (score < 75) {
                    bar.classList.add('bg-amber-400');
                    text = 'Sedang';
                    hintText = 'Tambah huruf besar / simbol';
                } else {
                    bar.classList.add('bg-emerald-500');
                    text = 'Kuat';
                    hintText = 'Bagus. Jangan dibagikan 😄';
                }

                label.textContent = 'Kekuatan password: ' + text;
                hint.textContent = hintText;
            }

            function setMatchUI() {
                if (!pw2) return;
                const a = pw.value || '';
                const b = pw2.value || '';
                if (!b) {
                    matchLabel.textContent = 'Konfirmasi: —';
                    matchLabel.className = 'text-slate-500';
                    return;
                }
                if (a === b) {
                    matchLabel.textContent = 'Konfirmasi: Cocok ✅';
                    matchLabel.className = 'text-emerald-600';
                } else {
                    matchLabel.textContent = 'Konfirmasi: Belum cocok';
                    matchLabel.className = 'text-red-500';
                }
            }

            pw.addEventListener('input', () => {
                setStrengthUI(scorePassword(pw.value));
                setMatchUI();
            });

            if (pw2) {
                pw2.addEventListener('input', setMatchUI);
            }

            // init
            setStrengthUI(scorePassword(pw.value));
            setMatchUI();
        })();
    </script>
</x-guest-layout>
