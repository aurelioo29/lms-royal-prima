<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER SECTION --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-amber-500"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route($routePrefix . '.modules.index', $course->id) }}"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                    Edit Modul: {{ $module->title }}
                                </h1>
                                <p class="text-sm text-slate-500">
                                    Memperbarui materi untuk kursus: <span
                                        class="font-medium text-slate-700">{{ $course->title }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM SECTION --}}
            <form action="{{ route($routePrefix . '.modules.update', [$course->id, $module->id]) }}" method="POST"
                enctype="multipart/form-data" x-data="{
                    type: '{{ old('type', $module->type) }}',
                    quill: null,
                    initQuill() {
                        this.quill = new Quill('#editor', {
                            theme: 'snow',
                            placeholder: 'Perbarui deskripsi modul...',
                            modules: {
                                toolbar: [
                                    ['bold', 'italic', 'underline'],
                                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                    ['clean']
                                ]
                            }
                        });
                
                        this.quill.on('text-change', () => {
                            $refs.description.value = this.quill.root.innerHTML;
                        });
                
                        // Set initial content from module or old input
                        const initialContent = `{!! old('description', $module->description) !!}`;
                        this.quill.root.innerHTML = initialContent;
                    }
                }" x-init="initQuill()"
                class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                @csrf
                @method('PUT')

                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 space-y-6">

                        {{-- Judul --}}
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Modul
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title', $module->title) }}"
                                class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-500/10 transition-all">
                            @error('title')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Quill Editor --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                            <div class="prose max-w-none" wire:ignore>
                                <div id="editor" class="rounded-xl min-h-[200px] bg-slate-50/30"></div>
                            </div>
                            <input type="hidden" name="description" x-ref="description"
                                value="{{ old('description', $module->description) }}">
                            @error('description')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-6 border-t border-slate-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                {{-- Select Type --}}
                                <div>
                                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe
                                        Modul <span class="text-red-500">*</span></label>
                                    <select name="type" id="type" x-model="type"
                                        class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-500/10">
                                        <option value="pdf">Dokumen (PDF)</option>
                                        <option value="video">Video (URL/Embed)</option>
                                        <option value="link">Tautan Luar (Link)</option>
                                        <option value="quiz">Kuis Interaktif</option>
                                    </select>
                                </div>

                                {{-- Sort Order --}}
                                <div>
                                    <label for="sort_order"
                                        class="block text-sm font-semibold text-slate-700 mb-1.5">Urutan Modul</label>
                                    <input type="number" name="sort_order" id="sort_order"
                                        value="{{ old('sort_order', $module->sort_order) }}"
                                        class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-500/10">
                                </div>
                            </div>

                            {{-- Dynamic Content --}}
                            <div class="space-y-6">
                                <div x-show="type !== 'pdf'" x-transition>
                                    <label for="content" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        <span
                                            x-text="type === 'video' ? 'Link Video / Embed Code' : (type === 'quiz' ? 'Instruksi Kuis' : 'Alamat URL Link')"></span>
                                    </label>
                                    <textarea name="content" id="content" rows="3"
                                        class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-500/10">{{ old('content', $module->content) }}</textarea>
                                </div>

                                {{-- File Upload & Preview --}}
                                <div class="p-5 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200">
                                    @if ($module->file_path)
                                        <div
                                            class="mb-4 p-3 bg-white rounded-xl border border-slate-200 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-xs font-bold text-slate-700 truncate">File Saat Ini:
                                                    </p>
                                                    <a href="{{ Storage::url($module->file_path) }}" target="_blank"
                                                        class="text-[11px] text-blue-600 hover:underline truncate block">
                                                        Lihat Dokumen Terunggah
                                                    </a>
                                                </div>
                                            </div>
                                            <span
                                                class="text-[10px] px-2 py-1 bg-slate-100 text-slate-500 rounded-md font-medium italic">Tersimpan</span>
                                        </div>
                                    @endif

                                    <div class="flex flex-col items-center justify-center text-center">
                                        <label for="file"
                                            class="block text-sm font-semibold text-slate-700 cursor-pointer">
                                            Ganti / Perbarui File
                                        </label>
                                        <p class="text-[11px] text-slate-500 mt-1 mb-4">Kosongkan jika tidak ingin
                                            mengubah file.</p>
                                        <input type="file" name="file" id="file"
                                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-white hover:file:opacity-90 transition-all cursor-pointer">
                                    </div>
                                    @error('file')
                                        <p class="mt-2 text-xs text-red-500 text-center">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden sticky top-6">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h2 class="font-bold text-slate-800 text-sm tracking-wide">PENGATURAN MODUL</h2>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_required" class="sr-only peer"
                                            {{ old('is_required', $module->is_required) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-amber-500 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-sm font-semibold text-slate-700">Wajib Selesai</span>
                                    </div>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" class="sr-only peer"
                                            {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-sm font-semibold text-slate-700">Modul Aktif</span>
                                    </div>
                                </label>
                            </div>

                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-amber-500 text-white hover:bg-amber-600 font-bold shadow-lg shadow-amber-500/20 transition-all active:scale-[0.98]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Perbarui Modul
                                </button>
                                <a href="{{ route($routePrefix . '.modules.index', $course->id) }}"
                                    class="w-full flex items-center justify-center px-6 py-3 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-all">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
