<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstructorDocument\ReviewMotRequest;
use App\Models\InstructorDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminMotReviewController extends Controller
{
    public function index(): View
    {
        $q = request('q');

        $docs = InstructorDocument::query()
            ->where('type', 'mot')
            ->with('user') // biar nggak N+1
            ->when($q, function ($query) use ($q) {
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByRaw("FIELD(status,'pending','rejected','approved')")
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString(); // penting: biar q ikut kebawa pagination

        return view('admin.mot.index', compact('docs', 'q'));
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
}
