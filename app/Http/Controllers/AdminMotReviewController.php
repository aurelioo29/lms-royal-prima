<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstructorDocument\ReviewMotRequest;
use App\Models\InstructorDocument;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminMotReviewController extends Controller
{
    public function index(): View
    {
        $q = request('q');

        $instructors = User::query()
            ->with(['latestMot', 'latestMot.verifier', 'latestMot.uploader'])
            ->whereHas('role', function ($r) {
                $r->where('name', 'Narasumber'); // adjust to your role naming
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mot.index', compact('instructors', 'q'));
    }

    public function show(InstructorDocument $doc): View
    {
        abort_unless($doc->type === 'mot', 404);

        $doc->load(['user', 'verifier']);

        return view('admin.mot.show', compact('doc'));
    }

    public function update(ReviewMotRequest $request, InstructorDocument $doc): RedirectResponse
    {
        abort_unless($doc->type === 'mot', 404);

        $data = $request->validated();

        $doc->update([
            'status' => $data['status'],
            'rejected_reason' => $data['status'] === 'rejected' ? ($data['rejected_reason'] ?? null) : null,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.mot.index')->with('success', 'Status MOT berhasil diperbarui.');
    }

    public function create(User $user): View
    {
        // only admin/kabid who can manage users
        abort_unless(auth()->user()->role?->can_manage_users, 403);

        return view('admin.mot.upload', compact('user'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->role?->can_manage_users, 403);

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('instructor/mot', 'public');

        // Admin-assisted upload => AUTO APPROVED
        InstructorDocument::create([
            'user_id' => $user->id,
            'type' => 'mot',
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),

            'status' => 'approved',
            'uploaded_by' => auth()->id(),

            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejected_reason' => null,
        ]);

        return redirect()
            ->route('admin.mot.index')
            ->with('success', "MOT untuk {$user->name} berhasil diupload dan langsung APPROVED.");
    }

    public function destroyInstructor(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->role?->can_manage_users, 403);

        // pastikan yang dihapus memang Narasumber (biar gak salah bunuh)
        abort_unless($user->role?->name === 'Narasumber', 403);

        DB::transaction(function () use ($user) {

            // 1) Hapus dokumen-dokumen instructor (termasuk MOT) + file fisiknya
            $docs = $user->instructorDocuments()->get(); // pastikan relasi ini ada
            foreach ($docs as $doc) {
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }

            // 2) Hapus relasi lain kalau ada (pilih sesuai struktur projectmu)
            // Contoh umum:
            // $user->courseInstructors()->delete();
            // $user->notifications()->delete();
            // dst...

            // 3) Hapus usernya
            $user->delete();
        });

        return redirect()
            ->route('admin.mot.index')
            ->with('success', "Akun narasumber {$user->name} berhasil dihapus.");
    }
}
