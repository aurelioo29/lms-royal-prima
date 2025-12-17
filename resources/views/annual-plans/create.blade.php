<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h1 class="text-lg font-semibold text-slate-900">Buat Annual Plan</h1>
                            <p class="mt-1 text-sm text-slate-600">
                                Status awal otomatis: <span class="font-semibold text-slate-800">DRAFT</span>.
                            </p>
                        </div>

                        <a href="{{ route('annual-plans.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('annual-plans.store') }}" class="px-5 py-5 sm:px-6 space-y-5">
                    @csrf

                    {{-- Year --}}
                    <div>
                        <label for="year" class="block text-sm font-semibold text-slate-700">
                            Tahun
                        </label>
                        <p class="mt-1 text-xs text-slate-500">
                            Gunakan format tahun, contoh: 2026.
                        </p>
                        <input id="year" name="year" type="number" inputmode="numeric"
                            value="{{ old('year', now()->year) }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                        @error('year')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700">
                            Judul
                        </label>
                        <p class="mt-1 text-xs text-slate-500">
                            Buat ringkas, jelas, dan mudah dicari.
                        </p>
                        <input id="title" name="title" type="text" value="{{ old('title') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                        @error('title')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700">
                            Deskripsi
                        </label>
                        <p class="mt-1 text-xs text-slate-500">
                            Jelaskan tujuan dan cakupan singkat (opsional).
                        </p>
                        <textarea id="description" name="description" rows="5"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Footer --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 pt-2 border-t border-slate-200">
                        <a href="{{ route('annual-plans.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
