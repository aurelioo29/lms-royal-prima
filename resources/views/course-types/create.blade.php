<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Buat Course Type</h1>
                        <p class="text-sm text-slate-600 mt-1">Type ini akan muncul di form pembuatan Course.</p>
                    </div>
                    <a href="{{ route('course-types.index') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('course-types.store') }}" class="p-5 sm:p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Name</label>
                        <input name="name" value="{{ old('name') }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30"
                            placeholder="Contoh: Workshop, Seminar, E-learning">
                        @error('name')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Slug</label>
                        <input name="slug" value="{{ old('slug') }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30"
                            placeholder="Contoh: workshop (boleh kosong, auto dari name)">
                        @error('slug')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                        <div class="text-xs text-slate-500 mt-1">Kalau slug kosong, akan dibuat otomatis dari name.
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" rows="4"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30"
                            placeholder="(Opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                class="h-4 w-4 rounded border-slate-300" @checked(old('is_active', true))>
                            <label for="is_active" class="text-sm font-semibold text-slate-700">
                                Active
                            </label>
                        </div>

                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
