<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold text-slate-900">Edit Course Type</h1>

                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold
                                {{ $courseType->is_active ? 'bg-green-50 text-green-800 border-green-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                {{ $courseType->is_active ? 'ACTIVE' : 'INACTIVE' }}
                            </span>

                            <span class="text-xs text-slate-500">
                                Slug auto & unik (tidak perlu di-edit).
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('course-types.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('course-types.update', $courseType) }}"
                    class="p-5 sm:p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-semibold text-slate-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input name="name" value="{{ old('name', $courseType->name) }}" required
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm
                            focus:outline-none focus:ring-2 focus:ring-[#121293]/25 focus:border-[#121293]/30">
                        @error('name')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                        <div class="text-xs text-slate-500 mt-1">
                            Jika name berubah, slug akan ikut dibuat ulang otomatis (tetap unik).
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-start gap-3">
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-[#121293] focus:ring-[#121293]"
                                @checked(old('is_active', $courseType->is_active))>
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-slate-800">Active</label>
                                <div class="text-xs text-slate-600">Matikan untuk menyembunyikan dari pilihan course.
                                </div>
                            </div>
                        </div>

                        <button
                            class="inline-flex items-center justify-center rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Update
                        </button>
                    </div>
                </form>

                <div
                    class="border-t border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-slate-600">
                        Hapus hanya kalau sudah tidak dipakai oleh course.
                    </div>

                    <form method="POST" action="{{ route('course-types.destroy', $courseType) }}"
                        onsubmit="return confirm('Hapus course type ini?');">
                        @csrf
                        @method('DELETE')
                        <button
                            class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
