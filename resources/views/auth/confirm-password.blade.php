<x-guest-layout>
    <div class="min-h-dvh w-full bg-slate-50 relative overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute -top-40 -left-40 h-[520px] w-[520px] rounded-full blur-3xl opacity-40
                       bg-gradient-to-br from-indigo-300 via-sky-200 to-white">
            </div>

            <div
                class="absolute -bottom-48 -right-48 h-[620px] w-[620px] rounded-full blur-3xl opacity-40
                       bg-gradient-to-br from-blue-300 via-indigo-200 to-white">
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
                        Konfirmasi Password 🛡️
                    </h1>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Demi keamanan, masukkan password kamu sekali lagi sebelum lanjut.
                    </p>
                </div>

                {{-- Card --}}
                <div
                    class="rounded-2xl border border-white/60 bg-white/70 backdrop-blur-xl shadow-xl shadow-slate-200/60">
                    <div class="p-6 sm:p-7">
                        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                            @csrf

                            {{-- Password --}}
                            <div>
                                <x-input-label for="password" :value="__('Password')" />

                                <div class="relative mt-1">
                                    {{-- Lock icon --}}
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

                                    {{-- Toggle eye --}}
                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                                        aria-label="Tampilkan/Sembunyikan password">
                                        {{-- Eye --}}
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>

                                        {{-- Eye off --}}
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

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Info --}}
                            <div
                                class="rounded-xl border border-slate-200 bg-white/60 px-4 py-3 text-xs text-slate-600">
                                <div class="flex gap-2">
                                    <span class="mt-[2px] text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 9v4"></path>
                                            <path d="M12 17h.01"></path>
                                            <path d="M10 2h4"></path>
                                            <path d="M12 2v2"></path>
                                            <path d="M7 7a7 7 0 1 1 10 0l-1 1a3 3 0 0 0-1 2v1H9v-1a3 3 0 0 0-1-2l-1-1z">
                                            </path>
                                        </svg>
                                    </span>
                                    <p>
                                        Ini halaman keamanan. Jangan bagikan password ke siapa pun (termasuk “admin”
                                        yang minta di WA 😄).
                                    </p>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="pt-2 space-y-3">
                                <x-primary-button
                                    class="w-full justify-center !rounded-xl !py-3 !text-[15px]
                                           bg-[#121293] hover:bg-[#0e0e75] focus:ring-[#121293]/40">
                                    {{ __('Konfirmasi') }}
                                </x-primary-button>

                                {{-- Back --}}
                                <a href="{{ url()->previous() }}"
                                    class="w-full inline-flex justify-center rounded-xl border border-slate-200 bg-white/70 py-3
                                           text-sm font-semibold text-slate-700 hover:bg-white hover:border-slate-300
                                           focus:outline-none focus:ring-2 focus:ring-[#121293]/30 transition">
                                    Kembali
                                </a>
                            </div>

                            {{-- Version --}}
                            <div class="text-center text-xs text-slate-400 pt-2">
                                Version {{ config('app.version') }}
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-6 text-center text-xs text-slate-500">
                    Jika kamu tidak merasa melakukan aksi ini, batalkan dan hubungi admin sistem.
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
