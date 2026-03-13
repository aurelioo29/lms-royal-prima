<x-app-layout>
    <div class="max-w-full px-6 md:px-8 py-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Video Module</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Kumpulan video pembelajaran yang bisa diakses user.
                </p>
            </div>

            @if ($isDeveloper ?? auth()->user()?->role?->name === 'Developer')
                <a href="{{ route('videos.create') }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-[#121293] text-white text-sm font-medium hover:opacity-90 shadow-sm">
                    + Tambah Video
                </a>
            @endif
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-700 text-sm border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-sm border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        {{-- Search --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <input name="q" value="{{ $q }}" placeholder="Cari judul video..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm
                           focus:border-[#121293]/30 focus:ring-4 focus:ring-[#121293]/10">

                <button
                    class="shrink-0 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:opacity-90">
                    Cari
                </button>
            </form>
        </div>

        {{-- Grid Video --}}
        @if ($videos->count())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($videos as $video)
                    <div
                        class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition">

                        {{-- Preview --}}
                        <div class="aspect-video bg-slate-100 border-b border-slate-200">
                            @if ($video->embed_url)
                                <iframe src="{{ $video->embed_url }}" class="w-full h-full" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center px-4 text-center">
                                    <a href="{{ $video->video_url }}" target="_blank"
                                        class="text-sm font-medium text-[#121293] hover:underline break-all">
                                        Buka Video
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800 line-clamp-2">
                                        {{ $video->title }}
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ ucfirst($video->platform ?? 'unknown') }} •
                                        {{ $video->created_at?->format('d M Y') }}
                                    </p>
                                </div>

                                <span
                                    class="shrink-0 px-2 py-1 rounded-full text-xs border
                                    {{ $video->is_active
                                        ? 'bg-green-50 text-green-700 border-green-100'
                                        : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                    {{ $video->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            @if ($video->description)
                                <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 mb-4">
                                    {{ $video->description }}
                                </p>
                            @else
                                <p class="text-sm text-slate-400 italic mb-4">
                                    Tidak ada deskripsi.
                                </p>
                            @endif

                            {{-- Actions --}}
                            <div class="flex items-center justify-between gap-3 pt-3 border-t border-slate-100">
                                <a href="{{ $video->video_url }}" target="_blank"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900 hover:underline">
                                    Open Link
                                </a>

                                @if (auth()->user()?->role?->name === 'Developer')
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('videos.edit', $video) }}"
                                            class="text-sm font-medium text-[#121293] hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('videos.destroy', $video) }}" method="POST"
                                            onsubmit="return confirm('Hapus video ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm font-medium text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($videos->hasPages())
                <div class="mt-6">
                    {{ $videos->links() }}
                </div>
            @endif
        @else
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-12 text-center">
                <div class="max-w-md mx-auto">
                    <h3 class="text-base font-semibold text-slate-800">Belum ada video</h3>
                    <p class="text-sm text-slate-500 mt-2">
                        Video yang ditambahkan nanti akan muncul di sini dalam bentuk card.
                    </p>

                    @if (auth()->user()?->role?->name === 'Developer')
                        <a href="{{ route('videos.create') }}"
                            class="inline-flex mt-5 items-center px-4 py-2.5 rounded-xl bg-[#121293] text-white text-sm font-medium hover:opacity-90 shadow-sm">
                            + Tambah Video Pertama
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
