<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoModule\StoreVideoRequest;
use App\Http\Requests\VideoModule\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        abort_unless($user?->role?->can_see_module || $user?->role?->name === 'Developer', 403);

        $q = $request->get('q');

        $videos = Video::query()
            ->when($q, fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('videos.index', compact('videos', 'q'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->role?->name === 'Developer', 403);

        return view('videos.create');
    }

    public function store(StoreVideoRequest $request): RedirectResponse
    {
        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'platform' => $this->detectPlatform($request->video_url),
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video): View
    {
        abort_unless(auth()->user()?->role?->name === 'Developer', 403);

        return view('videos.edit', compact('video'));
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'platform' => $this->detectPlatform($request->video_url),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('videos.index')->with('success', 'Video berhasil diupdate.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        abort_unless(auth()->user()?->role?->name === 'Developer', 403);

        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video berhasil dihapus.');
    }

    public function show(Video $video): View
    {
        $user = auth()->user();

        abort_unless($user?->role?->can_see_module || $user?->role?->name === 'Developer', 403);

        return view('videos.show', compact('video'));
    }

    private function detectPlatform(string $url): ?string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'youtube';
        }

        if (str_contains($url, 'vimeo.com')) {
            return 'vimeo';
        }

        if (str_contains($url, 'drive.google.com')) {
            return 'google_drive';
        }

        return 'other';
    }
}
