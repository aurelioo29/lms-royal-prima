<x-guest-layout>
    @php
        $loginPics = ['images/pic-login-1.jpg', 'images/pic-login-2.jpg', 'images/pic-login-3.jpg'];
        $pickedPic = $loginPics[array_rand($loginPics)];
    @endphp

    <div class="h-screen overflow-hidden flex bg-white">
        {{-- Left: Form --}}
        <div class="w-full lg:w-1/2 h-full flex items-center justify-center px-6 py-8">
            <div class="w-full max-w-md">

                {{-- Logo --}}
                <div class="flex items-center justify-center mb-6">
                    <img src="{{ asset('images/logo-royal.png') }}" alt="RSU Royal Prima" class="h-24 w-auto"
                        draggable="false" />
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username"
                            placeholder="Masukan Email Anda" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" />

                        <div class="relative mt-1">
                            <x-text-input id="password" class="block w-full pr-12" type="password" name="password"
                                required autocomplete="current-password" placeholder="Masukan Kata Sandi" />

                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none"
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
                                    <path d="M9.9 4.24A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a21.7 21.7 0 0 1-3.43 5.1">
                                    </path>
                                    <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88"></path>
                                </svg>
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-[#121293] shadow-sm focus:ring-[#121293]"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600 cursor-pointer">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-[#121293] hover:text-indigo-700"
                                href="{{ route('password.request') }}">
                                {{ __('Lupa Kata Sandi?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Button --}}
                    <div class="pt-1">
                        <x-primary-button class="w-full justify-center">
                            {{ __('Masuk') }}
                        </x-primary-button>
                    </div>

                    {{-- Register link (optional) --}}
                    @if (Route::has('register'))
                        <div class="text-center text-sm text-gray-500 pt-3">
                            Belum Punya Akun Narasumber?
                            <a href="{{ route('register') }}" class="text-[#121293] hover:text-indigo-700 font-medium">
                                Daftar Sekarang
                            </a>
                        </div>
                    @endif

                    <div class="text-center text-xs text-gray-400 pt-4">
                        Version 1.0.0 - Beta
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Illustration --}}
        <div class="hidden lg:block lg:w-1/2 h-full p-6">
            <div class="h-full w-full rounded-2xl overflow-hidden">
                <img src="{{ asset($pickedPic) }}" alt="Login Illustration" class="h-full w-full object-cover"
                    draggable="false" loading="lazy" />
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
