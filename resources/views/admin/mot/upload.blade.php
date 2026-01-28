<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Upload MOT Narasumber</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Upload dokumen MOT untuk narasumber. Jika upload oleh admin/kabid, status akan otomatis <span
                            class="font-semibold">APPROVED</span>.
                    </p>
                </div>

                <a href="{{ route('admin.mot.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-700">
                    ← Kembali
                </a>
            </div>

            {{-- Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-200 bg-slate-50/60">
                    <div class="text-sm text-slate-600">Narasumber</div>
                    <div class="mt-1 font-semibold text-slate-900">{{ $user->name }}</div>
                    <div class="text-sm text-slate-600">{{ $user->email }}</div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('admin.mot.store', $user) }}" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf

                        <div>
                            <x-required-label value="File MOT" />
                            <input type="file" name="file" required
                                class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm
                                       file:mr-4 file:rounded-lg file:border-0 file:bg-[#121293] file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold
                                       hover:file:opacity-90 focus:border-[#121293] focus:ring-[#121293]/20" />

                            @error('file')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror

                            <div class="mt-2 text-xs text-slate-500">
                                Format: PDF / JPG / PNG. Maks: 10MB.
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row gap-3 sm:items-center">
                            <button
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#121293] text-white
                                       hover:opacity-90 font-medium shadow-sm">
                                Upload & Auto Approve
                            </button>

                            <a href="{{ route('admin.mot.index') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200
                                       hover:bg-slate-50 font-medium text-slate-700">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Note --}}
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 text-sm">
                <div class="font-semibold">Catatan</div>
                <div class="mt-1">
                    Jika ingin tetap lewat proses ACC (pending → approve), jangan pakai halaman ini.
                    Halaman ini memang dibuat untuk “dibantu administrasi” sehingga langsung approved.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
