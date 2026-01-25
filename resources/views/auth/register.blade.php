<x-guest-layout>
    @php
        $loginPics = ['images/pic-login-1.jpg', 'images/pic-login-2.jpg', 'images/pic-login-3.jpg'];
        $pickedPic = $loginPics[array_rand($loginPics)];
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
            {{-- Left: Form --}}
            <div class="w-full lg:w-1/2 flex flex-col">
                {{-- Header --}}
                <div class="px-6 pt-10 pb-6 flex items-center justify-center">
                    <img src="{{ asset('images/logo-royal.png') }}" alt="RSU Royal Prima" class="h-20 w-auto select-none"
                        draggable="false" />
                </div>

                {{-- Scrollable Content --}}
                <div class="flex-1 overflow-y-auto px-6 pb-10">
                    <div class="w-full max-w-md mx-auto">
                        {{-- Title --}}
                        <div class="text-center mb-7">
                            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                                Buat Akun Baru ✨
                            </h1>
                            <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                                Lengkapi data di bawah untuk mengakses LMS Royal Prima.
                            </p>
                        </div>

                        {{-- Glass Card --}}
                        <div
                            class="rounded-2xl border border-white/60 bg-white/70 backdrop-blur-xl shadow-xl shadow-slate-200/60">
                            <div class="p-6 sm:p-7">
                                <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
                                    @csrf

                                    {{-- Daftar Sebagai --}}
                                    <div>
                                        <x-required-label value="Daftar Sebagai" />
                                        <div class="mt-2 space-y-3">

                                            {{-- Karyawan --}}
                                            <label class="block cursor-pointer">
                                                <input type="radio" name="role_slug" value="karyawan"
                                                    class="peer sr-only"
                                                    {{ old('role_slug', 'karyawan') === 'karyawan' ? 'checked' : '' }} />

                                                <div
                                                    class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 flex items-center justify-between
                                                           peer-checked:border-[#121293] peer-checked:ring-2 peer-checked:ring-[#121293]/20
                                                           peer-checked:[&_.radio-ring]:border-[#121293] peer-checked:[&_.radio-dot]:opacity-100 transition">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-[#121293]/10 flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6 text-[#121293]" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M16 20V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v16">
                                                                </path>
                                                                <path d="M8 6h4"></path>
                                                                <path d="M8 10h4"></path>
                                                                <path d="M8 14h4"></path>
                                                                <path d="M18 20V10a2 2 0 0 0-2-2h-2"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="leading-tight">
                                                            <div class="text-sm font-semibold text-slate-900">Karyawan
                                                            </div>
                                                            <div class="text-xs text-slate-500">Akun untuk karyawan RSU
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="radio-ring h-5 w-5 rounded-full border-2 border-slate-300 flex items-center justify-center">
                                                        <div
                                                            class="radio-dot h-2.5 w-2.5 rounded-full bg-[#121293] opacity-0 transition">
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>

                                            {{-- Narasumber --}}
                                            <label class="block cursor-pointer">
                                                <input type="radio" name="role_slug" value="instructor"
                                                    class="peer sr-only"
                                                    {{ old('role_slug') === 'instructor' ? 'checked' : '' }} />

                                                <div
                                                    class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 flex items-center justify-between
                                                           peer-checked:border-[#121293] peer-checked:ring-2 peer-checked:ring-[#121293]/20
                                                           peer-checked:[&_.radio-ring]:border-[#121293] peer-checked:[&_.radio-dot]:opacity-100 transition">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-[#121293]/10 flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6 text-[#121293]" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                                                <circle cx="12" cy="7" r="4"></circle>
                                                            </svg>
                                                        </div>
                                                        <div class="leading-tight">
                                                            <div class="text-sm font-semibold text-slate-900">Narasumber
                                                            </div>
                                                            <div class="text-xs text-slate-500">Pendaftaran khusus
                                                                narasumber</div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="radio-ring h-5 w-5 rounded-full border-2 border-slate-300 flex items-center justify-center">
                                                        <div
                                                            class="radio-dot h-2.5 w-2.5 rounded-full bg-[#121293] opacity-0 transition">
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <x-input-error :messages="$errors->get('role_slug')" class="mt-2" />
                                    </div>

                                    {{-- Nama + NIK --}}
                                    <div>
                                        <x-required-label for="name" value="Nama Lengkap" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text"
                                            name="name" :value="old('name')" required autofocus autocomplete="name"
                                            placeholder="Masukan Nama Lengkap" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-required-label for="nik" value="NIK" />
                                        <x-text-input id="nik" class="block mt-1 w-full" type="text"
                                            name="nik" :value="old('nik')" required autocomplete="off"
                                            placeholder="Masukan NIK Anda" />
                                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                                    </div>

                                    {{-- Email + Phone --}}
                                    <div>
                                        <x-required-label for="email" value="Email" />
                                        <x-text-input id="email" class="block mt-1 w-full" type="email"
                                            name="email" :value="old('email')" required autocomplete="username"
                                            placeholder="Masukan Email Anda" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-required-label for="phone" value="No Handphone" />
                                        <x-text-input id="phone" class="block mt-1 w-full" type="text"
                                            name="phone" :value="old('phone')" required autocomplete="off"
                                            placeholder="Masukan Nomor Hp Anda" />
                                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                    </div>

                                    {{-- Birth date + Gender --}}
                                    <div>
                                        <x-required-label for="birth_date" value="Tanggal Lahir" />
                                        <x-text-input id="birth_date" class="block mt-1 w-full" type="date"
                                            name="birth_date" :value="old('birth_date')" required />
                                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-required-label value="Jenis Kelamin" />
                                        <div class="mt-2 flex items-center gap-6">
                                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                                <input type="radio" name="gender" value="M"
                                                    class="text-[#121293] focus:ring-[#121293]"
                                                    {{ old('gender', 'M') === 'M' ? 'checked' : '' }} required />
                                                Laki-laki
                                            </label>

                                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                                <input type="radio" name="gender" value="F"
                                                    class="text-[#121293] focus:ring-[#121293]"
                                                    {{ old('gender') === 'F' ? 'checked' : '' }} required />
                                                Perempuan
                                            </label>
                                        </div>
                                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                    </div>

                                    {{-- SECTION: Data Pekerjaan --}}
                                    <div id="karyawanFields" class="hidden space-y-4">
                                        <div class="rounded-xl border border-slate-200 bg-white/60 p-4">
                                            <div class="text-sm font-semibold text-slate-900">Data Pekerjaan</div>
                                            <div class="text-xs text-slate-500 mt-1">
                                                Wajib diisi untuk akun Karyawan.
                                            </div>

                                            <div class="grid md:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <x-required-label value="Job Category" />
                                                    <select id="jobCategory" name="job_category_id"
                                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white/70 focus:border-[#121293] focus:ring-[#121293]">
                                                        <option value="">-- pilih --</option>
                                                        @foreach ($jobCategories as $jc)
                                                            <option value="{{ $jc->id }}"
                                                                @selected(old('job_category_id') == $jc->id)>
                                                                {{ $jc->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('job_category_id')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-required-label value="Job Title" />
                                                    <select id="jobTitle" name="job_title_id"
                                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white/70 focus:border-[#121293] focus:ring-[#121293]">
                                                        <option value="">-- pilih --</option>
                                                        @foreach ($jobTitles as $jt)
                                                            <option value="{{ $jt->id }}"
                                                                @selected(old('job_title_id') == $jt->id)>
                                                                {{ $jt->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('job_title_id')" class="mt-2" />
                                                </div>
                                            </div>

                                            {{-- SUB SECTION: Perawat --}}
                                            <div id="perawatFields" class="hidden grid md:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <x-required-label for="jabatan" value="Jabatan (Manual)" />
                                                    <x-text-input id="jabatan" class="block mt-1 w-full"
                                                        type="text" name="jabatan" :value="old('jabatan')"
                                                        placeholder="Contoh: Perawat Pelaksana" />
                                                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-required-label for="unit" value="Unit (Manual)" />
                                                    <x-text-input id="unit" class="block mt-1 w-full"
                                                        type="text" name="unit" :value="old('unit')"
                                                        placeholder="Contoh: IGD / ICU / Rawat Inap" />
                                                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Password --}}
                                    <div>
                                        <x-required-label for="password" value="Password" />
                                        <div class="relative mt-1">
                                            <x-text-input id="password" class="block w-full pr-12" type="password"
                                                name="password" required autocomplete="new-password"
                                                placeholder="Masukan Kata Sandi" />
                                            <button type="button" id="togglePassword"
                                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                                                aria-label="Tampilkan/Sembunyikan password">
                                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
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

                                    {{-- Password Confirmation --}}
                                    <div>
                                        <x-required-label for="password_confirmation" value="Konfirmasi Password" />
                                        <div class="relative mt-1">
                                            <x-text-input id="password_confirmation" class="block w-full pr-12"
                                                type="password" name="password_confirmation" required
                                                autocomplete="new-password" placeholder="Ulangi Kata Sandi" />
                                            <button type="button" id="togglePassword2"
                                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                                                aria-label="Tampilkan/Sembunyikan konfirmasi password">
                                                <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
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
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>

                                    {{-- Submit --}}
                                    <div class="pt-2">
                                        <x-primary-button
                                            class="w-full justify-center !rounded-xl !py-3 !text-[15px]
                                                   bg-[#121293] hover:bg-[#0e0e75] focus:ring-[#121293]/40">
                                            {{ __('Buat Akun') }}
                                        </x-primary-button>
                                    </div>

                                    {{-- Divider + Login --}}
                                    <div class="text-center text-sm text-slate-600 pt-2">
                                        <div class="flex items-center gap-3 my-2">
                                            <div class="h-px flex-1 bg-slate-200"></div>
                                            <div class="text-xs text-slate-400">atau</div>
                                            <div class="h-px flex-1 bg-slate-200"></div>
                                        </div>

                                        Sudah punya akun?
                                        <a href="{{ route('login') }}"
                                            class="text-[#121293] hover:text-indigo-700 font-semibold">
                                            Login
                                        </a>
                                    </div>

                                    <div class="text-center text-xs text-slate-400 pt-2">
                                        Version {{ config('app.version') }}
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="mt-6 text-center text-xs text-slate-500">
                            Dengan mendaftar, kamu menyetujui kebijakan penggunaan sistem internal RSU Royal Prima.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Illustration --}}
            <div class="hidden lg:block lg:w-1/2 p-8">
                <div
                    class="h-full w-full rounded-3xl overflow-hidden relative shadow-2xl shadow-slate-200/70 border border-white/60">
                    <img src="{{ asset($pickedPic) }}" alt="Register Illustration"
                        class="h-full w-full object-cover" draggable="false" loading="lazy" />

                    {{-- soften --}}
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-[1px]"></div>
                    {{-- overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-slate-950/15 to-transparent">
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="max-w-md">
                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-white/90 text-xs backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                                Pendaftaran Akun
                            </div>

                            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white">
                                Mulai belajar dengan lebih rapi.
                            </h2>
                            <p class="mt-3 text-sm text-white/80 leading-relaxed">
                                Satu akun untuk akses modul, jadwal pelatihan, dan progress JPL.
                            </p>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/85 backdrop-blur">🧾
                                    Data Lengkap</span>
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/85 backdrop-blur">📚
                                    Modul Terstruktur</span>
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/85 backdrop-blur">📈
                                    Tracking Progress</span>
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

            const roleRadios = document.querySelectorAll('input[name="role_slug"]');
            const karyawanFields = document.getElementById('karyawanFields');

            const jobCategory = document.getElementById('jobCategory');
            const jobTitle = document.getElementById('jobTitle');

            const perawatFields = document.getElementById('perawatFields');
            const jabatan = document.getElementById('jabatan');
            const unit = document.getElementById('unit');

            function setRequired(el, required) {
                if (!el) return;
                el.required = required;
            }

            function clearValue(el) {
                if (!el) return;
                el.value = '';
            }

            function getSelectedRole() {
                return document.querySelector('input[name="role_slug"]:checked')?.value ?? null;
            }

            function toggleKaryawanFields() {
                const isKaryawan = getSelectedRole() === 'karyawan';

                karyawanFields?.classList.toggle('hidden', !isKaryawan);

                setRequired(jobCategory, isKaryawan);
                setRequired(jobTitle, isKaryawan);

                if (!isKaryawan) {
                    perawatFields?.classList.add('hidden');

                    setRequired(jabatan, false);
                    setRequired(unit, false);

                    clearValue(jobCategory);
                    clearValue(jobTitle);
                    clearValue(jabatan);
                    clearValue(unit);
                } else {
                    togglePerawatFields();
                }
            }

            function togglePerawatFields() {
                if (!jobTitle) return;

                const selectedText = jobTitle.options[jobTitle.selectedIndex]?.text?.trim().toLowerCase();
                const isPerawat = selectedText === 'perawat';

                perawatFields?.classList.toggle('hidden', !isPerawat);

                setRequired(jabatan, isPerawat);
                setRequired(unit, isPerawat);

                if (!isPerawat) {
                    clearValue(jabatan);
                    clearValue(unit);
                }
            }

            roleRadios.forEach(r => r.addEventListener('change', toggleKaryawanFields));
            jobTitle?.addEventListener('change', togglePerawatFields);

            toggleKaryawanFields();
        })();
    </script>
</x-guest-layout>
