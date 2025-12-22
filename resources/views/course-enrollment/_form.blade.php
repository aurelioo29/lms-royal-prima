<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-full px-4 sm:px-6 lg:px-8">

            {{-- ================= CARD ================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-slate-100">
                    <h1 class="text-xl font-bold text-slate-900">
                        Enroll ke Course
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $course->torSubmission->planEvent?->title }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-6">

                    {{-- Info Box --}}
                    <div class="flex gap-3 bg-[#121293]/5 border-l-4 border-[#121293] p-4 rounded-r-xl">
                        <div class="flex-shrink-0 text-[#121293]">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-[#121293]">
                                Informasi Enrollment
                            </h3>
                            <p class="mt-1 text-sm text-[#121293]/80 leading-relaxed">
                                Masukkan <strong>Enrollment Key</strong> yang Anda terima dari
                                administrator atau departemen terkait untuk mulai mengikuti pelatihan ini.
                            </p>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('employee.courses.enroll') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="enrollment_key" class="block text-sm font-semibold text-slate-700 mb-1">
                                Enrollment Key
                            </label>

                            <input id="enrollment_key" type="text" name="enrollment_key"
                                value="{{ old('enrollment_key') }}" placeholder="Contoh: PEL-8F3KQ2XJ"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800
                                       focus:outline-none focus:ring-4 focus:ring-[#121293]/20 focus:border-[#121293]
                                       placeholder-slate-400"
                                required>

                            @error('enrollment_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">

                            {{-- Kembali --}}
                            <a href="{{ route('employee.courses.index') }}"
                                class="inline-flex justify-center items-center px-5 py-2.5
                                       text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl
                                       hover:bg-slate-200 transition">
                                Kembali
                            </a>

                            {{-- Submit --}}
                            <button type="submit"
                                class="inline-flex justify-center items-center px-6 py-2.5
                                       text-sm font-semibold text-white bg-[#121293] rounded-xl
                                       hover:opacity-90 transition shadow-md shadow-blue-900/20">
                                Enroll Sekarang
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
