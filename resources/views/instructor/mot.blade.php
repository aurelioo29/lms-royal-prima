<x-app-layout>
    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8">

            {{-- PAGE HEADER --}}
            <div class="mb-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Dokumen MOT</h1>
                        <p class="text-sm text-slate-500 mt-1">
                            Upload MOT kamu supaya bisa mengajar. Admin akan review statusnya.
                        </p>
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-700">
                        ← Kembali
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                    <div class="font-semibold">Berhasil</div>
                    <div class="text-sm mt-1">{{ session('success') }}</div>
                </div>
            @endif

            {{-- MAIN CARD --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">

                {{-- TOP STRIP --}}
                <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-xl bg-[#121293]/10 text-[#121293] flex items-center justify-center font-semibold">
                                    M
                                </div>
                                <div>
                                    <div class="text-slate-900 font-semibold">Status Saat Ini</div>
                                    <div class="text-sm text-slate-500">Cek status dokumen kamu sebelum mengajar.</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                @if (!$mot)
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm
                                                 border border-red-200 bg-red-50 text-red-700">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                        Belum ada MOT
                                    </span>
                                @else
                                    @php
                                        $badge = match ($mot->status) {
                                            'pending' => 'border-amber-200 bg-amber-50 text-amber-800',
                                            'approved' => 'border-green-200 bg-green-50 text-green-800',
                                            'rejected' => 'border-red-200 bg-red-50 text-red-700',
                                            default => 'border-slate-200 bg-slate-50 text-slate-700',
                                        };

                                        $dot = match ($mot->status) {
                                            'pending' => 'bg-amber-500',
                                            'approved' => 'bg-green-500',
                                            'rejected' => 'bg-red-500',
                                            default => 'bg-slate-400',
                                        };

                                        $statusText = ucfirst($mot->status);
                                    @endphp

                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm border {{ $badge }}">
                                        <span class="h-2 w-2 rounded-full {{ $dot }}"></span>
                                        Status: <span class="font-semibold">{{ $statusText }}</span>
                                    </span>

                                    @if ($mot->status === 'rejected' && $mot->rejected_reason)
                                        <div class="mt-3 text-sm text-slate-600">
                                            <span class="font-medium text-slate-800">Alasan:</span>
                                            {{ $mot->rejected_reason }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- FILE MINI CARD --}}
                        <div class="w-full sm:w-[320px]">
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="text-xs uppercase tracking-wide text-slate-500">File</div>
                                <div class="mt-1 text-sm font-medium text-slate-800 truncate">
                                    {{ $mot?->original_name ?? '-' }}
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3 text-xs text-slate-500">
                                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-200">
                                        <div class="text-[11px] uppercase tracking-wide">Issued</div>
                                        <div class="mt-1 text-sm text-slate-700">
                                            {{ $mot?->issued_at ? \Carbon\Carbon::parse($mot->issued_at)->format('d M Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-200">
                                        <div class="text-[11px] uppercase tracking-wide">Expires</div>
                                        <div class="mt-1 text-sm text-slate-700">
                                            {{ $mot?->expires_at ? \Carbon\Carbon::parse($mot->expires_at)->format('d M Y') : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @if ($mot && $mot->file_path)
                                        <a href="{{ asset('storage/' . $mot->file_path) }}" target="_blank"
                                            class="inline-flex items-center justify-center w-full px-4 py-2 rounded-xl
                                                  border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-[#121293]">
                                            Lihat File
                                        </a>
                                    @else
                                        <button disabled
                                            class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-400 cursor-not-allowed">
                                            Lihat File
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FORM AREA --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('instructor.mot.store') }}" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf

                        <div x-data="{
                            fileName: '',
                            fileSize: '',
                            fileType: '',
                            error: '',
                            setFromInput() {
                                const f = this.$refs.input.files?.[0];
                                this.setFile(f);
                            },
                            setFile(f) {
                                this.error = '';
                                if (!f) {
                                    this.clear();
                                    return;
                                }
                        
                                const max = 5 * 1024 * 1024; // 5MB
                                if (f.size > max) {
                                    this.error = 'File terlalu besar. Maksimal 5MB.';
                                    this.clear();
                                    return;
                                }
                        
                                this.fileName = f.name;
                                this.fileType = (f.type || 'unknown').toUpperCase();
                                this.fileSize = this.humanSize(f.size);
                            },
                            drop(e) {
                                const f = e.dataTransfer.files?.[0];
                                if (!f) return;
                        
                                const dt = new DataTransfer();
                                dt.items.add(f);
                                this.$refs.input.files = dt.files;
                        
                                this.setFile(f);
                            },
                            openPicker() {
                                this.$refs.input.click();
                            },
                            clear() {
                                this.fileName = '';
                                this.fileSize = '';
                                this.fileType = '';
                                this.$refs.input.value = '';
                            },
                            humanSize(bytes) {
                                const units = ['B', 'KB', 'MB', 'GB'];
                                let i = 0,
                                    n = bytes;
                                while (n >= 1024 && i < units.length - 1) { n /= 1024;
                                    i++; }
                                return (i === 0 ? n : n.toFixed(1)) + ' ' + units[i];
                            }
                        }" class="space-y-2">
                            <div class="flex items-center justify-between">
                                <x-required-label value="File MOT (PDF/JPG/PNG, max 5MB)" />
                                <span class="text-xs text-slate-500">Wajib</span>
                            </div>

                            {{-- ✅ HANYA 1 INPUT FILE DI SELURUH FORM --}}
                            <input x-ref="input" type="file" name="mot_file" required class="sr-only"
                                accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                @change="setFromInput()" />

                            <div class="rounded-2xl border border-dashed p-5 transition"
                                :class="fileName ? 'border-[#121293] bg-[#121293]/[0.04]' :
                                    'border-slate-300 bg-slate-50 hover:bg-slate-100'"
                                @dragover.prevent @drop.prevent="drop($event)">
                                <div class="flex items-start gap-4">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center border"
                                        :class="fileName ? 'bg-white border-[#121293]/20 text-[#121293]' :
                                            'bg-white border-slate-200 text-slate-600'">
                                        <span x-text="fileName ? '✓' : '⬆️'"></span>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-slate-800">
                                            <template x-if="!fileName">
                                                <span>Klik untuk pilih file, atau drag & drop di sini</span>
                                            </template>
                                            <template x-if="fileName">
                                                <span class="block truncate">
                                                    File dipilih: <span class="font-semibold" x-text="fileName"></span>
                                                </span>
                                            </template>
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1">
                                            <template x-if="!fileName">
                                                <span>Format: PDF/JPG/PNG • Maks: 5MB</span>
                                            </template>

                                            <template x-if="fileName">
                                                <span class="inline-flex flex-wrap gap-2">
                                                    <span
                                                        class="px-2 py-1 rounded-full bg-white border border-slate-200 text-slate-600">
                                                        <span class="font-medium">Size:</span> <span
                                                            x-text="fileSize"></span>
                                                    </span>
                                                    <span
                                                        class="px-2 py-1 rounded-full bg-white border border-slate-200 text-slate-600">
                                                        <span class="font-medium">Type:</span> <span
                                                            x-text="fileType"></span>
                                                    </span>
                                                </span>
                                            </template>
                                        </div>

                                        <template x-if="error">
                                            <div class="mt-2 text-xs text-red-600" x-text="error"></div>
                                        </template>

                                        <div class="mt-3 flex flex-col sm:flex-row gap-2">
                                            <button type="button"
                                                class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-700"
                                                x-show="fileName" @click="clear()">
                                                Hapus File
                                            </button>

                                            <button type="button"
                                                class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#121293] text-white hover:opacity-90 text-sm font-medium"
                                                @click="openPicker()">
                                                <span x-text="fileName ? 'Ganti File' : 'Pilih File'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('mot_file')" class="mt-2" />
                        </div>


                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Issued at (optional)" />
                                <x-text-input type="date" name="issued_at" class="mt-1 w-full"
                                    value="{{ old('issued_at') }}" />
                                <x-input-error :messages="$errors->get('issued_at')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label value="Expires at (optional)" />
                                <x-text-input type="date" name="expires_at" className="mt-1 w-full"
                                    value="{{ old('expires_at') }}" />
                                <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="pt-2 flex flex-col sm:flex-row gap-3 sm:items-center">
                            <button
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#121293] text-white
                                       hover:opacity-90 font-medium shadow-sm">
                                {{ $mot ? 'Upload Ulang' : 'Upload' }}
                            </button>

                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200
                                      hover:bg-slate-50 font-medium text-slate-700">
                                Batal
                            </a>

                            <div class="sm:ml-auto text-xs text-slate-500">
                                Setelah upload, status otomatis <span class="font-medium text-amber-700">Pending</span>
                                sampai admin approve.
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
