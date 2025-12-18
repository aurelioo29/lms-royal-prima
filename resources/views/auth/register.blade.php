<x-guest-layout>
    @php
        $loginPics = ['images/pic-login-1.jpg', 'images/pic-login-2.jpg', 'images/pic-login-3.jpg'];
        $pickedPic = $loginPics[array_rand($loginPics)];
    @endphp

    <div class="h-screen overflow-hidden flex bg-white">
        {{-- Left: Form (scroll inside) --}}
        <div class="w-full lg:w-1/2 h-full flex flex-col">
            {{-- Header --}}
            <div class="px-6 pt-8 pb-4 flex items-center justify-center bg-white">
                <img src="{{ asset('images/logo-royal.png') }}" alt="RSU Royal Prima" class="h-24 w-auto"
                    draggable="false" />
            </div>

            {{-- Scrollable content --}}
            <div class="flex-1 overflow-y-auto px-6 pb-8">
                <div class="w-full max-w-md mx-auto">

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        {{-- Daftar Sebagai (Narasumber only) --}}
                        <div>
                            <x-required-label value="Daftar Sebagai" />
                            <div class="mt-2">
                                <div
                                    class="w-full rounded-xl border border-[#121293] bg-white px-4 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-[#121293]/10 flex items-center justify-center overflow-hidden">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#121293]"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>

                                        <div class="leading-tight">
                                            <div class="text-sm font-semibold text-[#121293]">Narasumber</div>
                                            <div class="text-xs text-gray-400">Pendaftaran khusus narasumber</div>
                                        </div>
                                    </div>

                                    <div
                                        class="h-5 w-5 rounded-full border-2 border-[#121293] flex items-center justify-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-[#121293]"></div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="role_slug" value="instructor">
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <x-required-label for="name" value="Nama Lengkap" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name"
                                placeholder="Masukan Nama Lengkap" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- NIK --}}
                        <div>
                            <x-required-label for="nik" value="NIK" />
                            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik"
                                :value="old('nik')" required autocomplete="off" placeholder="Masukan NIK Anda" />
                            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-required-label for="email" value="Email" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" placeholder="Masukan Email Anda" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- No Handphone --}}
                        <div>
                            <x-required-label for="phone" value="No Handphone" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                :value="old('phone')" required autocomplete="off" placeholder="Masukan Nomor Hp Anda" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <x-required-label for="birth_date" value="Tanggal Lahir" />
                            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date"
                                :value="old('birth_date')" required />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <x-required-label value="Jenis Kelamin" />
                            <div class="mt-2 flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="gender" value="M"
                                        class="text-indigo-600 focus:ring-indigo-500"
                                        {{ old('gender', 'M') === 'M' ? 'checked' : '' }} required />
                                    Laki - Laki
                                </label>

                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="gender" value="F"
                                        class="text-indigo-600 focus:ring-indigo-500"
                                        {{ old('gender') === 'F' ? 'checked' : '' }} required />
                                    Perempuan
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-required-label for="password" value="Password" />
                            <div class="relative mt-1">
                                <x-text-input id="password" class="block w-full pr-12" type="password" name="password"
                                    required autocomplete="new-password" placeholder="Masukan Kata Sandi" />
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none"
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
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <x-required-label for="password_confirmation" value="Konfirmasi Password" />
                            <div class="relative mt-1">
                                <x-text-input id="password_confirmation" class="block w-full pr-12" type="password"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Ulangi Kata Sandi" />
                                <button type="button" id="togglePassword2"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    aria-label="Tampilkan/Sembunyikan konfirmasi password">
                                    <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>

                                    <svg id="eyeOffIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
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
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        {{-- Button --}}
                        <div class="pt-1">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Buat Akun') }}
                            </x-primary-button>
                        </div>

                        <div class="text-center text-sm text-gray-500 pt-2">
                            <div class="flex items-center gap-3 my-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <div class="text-xs text-gray-400">Or</div>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            Sudah Punya Akun?
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                                Login
                            </a>
                        </div>

                        <div class="text-center text-xs text-gray-400 pt-4">
                            Version {{ config('app.version') }}
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- Right: Illustration --}}
        <div class="hidden lg:block lg:w-1/2 h-full p-6">
            <div class="h-full w-full rounded-2xl overflow-hidden">
                <img src="{{ asset($pickedPic) }}" alt="Register Illustration" class="h-full w-full object-cover"
                    draggable="false" loading="lazy" />
            </div>
        </div>
    </div>

    <script>
        (function() {
            function bindToggle(btnId, inputId, eyeId, eyeOffId) {
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

            bindToggle('togglePassword', 'password', 'eyeIcon', 'eyeOffIcon');
            bindToggle('togglePassword2', 'password_confirmation', 'eyeIcon2', 'eyeOffIcon2');
        })();
    </script>
</x-guest-layout>
