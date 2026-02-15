<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER SECTION --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            {{-- Back Button --}}
                            <a href="{{ route($routePrefix . '.modules.index', $course->id) }}"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors shadow-sm"
                                title="Kembali ke Daftar Modul">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                    Tambah Modul Baru
                                </h1>
                                <p class="text-sm text-slate-500">
                                    Menambahkan materi untuk kursus: <span
                                        class="font-medium text-slate-700">{{ $course->title }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM SECTION --}}
            <form action="{{ route($routePrefix . '.modules.store', $course->id) }}" method="POST"
                enctype="multipart/form-data" x-data="{
                    type: '{{ old('type', 'pdf') }}',
                    video_mode: '{{ old('video_mode', 'link') }}',
                    has_quiz: {{ old('has_quiz') ? 'true' : 'false' }},
                    initQuill() {
                        const quill = new Quill('#editor', {
                            theme: 'snow',
                            placeholder: 'Tulis deskripsi atau instruksi modul di sini...',
                        });
                        quill.on('text-change', () => {
                            $refs.description.value = quill.root.innerHTML;
                        });
                        // Sync initial old data if validation fails
                        if ($refs.description.value) {
                            quill.root.innerHTML = $refs.description.value;
                        }
                    }
                }" x-init="initQuill()"
                class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                @csrf

                {{-- LEFT COLUMN: Content Info --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 space-y-6">

                        {{-- Judul Modul --}}
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Modul
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="w-full rounded-xl border-slate-200 focus:border-[#121293] focus:ring focus:ring-[#121293]/10 transition-all placeholder:text-slate-400"
                                placeholder="Contoh: Dasar-dasar Pemrograman Laravel">
                            @error('title')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi (Quill Editor) --}}
                        <div wire:ignore>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                            <div class="prose max-w-none">
                                <div id="editor" class="rounded-xl min-h-[180px] bg-slate-50/30"></div>
                            </div>
                            <input type="hidden" name="description" x-ref="description"
                                value="{{ old('description') }}">
                            @error('description')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe & Konten Dinamis --}}
                        <div class="pt-6 border-t border-slate-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                {{-- Select Type --}}
                                <div>
                                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe
                                        Modul <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="type" id="type" x-model="type"
                                            class="w-full appearance-none rounded-xl border-slate-200 focus:border-[#121293] focus:ring focus:ring-[#121293]/10">
                                            <option value="pdf">Dokumen (PDF)</option>
                                            <option value="video">Video (URL/Embed)</option>
                                            <option value="link">Tautan Luar (Link)</option>
                                        </select>
                                    </div>
                                    <p class="mt-1.5 text-[11px] text-slate-500">Pilih jenis materi yang akan diberikan.
                                    </p>
                                </div>

                                {{-- Sort Order --}}
                                <div>
                                    <label for="sort_order"
                                        class="block text-sm font-semibold text-slate-700 mb-1.5">Urutan Modul</label>
                                    <input type="number" name="sort_order" id="sort_order" min="1"
                                        value="{{ old('sort_order') }}"
                                        class="w-full rounded-xl border-slate-200 focus:border-[#121293] focus:ring focus:ring-[#121293]/10 placeholder:text-slate-400"
                                        placeholder="Kosongkan untuk otomatis">
                                    @error('sort_order')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Dinamis (Content Area) --}}
                            <div class="space-y-6">
                                {{-- Opsi Video Mode --}}
                                <div x-show="type === 'video'" class="space-y-3">
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Sumber Video <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="video_mode" value="link" x-model="video_mode">
                                            <span class="text-sm text-slate-700">Link / Embed</span>
                                        </label>

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="video_mode" value="upload" x-model="video_mode">
                                            <span class="text-sm text-slate-700">Upload Video</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Textarea untuk Video/Link/Quiz --}}
                                <div x-show="type === 'link' || (type === 'video' && video_mode === 'link')"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2">
                                    <label for="content" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        <span
                                            x-text="type === 'video' ? 'Link Video / Embed Code' : (type === 'quiz' ? 'Instruksi Kuis' : 'Alamat URL Link')"></span>
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <textarea name="content" id="content" rows="3"
                                        class="w-full rounded-xl border-slate-200 focus:border-[#121293] focus:ring focus:ring-[#121293]/10 transition-all shadow-sm"
                                        placeholder="Tempelkan konten atau tautan di sini...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- File Upload Area --}}
                                <div x-show="type === 'pdf' || (type === 'video' && video_mode === 'upload')"
                                    class="p-5 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 transition-colors hover:border-[#121293]/30 group">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div
                                            class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-slate-400 group-hover:text-[#121293] transition-colors shadow-sm mb-3">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <label for="file"
                                            class="block text-sm font-semibold text-slate-700 cursor-pointer">
                                            Pilih File Modul
                                        </label>
                                        <p class="text-[11px] text-slate-500 mt-1 mb-4">
                                            <span
                                                x-text="type === 'pdf' ? 'Wajib untuk PDF.' : 'Opsional sebagai lampiran.'"></span>
                                            Maksimal 20MB.
                                        </p>

                                        <input type="file" name="file" id="file"
                                            :accept="type === 'video' && video_mode === 'upload' ?
                                                'video/mp4,video/mov,video/avi' :
                                                'application/pdf'"
                                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#121293] file:text-white hover:file:opacity-90 transition-all cursor-pointer">
                                    </div>
                                    @error('file')
                                        <p class="mt-2 text-xs text-red-500 text-center font-medium">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @include('courses.modules.quizz.form-quiz')
                    </div>

                </div>

                {{-- RIGHT COLUMN: Settings --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden  top-6">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h2 class="font-bold text-slate-800 text-sm tracking-wide">VISIBILITAS & ATURAN</h2>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Checkboxes --}}
                            <div class="space-y-4">

                                {{-- Checkbox Trigger Quiz (TAMBAHAN) --}}
                                <label
                                    class="flex items-center cursor-pointer group mt-4 pt-4 border-t border-slate-100">
                                    <div class="relative">
                                        <input type="checkbox" name="has_quiz" x-model="has_quiz"
                                            class="sr-only peer" value="1">
                                        <div
                                            class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500">
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-sm font-semibold text-slate-700">Modul ini memiliki
                                            Quiz</span>
                                        <p class="text-[10px] text-slate-500">Aktifkan untuk menambah kuis di akhir
                                            modul.</p>
                                    </div>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_required" class="sr-only peer"
                                            {{ old('is_required', true) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#121293]">
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-sm font-semibold text-slate-700">Wajib Selesai</span>
                                        <p class="text-[10px] text-slate-500">User harus membuka modul ini.</p>
                                    </div>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" class="sr-only peer"
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-sm font-semibold text-slate-700">Aktifkan Modul</span>
                                        <p class="text-[10px] text-slate-500">Langsung muncul di daftar user.</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#121293] text-white hover:bg-[#121293]/90 font-bold shadow-lg shadow-[#121293]/20 transition-all active:scale-[0.98]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Simpan Modul
                                </button>
                                <a href="{{ route($routePrefix . '.modules.index', $course->id) }}"
                                    class="w-full flex items-center justify-center px-6 py-3 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold transition-all">
                                    Batalkan
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- INFO CARD --}}
                    <div class="rounded-2xl bg-blue-50 border border-blue-100 p-5">
                        <div class="flex gap-3">
                            <div
                                class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900">Catatan Upload</h4>
                                <p class="text-xs text-blue-700 mt-1 leading-relaxed">
                                    Pastikan file PDF sudah memiliki penamaan yang rapi sebelum diunggah agar memudahkan
                                    identifikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

</x-app-layout>
