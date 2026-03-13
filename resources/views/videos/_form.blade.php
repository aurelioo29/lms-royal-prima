@csrf

<div class="space-y-8">

    {{-- Basic Info --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-slate-200">
            <p class="text-sm font-semibold text-slate-900">Video Details</p>
            <p class="text-xs text-slate-500 mt-0.5">
                Atur judul, link video, deskripsi, dan status tayang.
            </p>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Title <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}"
                        placeholder="Contoh: Pengenalan Sistem LMS" required
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                               shadow-sm outline-none transition
                               focus:border-[#121293]/40 focus:ring-4 focus:ring-[#121293]/10">

                    @error('title')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Video URL --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Video URL <span class="text-red-500">*</span>
                    </label>

                    <input type="url" name="video_url" value="{{ old('video_url', $video->video_url ?? '') }}"
                        placeholder="https://www.youtube.com/watch?v=xxxx" required
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                               shadow-sm outline-none transition
                               focus:border-[#121293]/40 focus:ring-4 focus:ring-[#121293]/10">

                    <p class="mt-2 text-xs text-slate-500">
                        Bisa pakai link YouTube, Vimeo, atau link video lain. Kalau formatnya cocok, preview akan tampil
                        otomatis.
                    </p>

                    @error('video_url')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Description
                    </label>

                    <textarea name="description" rows="5" placeholder="Tulis ringkasan isi video ini..."
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                               shadow-sm outline-none transition resize-y
                               focus:border-[#121293]/40 focus:ring-4 focus:ring-[#121293]/10">{{ old('description', $video->description ?? '') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Publish Settings --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-900">Publish Settings</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    Tentukan apakah video langsung aktif dan bisa dilihat user.
                </p>
            </div>

            <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                Status
            </span>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label
                    class="group flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4
                           hover:bg-slate-50 transition cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                        class="mt-0.5 h-5 w-5 rounded-md border-slate-300 text-[#121293]
                               focus:ring-[#121293]/20 focus:ring-4"
                        {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 group-hover:text-slate-950">
                            Active / Published
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Jika dicentang, video tampil dan bisa diakses user yang punya izin.
                        </p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    {{-- Preview --}}
    @php
        $previewUrl = old('video_url', $video->video_url ?? null);
        $embedUrl = $video->embed_url ?? null;
    @endphp

    @if (!empty($video) && !empty($embedUrl))
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-slate-200">
                <p class="text-sm font-semibold text-slate-900">Current Preview</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    Preview video berdasarkan link yang tersimpan saat ini.
                </p>
            </div>

            <div class="p-5">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 aspect-video">
                    <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="mt-3">
                    <a href="{{ $video->video_url }}" target="_blank"
                        class="text-sm font-medium text-[#121293] hover:underline">
                        Buka link video asli
                    </a>
                </div>
            </div>
        </div>
    @elseif (!empty($video) && !empty($previewUrl))
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-slate-200">
                <p class="text-sm font-semibold text-slate-900">Preview Info</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    Link tersimpan, tapi belum bisa di-embed otomatis. Ya begitulah kalau link-nya ngeselin.
                </p>
            </div>

            <div class="p-5">
                <a href="{{ $previewUrl }}" target="_blank"
                    class="text-sm font-medium text-[#121293] hover:underline break-all">
                    {{ $previewUrl }}
                </a>
            </div>
        </div>
    @endif

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
        <p class="text-xs text-slate-500">
            Pastikan link valid. Kalau bukan URL yang benar, validator akan protes. Dan kali ini dia benar.
        </p>

        <div class="flex gap-2">
            <button
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                       bg-[#121293] text-white text-sm font-semibold shadow-sm
                       hover:opacity-90 active:opacity-80 transition">
                Simpan
            </button>

            <a href="{{ route('videos.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                       border border-slate-200 bg-white text-slate-700 text-sm font-semibold
                       hover:bg-slate-50 transition">
                Batal
            </a>
        </div>
    </div>

</div>
