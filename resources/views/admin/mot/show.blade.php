<x-app-layout>
    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8 space-y-6">

            {{-- PAGE HEADER --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Review Dokumen MOT</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Tinjau dokumen narasumber, lalu tentukan keputusan.
                    </p>
                </div>

                <a href="{{ route('admin.mot.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-700">
                    ← Kembali
                </a>
            </div>

            @php
                $status = $doc->status;
                $badge = match ($status) {
                    'pending' => 'border-amber-200 bg-amber-50 text-amber-800',
                    'approved' => 'border-green-200 bg-green-50 text-green-800',
                    'rejected' => 'border-red-200 bg-red-50 text-red-700',
                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                };
                $dot = match ($status) {
                    'pending' => 'bg-amber-500',
                    'approved' => 'bg-green-500',
                    'rejected' => 'bg-red-500',
                    default => 'bg-slate-400',
                };
            @endphp

            {{-- TOP SUMMARY CARD --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-6 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="h-10 w-10 rounded-xl bg-[#121293]/10 text-[#121293] flex items-center justify-center font-semibold">
                                    M
                                </div>
                                <div>
                                    <div class="text-slate-900 font-semibold">Status Saat Ini</div>
                                    <div class="text-sm text-slate-500">Pastikan dokumen sesuai sebelum approve.</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm border {{ $badge }}">
                                    <span class="h-2 w-2 rounded-full {{ $dot }}"></span>
                                    Status: <span class="font-semibold">{{ ucfirst($doc->status) }}</span>
                                </span>

                                @if ($doc->rejected_reason)
                                    <div class="mt-3 text-sm text-red-700">
                                        <span class="font-medium">Alasan ditolak:</span>
                                        {{ $doc->rejected_reason }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- FILE CARD --}}
                        <div class="w-full sm:w-[360px]">
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="text-xs uppercase tracking-wide text-slate-500">File</div>
                                <div class="mt-1 text-sm font-medium text-slate-800 truncate">
                                    {{ $doc->original_name ?? '-' }}
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-200">
                                        <div class="text-[11px] uppercase tracking-wide text-slate-500">Uploaded</div>
                                        <div class="mt-1 text-sm text-slate-700">
                                            {{ $doc->created_at->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-200">
                                        <div class="text-[11px] uppercase tracking-wide text-slate-500">Type</div>
                                        <div class="mt-1 text-sm text-slate-700">
                                            {{ strtoupper($doc->mime_type ?? '-') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @if ($doc->file_path)
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                            class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-xl
                                                  bg-slate-900 text-white hover:opacity-90 text-sm font-medium">
                                            Lihat File
                                        </a>
                                    @else
                                        <button disabled
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-400 cursor-not-allowed">
                                            File tidak tersedia
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- REVIEW FORM --}}
                <div class="p-6" x-data="{ status: @js(old('status', $doc->status)) }">
                    <form method="POST" action="{{ route('admin.mot.update', $doc) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-5">
                                <x-required-label value="Keputusan" />
                                <select name="status" required x-model="status"
                                    class="mt-1 w-full rounded-xl border-slate-200 focus:border-[#121293] focus:ring-[#121293]/20">
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />

                                <div class="mt-3 text-xs text-slate-500">
                                    Approve = dokumen valid. Reject = butuh perbaikan.
                                </div>
                            </div>

                            <div class="md:col-span-7">
                                <x-input-label value="Alasan Penolakan (wajib jika reject)" />
                                <div class="mt-1">
                                    <textarea name="rejected_reason" rows="4" :required="status === 'rejected'"
                                        class="w-full rounded-xl border-slate-200 focus:border-[#121293] focus:ring-[#121293]/20"
                                        placeholder="Tulis alasan yang jelas biar narasumber tahu apa yang harus diperbaiki...">{{ old('rejected_reason', $doc->rejected_reason) }}</textarea>
                                </div>

                                <div class="mt-2 text-xs"
                                    :class="status === 'rejected' ? 'text-red-600' : 'text-slate-400'">
                                    <span x-show="status === 'rejected'">Wajib diisi saat Reject.</span>
                                    <span x-show="status !== 'rejected'">Tidak perlu diisi kalau Approve.</span>
                                </div>

                                <x-input-error :messages="$errors->get('rejected_reason')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row gap-3 sm:items-center">
                            <button
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#121293] text-white
                                       hover:opacity-90 font-medium shadow-sm">
                                Simpan Keputusan
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

        </div>
    </div>
</x-app-layout>
