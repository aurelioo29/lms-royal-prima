<x-app-layout>
    <div class="max-w-full px-6 md:px-8 py-6">

        {{-- Back --}}
        <div class="mb-4">
            <a href="{{ route('videos.index') }}"
                class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-[#121293] transition">
                ← Kembali ke List Video
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Video Preview --}}
            <div class="aspect-video bg-slate-100 border-b border-slate-200">
                @if ($video->embed_url)
                    <iframe src="{{ $video->embed_url }}" class="w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center px-6 text-center">
                        <div>
                            <p class="text-slate-700 font-medium mb-2">Preview embed tidak tersedia</p>
                            <a href="{{ $video->video_url }}" target="_blank"
                                class="text-[#121293] hover:underline text-sm">
                                Buka video asli
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-slate-800">
                            {{ $video->title }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-2 mt-3 text-sm text-slate-500">
                            <span>{{ ucfirst($video->platform ?? 'unknown') }}</span>
                            <span>•</span>
                            <span>{{ $video->created_at?->format('d M Y') }}</span>

                            <span
                                class="ml-1 px-2 py-1 rounded-full text-xs border
                                {{ $video->is_active
                                    ? 'bg-green-50 text-green-700 border-green-100'
                                    : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                {{ $video->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    @if (auth()->user()?->role?->name === 'Developer')
                        <div class="flex items-center gap-2">
                            <a href="{{ route('videos.edit', $video) }}"
                                class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-medium text-[#121293] hover:bg-slate-50 transition">
                                Edit
                            </a>

                            <form action="{{ route('videos.destroy', $video) }}" method="POST"
                                onsubmit="return confirm('Hapus video ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 rounded-xl border border-red-200 bg-red-50 text-sm font-medium text-red-600 hover:bg-red-100 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-slate-900 mb-2">Deskripsi</h2>

                    @if ($video->description)
                        <p class="text-sm leading-7 text-slate-600 whitespace-pre-line">
                            {{ $video->description }}
                        </p>
                    @else
                        <p class="text-sm text-slate-400 italic">
                            Tidak ada deskripsi untuk video ini.
                        </p>
                    @endif
                </div>

                {{-- Link --}}
                <div class="pt-5 border-t border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-900 mb-2">Sumber Video</h2>

                    <a href="{{ $video->video_url }}" target="_blank"
                        class="text-sm font-medium text-[#121293] hover:underline break-all">
                        {{ $video->video_url }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
