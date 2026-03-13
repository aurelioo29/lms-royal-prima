<x-app-layout>
    <div class="max-w-full mx-auto">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-slate-800">Edit Video</h1>
            <p class="text-sm text-slate-500">
                Video: {{ $video->title }}
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('videos.update', $video) }}">
                @method('PUT')
                @include('videos._form', ['video' => $video])
            </form>
        </div>
    </div>
</x-app-layout>
