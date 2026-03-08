<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill="currentColor"
                                            d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5Zm0 2c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4Z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                        Profile
                                    </h1>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Kelola informasi akun, password, dan pengaturan keamanan Anda.
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ auth()->user()->name }}
                                        </span>

                                        <span
                                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ auth()->user()->email }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- PROFILE INFORMATION --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50/60 px-5 py-4 sm:px-6">
                    <div class="font-semibold text-slate-900">Informasi Profil</div>
                    <div class="text-sm text-slate-600 mt-1">
                        Perbarui nama dan email akun Anda.
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="max-w-3xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- UPDATE PASSWORD --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50/60 px-5 py-4 sm:px-6">
                    <div class="font-semibold text-slate-900">Update Password</div>
                    <div class="text-sm text-slate-600 mt-1">
                        Gunakan password yang kuat dan tidak mudah ditebak.
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="max-w-3xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- DELETE ACCOUNT --}}
            <div class="rounded-2xl border border-red-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-red-100 bg-red-50/60 px-5 py-4 sm:px-6">
                    <div class="font-semibold text-red-700">Zona Bahaya</div>
                    <div class="text-sm text-red-600 mt-1">
                        Hapus akun secara permanen jika memang sudah tidak dibutuhkan.
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="max-w-3xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
