<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstructorDocument\UploadMotRequest;
use App\Models\InstructorDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstructorMotController extends Controller
{
    public function show(): View
    {
        $mot = auth()->user()->motDocument()->first();

        return view('instructor.mot', compact('mot'));
    }

    public function store(UploadMotRequest $request): RedirectResponse
    {
        $user = $request->user();
        $file = $request->file('mot_file');

        // If exist, delete old file (optional)
        $existing = $user->motDocument()->first();
        if ($existing && $existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $path = $file->store("instructor-documents/{$user->id}", 'public');

        InstructorDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'mot',
            ],
            [
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => 'pending',
                'issued_at' => $request->validated()['issued_at'] ?? null,
                'expires_at' => $request->validated()['expires_at'] ?? null,
                'verified_by' => null,
                'verified_at' => null,
                'rejected_reason' => null,
            ]
        );

        return redirect()->route('instructor.mot.show')->with('success', 'MOT berhasil diupload. Status: pending.');
    }
}
